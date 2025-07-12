<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Center;
use App\Models\Usercenter;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\DevMilestone;
use App\Models\DevMilestoneMain;
use App\Models\DevMilestoneSub;
use App\Models\EYLFActivity;
use App\Models\EYLFOutcome;
use App\Models\EYLFSubActivity;
use App\Models\MontessoriActivity;
use App\Models\MontessoriSubActivity;
use App\Models\MontessoriSubject;
use App\Models\Observation;
use App\Models\ObservationChild;
use App\Models\ObservationComment;
use App\Models\ObservationDevMilestoneSub;
use App\Models\ObservationEYLF;
use App\Models\ObservationLink;
use App\Models\ObservationMedia;
use App\Models\ObservationMontessori;
use App\Models\Room;
use App\Models\RoomStaff;
use App\Models\SeenObservation;
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



class ObservationsController extends Controller
{



    public function refine(Request $request)
    {
        $text = $request->input('text');

        if (!$text) {
            return response()->json([
                'status' => 'error',
                'message' => 'No text provided.'
            ]);
        }

        $refinedText = $this->callAIRefiner($text);

        return response()->json([
            'status' => 'success',
            'refined_text' => $refinedText
        ]);
    }

    private function callAIRefiner($text)
    {
        $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c'; // replace with your key
        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/chat/completions', [
            "model" => "deepseek-chat",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Refine the text and correct its grammar. Make it professional. Add relevant content if needed. Only return the modified text without any explanation."
                ],
                [
                    "role" => "user",
                    "content" => $text
                ]
            ]
        ]);

        $json = $response->json();

        return $json['choices'][0]['message']['content'] ?? $text;
    }





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

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } elseif (Auth::user()->userType == "Staff") {

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('userId', $authId)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } else {

            $childids = Childparent::where('parentid', $authId)->pluck('childid');
            $observationIds = ObservationChild::whereIn('childId', $childids)
                ->pluck('observationId')
                ->unique()
                ->toArray();
            // dd($childids);
            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->whereIn('id', $observationIds)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        }

        //  dd($observations);

        return view('observations.index', compact('observations', 'centers'));
    }



    public function applyFilters(Request $request)
    {
        //    dd($request->all());
        try {

            $centerid = Session('user_center_id');


            $query = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('centerid', $centerid);
            // Status filter
            if ($request->has('observations') && !empty($request->observations)) {
                $statusFilters = $request->observations;
                if (!in_array('All', $statusFilters)) {
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
                $observationIds = ObservationChild::whereIn('childId', $childIds)
                    ->pluck('observationId')
                    ->unique()
                    ->toArray();

                if (!empty($observationIds)) {
                    $query->whereIn('id', $observationIds);
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
                        $query->where('userId', Auth::id());
                    }

                    // ✅ If specific staff IDs are selected (as string IDs)
                    else {
                        $query->whereIn('userId', $authorFilters);
                    }
                }
            }


            $user = Auth::user();
            if ($user->userType === 'Staff') {
                $query->where('userId', Auth::id());
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
            $observations = $query->get();

            // Format the observations for response
            $formattedObservations = $observations->map(function ($observation) {
                return [
                    'id' => $observation->id,
                    'title' => html_entity_decode($observation->title ?? ''),
                    'obestitle' => $observation->obestitle ?? '',
                    'status' => $observation->status,
                    'media' => $observation->media->first(),
                    'mediaType' => $observation->observationsMediaType,
                    'userName' => $observation->user->name ?? 'Unknown',
                    'date_added' => Carbon::parse($observation->created_at)->format('d.m.Y'),
                    'created_at' => $observation->created_at->format('Y-m-d H:i:s'),
                    'seen' => $observation->seen->map(function ($seen) {
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

            return response()->json([
                'status' => 'success',
                'message' => 'Filters applied successfully',
                'observations' => $formattedObservations,
                'userRole' => Auth::user()->userType,
                'count' => $formattedObservations->count()
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

    /**
     * Get children list for filter modal
     */
    public function getChildren(Request $request)
    {
        try {
            $user = Auth::user();
            $children = collect();

            if ($user->userType === 'Superadmin') {
                $children = $this->getChildrenForSuperadmin();
            } elseif ($user->userType === 'Staff') {
                $children = $this->getChildrenForStaff();
            } else {
                $children = $this->getChildrenForParent();
            }

            return response()->json([
                'children' => $children,
                'status' => 'success',
                'success' => true
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


    private function getChildrenForSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get all room IDs for the center
        $roomIds = Room::where('centerid', $centerid)->pluck('id');

        // Get all children in those rooms
        $children = Child::whereIn('room', $roomIds)->get();

        return $children;
    }


    private function getChildrenForStaff()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get room IDs from RoomStaff where staff is assigned
        $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');

        // Get room IDs where user is the owner (userId matches)
        $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');

        // Merge both collections and remove duplicates
        $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique();

        // Get all children in those rooms
        $children = Child::whereIn('room', $allRoomIds)->get();

        return $children;
    }

    private function getChildrenForParent()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $childids = Childparent::where('parentid', $authId)->pluck('childid');

        $children = Child::whereIn('id', $childids)->get();

        return $children;
    }

    private function getStaffForSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get all room IDs for the center
        $usersid = Usercenter::where('centerid', $centerid)->pluck('userid')->toArray();

        // Exclude current user and Superadmins
        $staff = User::whereIn('id', $usersid)
            ->where('userType', 'Staff')
            ->get();

        return $staff;
    }


    public function getStaff(Request $request)
    {
        try {
            $user = Auth::user();
            $children = collect();

            if ($user->userType === 'Superadmin') {
                $staff = $this->getStaffForSuperadmin();
            }
            // elseif($user->userType === 'Staff'){
            // $children = $this->getChildrenForStaff();
            // }else{
            // $children = $this->getChildrenForParent();
            // }

            return response()->json([
                'staff' => $staff,
                'status' => 'success',
                'success' => true
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



    private function getroomsforSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $rooms = Room::where('centerid', $centerid)->get();
        return $rooms;
    }

    private function getroomsforStaff()
    {
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



    public function getrooms()
    {
        try {
            $user = Auth::user();
            $rooms = collect();

            if ($user->userType === 'Superadmin') {
                $rooms = $this->getroomsforSuperadmin();
            } else {
                $rooms = $this->getroomsforStaff();
            }

            return response()->json([
                'rooms' => $rooms,
                'status' => 'success',
                'success' => true
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




    public function storepage($id = null, $activeTab = 'observation', $activesubTab = 'MONTESSORI')
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        $observation = null;
        if ($id) {
            $observation = Observation::with(['media', 'child.child', 'montessoriLinks', 'eylfLinks', 'devMilestoneSubs', 'links'])->find($id);
        }

        $childrens = $observation
            ? $observation->child->pluck('child')->filter()
            : collect();


        $rooms = collect();

        if ($observation && $observation->room) {
            $roomIds = explode(',', $observation->room); // Convert comma-separated string to array
            $rooms = Room::whereIn('id', $roomIds)->get();
        }

        $subjects = MontessoriSubject::with(['activities.subActivities'])->get();
        $outcomes = EYLFOutcome::with('activities.subActivities')->get();
        $milestones = DevMilestone::with('mains.subs')->get();

        return view('observations.storeObservation', compact('centers', 'observation', 'childrens', 'activeTab', 'rooms', 'activesubTab', 'subjects', 'outcomes', 'milestones'));
    }


    public function storeMontessoriData(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'observationId' => 'required|exists:observation,id',
            'subactivities' => 'array',
            'subactivities.*.idSubActivity' => 'required|integer',
            'subactivities.*.assesment' => 'required|in:Introduced,Working,Completed',
        ]);

        // Delete existing data
        ObservationMontessori::where('observationId', $request->observationId)->delete();

        // Insert new data
        foreach ($request->subactivities as $entry) {
            ObservationMontessori::create([
                'observationId' => $request->observationId,
                'idSubActivity' => $entry['idSubActivity'],
                'assesment' => $entry['assesment'],
                'idExtra' => 0 // or other default
            ]);
        }

        return response()->json(['message' => 'Saved successfully', 'status' => 'success', 'id' => $request->observationId]);
    }


    public function storeEylfData(Request $request)
    {
        $request->validate([
            'observationId' => 'required|exists:observation,id',
            'subactivityIds' => 'array',
            'subactivityIds.*' => 'integer|exists:eylfsubactivity,id'
        ]);

        ObservationEYLF::where('observationId', $request->observationId)->delete();

        foreach ($request->subactivityIds ?? [] as $subId) {
            $activityid = EYLFSubActivity::find($subId)->activityid ?? null;
            if ($activityid) {
                ObservationEYLF::create([
                    'observationId' => $request->observationId,
                    'eylfSubactivityId' => $subId,
                    'eylfActivityId' => $activityid
                ]);
            }
        }

        return response()->json(['status' => 'success', 'id' => $request->observationId]);
    }



    public function storeDevMilestone(Request $req)
    {
        // dd($req->all());
        $req->validate([
            'observationId' => 'required|exists:observation,id',
            'selections' => 'array',
            'selections.*.idSub' => 'required|integer',
            'selections.*.assessment' => 'required|in:Introduced,Working towards,Achieved',
        ]);

        ObservationDevMilestoneSub::where('observationId', $req->observationId)->delete();

        foreach ($req->selections as $sel) {
            ObservationDevMilestoneSub::create([
                'observationId' => $req->observationId,
                'devMilestoneId' => $sel['idSub'],
                'assessment' => $sel['assessment']
            ]);
        }

        return response()->json(['status' => 'success', 'id' => $req->observationId]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'observationId' => 'required|exists:observation,id',
            'status' => 'required|in:Published,Draft'
        ]);

        $observation = Observation::find($request->observationId);
        $observation->status = $request->status;
        $observation->save();

        return response()->json(['status' => 'success', 'id' => $observation->id]);
    }


    public function view($id)
    {
        $observation = Observation::with([
            'media',
            'child.child',
            'montessoriLinks.subActivity.activity.subject',
            'eylfLinks.subActivity.activity.outcome',
            'devMilestoneSubs.devMilestone.main',
            'devMilestoneSubs.devMilestone.milestone'
        ])->findOrFail($id);

        return view('observations.view', compact('observation'));
    }

    public function linkobservationdata(Request $request)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');
        $search = $request->query('search');
        $obsId = $request->query('obsId'); // Current observation ID for checking existing links

        $observations = Observation::with(['media', 'child.child', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('obestitle', 'like', "%{$search}%");
            })
            ->where('centerid', $centerid)
            ->orderBy('created_at', 'desc')
            ->get();

        $linkedIds = ObservationLink::where('observationId', $obsId)
            ->where('linktype', 'OBSERVATION')
            ->pluck('linkid')
            ->toArray();

        return response()->json([
            'observations' => $observations,
            'linked_ids' => $linkedIds
        ]);
    }


    public function storelinkobservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obsId' => 'required|exists:observation,id',
            'observation_ids' => 'required|array',
            'observation_ids.*' => 'exists:observation,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $observationId = $request->input('obsId');

        ObservationLink::where('observationId', $observationId)->delete();

        $linkIds = $request->input('observation_ids');

        foreach ($linkIds as $linkId) {
            ObservationLink::create([
                'observationId' => $observationId,
                'linkid' => $linkId,
                'linktype' => 'OBSERVATION',
            ]);
        }

        return response()->json(['message' => 'Links saved successfully.', 'id' => $observationId]);
    }



    // public function store(Request $request)
    // {
    //     // Get PHP upload limits
    //     $uploadMaxSize = min(
    //         $this->convertToBytes(ini_get('upload_max_filesize')),
    //         $this->convertToBytes(ini_get('post_max_size'))
    //     );

    //     // Validate input
    //     $validator = Validator::make($request->all(), [
    //         'selected_rooms'   => 'required',
    //         'obestitle'        => 'required|string|max:255',
    //         'title'            => 'required|string|max:255',
    //         'notes'            => 'required|string',
    //         'reflection'       => 'required|string',
    //         'child_voice'      => 'required|string',
    //         'future_plan'      => 'required|string',
    //         'selected_children'=> 'required|string',
    //         'media'            => 'required|array|min:1',
    //         'media.*'          => "file|mimes:jpeg,png,jpg,gif,webp|max:" . intval($uploadMaxSize / 1024), // Convert to KB
    //     ], [
    //         'media.required' => 'At least one media file is required.',
    //         'media.*.max' => 'Each file must be smaller than ' . ($uploadMaxSize / 1024 / 1024) . 'MB.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $authId = Auth::user()->id;
    //         $centerid = Session('user_center_id');

    //         $observation = new Observation();
    //         $observation->room = $request->input('selected_rooms');
    //         $observation->obestitle = $request->input('obestitle');
    //         $observation->title = $request->input('title');
    //         $observation->notes = $request->input('notes');
    //         $observation->userId = $authId;
    //         $observation->centerid = $centerid;
    //         $observation->reflection = $request->input('reflection');
    //         $observation->child_voice = $request->input('child_voice');
    //         $observation->future_plan = $request->input('future_plan');
    //         $observation->save();

    //         $observationId = $observation->id;

    //         $selectedChildren = explode(',', $request->input('selected_children'));
    //         foreach ($selectedChildren as $childId) {
    //             if (trim($childId) !== '') {
    //                 ObservationChild::create([
    //                     'observationId' => $observationId,
    //                     'childId' => trim($childId),
    //                 ]);
    //             }
    //         }

    //         $manager = new ImageManager(new GdDriver());

    //         foreach ($request->file('media') as $file) {
    //             if ($file->isValid()) {
    //                 $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $destinationPath = public_path('uploads/Observation');

    //                 if (!file_exists($destinationPath)) {
    //                     mkdir($destinationPath, 0777, true);
    //                 }

    //                 if ($file->getSize() > 1024 * 1024) {
    //                     $image = $manager->read($file->getPathname());
    //                     $image->scale(1920);
    //                     $image->save($destinationPath . '/' . $filename, 75);
    //                 } else {
    //                     $file->move($destinationPath, $filename);
    //                 }

    //                 ObservationMedia::create([
    //                     'observationId' => $observationId,
    //                     'mediaUrl' => 'uploads/Observation/' . $filename,
    //                     'mediaType' => $file->getClientMimeType(),
    //                 ]);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json(['status' => 'success', 'message' => 'Observation saved successfully.','id' => $observationId]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Observation Store Failed: ' . $e->getMessage());

    //         return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
    //     }
    // }


    public function store(Request $request)
    {

        $uploadMaxSize = min(
            $this->convertToBytes(ini_get('upload_max_filesize')),
            $this->convertToBytes(ini_get('post_max_size'))
        );

        $isEdit = $request->filled('id');

        $rules = [
            'selected_rooms'    => 'required',
            'obestitle'         => 'required|string|max:255',
            'title'             => 'required|string|max:255',
            'notes'             => 'required|string',
            'reflection'        => 'required|string',
            'child_voice'       => 'required|string',
            'future_plan'       => 'required|string',
            'selected_children' => 'required|string',
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

            $observation = $isEdit
                ? Observation::findOrFail($request->id)
                : new Observation();

            $observation->room         = $request->input('selected_rooms');
            $observation->obestitle    = $request->input('obestitle');
            $observation->title        = $request->input('title');
            $observation->notes        = $request->input('notes');
            $observation->reflection   = $request->input('reflection');
            $observation->child_voice  = $request->input('child_voice');
            $observation->future_plan  = $request->input('future_plan');
            $observation->userId       = $authId;
            $observation->centerid     = $centerid;
            $observation->save();

            $observationId = $observation->id;

            // Replace all existing ObservationChild records
            ObservationChild::where('observationId', $observationId)->delete();

            $selectedChildren = explode(',', $request->input('selected_children'));
            foreach ($selectedChildren as $childId) {
                if (trim($childId) !== '') {
                    ObservationChild::create([
                        'observationId' => $observationId,
                        'childId' => trim($childId),
                    ]);
                }
            }

            // Process uploaded media only if present
            if ($request->hasFile('media')) {
                $manager = new ImageManager(new GdDriver());

                foreach ($request->file('media') as $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('uploads/Observation');

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

                        ObservationMedia::create([
                            'observationId' => $observationId,
                            'mediaUrl' => 'uploads/Observation/' . $filename,
                            'mediaType' => $file->getClientMimeType(),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $isEdit ? 'Observation updated successfully.' : 'Observation saved successfully.',
                'id' => $observationId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Observation Store/Update Failed: ' . $e->getMessage());

            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
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
        $media = ObservationMedia::findOrFail($id);

        // Optionally delete the file from storage
        if (file_exists(public_path($media->mediaUrl))) {
            @unlink(public_path($media->mediaUrl));
        }

        $media->delete();

        return response()->json(['status' => 'success']);
    }

    public function print($id)
    {
        try {
            // Fetch the observation with all related data using the same relationships as view method
            $observation = Observation::with([
                'media',
                'child.child',
                'montessoriLinks.subActivity.activity.subject',
                'eylfLinks.subActivity.activity.outcome',
                'devMilestoneSubs.devMilestone.main',
                'devMilestoneSubs.devMilestone.milestone',
                'user'  // Assuming you have a room relationship
            ])->findOrFail($id);


            // Only track if the user is authenticated and is a Parent
            $user = Auth::user();
            if ($user && $user->userType === 'Parent') {
                // Check if already seen
                $alreadySeen = SeenObservation::where('user_id', $user->id)
                    ->where('observation_id', $id)
                    ->exists();

                if (! $alreadySeen) {
                    SeenObservation::create([
                        'user_id' => $user->id,
                        'observation_id' => $id
                    ]);
                }
            }


            $roomNames = Room::whereIn('id', explode(',', $observation->room))
                ->pluck('name')
                ->implode(', '); // or ->toArray() if you prefer array

            return view('observations.print', compact('observation', 'roomNames'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Observation not found or error occurred while loading the print view.');
        }
    }
}
