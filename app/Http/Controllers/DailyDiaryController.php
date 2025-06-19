<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\support\Facades\Auth;
use App\Models\UserCenter; 
use App\Models\Room;
use App\Models\ChildParent;
use App\Models\Child;
use App\Models\RoomStaff;
use App\Models\DailyDiarySettings;
use App\Models\DailyDiaryBreakfast;
use App\Models\DailyDiaryMorningTea;
use App\Models\DailyDiaryLunch;
use App\Models\DailyDiarySleep;
use App\Models\DailyDiaryAfternoonTea;
use App\Models\DailyDiarySnacks;
use App\Models\DailyDiarySunscreen;
use App\Models\DailyDiaryToileting;
use App\Models\DailyDiaryBottle;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Services\AuthTokenService; // Custom service to verify token
use App\Models\DailyDiaryModel;
use Illuminate\Support\Carbon;   



class DailyDiaryController extends Controller
{


public function list(Request $request)
{
   

        // Get center ID
        if ($request->has('centerid')) {
            $centerid = $request->centerid;
        } else {
            $centerid = Session('user_center_id');
            // $centerid = $centers[0]->id ?? null;
        }

        $data = [
            'centerid' => $centerid,
        ];

        // Optional parameters
        if ($request->has('date')) {
            $data['date'] = $request->input('date');
        }

        if ($request->has('roomid')) {
            $data['roomid'] = $request->input('roomid');
        }

        // Superadmin, Parent, or other
        $userType = Auth::user()->userType;
        if ($userType == 'Superadmin') {
            $data['superadmin'] = 1;
        } elseif ($userType == 'Parent') {
            $data['superadmin'] = 2;
        } else {
            $data['superadmin'] = 0;
        }

        $userid = Auth::user()->userid;

        $responseData = $this->getDailyDiary($data);

        if($responseData) {
          

    $responseData->centerid   = $centerid;
    // dd($responseData->centerid);

            // Filter childs based on attendance days (for non-parent)
            if ($userType != 'Parent' && isset($responseData->date)) {
                $reqDate = $responseData->date;
                $dayIndex = date('N', strtotime($reqDate)) - 1; // 0 = Monday, 4 = Friday

                if ($dayIndex >= 0 && $dayIndex <= 4 && isset($responseData->childs)) {
                    $filteredChilds = [];

                    foreach ($responseData->childs as $child) {
                        if (isset($child->daysAttending) && strlen($child->daysAttending) >= 5) {
                            if ($child->daysAttending[$dayIndex] === '1') {
                                $filteredChilds[] = $child;
                            }
                        }
                    }

                    $responseData->childs = $filteredChilds;
                }
            }
            // dd($responseData);

            return view('Daily_diary.daily_diary_list', (array)$responseData);
        } 
    

    return redirect('login');
}

