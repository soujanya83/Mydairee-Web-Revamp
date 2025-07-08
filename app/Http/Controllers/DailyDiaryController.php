<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\support\Facades\Auth;
use App\Models\Usercenter; 
use App\Models\Center; 
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
use Illuminate\Support\Facades\Log;



class DailyDiaryController extends Controller
{


    public function list(Request $request)
    {
        $authId = Auth::user()->id; 
        // $centerid = session('user_center_id');


        $centerid = session('user_center_id') ?? $request->query('center_id');

        // Store centerid back into session if it came from query
        if (!session()->has('user_center_id') && $centerid) {
            session(['user_center_id' => $centerid]);
        }

    
        if(Auth::user()->userType == "Superadmin"){
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }
    
        $room = $this->getrooms5();
    
        // Try to find the selected room in the available rooms
        $selectedroom = $room->where('id', $request->query('room_id'))->first();
    
        // If not found or not selected, fallback to first room
        if (!$selectedroom) {
            $selectedroom = $room->first();
        }
    
      // Handle selected date
            $selectedDate = $request->query('selected_date')
            ? \Carbon\Carbon::parse($request->query('selected_date'))
            : now();

        $dayIndex = $selectedDate->dayOfWeekIso - 1; // 0 = Monday

        // Filter children by attendance on selected date
        $children = collect();
        if ($selectedroom) {
            $children = Child::where('room', $selectedroom->id)
                ->get()
                ->filter(function ($child) use ($dayIndex) {
                    return isset($child->daysAttending[$dayIndex]) && $child->daysAttending[$dayIndex] === '1';
                })
                ->map(function ($child) use ($selectedDate) {
                    return [
                        'child' => $child,
                        'bottle' => DailyDiaryBottle::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'toileting' => DailyDiaryToileting::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'sunscreen' => DailyDiarySunscreen::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'snacks' => DailyDiarySnacks::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'afternoon_tea' => DailyDiaryAfternoonTea::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'sleep' => DailyDiarySleep::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'lunch' => DailyDiaryLunch::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'morning_tea' => DailyDiaryMorningTea::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                        'breakfast' => DailyDiaryBreakfast::where('childid', $child->id)->whereDate('diarydate', $selectedDate)->first(),
                    ];
                })
                ->values();
        }

        // dd($children);
        

        return view('Daily_Diary.daily_diary_list', compact('centers', 'room', 'selectedroom', 'children','selectedDate'));
    }
    



