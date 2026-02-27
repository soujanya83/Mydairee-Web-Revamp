<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User; 
use App\Models\Center; 
use App\Models\Usercenter; 
use App\Models\Child; 
use App\Models\Childparent; 
use App\Models\Reflection; 
use App\Models\ReflectionChild; 
use App\Models\ReflectionMedia; 
use App\Models\EYLFOutcome; 
use App\Models\ReflectionStaff; 
use App\Models\Room; 
use App\Models\RoomStaff;
use App\Models\SeenReflection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ReflectionController extends Controller
{
    //   public function index( Request $request){

    //     $authId = Auth::user()->id; 
    //     $centerid = $request->center_id;

    //     if(Auth::user()->userType == "Superadmin"){
    //         $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
    //         $centers = Center::whereIn('id', $center)->get();
    //          }else{
    //         $centers = Center::where('id', $centerid)->get();
    //          }


    //          if(Auth::user()->userType == "Superadmin"){

    //             $reflection = Reflection::with(['creator', 'center','children.child','media','staff.staff','Seen.user'])
    //             ->where('centerid', $centerid)
    //             ->orderBy('id', 'desc') // optional: to show latest first
    //             ->get(); // 10 items per page   
   
    //             }elseif(Auth::user()->userType == "Staff"){
   
    //             //    $reflection = Reflection::with(['user', 'child','media'])
    //             //    ->where('userId', $authId)
    //             //    ->orderBy('id', 'desc') // optional: to show latest first
    //             //    ->paginate(10); // 10 items per page 

    //             $reflection = Reflection::with(['creator', 'center','children.child','media','staff.staff','Seen.user'])
    //             ->where('centerid', $centerid)
    //             ->orderBy('id', 'desc') // optional: to show latest first
    //             ->get(); // 10 items per page   
   
    //             }else{
   
    //                $childids = Childparent::where('parentid', $authId)->pluck('childid');
    //                $reflectionIds = ReflectionChild::whereIn('childId', $childids)
    //                ->pluck('reflectionid')
    //                ->unique()
    //                ->toArray();
    //                // dd($childids);
    //            $reflection = Reflection::with(['creator', 'center','children.child','media','staff.staff','Seen.user'])
    //            ->whereIn('id', $reflectionIds)
    //             ->orderBy('id', 'desc') // optional: to show latest first
    //             ->get(); // 10 items per page   
   
    //             }

    //             $data = [ 
    //                 'centers' => $centers,
    //                 'reflection' => $reflection
    //             ];

    //         // dd($reflection);
    //         return response()->json(['status' => true,'message' => 'data retrived successfully', 'data' => $data]);


    // }

public function index(Request $request)
{
    $authId = Auth::user()->id; 
    $centerid = $request->center_id;
    $perPage = $request->get('per_page', 10); // Default 10 items per page

    if (Auth::user()->userType == "Superadmin") {
        $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
        $centers = Center::whereIn('id', $center)->get();
    } else {
        $centers = Center::where('id', $centerid)->get();
    }

    if (Auth::user()->userType == "Superadmin") {
        $reflection = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
            ->where('centerid', $centerid)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    } elseif (Auth::user()->userType == "Staff") {
        $reflection = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
            ->where('centerid', $centerid)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    } else {
        $childids = Childparent::where('parentid', $authId)->pluck('childid');
        $reflectionIds = ReflectionChild::whereIn('childId', $childids)
            ->pluck('reflectionid')
            ->unique()
            ->toArray();

        $reflection = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
            ->whereIn('id', $reflectionIds)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    return response()->json([
        'status' => true,
        'message' => 'Data retrieved successfully',
        'data' => [
            'centers' => $centers,
            'reflection' => $reflection
        ]
    ]);
}


    public function storepage(Request $request)
     {
        $authId = Auth::user()->id; 
        // $centerid = $request->center_id;
        $id = $request->id;

        $reflection = null;
        if ($id) {
            $reflection = Reflection::with(['creator', 'center','children','media','staff'])->find($id);
        }

        $childrens = $reflection
        ? $reflection->children->pluck('child')->filter()
        : collect();

        $rooms = collect();

        if ($reflection && $reflection->roomids) {
        $roomIds = explode(',', $reflection->roomids); // Convert comma-separated string to array
        $rooms = Room::whereIn('id', $roomIds)->get();
         }

         $staffs = $reflection
    ? $reflection->staff->pluck('staff')->filter()
    : collect();

    $outcomes = EYLFOutcome::with('activities.subActivities')->get();


       return response()->json([
            'status'    => true,
            'message'   => 'Reflection data loaded successfully.',
            'data'      => [
                'reflection' => $reflection,
                'childrens' => $childrens,
                'rooms'     => $rooms,
                'staffs'    => $staffs,
                'outcomes'  => $outcomes,
            ]
        ]);

     }


//      public function print(Request $request){
//         $id = $request->id;

//         if ($id) {
//             $reflection = Reflection::with(['creator', 'center','children','media','staff'])->find($id);
//         }


//         $user = Auth::user();
//            if ($user && $user->userType === 'Parent') {
//                // Check if already seen
//                $alreadySeen = SeenReflection::where('user_id', $user->id)
//                    ->where('reflection_id', $id)
//                    ->exists();
   
//                if (! $alreadySeen) {
//                 SeenReflection::create([
//                        'user_id' => $user->id,
//                        'reflection_id' => $id
//                    ]);
//                }
//            }


//         $roomNames = Room::whereIn('id', explode(',', $reflection->roomids))
//         ->pluck('name')
//         ->implode(', '); // or ->toArray() if you prefer array

//         // dd($reflection);

//        return response()->json([
//     'status'    => true,
//     'message'   => 'Reflection print data fetched successfully.',
//     'data'      => [
//         'reflection' => $reflection,
//         'roomNames'  => $roomNames,
//     ]
// ]);

//      }


public function print(Request $request)
{
    $id = $request->id;
    if (! $id) {
        return response()->json([
            'status'  => false,
            'message' => 'Reflection ID is required',
        ], 422);
    }

    $reflection = Reflection::with(['creator', 'center', 'children.childDetails', 'media', 'staff.staffDetails'])->find($id);

    if (! $reflection) {
        return response()->json([
            'status'  => false,
            'message' => 'Reflection not found',
        ], 404);
    }

    $user = Auth::user();
    $alreadySeen = false;

    if ($user && $user->userType === 'Parent') {
        $alreadySeen = SeenReflection::where('user_id', $user->id)
            ->where('reflection_id', $id)
            ->exists();

        if (! $alreadySeen) {
            SeenReflection::create([
                'user_id'       => $user->id,
                'reflection_id' => $id
            ]);
            $alreadySeen = true; // mark as seen after creating
        }
    }

    $roomNames = Room::whereIn('id', explode(',', $reflection->roomids ?? ''))
        ->pluck('name')
        ->implode(', ');

    return response()->json([
        'status'     => true,
        'message'    => 'Reflection data retrieved successfully',
        'reflection' => $reflection,
        'roomNames'  => $roomNames,
        'seen'       => $alreadySeen
    ]);
}



 public function store(Request $request)
{
    // Handle max upload size
    $uploadMaxSize = min(
        $this->convertToBytes(ini_get('upload_max_filesize')),
        $this->convertToBytes(ini_get('post_max_size'))
    );

    $isEdit = $request->filled('id');

    // Validation rules
    $rules = [
        'selected_rooms'    => 'required|string',
        'title'             => 'required|string|max:255',
        'about'             => 'nullable|string',
        'eylf'              => 'nullable|string',
        'selected_children' => 'required|string',
        'selected_staff'    => 'required|string',
        'center_id'         => 'required|integer|exists:centers,id',
    ];

    if (!$isEdit) {
        $rules['media'] = 'required|array|min:1';
    } else {
        $rules['media'] = 'nullable|array';
    }

    $rules['media.*'] = 'file|mimes:jpeg,png,jpg,gif,webp,mp4|max:' . intval($uploadMaxSize / 1024);

    $messages = [
        'media.required' => 'At least one media file is required.',
        'media.*.max'    => 'Each file must be smaller than ' . round($uploadMaxSize / 1024 / 1024, 2) . 'MB.',
    ];

    // Validate
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Begin DB transaction
    DB::beginTransaction();

    try {
        $authId = Auth::user()->id;
        $centerId = $request->center_id;

        $reflection = $isEdit
            ? Reflection::findOrFail($request->id)
            : new Reflection();

        $reflection->roomids   = $request->selected_rooms;
        $reflection->title     = $request->title;
        $reflection->about     = $request->about;
        $reflection->eylf      = $request->eylf;
        $reflection->centerid  = $centerId;
        $reflection->createdBy = $authId;
      
        $reflection->save();

        $reflectionId = $reflection->id;

        // Handle children
        ReflectionChild::where('reflectionid', $reflectionId)->delete();
        $childIds = explode(',', $request->selected_children);
        foreach ($childIds as $childId) {
            if (trim($childId) !== '') {
                ReflectionChild::create([
                    'reflectionid' => $reflectionId,
                    'childid' => trim($childId),
                ]);
            }
        }

        // Handle staff
        ReflectionStaff::where('reflectionid', $reflectionId)->delete();
        $staffIds = explode(',', $request->selected_staff);
        foreach ($staffIds as $staffId) {
            if (trim($staffId) !== '') {
                ReflectionStaff::create([
                    'reflectionid' => $reflectionId,
                    'staffid' => trim($staffId),
                ]);
            }
        }

        // Handle media uploads
        if ($request->hasFile('media')) {
            $manager = new ImageManager(new GdDriver());
            foreach ($request->file('media') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('uploads/Reflections');

                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    if (Str::startsWith($file->getMimeType(), 'image') && $file->getSize() > 1024 * 1024) {
                        $image = $manager->read($file->getPathname());
                        $image->scale(1920);
                        $image->save($destinationPath . '/' . $filename, 75);
                    } else {
                        $file->move($destinationPath, $filename);
                    }

                    $mime = $file->getClientMimeType();
                $type = 'File'; // default fallback

                if (Str::startsWith($mime, 'image/')) {
                    $type = 'Image';
                } elseif (Str::startsWith($mime, 'video/')) {
                    $type = 'Video';
                }


                    ReflectionMedia::create([
                        'reflectionid' => $reflectionId,
                        'mediaUrl'     => 'uploads/Reflections/' . $filename,
                        'mediaType'    => $type,
                    ]);
                }
            }
        }

        DB::commit();

        // Send notification to all parents of the attached children ONLY if published
        if (!empty($selectedChildren) && $reflection->status === 'PUBLISHED')  {
            $service = app(\App\Services\Firebase\FirebaseNotificationService::class);
            \App\Http\Controllers\API\DeviceController::notifyParentsModuleCreated(
                $childIds,
                'reflection',
                $reflectionId,
                $authId,
                $service
            );
        }

        return response()->json([
            'status'  => 'success',
            'message' => $isEdit ? 'Reflection updated successfully.' : 'Reflection saved successfully.',
            'id'      => $reflectionId
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Reflection Store/Update Failed: ' . $e->getMessage());

        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong.',
            'error'   => $e->getMessage()
        ], 500);
    }
}



     // Helper to convert ini values to bytes
private function convertToBytes($value)
{
    $unit = strtolower(substr($value, -1));
    $bytes = (int) $value;

    switch ($unit) {
        case 'g':
            $bytes *= 1024 * 1024 * 1024;
            break;
        case 'm':
            $bytes *= 1024 * 1024;
            break;
        case 'k':
            $bytes *= 1024;
            break;
    }

    return $bytes;
}


public function destroyimage(Request $request)
{
    // dd('here');
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'id' => 'required|integer|exists:reflectionmedia,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    $id = $validator->validated()['id'];

    $media = ReflectionMedia::findOrFail($id);

    // Optionally delete the physical file
    $filePath = public_path($media->mediaUrl);
    if (file_exists($filePath)) {
        @unlink($filePath);
    }

    // Delete record from DB
    $media->delete();

    return response()->json([
        'status'  => true,
        'message' => 'Reflection media deleted successfully.'
    ]);
}



public function updateStatus(Request $request)
{
    // Validate request input
    $validator = Validator::make($request->all(), [
        'reflectionId' => 'required|integer|exists:reflection,id',
        'status'       => 'required|in:Published,Draft',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    $validated = $validator->validated();

    // Update reflection status
    $reflection = Reflection::find($validated['reflectionId']);
    $reflection->status = $validated['status'];
    $reflection->save();

    return response()->json([
        'status'  => true,
        'message' => 'Reflection status updated successfully.',
        'data' => [
            'id' => $reflection->id
        ]
      
    ]);
}








public function destroy($id)
{
    // Validate route parameter using Validator
    $validator = Validator::make(['id' => $id], [
        'id' => 'required|integer|exists:reflection,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status'  => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    // Proceed to delete after validation passes
    $reflection = Reflection::findOrFail($id);
    $reflection->delete();

    return response()->json([
        'status'  => true,
        'message' => 'Reflection deleted successfully.'
    ]);
}





public function applyFilters(Request $request)
{
//    dd($request->all());
    try {

     $validator = Validator::make($request->all(), [
            'center_id' => 'required|integer|exists:centers,id',
        ]);

        

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $centerid = $validator->validated()['center_id'];


        $query = Reflection::with(['creator', 'center','children.child','media','staff.staff','Seen.user'])
        ->where('centerid', $centerid);            
        // Status filter
        if ($request->has('observations') && !empty($request->observations)) {
            $statusFilters = array_map('strtoupper', $request->observations);
           
            if (!in_array('ALL', $statusFilters)) {
                $query->whereIn('status', $statusFilters);
            }
        }

        // Date filter
        if ($request->has('added') && !empty($request->added)) {
            $dateFilters = $request->added;
            
            foreach ($dateFilters as $dateFilter) {
                switch ($dateFilter) {
                    case 'Today':
                        $query->whereDate('createdAt', Carbon::today());
                        break;
                        
                    case 'This Week':
                        $query->whereBetween('createdAt', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek()
                        ]);
                        break;
                        
                    case 'This Month':
                        $query->whereBetween('createdAt', [
                            Carbon::now()->startOfMonth(),
                            Carbon::now()->endOfMonth()
                        ]);
                        break;
                        
                    case 'Custom':
                        if ($request->fromDate && $request->toDate) {
                            $fromDate = Carbon::parse($request->fromDate)->startOfDay();
                            $toDate = Carbon::parse($request->toDate)->endOfDay();
                            $query->whereBetween('createdAt', [$fromDate, $toDate]);
                        }
                        break;
                }
            }
        }
        
        // Child filter
        if ($request->has('childs') && !empty($request->childs)) {
            $childIds = $request->childs;
            
            // Get observation IDs that have the selected children
            $reflectionIds = ReflectionChild::whereIn('childid', $childIds)
                ->pluck('reflectionid')
                ->unique()
                ->toArray();
            
            if (!empty($reflectionIds)) {
                $query->whereIn('id', $reflectionIds);
            } else {
                // If no observations found for selected children, return empty result
                $query->where('id', 0);
            }
        }
        
        // Author filter
        if ($request->has('authors') && !empty($request->authors)) {
            $authorFilters = $request->authors;
        
            // 🚫 Skip filter if "Any" is selected
            if (!in_array('Any', $authorFilters)) {
        
                // ✅ If "Me" is selected
                if (in_array('Me', $authorFilters)) {
                    $query->where('createdBy', Auth::id());
                }
        
                // ✅ If specific staff IDs are selected (as string IDs)
                else {
                    $query->whereIn('createdBy', $authorFilters);
                }
            }
        }


        $user = Auth::user();
        // if ($user->userType === 'Staff') {
        //     $query->where('createdBy', Auth::id());
        // }
        
        // Apply user-specific filters based on role
        // $user = Auth::user();
        // if ($user->userType === 'Parent') {
        //     // Parents can only see observations of their children
        //     $userChildIds = $user->children()->pluck('id')->toArray();
        //     if (!empty($userChildIds)) {
        //         $parentObservationIds = ObservationChild::whereIn('childId', $userChildIds)
        //             ->pluck('observationId')
        //             ->unique()
        //             ->toArray();
                
        //         if (!empty($parentObservationIds)) {
        //             $query->whereIn('id', $parentObservationIds);
        //         } else {
        //             $query->where('id', 0);
        //         }
        //     }
        // } elseif ($user->userType === 'Teacher') {
        //     // Teachers can see observations of children in their classes
        //     // Adjust this logic based on your relationship structure
        //     $teacherChildIds = $user->teacherChildren()->pluck('id')->toArray();
        //     if (!empty($teacherChildIds)) {
        //         $teacherObservationIds = ObservationChild::whereIn('childId', $teacherChildIds)
        //             ->pluck('observationId')
        //             ->unique()
        //             ->toArray();
                
        //         if (!empty($teacherObservationIds)) {
        //             $query->whereIn('id', $teacherObservationIds);
        //         } else {
        //             $query->where('id', 0);
        //         }
        //     }
        // }
        
        // Order by latest first
        $query->orderBy('createdAt', 'desc');
        
        // Get the filtered observations
        $reflections = $query->get();

        // dd($reflections);
        
        // Format the observations for response
        $formattedReflections = $reflections->map(function ($reflection) {
            return [
                'id' => $reflection->id,
                'title' => html_entity_decode($reflection->title ?? ''),
                 'status' => $reflection->status,
                'media' => $reflection->media,
                'children' => $reflection->children,
                'staff' => $reflection->staff,
                'created_at_formatted' => \Carbon\Carbon::parse($reflection->createdAt)->format('M d, Y'),
                'seen' => $reflection->seen->map(function ($seen) {
                    if ($seen->user && $seen->user->userType === 'Parent') {
                        return [
                            'name' => $seen->user->name,
                            'imageUrl' => $seen->user->imageUrl,
                            'gender' => $seen->user->gender,
                        ];
                    }
                    return null;
                })->filter(),
            ];
        });

        // dd($formattedReflections);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Reflection filtered successfully',
            'data' => [
            'reflections' => $formattedReflections,
            'count' => $formattedReflections->count()
            ]
        ]);


        
    } catch (\Exception $e) {
        Log::error('Filter error: ' . $e->getMessage());
        
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while applying filters',
            'error' => $e->getMessage()
        ], 500);
    }
}

}