   public function getDailyDiary($data)
    {

        $userid = Auth::user()->userid;
        $userArr = Auth::user();
        $centerid= Session('user_center_id');

        $userCenters = UserCenter::where('userid', $userid)->get();


        if (Auth::user()->userType === 'Superadmin') {
    $data['superadmin'] = 1;
} elseif (Auth::user()->userType === 'Parent') {
    $data['superadmin'] = 2;
} else {
    $data['superadmin'] = 0;
}
        // Room and room details
        if (empty($request->roomid)) {
            $getRooms =  $data['superadmin'] == 1
                ? $this->getRooms($centerid)
                : ( $data['superadmin'] == 2
                    ? $this->getRoomsofParents($userid)
                    : $this->getRooms2($userid));
        } else {
            $roomid = $data['roomid'];
            $getRoom = $this->getRooms(null, $roomid);
            $roomname = $getRoom[0]->name ?? null;
            $roomcolor = $getRoom[0]->color ?? null;

            $getRooms =  $data['superadmin'] == 1
                ? $this->getRooms($centerid)
                : ( $data['superadmin'] == 2
                    ? $this->getRoomsofParents($userid)
                    : $this->getRooms2($userid));
        }

        $roomid = $roomid ?? $getRooms[0]->id ?? null;
        $roomname = $roomname ?? $getRooms[0]->name ?? null;
        $roomcolor = $roomcolor ?? $getRooms[0]->color ?? null;

        $date = !empty($json->date) ? date("Y-m-d", strtotime($json->date)) : date("Y-m-d");

        $childs = $userArr->userType == 'Parent'
            ? $this->getChildsFromRoomOfParent($roomid, $userid)
            : $this->getChildsFromRoom($roomid);

        foreach ($childs as $child) {
            $child->id = $child->childid ?? $child->id;
        }

        $settings = $this->getCenterDDSettings($centerid) ?? (object) [
            'id' => 1,
            'breakfast' => 1,
            'morningtea' => 1,
            'lunch' => 1,
            'sleep' => 1,
            'afternoontea' => 1,
            'latesnacks' => 1,
            'sunscreen' => 1,
            'toileting' => 1,
        ];

        foreach ($childs as $child) {
            $childId = $child->id;

            if ($settings->breakfast) $child->breakfast = $this->getBreakfast($childId, $date);
            if ($settings->morningtea) $child->morningtea = $this->getMorningTea($childId, $date);
            if ($settings->lunch) $child->lunch = $this->getLunch($childId, $date);
            if ($settings->sleep) $child->sleep = $this->getSleep($childId, $date);
            if ($settings->afternoontea) $child->afternoontea = $this->getAfternoonTea($childId, $date);
            if ($settings->latesnacks) $child->snacks = $this->getSnacks($childId, $date);
            if ($settings->sunscreen) $child->sunscreen = $this->getSunscreen($childId, $date);
            if ($settings->toileting) $child->toileting = $this->getToileting($childId, $date);

            $child->bottle = $this->getBottle($childId, $date);
        }

        // dd([
        //     'Status' => 'SUCCESS',
        //     'centerid' => $centerid,
        //     'date' => $date,
        //     'roomid' => $roomid,
        //     'roomname' => $roomname,
        //     'roomcolor' => $roomcolor,
        //     'rooms' => $getRooms,
        //     'childs' => $childs,
        //     'columns' => $settings
        // ]);

        return (object)[
            'Status' => 'SUCCESS',
            'centerid' => $centerid,
            'date' => $date,
            'roomid' => $roomid,
            'roomname' => $roomname,
            'roomcolor' => $roomcolor,
            'rooms' => $getRooms,
            'childs' => $childs,
            'columns' => $settings
        ];
    }



public function getBottle($childid, $date = null)
{
    $query = DailyDiaryBottle::where('childid', $childid);

    if ($date !== null) {
        $query->where('diarydate', $date);
    }

    return $query->get(); // Returns a collection (like CodeIgniter's result())
}


public function getToileting($childid, $date = null)
{
    $query = DailyDiaryToileting::where('childid', $childid);

    if (!is_null($date)) {
        $query->where('diarydate', $date);
    }

    return $query->get(); // Returns a collection of matching records
}


public function getSunscreen($childid, $date = null)
{
    $query = DailyDiarySunscreen::where('childid', $childid);

    if ($date !== null) {
        $query->where('diarydate', $date);
    }

    return $query->first(); // returns one row like row() in CI
}


public function getSnacks($childid, $date = null)
{
    if (is_null($date)) {
        return DailyDiarySnacks::where('childid', $childid)->latest('diarydate')->first();
    } else {
        return DailyDiarySnacks::where('childid', $childid)
            ->where('diarydate', $date)
            ->first();
    }
}

public function getAfternoonTea($childid, $date = null)
{
    if (is_null($date)) {
        return DailyDiaryAfternoonTea::where('childid', $childid)->first();
    }

    return DailyDiaryAfternoonTea::where('childid', $childid)
        ->where('diarydate', $date)
        ->first();
}


public function getSleep($childid, $date = null)
{
    $query = DailyDiarySleep::where('childid', $childid);

    if (!is_null($date)) {
        $query->where('diarydate', $date);
    }

    return $query->get(); // returns a collection, like CodeIgniter's `result()`
}

    public function getLunch($childid, $date = null)
{
    $query = DailyDiaryLunch::where('childid', $childid);

    if (!is_null($date)) {
        $query->where('diarydate', $date);
    }

    return $query->first();
}

    public function getMorningTea($childid, $date = null)
{
    $query = DailyDiaryMorningTea::where('childid', $childid);

    if (!is_null($date)) {
        $query->where('diarydate', $date);
    }

    return $query->first();
}

