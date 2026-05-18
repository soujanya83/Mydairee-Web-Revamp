<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnnouncementsModel;
use App\Models\Child;
use App\Models\RecipeModel;
use App\Models\Snapshot;
use App\Models\Observation;
use App\Models\Reflection;
use App\Models\PTM;
use App\Models\PubicHoliday_Model;
use App\Models\User;
use App\Models\Room;
use App\Models\Usercenter;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\AnnouncementChildModel;
use App\Models\Childparent;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\ReEnrolment;

class Dashboard extends Controller
{

    
    public function university()
{
    $totalUsers = User::count();
    $totalSuperadmin = User::where('userType', 'Superadmin')->count();
    $totalStaff = User::where('userType', 'Staff')->count();
    $totalParent = User::where('userType', 'Parent')->count();
    $totalCenter = Usercenter::count();
    $totalRooms = Room::count();
    $totalRecipes = RecipeModel::count();

    return response()->json([
        'status' => true,
        'message' => 'University dashboard stats fetched successfully',
        'data' => [
            'totalUsers'      => $totalUsers,
            'totalSuperadmin' => $totalSuperadmin,
            'totalStaff'      => $totalStaff,
            'totalParent'     => $totalParent,
            'totalCenter'     => $totalCenter,
            'totalRooms'      => $totalRooms,
            'totalRecipes'    => $totalRecipes,
        ]
    ]);
}