        public function getrooms5(){
            try {
                $user = Auth::user();
                $rooms = collect();
                
            if($user->userType === 'Superadmin') {
                $rooms = $this->getroomsforSuperadmin();
                }else{
                $rooms = $this->getroomsforStaff();
                }
        
                return $rooms;
            
            } catch (\Exception $e) {
                Log::error('Filter error: ' . $e->getMessage());
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while applying filters',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
        



        private function getroomsforSuperadmin(){
            $authId = Auth::user()->id; 
            $centerid = Session('user_center_id');
        
            $rooms = Room::where('centerid', $centerid)->get();
            return $rooms;
        }
        
        private function getroomsforStaff(){
            $authId = Auth::user()->id; 
            $centerid = Session('user_center_id');
        
            $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');
            
            // Get room IDs where user is the owner (userId matches)
            $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');
            
            // Merge both collections and remove duplicates
            $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique();
        
            $rooms = Room::where('id', $allRoomIds)->get();
            return $rooms;
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



// public function storeBottle(Request $request){
// dd('here');
// }
public function storeFood(Request $request){

}

// public function storeSleep(Request $request){

// }


// public function storeToileting(Request $request){

// }


// public function storeSunscreen(Request $request){

// }


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


public function storeBreakfast(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'item' => 'required|string|max:255',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();

        $authId = Auth::user()->id; 
        
        $count = 0;
        $errors = [];

        foreach ($request->child_ids as $childId) {
            // Check if entry already exists for this child and date
            $existingEntry = DailyDiaryBreakfast::where('childid', $childId)
                                    ->whereDate('diarydate', $request->date)
                                    ->first();

            if ($existingEntry) {
                // Update existing entry
                $existingEntry->update([
                    'startTime' => $request->time,
                    'item' => $request->item,
                    'comments' => $request->comments
                ]);
                $count++;
            } else {
                // Create new entry
                DailyDiaryBreakfast::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'item' => $request->item,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
                $count++;
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Breakfast entries updated/created successfully for {$count} children" 
                : "Breakfast entry updated/created successfully"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to save breakfast entries: ' . $e->getMessage()
        ], 500);
    }
}


public function storeLunch(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'item' => 'required|string|max:255',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();

        $authId = Auth::user()->id;
        
        $count = 0;
        $errors = [];

        foreach ($request->child_ids as $childId) {
            // Check if entry already exists for this child and date
            $existingEntry = DailyDiaryLunch::where('childid', $childId)
                                    ->whereDate('diarydate', $request->date)
                                    ->first();

            if ($existingEntry) {
                // Update existing entry
                $existingEntry->update([
                    'startTime' => $request->time,
                    'item' => $request->item,
                    'comments' => $request->comments,
                    'updated_at' => now()
                ]);
                $count++;
            } else {
                // Create new entry
                DailyDiaryLunch::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'item' => $request->item,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
                $count++;
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Lunch entries updated/created successfully for {$count} children" 
                : "Lunch entry updated/created successfully"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to save lunch entries: ' . $e->getMessage()
        ], 500);
    }
}



public function storeMorningTea(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();

        $authId = Auth::user()->id;
        
        $count = 0;
        $errors = [];

        foreach ($request->child_ids as $childId) {
            // Check if entry already exists for this child and date
            $existingEntry = DailyDiaryMorningTea::where('childid', $childId)
                                    ->whereDate('diarydate', $request->date)
                                    ->first();

            if ($existingEntry) {
                // Update existing entry
                $existingEntry->update([
                    'startTime' => $request->time,
                    'comments' => $request->comments,
                    'updated_at' => now()
                ]);
                $count++;
            } else {
                // Create new entry
                DailyDiaryMorningTea::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
                $count++;
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Morning Tea entries updated/created successfully for {$count} children" 
                : "Morning Tea entry updated/created successfully"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to save morning tea entries: ' . $e->getMessage()
        ], 500);
    }
}

public function storeSleep(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'sleep_time' => 'nullable|date_format:H:i',
        'wake_time' => 'nullable|date_format:H:i',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();

        $authId = Auth::user()->id;
        
        $count = 0;
        $errors = [];

        foreach ($request->child_ids as $childId) {
            // Check if entry already exists for this child and date
            $existingEntry = DailyDiarySleep::where('childid', $childId)
                                    ->whereDate('diarydate', $request->date)
                                    ->first();

                                    if ($existingEntry) {
                                        $updateData = [
                                            'comments' => $request->comments,
                                            'updated_at' => now(),
                                        ];
                                    
                                        if ($request->sleep_time) {
                                            $updateData['startTime'] = $request->sleep_time;
                                        }
                                    
                                        if ($request->wake_time) {
                                            $updateData['endTime'] = $request->wake_time;
                                        }
                                    
                                        $existingEntry->update($updateData);
                                    } else {
                // Create new entry
                DailyDiarySleep::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->sleep_time,
                    'endTime' => $request->wake_time,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
                $count++;
            }
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Sleep entries updated/created successfully for {$count} children" 
                : "Sleep entry updated/created successfully"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to save sleep entries: ' . $e->getMessage()
        ], 500);
    }
}