    public function getBreakfast($childid, $date = null)
{
    if (is_null($date)) {
        return DailyDiaryBreakfast::where('childid', $childid)->first();
    }

    return DailyDiaryBreakfast::where('childid', $childid)
                              ->where('diarydate', $date)
                              ->first();
}


public function getRooms($centerid = null, $roomid = null)
{
    if (is_null($centerid) && is_null($roomid)) {
        return Room::all();
    } elseif (is_null($roomid)) {
        return Room::where('centerid', $centerid)->get();
    } else {
        return Room::where('id', $roomid)->get(); // use ->first() if you expect only one
    }
}

public function getRoomsofParents($userid)
{
    if (empty($userid)) {
        return collect(); // return empty collection
    }

    // Step 1: Get child IDs for the parent
    $childIds = ChildParent::where('parentid', $userid)->pluck('childid');

    if ($childIds->isEmpty()) {
        return collect();
    }

    // Step 2: Get room IDs from children
    $roomIds = Child::whereIn('id', $childIds)
                    ->whereNotNull('room')
                    ->pluck('room');

    if ($roomIds->isEmpty()) {
        return collect();
    }

    // Step 3: Get Room data
    return Room::whereIn('id', $roomIds)->get();
}

public function getRooms2($userid)
{
    // Step 1: Get all room IDs where staffid = $userid
    $roomIds = RoomStaff::where('staffid', $userid)->pluck('roomid');

    if ($roomIds->isEmpty()) {
        return collect(); // return empty collection
    }

    // Step 2: Get room data for the found room IDs
    return Room::whereIn('id', $roomIds)->get();
}

public function getChildsFromRoomOfParent($roomid, $parentid)
{
    return Child::where('room', $roomid)
        ->whereHas('parents', function ($query) use ($parentid) {
            $query->where('parentid', $parentid);
        })
        ->get();
}

public function getChildsFromRoom($roomid)
{
    return Child::where('room', $roomid)->get();
}

public function getCenterDDSettings($centerid = '')
{
    return DailyDiarySettings::where('centerid', $centerid)->first();
}



public function storeBottle(Request $request){
dd('here');
}
public function storeFood(Request $request){

}

public function storeSleep(Request $request){

}


public function storeToileting(Request $request){

}


public function storeSunscreen(Request $request){

}


public function getItems(Request $request){

}



