<?php

namespace App\Http\Controllers;

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
use App\Notifications\ReflectionAdded;

class ReflectionController extends Controller
{
    public function index()
    {

        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }


        if (Auth::user()->userType == "Superadmin") {

            $reflection = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } elseif (Auth::user()->userType == "Staff") {

               $reflection = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
               ->where('createdBy', $authId)
               ->orderBy('id', 'desc') // optional: to show latest first
               ->paginate(10); // 10 items per page

            // $reflection = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
            //     ->where('centerid', $centerid)
            //     ->orderBy('id', 'desc') // optional: to show latest first
            //     ->paginate(10); // 10 items per page

        } else {

            $childids = Childparent::where('parentid', $authId)->pluck('childid');
            $reflectionIds = ReflectionChild::whereIn('childId', $childids)
                ->pluck('reflectionid')
                ->unique()
                ->toArray();
            // dd($childids);
            $reflection = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
                ->whereIn('id', $reflectionIds)
                ->where('status',"Published")
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        }

        // dd($reflection);

        return view('reflections.index', compact('centers', 'reflection'));
    }






    public function storepage($id = null)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $reflection = null;
        if ($id) {
            $reflection = Reflection::with(['creator', 'center', 'children', 'media', 'staff'])->find($id);
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


        return view('reflections.storeReflection', compact('reflection', 'childrens', 'rooms', 'staffs', 'outcomes'));
    }


    public function print($id)
    {
        if ($id) {
            $reflection = Reflection::with(['creator', 'center', 'children', 'media', 'staff'])->find($id);
        }
        $user = Auth::user();
        if ($user && $user->userType === 'Parent') {
            // Check if already seen
            $alreadySeen = SeenReflection::where('user_id', $user->id)
                ->where('reflection_id', $id)
                ->exists();

            if (! $alreadySeen) {
                SeenReflection::create([
                    'user_id' => $user->id,
                    'reflection_id' => $id
                ]);
            }
        }
        $roomNames = Room::whereIn('id', explode(',', $reflection->roomids))
            ->pluck('name')
            ->implode(', '); // or ->toArray() if you prefer array

        return view('reflections.printReflection', compact('reflection', 'roomNames'));
    }



    public function store(Request $request)
    {
        //    dd($request->all());

        $uploadMaxSize = min(
            $this->convertToBytes(ini_get('upload_max_filesize')),
            $this->convertToBytes(ini_get('post_max_size'))
        );

        $isEdit = $request->filled('id');

        $rules = [
            'selected_rooms'    => 'required',
            'title'             => 'required|string',
            'about'             => 'required|string',
            'eylf'              => 'required|string',
            'selected_children' => 'required|string',
            'selected_staff' => 'required|string',
        ];

        if (!$isEdit) {
            $rules['media'] = 'required|array|min:1';
        } else {
            $rules['media'] = 'nullable|array';
        }

        $rules['media.*'] = "file|mimes:jpeg,png,jpg,gif,webp,mp4|max:" . intval($uploadMaxSize / 1024);

        $messages = [
            'media.required' => 'At least one media file is required.',
            'media.*.max' => 'Each file must be smaller than ' . ($uploadMaxSize / 1024 / 1024) . 'MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }




        DB::beginTransaction();

        try {
            $authId = Auth::user()->id;
            $centerid = Session('user_center_id');

            $reflection = $isEdit
                ? Reflection::findOrFail($request->id)
                : new Reflection();

            $reflection->roomids      = $request->input('selected_rooms');
            $reflection->title        = $request->input('title');
            $reflection->status        = $request->input('status');
            $reflection->about        = $request->input('about');
            $reflection->eylf         = $request->input('eylf');
            $reflection->centerid     = $centerid;
            if (!$isEdit) {
            $reflection->createdBy    = $authId;
            }
            $reflection->save();

            $reflectionId = $reflection->id;

            // Replace all existing reflectionchilds records
            ReflectionChild::where('reflectionid', $reflectionId)->delete();

            $selectedChildren = explode(',', $request->input('selected_children'));
            foreach ($selectedChildren as $childId) {
                if (trim($childId) !== '') {
                    ReflectionChild::create([
                        'reflectionid' => $reflectionId,
                        'childid' => trim($childId),
                    ]);
                }
            }

            // Replace all existing reflectionstaffs records
            ReflectionStaff::where('reflectionid', $reflectionId)->delete();

            $selectedStaffs = explode(',', $request->input('selected_staff'));
            foreach ($selectedStaffs as $staffids) {
                if (trim($staffids) !== '') {
                    ReflectionStaff::create([
                        'reflectionid' => $reflectionId,
                        'staffid' => trim($staffids),
                    ]);
                }
            }




            // Process uploaded media only if present
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

                        ReflectionMedia::create([
                            'reflectionid' => $reflectionId,
                            'mediaUrl' => 'uploads/Reflections/' . $filename,
                            'mediaType' => $file->getClientMimeType(),
                        ]);
                    }
                }
            }

            DB::commit();
            // $user = User::find(1); // You can loop over multiple users too
            // $user->notify(new ReflectionAdded($reflection));


            $selectedChildren = explode(',', $request->input('selected_children'));

            foreach ($selectedChildren as $childId) {
                $childId = trim($childId);
                if ($childId !== '') {
                    // Get all related parent entries for this child
                    $parentRelations = Childparent::where('childid', $childId)->get();

                    foreach ($parentRelations as $relation) {
                        $parentUser = User::find($relation->parentid); // assuming users table stores parent records

                        if ($parentUser) {
                            $parentUser->notify(new ReflectionAdded($reflection));
                        }
                    }
                }
            }


            return response()->json([
                'status' => 'success',
                'message' => $isEdit ? 'Reflection updated successfully.' : 'Reflection saved successfully.',
                'id' => $reflectionId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Observation Store/Update Failed: ' . $e->getMessage());

            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
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


    public function destroyimage($id)
    {
        $media = ReflectionMedia::findOrFail($id);

        // Optionally delete the file from storage
        if (file_exists(public_path($media->mediaUrl))) {
            @unlink(public_path($media->mediaUrl));
        }

        $media->delete();

        return response()->json(['status' => 'success']);
    }



    public function updateStatus(Request $request)
    {
        $request->validate([
            'reflectionId' => 'required|exists:reflection,id',
            'status' => 'required|in:Published,Draft'
        ]);

        $reflection = Reflection::find($request->reflectionId);
        $reflection->status = $request->status;
        $reflection->save();

        return response()->json(['status' => 'success', 'id' => $reflection->id]);
    }








    public function destroy($id)
    {
        $reflection = Reflection::findOrFail($id);
        $reflection->delete();

        return response()->json(['message' => 'Reflection deleted successfully.']);
    }






    public function applyFilters(Request $request)
    {
        //    dd($request->all());
        try {

            $centerid = Session('user_center_id');


            $query = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
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
                            $query->whereDate('created_at', Carbon::today());
                            break;

                        case 'This Week':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfWeek(),
                                Carbon::now()->endOfWeek()
                            ]);
                            break;

                        case 'This Month':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfMonth(),
                                Carbon::now()->endOfMonth()
                            ]);
                            break;

                        case 'Custom':
                            if ($request->fromDate && $request->toDate) {
                                $fromDate = Carbon::parse($request->fromDate)->startOfDay();
                                $toDate = Carbon::parse($request->toDate)->endOfDay();
                                $query->whereBetween('created_at', [$fromDate, $toDate]);
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
            if ($user->userType === 'Staff') {
                $query->where('createdBy', Auth::id());
            }

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
            $query->orderBy('created_at', 'desc');

            // Get the filtered observations
            $reflections = $query->get();

            // dd($reflections);

            // Format the observations for response
            $formattedReflections = $reflections->map(function ($reflection) {
                return [
                    'id' => $reflection->id,
                    'title' => html_entity_decode($reflection->title ?? ''),
                    'media' => $reflection->media,
                    'children' => $reflection->children,
                    'staff' => $reflection->staff,
                    'created_at_formatted' => \Carbon\Carbon::parse($reflection->created_at)->format('M d, Y'),
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
                'reflections' => $formattedReflections,
                'count' => $formattedReflections->count()
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