public function storeAfternoonTea(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();

        $authId = Auth::user()->id;
        
        $count = 0;

        foreach ($request->child_ids as $childId) {
            // Check if entry exists for child+date
            $existingEntry = DailyDiaryAfternoonTea::where('childid', $childId)
                                    ->whereDate('diarydate', $request->date)
                                    ->first();

            if ($existingEntry) {
                // Update existing
                $existingEntry->update([
                    'startTime' => $request->time,
                    'comments' => $request->comments,
                    'updated_at' => now()
                ]);
            } else {
                // Create new
                DailyDiaryAfternoonTea::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
            }
            $count++;
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Afternoon Tea entries saved for {$count} children" 
                : "Afternoon Tea entry saved"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Error saving afternoon tea: ' . $e->getMessage()
        ], 500);
    }

}



  
public function storeSnacks(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'item' => 'required|string|max:255',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();
        $authId = Auth::user()->id;
        $count = 0;

        foreach ($request->child_ids as $childId) {
            $existingEntry = DailyDiarySnacks::where('childid', $childId)
                                ->whereDate('diarydate', $request->date)
                                ->first();

            if ($existingEntry) {
                $existingEntry->update([
                    'startTime' => $request->time,
                    'item' => $request->item,
                    'comments' => $request->comments
                ]);
            } else {
                DailyDiarySnacks::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'item' => $request->item,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
            }
            $count++;
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Snacks entries saved for {$count} children" 
                : "Snacks entry saved"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Error saving snacks: ' . $e->getMessage()
        ], 500);
    }
}




public function storeSunscreen(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();
        $authId = Auth::user()->id;
        $count = 0;

        foreach ($request->child_ids as $childId) {
            $existingEntry = DailyDiarySunscreen::where('childid', $childId)
                                ->whereDate('diarydate', $request->date)
                                ->first();

            if ($existingEntry) {
                $existingEntry->update([
                    'startTime' => $request->time,
                    'comments' => $request->comments
                ]);
            } else {
                DailyDiarySunscreen::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
            }
            $count++;
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Sunscreen entries saved for {$count} children" 
                : "Sunscreen entry saved"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Error saving sunscreen: ' . $e->getMessage()
        ], 500);
    }
}



public function storeToileting(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'status' => 'required|in:clean,wet,soiled,successful',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();
        $authId = Auth::user()->id;
        $count = 0;

        foreach ($request->child_ids as $childId) {
            $existingEntry = DailyDiaryToileting::where('childid', $childId)
                                ->whereDate('diarydate', $request->date)
                                ->first();

            if ($existingEntry) {
                $existingEntry->update([
                    'startTime' => $request->time,
                    'status' => $request->status,
                    'comments' => $request->comments
                ]);
            } else {
                DailyDiaryToileting::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'status' => $request->status,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
            }
            $count++;
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Toileting entries saved for {$count} children" 
                : "Toileting entry saved"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Error saving toileting: ' . $e->getMessage()
        ], 500);
    }
}


public function storeBottle(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'child_ids' => 'required|array',
        'child_ids.*' => 'exists:child,id',
        'time' => 'required|date_format:H:i',
        'comments' => 'nullable|string'
    ]);

    try {
        DB::beginTransaction();
        $authId = Auth::user()->id;
        $count = 0;

        foreach ($request->child_ids as $childId) {
            $existingEntry = DailyDiaryBottle::where('childid', $childId)
                                ->whereDate('diarydate', $request->date)
                                ->first();

            if ($existingEntry) {
                $existingEntry->update([
                    'startTime' => $request->time,
                    'comments' => $request->comments,
                    'updated_at' => now()
                ]);
            } else {
                DailyDiaryBottle::create([
                    'childid' => $childId,
                    'diarydate' => $request->date,
                    'startTime' => $request->time,
                    'comments' => $request->comments,
                    'createdBy' => $authId
                ]);
            }
            $count++;
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => $count > 1 
                ? "Bottle entries saved for {$count} children" 
                : "Bottle entry saved"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Error saving bottle: ' . $e->getMessage()
        ], 500);
    }
}








}