 // Custom model/method for insert logic

public function addFoodRecord(Request $request)
{
    $userId = Auth::user()->userid;

    $foodType = strtoupper($request->type);
    $childIds = json_decode($request->childid, true);


    $payload = [
         'childid'    => $request->childid,
        'diarydate'  => $request->diarydate,
        'startTime'  => $request->startTime ?? '',
          'item'       => json_encode($request->item ?? []), 
        'calories'   => $request->calories ?? 0,
        'qty'        => $request->qty ?? 0,
        'comments'   => $request->comments ?? null,
    ];


    $foodTableMap = [
        'BREAKFAST' => 'dailydiarybreakfast',
        'MORNINGTEA' => 'dailydiarymorningtea',
        'LUNCH' => 'dailydiarylunch',
        'AFTERNOONTEA' => 'dailydiaryafternoontea',
        'SNACKS' => 'dailydiarysnacks',
    ];

    $table = $foodTableMap[$foodType] ?? null;

    if (!$table) {
        return response()->json([
            'Status' => 'ERROR',
            'Message' => 'Please send food type',
        ], 400);
    }

    // Insert food records
    $lastInsertedIds = [];

    foreach ($childIds as $childId) {
        $payload['childid'] = $childId;
        $id = $this->addFoodRecord1((object)$payload, $table);
        $lastInsertedIds[] = $id;
    }

    return response()->json([
        'Status' => 'SUCCESS',
        'Message' => 'Food Record Added Successfully',
        'last_rec_ids' => $lastInsertedIds,
    ]);
}



public function addFoodRecord1($data, $table)
{
    $diaryDate = isset($data->diarydate)
        ? Carbon::parse($data->diarydate)->format('Y-m-d')
        : now()->format('Y-m-d');

    // Delete existing record for same child on the same date
    DB::table($table)->where([
        ['childid', '=', $data->childid],
        ['diarydate', '=', $diaryDate]
    ])->delete();

    // dd();

    // Prepare insert data
    $insertData = [
        'childid'    => $data->childid,
        'diarydate'  => $data->diarydate,
        'startTime'  => $data->startTime ?? '',
        'item'       => $data->item ?? '',
        'calories'   => $data->calories ?? 0,
        'qty'        => $data->qty ?? 0,
        'comments'   => $data->comments ?? null,
        'createdBy'  => Auth::user()->userid,
        'createdAt'  => now(), // Laravel handles timestamps easily
    ];

    // dd($insertData);

    // Insert and return the new record ID
    return DB::table($table)->insertGetId($insertData);
}



public function addSleepRecord(Request $request){

}

public function addToiletingRecord(Request $request){

}

public function addSunscreenRecord(Request $request){

}



public function addBottle(Request $request)
{
    $childId = $request->input('childid');
    $diaryDate = $request->input('diarydate');
    $startTimes = $request->input('startTime'); // array of times

    // Format the diary date
    $diaryDate = date('Y-m-d', strtotime($diaryDate));

    $createdBy = Auth::id(); // Or auth()->user()->userid if needed

    if (!empty($startTimes) && is_array($startTimes)) {
        foreach ($startTimes as $startTime) {
            DailyDiaryBottle::create([
                'childid'   => $childId,
                'diarydate' => $diaryDate,
                'startTime' => $startTime,
                'createdBy' => $createdBy,
            ]);
        }
    }

    return response()->json(['status' => 'success']);
}

public function deleteBottleTime(Request $request){

}

public function updateBottleTimes(Request $request){

}





public function viewChildDiary(Request $request)
{
    // Get center ID
    if ($request->has('centerid')) {
        $centerid = $request->query('centerid');
    } else {
        $centers = Session('user_center_id');
        // $centerid = $centers[0]->id ?? null;
    }

    // Prepare data
    $data =[
        'userid'    => session('LoginId'),
        'date'      => $request->query('date'),
        'childid'   => $request->query('childid'),
        'centerid'  => $centerid
    ];

    // Make the API request
    $response = $this->viewChildDiary1($data);

    // Handle response
    if ($response->successful()) {
        $responseData = $response->object(); // returns stdClass object
        $responseData->centerid = $centerid;

        return view('viewChildDiary', (array) $responseData);
    }

    if ($response->status() == 401) {
        return redirect('welcome');
    }

    // Optional: handle other errors
    return abort(500, 'Something went wrong while fetching the diary.');
}

// api
public function viewChildDiary1($data)
{
    $user = Auth::user();

    $payload = (object) $data;

    // if (!$payload || !$user || $user->userid != ($payload->userid ?? null)) {
    //     return Response::json([
    //         'Status'  => 'ERROR',
    //         'Message' => 'Required data not sent!',
    //     ], 401);
    // }

    $payload->date = $payload->date ?? now()->toDateString();
    // $childRecords = app(\App\Models\DailyDiaryModel::class)->getChildInfo($payload->childid);
    $childRecords = Child::with('room:id,name,color')
    ->select('id', 'name', 'room as roomId')
    ->where('id', $payload->childid)
    ->first();

// if ($child) {
//     $roomName = $child->room->name;
//     $roomColor = $child->room->color;
// }


    foreach ($childRecords as $child) {
      
        $child->breakfast     = $this->getBreakfast($child->id, $payload->date) ?? null;
        $child->morningtea    = $this->getMorningTea($child->id, $payload->date) ?? null;
        $child->lunch         = $this->getLunch($child->id, $payload->date) ?? null;
        $child->sleep         = $this->getSleep($child->id, $payload->date) ?? null;
        $child->afternoontea  = $this->getAfternoonTea($child->id, $payload->date) ?? null;
        $child->snack         = $this->getSnacks($child->id, $payload->date) ?? null;
        $child->sunscreen     = $this->getSunscreen($child->id, $payload->date) ?? null;
        $child->toileting     = $this->getToileting($child->id, $payload->date) ?? null;
    }

    return Response::json([
        'Status'    => 'SUCCESS',
        'child'     => $childRecords,
        'breakfast' => app(\App\Models\DailyDiaryModel::class)->getRecipes("breakfast", $payload->centerid),
        'tea'       => app(\App\Models\DailyDiaryModel::class)->getRecipes("tea", $payload->centerid),
        'lunch'     => app(\App\Models\DailyDiaryModel::class)->getRecipes("lunch", $payload->centerid),
        'snack'     => app(\App\Models\DailyDiaryModel::class)->getRecipes("snacks", $payload->centerid),
    ]);
}



}