      // New University Dashboard Function
        public function newdashboard(\Illuminate\Http\Request $request)
        {
            $auth = Auth::user();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $userid = $auth->userid ?? null;


            $centerid = $request->input('centerid')
                ?? $request->input('center_id')
                ?? $request->header('X-Center-Id');

            if ($centerid === null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Center id is required'
                ], 400);
            }

            if (!filter_var($centerid, FILTER_VALIDATE_INT)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid center id'
                ], 400);
            }

            $centerid = (int) $centerid;

            /*
            |--------------------------------------------------------------------------
            | Authorization Check
            |--------------------------------------------------------------------------
            */

            $isAdmin = isset($auth->admin) && $auth->admin == '1';

            $isAssociated = Usercenter::where('userid', $userid)
                ->where('centerid', $centerid)
                ->exists();

            // Superadmin can access any center
            // Normal users only their mapped centers
            if (!$isAdmin && !$isAssociated) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized for this center'
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | Get All User IDs Belonging To Current Center
            |--------------------------------------------------------------------------
            */

            $centerUserIds = Usercenter::where('centerid', $centerid)
                ->distinct()
                ->pluck('userid');

            /*
            |--------------------------------------------------------------------------
            | Dashboard Counts (CENTER SCOPED ONLY)
            |--------------------------------------------------------------------------
            */

            $totalUsers = User::whereIn('userid', $centerUserIds)
                ->where('status', 'ACTIVE')
                ->count();


            $totalStaff = User::whereIn('userid', $centerUserIds)
                ->where('userType', 'Staff')
                ->where('status', 'ACTIVE')
                ->count();

            $totalParent = User::whereIn('userid', $centerUserIds)
                ->where('userType', 'Parent')
                ->where('status', 'ACTIVE')
                ->count();

            $newEnrolmentsLastYear = ReEnrolment::whereDate('created_at', '>=', now()->subYear())
            ->count();

            $totalRooms = Room::where('centerid', $centerid)
                ->where('status', 'Active')
                ->count();

            $totalRecipes = RecipeModel::where('centerid', $centerid)
                ->count();

            $activeChildren = Child::where('centerid', $centerid)
                ->where('status', 'Active')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' => true,
                'message' => 'Dashboard fetched successfully',
                'data' => [
                    'centerid'         => $centerid,
                    'totalUsers'       => $totalUsers,
                    'totalStaff'       => $totalStaff,
                    'totalParent'      => $totalParent,
                    'newEnrolmentsLastYear' => $newEnrolmentsLastYear,
                    'totalRooms'       => $totalRooms,
                    'totalRecipes'     => $totalRecipes,
                    'activeChildren'   => $activeChildren,
                ]
            ]);
        }

        public function parentDashboard(Request $request)
        {
            $auth = Auth::user();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $userid = $auth->userid ?? null;
            $usertype = $auth->userType ?? null;

            // ✅ Check if user is a parent - reject superadmin/staff
            if (!$usertype || strtolower((string) $usertype) !== 'parent') {
                return response()->json([
                    'status' => false,
                    'message' => 'This endpoint is only for parents. Your account type (' . ($usertype ?? 'unknown') . ') does not have access to parent dashboard.',
                    'userType' => $usertype,
                    'endpoint' => 'parent-dashboard'
                ], 403);
            }

            $parentChildIds = collect();
            if ($userid && strtolower((string) $usertype) === 'parent') {
                $parentChildIds = Childparent::where('parentid', $userid)->pluck('childid')->values();
            }

            $requestedCenter = $request->input('centerid') ?? $request->input('center_id') ?? $request->header('X-Center-Id');
            $centerid = null;

            if ($requestedCenter !== null) {
                if (!filter_var($requestedCenter, FILTER_VALIDATE_INT)) {
                    return response()->json(['status' => false, 'message' => 'Invalid center id'], 400);
                }

                $centerid = (int) $requestedCenter;

                $isAdmin = isset($auth->admin) && $auth->admin == '1';
                $isAssociated = Usercenter::where('userid', $userid)->where('centerid', $centerid)->exists();
                $hasChildInCenter = $parentChildIds->isNotEmpty()
                    && Child::whereIn('id', $parentChildIds)->where('centerid', $centerid)->exists();

                if (!$isAdmin && !$isAssociated && !$hasChildInCenter) {
                    return response()->json(['status' => false, 'message' => 'Unauthorized for this center'], 403);
                }
            }

            if (!$centerid && $userid) {
                $centerid = Usercenter::where('userid', $userid)->value('centerid');
            }

            if (!$centerid && $parentChildIds->isNotEmpty()) {
                $centerid = Child::whereIn('id', $parentChildIds)->value('centerid');
            }

            if (!$centerid) {
                return response()->json([
                    'status' => false,
                    'message' => 'No center specified or associated with user',
                ], 400);
            }

            $staffusercenter = Usercenter::where('centerid', $centerid)->pluck('userid');

            // $totalUsers = User::whereIn('userid', $staffusercenter)->where('status', 'ACTIVE')->count();
            // $totalSuperadmin = User::where('admin', '1')->count();
            // $totalStaff = User::whereIn('userid', $staffusercenter)->where('userType', 'Staff')->where('status', 'ACTIVE')->count();
            // $totalParent = User::whereIn('userid', $staffusercenter)->where('userType', 'Parent')->where('status', 'ACTIVE')->count();
            // $totalCenter = Usercenter::where('centerid', $centerid)->where('userid', $userid)->count();
            // $totalRooms = Room::where('centerid', $centerid)->where('status', 'Active')->count();
            // $totalRecipes = RecipeModel::where('centerid', $centerid)->count();
            // $activeChildren = Child::where('centerid', $centerid)->where('status', 'Active')->count();

            $recentPtms = PTM::with(['children', 'ptmDates', 'ptmSlots'])
                ->where('centerid', $centerid)
                ->when(strtolower((string) $usertype) === 'parent', function ($query) use ($parentChildIds) {
                    $query->where('status', 'Published');

                    if ($parentChildIds->isNotEmpty()) {
                        $query->whereHas('children', function ($childQuery) use ($parentChildIds) {
                            $childQuery->whereIn('child.id', $parentChildIds);
                        });
                    }
                })
                ->orderBy('id', 'desc')
                ->take(5)
                ->get()
                ->map(function ($ptm) {
                    if ($ptm->ptmDates && $ptm->ptmDates->count() > 0) {
                        $ptm->ptm_date = $ptm->ptmDates->first()->date;
                    }

                    return $ptm;
                });

            $recentObservations = $this->getParentObservations($centerid, $parentChildIds, $usertype);
            $recentReflections = $this->getParentReflections($centerid, $parentChildIds, $usertype);
            $recentSnapshots = $this->getParentSnapshots($centerid, $parentChildIds, $usertype, $userid);
            $snapshotCount = $recentSnapshots->count();
            $announcements = $this->getParentAnnouncements($centerid, $parentChildIds, $usertype);
            $birthdays = $this->getParentChildren($centerid, $parentChildIds, $usertype);
            $holidays = $this->getParentHolidays($centerid);

            return response()->json([
                'status' => true,
                'message' => 'Parent dashboard fetched successfully',
                'data' => [
                    'centerid' => $centerid,
                    // 'stats' => [
                    //     'totalUsers' => $totalUsers,
                    //     'totalSuperadmin' => $totalSuperadmin,
                    //     'totalStaff' => $totalStaff,
                    //     'totalParent' => $totalParent,
                    //     'totalCenter' => $totalCenter,
                    //     'totalRooms' => $totalRooms,
                    //     'totalRecipes' => $totalRecipes,
                    //     'activeChildren' => $activeChildren,
                    //     'snapshotCount' => $snapshotCount,
                    // ],
                    'children' => $this->formatChildrenForApi($birthdays),
                    'birthdays' => $this->formatBirthdayChildren($birthdays),
                    'announcements' => $announcements,
                    'events' => $announcements,
                    'holidays' => $holidays,
                    'ptms' => $recentPtms->values(),
                    'observations' => $recentObservations->values(),
                    'reflections' => $recentReflections->values(),
                    'snapshots' => $recentSnapshots->values(),
                    'calendarEvents' => $this->buildCalendarEvents($announcements, $birthdays, $holidays, $recentPtms),
                ],
            ]);
        }

        private function getParentObservations(int $centerid, Collection $parentChildIds, $usertype): Collection
        {
            if (strtolower((string) $usertype) === 'parent') {
                if ($parentChildIds->isEmpty()) {
                    return collect();
                }

                $obsIds = \App\Models\ObservationChild::whereIn('childId', $parentChildIds)->pluck('observationId')->unique();

                return Observation::with('media')
                    ->whereIn('id', $obsIds)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }

            return Observation::with('media')
                ->where('centerid', $centerid)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        private function getParentReflections(int $centerid, Collection $parentChildIds, $usertype): Collection
        {
            $query = Reflection::with('media')
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc')
                ->take(5);

            if (strtolower((string) $usertype) === 'parent') {
                $query->where('status', 'Published');

                if ($parentChildIds->isEmpty()) {
                    return collect();
                }

                $reflectionIds = \App\Models\ReflectionChild::whereIn('childId', $parentChildIds)->pluck('reflectionid');

                return $query->whereIn('id', $reflectionIds)->get();
            }

            return $query->get();
        }

        private function getParentSnapshots(int $centerid, Collection $parentChildIds, $usertype, $userid): Collection
        {
            if (strtolower((string) $usertype) === 'parent') {
                if ($parentChildIds->isEmpty()) {
                    return collect();
                }

                $snapshotIds = \App\Models\SnapshotChild::whereIn('childid', $parentChildIds)->pluck('snapshotid')->unique();

                return Snapshot::with(['media'])
                    ->whereIn('id', $snapshotIds)
                    ->orderBy('id', 'desc')
                    ->take(5)
                    ->get();
            }

            if (strtolower((string) $usertype) === 'staff' && $userid) {
                return Snapshot::with(['media'])
                    ->where('createdBy', $userid)
                    ->orderBy('id', 'desc')
                    ->take(5)
                    ->get();
            }

            return Snapshot::with(['media'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
        }

        private function getParentAnnouncements(int $centerid, Collection $parentChildIds, $usertype): Collection
        {
            $query = AnnouncementsModel::query();

            if (strtolower((string) $usertype) === 'parent') {
                if ($parentChildIds->isEmpty()) {
                    return collect();
                }

                $announcementIds = AnnouncementChildModel::whereIn('childid', $parentChildIds)->pluck('aid');

                return $query->whereIn('id', $announcementIds)
                    ->whereIn('audience', ['all', 'parents'])
                    ->where('status', 'sent')
                    ->orderBy('id', 'desc')
                    ->get()
                    ->map(function ($announcement) {
                        return [
                            'id' => $announcement->id,
                            'title' => $announcement->title,
                            'text' => $this->cleanText($announcement->text) ?? '',
                            'status' => $announcement->status ?? '',
                            'announcementMedia' => $announcement->announcementMedia ?? '',
                            'eventColor' => $announcement->eventColor ?? '',
                            'type' => $announcement->type ?? '',
                            'eventDate' => $this->safeFormatDate($announcement->eventDate, 'Y-m-d'),
                            'createdAt' => $this->safeFormatDate($announcement->createdAt, 'Y-m-d H:i:s'),
                            'start' => $this->safeFormatDate($announcement->eventDate ?? $announcement->createdAt, 'Y-m-d'),
                        ];
                    });
            }

            return $query->where('centerid', $centerid)
                ->where('status', 'sent')
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($announcement) {
                    return [
                        'id' => $announcement->id,
                        'title' => $announcement->title,
                        'text' => $this->cleanText($announcement->text) ?? '',
                        'status' => $announcement->status ?? '',
                        'announcementMedia' => $announcement->announcementMedia ?? '',
                        'eventColor' => $announcement->eventColor ?? '',
                        'type' => $announcement->type ?? '',
                        'eventDate' => $this->safeFormatDate($announcement->eventDate, 'Y-m-d'),
                        'createdAt' => $this->safeFormatDate($announcement->createdAt, 'Y-m-d H:i:s'),
                        'start' => $this->safeFormatDate($announcement->eventDate ?? $announcement->createdAt, 'Y-m-d'),
                    ];
                });
        }

        private function getParentChildren(int $centerid, Collection $parentChildIds, $usertype): Collection
        {
            if (strtolower((string) $usertype) === 'parent') {
                if ($parentChildIds->isEmpty()) {
                    return collect();
                }

                return Child::whereIn('id', $parentChildIds)->get();
            }

            return Child::where('centerid', $centerid)->get();
        }

        private function getParentHolidays(int $centerid): Collection
        {
            return PubicHoliday_Model::where('centerid', $centerid)
                ->get()
                ->map(function ($holiday) {
                    $date = null;

                    if (!empty($holiday->Holiday_date)) {
                        $date = $this->safeFormatDate($holiday->Holiday_date, 'Y-m-d');
                    } elseif (!empty($holiday->month) && !empty($holiday->date)) {
                        $date = Carbon::create(null, (int) $holiday->month, (int) $holiday->date)->format('Y-m-d');
                    }

                    return [
                        'id' => $holiday->id,
                        'date' => $date,
                        'state' => $holiday->state ?? '',
                        'occasion' => $holiday->occasion ?? 'Holiday',
                        'status' => $holiday->status ?? null,
                    ];
                });
        }

        private function formatChildrenForApi(Collection $children): Collection
        {
            return $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'lastname' => $child->lastname ?? '',
                    'dob' => $child->dob,
                    'gender' => $child->gender ?? '',
                    'imageUrl' => $child->imageUrl ?? '',
                    'status' => $child->status ?? '',
                    'centerid' => $child->centerid ?? null,
                ];
            });
        }

        private function formatBirthdayChildren(Collection $children): Collection
        {
            return $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'lastname' => $child->lastname ?? '',
                    'dob' => $child->dob,
                    'gender' => $child->gender ?? '',
                    'imageUrl' => $child->imageUrl ?? '',
                    'birthdayDate' => $this->birthdayCalendarDate($child->dob),
                    'age' => $this->calculateAge($child->dob),
                ];
            });
        }

        private function buildCalendarEvents(Collection $announcements, Collection $children, Collection $holidays, Collection $ptms): Collection
        {
            $grouped = [];

            $addItem = function (string $date, string $bucket, array $item) use (&$grouped) {
                if (!$date) {
                    return;
                }

                if (!isset($grouped[$date])) {
                    $grouped[$date] = [
                        'announcements' => [],
                        'normalEvents' => [],
                        'birthdays' => [],
                        'holidays' => [],
                        'ptms' => [],
                    ];
                }

                $grouped[$date][$bucket][] = $item;
            };

            foreach ($announcements as $announcement) {
                $date = $announcement['eventDate'] ?? null;
                if (!$date) {
                    continue;
                }

                if (($announcement['type'] ?? '') === 'announcement') {
                    $addItem($date, 'announcements', $announcement);
                } else {
                    $addItem($date, 'normalEvents', $announcement);
                }
            }

            foreach ($children as $child) {
                $date = $this->birthdayCalendarDate($child->dob);
                if ($date) {
                    $addItem($date, 'birthdays', [
                        'id' => $child->id,
                        'name' => $child->name,
                        'lastname' => $child->lastname ?? '',
                        'dob' => $child->dob,
                        'gender' => $child->gender ?? '',
                        'imageUrl' => $child->imageUrl ?? '',
                        'age' => $this->calculateAge($child->dob),
                    ]);
                }
            }

            foreach ($holidays as $holiday) {
                if (!empty($holiday['date'])) {
                    $addItem($holiday['date'], 'holidays', $holiday);
                }
            }

            foreach ($ptms as $ptm) {
                $date = $ptm->ptm_date ?? optional($ptm->ptmDates->first())->date ?? null;
                if ($date) {
                    $addItem($this->safeFormatDate($date, 'Y-m-d'), 'ptms', [
                        'id' => $ptm->id,
                        'title' => $ptm->title ?? '',
                        'objective' => $ptm->objective ?? '',
                        'ptmdate' => $this->safeFormatDate($date, 'Y-m-d'),
                        'slot' => $ptm->finalSlot ?? optional($ptm->ptmSlots->first())->slot,
                    ]);
                }
            }

            return collect($grouped)->map(function ($items, $date) {
                return [
                    'title' => '',
                    'date' => $date,
                    'allDay' => true,
                    'className' => 'merged-event',
                    'extendedProps' => $items,
                ];
            })->values();
        }

        private function birthdayCalendarDate($dob): ?string
        {
            try {
                if (!$dob) {
                    return null;
                }

                $date = Carbon::parse($dob);

                return Carbon::create(now()->year, $date->month, $date->day)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        private function calculateAge($dob): ?int
        {
            try {
                return $dob ? Carbon::parse($dob)->age : null;
            } catch (\Exception $e) {
                return null;
            }
        }

        private function safeFormatDate($date, $format = 'Y-m-d')
        {
            try {
                return $date ? Carbon::parse($date)->format($format) : null;
            } catch (\Exception $e) {
                return null;
            }
        }

        private function cleanText($text)
        {
            if (empty($text)) {
                return '';
            }

            $cleanText = strip_tags($text);
            $cleanText = html_entity_decode($cleanText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $cleanText = preg_replace('/[^\P{C}\n]+/u', '', $cleanText);
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);

            return trim($cleanText);
        }
//    public function getEvents()
// {
//     $auth = Auth::user();
//     $userid = $auth->userid;
//     $usertype = $auth->userType;

//     // Base query
//     $query = AnnouncementsModel::query();

//     if ($usertype === 'Parent') {
//         // 1. Get all children for this parent
//         $childIds = Child::where('user_id', $userid)->pluck('id');

//         // 2. Get announcement IDs linked to these children
//         $announcementIds = AnnouncementChildModel::whereIn('childid', $childIds)
//             ->pluck('aid');

//         // 3. Filter announcements for these IDs
//         $query->whereIn('id', $announcementIds);
//     }

//     // 4. Fetch announcements & format for JSON
//     $events = $query->get()->map(function ($announcement) {
//         return [
//             'id'                => $announcement->id,
//             'title'             => $announcement->title,
//             'text'              => $announcement->text ?? '',
//             'status'            => $announcement->status ?? '',
//             'announcementMedia' => $announcement->announcementMedia ?? '',
//             'eventDate'         => $announcement->eventDate 
//                                     ? $announcement->eventDate->format('Y-m-d')
//                                     : null,
//             'createdAt'         => $announcement->createdAt 
//                                     ? $announcement->createdAt->format('Y-m-d H:i:s')
//                                     : null,
//             'start'             => $announcement->eventDate 
//                                     ? $announcement->eventDate->format('Y-m-d')
//                                     : $announcement->createdAt->format('Y-m-d'),
//         ];
//     });

//     return response()->json([
//         'status'  => true,
//         'message' => 'Events fetched successfully',
//         'events'  => $events,
//     ]);
// }

public function getEvents()
{
    $auth = Auth::user();

    // ✅ 1. Ensure user is authenticated
    if (!$auth) {
        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized',
            'events'  => [],
        ], 401);
    }

    $userid   = $auth->userid ?? null;
    $usertype = $auth->userType ?? null;

    // ✅ 2. Base query
    $query = AnnouncementsModel::query();

    // ✅ 3. Parent-specific filtering
    if ($usertype === 'Parent') {
        // Get child IDs for the parent (handle if empty)
        $childIds = Childparent::where('parentid', $userid)->pluck('childid') ?? collect();
        if ($childIds->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No events found for this parent',
                'events'  => [],
            ]);
        }

        // Verify child IDs exist in Child table
        $validChildIds = Child::whereIn('id', $childIds)->pluck('id') ?? collect();
        if ($validChildIds->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No valid children found',
                'events'  => [],
            ]);
        }

        // Get announcement IDs linked to children
        $announcementIds = AnnouncementChildModel::whereIn('childid', $validChildIds)
            ->pluck('aid') ?? collect();
        if ($announcementIds->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No announcements found for these children',
                'events'  => [],
            ]);
        }

        // Apply filtering to query
        $query->whereIn('id', $announcementIds);
    }

    // ✅ 4. Fetch announcements (empty result handled gracefully)
    $events = $query->get()->map(function ($announcement) {
        return [
            'id'                => $announcement->id ?? null,
            'title'             => $announcement->title ?? '',
            'text'              => $announcement->text ?? '',
            'status'            => $announcement->status ?? '',
            'announcementMedia' => $announcement->announcementMedia ?? '',
            'eventDate'         => $this->safeFormatDate($announcement->eventDate, 'Y-m-d'),
            'createdAt'         => $this->safeFormatDate($announcement->createdAt, 'Y-m-d H:i:s'),
            'start'             => $this->safeFormatDate(
                $announcement->eventDate ?? $announcement->createdAt,
                'Y-m-d'
            ),
        ];
    });

    return response()->json([
        'status'  => true,
        'message' => $events->isNotEmpty() 
                        ? 'Events fetched successfully' 
                        : 'No events found',
        'events'  => $events,
    ]);
}

public function getUser()
{
    $auth = Auth::user();
    $userid = $auth->userid;
    $usertype = strtolower($auth->userType); // normalize case

    if ($usertype === 'parent') {
        // Get IDs of children linked to the logged-in parent
        $childIds = Childparent::where('parentid', $userid)->pluck('childid'); 
        $children = Child::whereIn('id', $childIds)->get();
    } else {
        // Show all children for other user types
        $children = Child::all();
    }

    return response()->json([
        'status'  => true,
        'message' => 'Children fetched successfully',
        'data'    => $children
    ]);
}

}