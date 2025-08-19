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
use App\Models\Reflection;
use App\Models\SnapshotMedia;
use App\Models\SnapshotChild;
use App\Models\Snapshot;
use App\Models\ObservationMontessori;
use App\Models\Permission;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Userprogressplan;
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
use App\Notifications\ObservationAdded;


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
                    "content" => "Polish the text for grammar, clarity, and professionalism. Add relevant content where appropriate. Return only the revised text without any explanations. Make it precise."
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

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user','comments'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } elseif (Auth::user()->userType == "Staff") {

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user','comments'])
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
            $observations = Observation::with(['user', 'child', 'media', 'Seen.user','comments'])
                ->whereIn('id', $observationIds)
                ->where('status',"Published")
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


            $query = Observation::with(['user', 'child', 'media', 'Seen.user','comments'])
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
                                'userType' => $seen->user->userType,
                                'imageUrl' => $seen->user->imageUrl,
                                'gender' => $seen->user->gender,
                            ];
                        }
                        return null;
                    })->filter(),

                  // Add comments data
        'comments' => $observation->comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comments' => $comment->comments,
                'userId' => $comment->userId,
                'user_name' => $comment->user->name ?? 'Unknown',
                'created_at_human' => $comment->created_at->diffForHumans(),
                'created_at' => $comment->created_at->format('Y-m-d H:i:s'),
            ];
        }),
        

                    'userRole' => Auth::user()->userType,
                    'currentUserId' => Auth::id(),
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
        // dd('here');
        try {
            $user = Auth::user();
            $children = collect();

            if ($user->userType === 'Superadmin' || $user->userType === 'Staff') {
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

        $rooms = Room::whereIn('id', $allRoomIds)->get();
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
        // dd($id);
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

        $childIds = ObservationChild::where('observationId', $request->observationId)->pluck('childId')->toArray();

        Userprogressplan::where('observationId', $request->observationId)->delete();


        // Insert into Userprogressplan
        foreach ($childIds as $childId) {
            foreach ($request->subactivities as $entry) {
                Userprogressplan::create([
                    'observationId' => $request->observationId,
                    'childid'       => $childId,
                    'subid'         => $entry['idSubActivity'],
                    'status'        => $entry['assesment'],
                    'created_by'    => Auth::id(),
                    'created_at'    => now(),
                    'updated_by'    => Auth::id(),
                    'updated_at'    => now(),
                ]);
            }
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

        ObservationLink::where('observationId', $observationId) ->where('linktype', 'OBSERVATION')->delete();

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






    public function linkreflectiondata(Request $request)
{
    $authId = Auth::user()->id;
    $centerid = Session('user_center_id');
    $search = $request->query('search');
    $obsId = $request->query('obsId'); // Current observation ID for checking existing links

    $reflections = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
        ->when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%");
        })
        ->where('centerid', $centerid)
        ->orderBy('created_at', 'desc')
        ->get();

    $linkedIds = ObservationLink::where('observationId', $obsId)
        ->where('linktype', 'REFLECTION')
        ->pluck('linkid')
        ->toArray();

    return response()->json([
        'reflections' => $reflections,
        'linked_ids' => $linkedIds
    ]);
}

public function storelinkreflection(Request $request)
{
    $validator = Validator::make($request->all(), [
        'obsId' => 'required|exists:observation,id',
        'reflection_ids' => 'required|array',
        'reflection_ids.*' => 'exists:reflection,id', // Adjust table name if needed
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $observationId = $request->input('obsId');

    // Remove existing reflection links for this observation
    ObservationLink::where('observationId', $observationId)
        ->where('linktype', 'REFLECTION')
        ->delete();

    $linkIds = $request->input('reflection_ids');

    foreach ($linkIds as $linkId) {
        ObservationLink::create([
            'observationId' => $observationId,
            'linkid' => $linkId,
            'linktype' => 'REFLECTION',
        ]);
    }

    return response()->json(['message' => 'Reflection links saved successfully.', 'id' => $observationId]);
}




public function linkprogramplandata(Request $request)
{
    $authId = Auth::user()->id;
    $centerid = Session('user_center_id');
    $search = $request->query('search');
    $obsId = $request->query('obsId'); // Current observation ID for checking existing links

    // Month names for search
    $monthNames = [
        'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4,
        'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
        'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12
    ];

    $programPlans = ProgramPlanTemplateDetailsAdd::with(['room', 'creator'])
        ->when($search, function ($query, $search) use ($monthNames) {
            $searchLower = strtolower($search);
            $monthNumber = null;
            
            // Check if search matches any month name
            foreach ($monthNames as $monthName => $monthNum) {
                if (strpos($monthName, $searchLower) !== false) {
                    $monthNumber = $monthNum;
                    break;
                }
            }
            
            if ($monthNumber) {
                return $query->where('months', $monthNumber);
            }
            
            // If no month found, search in year as well
            return $query->where('year', 'like', "%{$search}%");
        })
        ->where('status', 'Published')
        ->where('centerid', $centerid)
        ->orderBy('created_at', 'desc')
        ->get();

    $linkedIds = ObservationLink::where('observationId', $obsId)
        ->where('linktype', 'PROGRAMPLAN')
        ->pluck('linkid')
        ->toArray();

    return response()->json([
        'program_plans' => $programPlans,
        'linked_ids' => $linkedIds
    ]);
}

public function storelinkprogramplan(Request $request)
{
    $validator = Validator::make($request->all(), [
        'obsId' => 'required|exists:observation,id',
        'program_plan_ids' => 'required|array',
        'program_plan_ids.*' => 'exists:programplantemplatedetailsadd,id', // Adjust table name if needed
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $observationId = $request->input('obsId');

    // Remove existing program plan links for this observation
    ObservationLink::where('observationId', $observationId)
        ->where('linktype', 'PROGRAMPLAN')
        ->delete();

    $linkIds = $request->input('program_plan_ids');

    foreach ($linkIds as $linkId) {
        ObservationLink::create([
            'observationId' => $observationId,
            'linkid' => $linkId,
            'linktype' => 'PROGRAMPLAN',
        ]);
    }

    return response()->json(['message' => 'Program Plan links saved successfully.', 'id' => $observationId]);
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

public function storeTitle(Request $request) {
    $request->validate([
        'obestitle' => 'required'
    ]);

    // dd($request->all());

    $centerid = Session('user_center_id');
    
    try {
        $Observation = new Observation();
        $Observation->obestitle = $request->obestitle;
        $Observation->centerid = $centerid;
        $Observation->userId = Auth::user()->userid;
        $Observation->save();
        
        // dd($Observation->id); // Comment this out!
        
        return redirect()->route('observation.addnew.optional', [
            'id' => $Observation->id,
            'tab' => 'observation',
            'tab2' => 'MONTESSORI'
        ])->with('success', 'Observation created successfully.');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}



public function autosaveobservation(Request $request)
{
    // Custom validation with fallback
    $validator = Validator::make($request->all(), [
        'obestitle'      => 'required',
        'title'          => 'nullable',
        'notes'          => 'nullable',
        'reflection'     => 'nullable',
        'child_voice'    => 'nullable',
        'future_plan'    => 'nullable',
        'observation_id' => 'required|integer',
    ]);

    if ($validator->fails()) {
        // Return JSON with validation errors instead of default 422
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed.',
            'errors' => $validator->errors()
        ], 400);
    }

    try {
        // Find existing observation
        $observation = Observation::find($request->observation_id);

        if (!$observation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Observation not found.'
            ], 404);
        }

        // Update fields
        $observation->obestitle   = $request->obestitle;
        $observation->title       = $request->title;
        $observation->notes       = $request->notes;
        $observation->reflection  = $request->reflection;
        $observation->child_voice = $request->child_voice;
        $observation->future_plan = $request->future_plan;

        $observation->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Observation autosaved successfully.',
            'observation_id' => $observation->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}



    public function store(Request $request)
    {

        $uploadMaxSize = min(
            $this->convertToBytes(ini_get('upload_max_filesize')),
            $this->convertToBytes(ini_get('post_max_size'))
        );

        $isEdit = $request->filled('id');

        $rules = [
            'selected_rooms'    => 'required',
            'obestitle'         => 'required|string',
            'title'             => 'required|string',
            'notes'             => 'nullable|string',
            'reflection'        => 'nullable|string',
            'child_voice'       => 'nullable|string',
            'future_plan'       => 'nullable|string',
            'selected_children' => 'required|string',
        ];




        if (!$isEdit) {
            $rules['media'] = 'nullable|array|min:1';
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
            if (!$isEdit) {
                $observation->userId   = $authId; // Only set when creating
            }
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


            $selectedChildren = explode(',', $request->input('selected_children'));

            foreach ($selectedChildren as $childId) {
                $childId = trim($childId);
                if ($childId !== '') {
                    // Get all related parent entries for this child
                    $parentRelations = Childparent::where('childid', $childId)->get();

                    foreach ($parentRelations as $relation) {
                        $parentUser = User::find($relation->parentid); // assuming users table stores parent records

                        if ($parentUser) {
                            $parentUser->notify(new ObservationAdded($observation));
                        }
                    }
                }
            }

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





    public function snapshotindex()
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

            $snapshots = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } elseif (Auth::user()->userType == "Staff") {

            $snapshots = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('createdBy', $authId)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        } else {

            $childids = Childparent::where('parentid', $authId)->pluck('childid');
            $snapshotid = SnapshotChild::whereIn('childid', $childids)
                ->pluck('snapshotid')
                ->unique()
                ->toArray();
            // dd($childids);
            $snapshots = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->whereIn('id', $snapshotid)
                ->orderBy('id', 'desc') // optional: to show latest first
                ->paginate(10); // 10 items per page

        }


        $allRoomIds = $snapshots->pluck('roomids')
            ->flatMap(function ($roomids) {
                return explode(',', $roomids);
            })
            ->unique()
            ->filter(); // Use filter to remove any empty values


        $rooms = collect();
        if ($allRoomIds->isNotEmpty()) {
            $rooms = Room::whereIn('id', $allRoomIds)->get()->keyBy('id');
        }


        $snapshots->each(function ($snapshot) use ($rooms) {
            $roomIds = explode(',', $snapshot->roomids);
            $snapshot->rooms = $rooms->whereIn('id', $roomIds)->values();
        });

       $permissions = Permission::where('userid', Auth::user()->userid)->first();

        //  dd($snapshots);

        return view('observations.snapshotindex', compact('snapshots', 'centers','permissions'));
    }



    public function snapshotindexstorepage($id = null)
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $reflection = null;
        if ($id) {
            $reflection = Snapshot::with(['creator', 'center', 'children', 'media'])->find($id);
        }

        $childrens = $reflection
            ? $reflection->children->pluck('child')->filter()
            : collect();

        $rooms = collect();

        if ($reflection && $reflection->roomids) {
            $roomIds = explode(',', $reflection->roomids); // Convert comma-separated string to array
            $rooms = Room::whereIn('id', $roomIds)->get();
        }
$educators = collect();
        if($reflection && $reflection->educators){
  $educatorsIds = explode(',', $reflection->educators); // Convert comma-separated string to array
            $educators = User::whereIn('userid', $educatorsIds)->get();
        }

        //     $staffs = $reflection
        // ? $reflection->staff->pluck('staff')->filter()
        // : collect();

        $outcomes = EYLFOutcome::with('activities.subActivities')->get();

        // $Usercenters = Usercenter::where('centerid',$centerid)->pluck('userid');
        // $educators = User::where('userType','Staff')->whereIn('userid',$Usercenters)->where('status','ACTIVE')->get();
        // dd( $educators);





        return view('observations.storesnapshots', compact('reflection', 'childrens', 'rooms', 'outcomes','educators'));
    }




    public function snapshotstore(Request $request)
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
            'selected_children' => 'required|string',
            'selected_staff' => 'required|string'
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
                ? Snapshot::findOrFail($request->id)
                : new Snapshot();

            $reflection->roomids      = $request->input('selected_rooms');
            $reflection->title        = $request->input('title');
            $reflection->about        = $request->input('about');
            $reflection->centerid     = $centerid;
            $reflection->createdBy    = $authId;
             $reflection->educators    = $request->input('selected_staff');

            $reflection->save();

            $reflectionId = $reflection->id;

            // Replace all existing reflectionchilds records
            SnapshotChild::where('snapshotid', $reflectionId)->delete();

            $selectedChildren = explode(',', $request->input('selected_children'));
            foreach ($selectedChildren as $childId) {
                if (trim($childId) !== '') {
                    SnapshotChild::create([
                        'snapshotid' => $reflectionId,
                        'childid' => trim($childId),
                    ]);
                }
            }

            // Replace all existing reflectionstaffs records
            //    ReflectionStaff::where('reflectionid', $reflectionId)->delete();

            //    $selectedStaffs = explode(',', $request->input('selected_staff'));
            //    foreach ($selectedStaffs as $staffids) {
            //        if (trim($staffids) !== '') {
            //            ReflectionStaff::create([
            //                'reflectionid' => $reflectionId,
            //                'staffid' => trim($staffids),
            //            ]);
            //        }
            //    }




            // Process uploaded media only if present
            if ($request->hasFile('media')) {
                $manager = new ImageManager(new GdDriver());

                foreach ($request->file('media') as $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('uploads/Snapshots');

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

                        SnapshotMedia::create([
                            'snapshotid' => $reflectionId,
                            'mediaUrl' => 'uploads/Snapshots/' . $filename,
                            'mediaType' => $file->getClientMimeType(),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $isEdit ? 'Snapshot updated successfully.' : 'Snapshot saved successfully.',
                'id' => $reflectionId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Snapshot Store/Update Failed: ' . $e->getMessage());

            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

public function viewSnapShot($id)
{
    // Fetch snapshot or fail gracefully
    $snapshots = Snapshot::findOrFail($id);

    // Get children linked to this snapshot
    $snapchildren = SnapshotChild::where('snapshotid', $id)->pluck('childid');
    $childrens = Child::whereIn('id', $snapchildren)->get();
    // dd( $childrens);

    // Get educators (stored as CSV in snapshot->educators)
    $snapeducators = $snapshots->educators;
    $educators = collect(); // default empty

    if (!empty($snapeducators)) {
        $educatorsarray = explode(',', $snapeducators);
        $educators = User::whereIn('userid', $educatorsarray)->get();
    }

     $snaprooms = $snapshots->roomids;
    //  dd($snaprooms );
    $rooms = collect(); // default empty

    if (!empty($snaprooms)) {
        $roomsarray = explode(',', $snaprooms);
        $rooms = Room::whereIn('id', $roomsarray)->get();
    }


    // Get media files linked to snapshot
    $snapmedia = SnapshotMedia::where('snapshotid', $id)->get();

    return view('observations.viewsnapshot', compact(
        'childrens',
        'snapshots',
        'educators',
        'snapmedia',
        'rooms'
    ));
}



    public function snapshotdestroyimage($id)
    {
        $media = SnapshotMedia::findOrFail($id);

        // Optionally delete the file from storage
        if (file_exists(public_path($media->mediaUrl))) {
            @unlink(public_path($media->mediaUrl));
        }

        $media->delete();

        return response()->json(['status' => 'success']);
    }

    public function snapshotupdateStatus(Request $request)
    {
        $request->validate([
            'reflectionId' => 'required|exists:snapshot,id',
            'status' => 'required|in:Published,Draft'
        ]);

        $reflection = Snapshot::find($request->reflectionId);
        $reflection->status = $request->status;
        $reflection->save();

        return response()->json(['status' => 'success', 'id' => $reflection->id]);
    }


    public function snapshotsdelete($id)
    {
        $snapshot = Snapshot::findOrFail($id);
        $snapshot->delete();

        return response()->json(['status' => 'success']);
    }
    

    public function changeCreatedAt(Request $request)
    {
        $obs = Observation::find($request->id);
        if(!$obs) return response()->json(['success' => false, 'message' => 'Observation not found']);

        $newDate = \Carbon\Carbon::parse($request->created_at)->addDay();

        $obs->created_at = $newDate;
        $obs->save();

        return response()->json(['success' => true]);
    }

    public function commentstore(Request $request, Observation $observation) {
        $validated = $request->validate([
            'comments' => 'required|string|max:1000'
        ]);
        $comment = $observation->comments()->create([
            'userId' => auth()->id(),
            'comments' => $validated['comments'],
        ]);
        return response()->json([
            'success' => true,
            'comment' => [
                'comments' => $comment->comments,
                'user_name' => $comment->user->name,
                'created_at' => $comment->created_at->diffForHumans()
            ]
        ]);
    }

    public function destroycomment(Request $request, ObservationComment $comment)
    {
        $user = $request->user();

        // Authorization: superadmin or owner of comment
        if ($user->userType === 'Superadmin' || $user->id === $comment->userId) {
            $comment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized to delete this comment'
        ], 403);
    }


    public function destroy($id)
    {
        try {
            // Find the observation by ID
            $observation = Observation::findOrFail($id);
            
            // Delete the observation
            $observation->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Observation deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting observation. Please try again.'
            ], 500);
        }
    }

    
}
