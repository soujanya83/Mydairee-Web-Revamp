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
use App\Models\Center;
use App\Models\Room;
use App\Models\RoomStaff;
use App\Models\Usercenter;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

            $currentMonthBirthdays = Child::where('centerid', $centerid)
                ->whereMonth('dob', now()->month)
                ->count();

            $totalRooms = Room::where('centerid', $centerid)
                ->where('status', 'Active')
                ->count();

            $totalRecipes = RecipeModel::where('centerid', $centerid)
                ->count();

            $activeChildren = Child::where('centerid', $centerid)
                ->where('status', 'Active')
                ->count();

            $affiliatedCenters = Usercenter::where('userid', $userid)
                ->distinct('centerid')
                ->count('centerid');

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
                    'currentMonthBirthdays' => $currentMonthBirthdays,
                    'affiliatedCenters'     => $affiliatedCenters,
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

            $requestedChildId = $request->input('child_id', $request->input('childid'));
            $savedChildId = User::where('userid', $userid)->value('selectedchildreanid');
            $selectedChildId = null;
            $selectedChildSource = null;

            if ($parentChildIds->isNotEmpty()) {
                if (!empty($requestedChildId) && trim((string) $requestedChildId) !== '') {
                    $requestedChildId = (int) $requestedChildId;

                    if ($parentChildIds->contains($requestedChildId)) {
                        $selectedChildId = $requestedChildId;
                        $selectedChildSource = 'request';
                    }
                }

                if (!$selectedChildId && !empty($savedChildId) && $parentChildIds->contains((int) $savedChildId)) {
                    $selectedChildId = (int) $savedChildId;
                    $selectedChildSource = 'saved';
                }

                if (!$selectedChildId) {
                    $selectedChildId = (int) $parentChildIds->first();
                    $selectedChildSource = 'fallback';
                }
            }

            $selectedChildIds = $selectedChildId ? collect([$selectedChildId]) : $parentChildIds;

            $requestedCenter = $request->input('centerid') ?? $request->input('center_id') ?? $request->header('X-Center-Id');
            $centerid = null;

            if ($selectedChildId) {
                $selectedChildCenterId = Child::where('id', $selectedChildId)->value('centerid');
                if ($selectedChildCenterId) {
                    $centerid = (int) $selectedChildCenterId;
                }
            }

            if ($requestedCenter !== null) {
                if (!filter_var($requestedCenter, FILTER_VALIDATE_INT)) {
                    return response()->json(['status' => false, 'message' => 'Invalid center id'], 400);
                }

                $centerid = (int) $requestedCenter;

                $isAdmin = isset($auth->admin) && $auth->admin == '1';
                $isAssociated = Usercenter::where('userid', $userid)->where('centerid', $centerid)->exists();
                $hasChildInCenter = $selectedChildIds->isNotEmpty()
                    && Child::whereIn('id', $selectedChildIds)->where('centerid', $centerid)->exists();

                if (!$isAdmin && !$isAssociated && !$hasChildInCenter) {
                    return response()->json(['status' => false, 'message' => 'Unauthorized for this center'], 403);
                }
            }

            if (!$centerid && $userid) {
                $centerid = Usercenter::where('userid', $userid)->value('centerid');
            }

            if (!$centerid && $selectedChildIds->isNotEmpty()) {
                $centerid = Child::whereIn('id', $selectedChildIds)->value('centerid');
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
                ->when(strtolower((string) $usertype) === 'parent', function ($query) use ($selectedChildIds) {
                    $query->where('status', 'Published');

                    if ($selectedChildIds->isNotEmpty()) {
                        $query->whereHas('children', function ($childQuery) use ($selectedChildIds) {
                            $childQuery->whereIn('child.id', $selectedChildIds);
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

            $recentObservations = $this->getParentObservations($centerid, $selectedChildIds, $usertype);
            $recentReflections = $this->getParentReflections($centerid, $selectedChildIds, $usertype);
            $recentSnapshots = $this->getParentSnapshots($centerid, $selectedChildIds, $usertype, $userid);
            $snapshotCount = $recentSnapshots->count();
            $announcements = $this->getParentAnnouncements($centerid, $selectedChildIds, $usertype);
            $birthdays = $this->getParentChildren($centerid, $selectedChildIds, $usertype);
            $holidays = $this->getParentHolidays($centerid);

            return response()->json([
                'status' => true,
                'message' => 'Parent dashboard fetched successfully',
                'data' => [
                    'centerid' => $centerid,
                    'selectedChildId' => $selectedChildId,
                    'selectedChildSource' => $selectedChildSource,
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


        public function saveSelectedChild(Request $request)
        {
            $auth = Auth::user();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $userid = $auth->userid ?? null;
            $usertype = strtolower((string) ($auth->userType ?? ''));

            if ($usertype !== 'parent') {
                return response()->json([
                    'status' => false,
                    'message' => 'This endpoint is only for parents.',
                ], 403);
            }

            $validated = $request->validate([
                'child_id' => 'required|integer',
            ]);

            $parentChildIds = Childparent::where('parentid', $userid)->pluck('childid')->values();

            if ($parentChildIds->isEmpty() || !$parentChildIds->contains((int) $validated['child_id'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'This child does not belong to this parent',
                ], 403);
            }

            User::where('userid', $userid)->update([
                'selectedchildreanid' => (int) $validated['child_id'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Selected child saved successfully',
                'data' => [
                    'selectedchildreanid' => (int) $validated['child_id'],
                ],
            ]);
        }

        public function getSelectedChild(Request $request)
        {
            $auth = Auth::user();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $userid = $auth->userid ?? null;
            $usertype = strtolower((string) ($auth->userType ?? ''));

            if ($usertype !== 'parent') {
                return response()->json([
                    'status' => false,
                    'message' => 'This endpoint is only for parents.',
                ], 403);
            }

            $user = User::where('userid', $userid)->first();

            if (!$user || !$user->selectedchildreanid) {
                return response()->json([
                    'status' => false,
                    'message' => 'No selected child found',
                    'data' => null,
                ], 404);
            }

            $childBelongsToParent = Childparent::where('parentid', $userid)
                ->where('childid', $user->selectedchildreanid)
                ->exists();

            if (!$childBelongsToParent) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected child does not belong to this parent',
                ], 403);
            }

            $child = Child::find($user->selectedchildreanid);
            return response()->json([
                'status' => true,
                'message' => 'Selected child fetched successfully',
                'data' => [
                    'selectedchildreanid' => (int) $user->selectedchildreanid,
                    'child' => $child,
                ],
            ]);
        }

        public function saveSelectedCenter(Request $request)
        {
            $auth = Auth::user();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $userid = $auth->userid ?? $auth->id ?? null;
            $centerIdInput = $request->input('center_id', $request->input('centerid'));

            if ($centerIdInput === null || trim((string) $centerIdInput) === '') {
                return response()->json([
                    'status' => false,
                    'message' => 'Center id is required',
                ], 422);
            }

            if (!filter_var($centerIdInput, FILTER_VALIDATE_INT)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid center id',
                ], 422);
            }

            $centerId = (int) $centerIdInput;
            $linkedCenterIds = Usercenter::where('userid', $userid)
                ->orderBy('id')
                ->pluck('centerid')
                ->map(fn ($value) => (int) $value)
                ->values();

            if ($linkedCenterIds->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No linked centers found for this user',
                ], 404);
            }

            if (!$linkedCenterIds->contains($centerId)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected center does not belong to this user',
                ], 403);
            }

            $center = Center::find($centerId);

            if (!$center) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected center not found',
                ], 404);
            }

            User::where('userid', $userid)->update([
                'selected_center_id' => $centerId,
            ]);

            Session::put('user_center_id', $centerId);

            return response()->json([
                'status' => true,
                'message' => 'Selected center saved successfully',
                'data' => [
                    'selected_center_id' => $centerId,
                    'center' => $center,
                ],
            ]);
        }

        public function getSelectedCenter(Request $request)
        {
            $auth = Auth::user();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $userid = $auth->userid ?? $auth->id ?? null;
            $linkedCenterIds = Usercenter::where('userid', $userid)
                ->orderBy('id')
                ->pluck('centerid')
                ->map(fn ($value) => (int) $value)
                ->values();

            if ($linkedCenterIds->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No linked centers found for this user',
                    'data' => null,
                ], 404);
            }

            $savedCenterId = User::where('userid', $userid)->value('selected_center_id');
            $selectedCenterId = null;
            $selectionSource = null;

            if (!empty($savedCenterId) && $linkedCenterIds->contains((int) $savedCenterId)) {
                $savedCenter = Center::find((int) $savedCenterId);

                if ($savedCenter) {
                    $selectedCenterId = (int) $savedCenterId;
                    $selectionSource = 'saved';
                }
            }

            if (!$selectedCenterId) {
                foreach ($linkedCenterIds as $linkedCenterId) {
                    $center = Center::find($linkedCenterId);

                    if ($center) {
                        $selectedCenterId = $linkedCenterId;
                        $selectionSource = 'fallback';

                        User::where('userid', $userid)->update([
                            'selected_center_id' => $selectedCenterId,
                        ]);

                        Session::put('user_center_id', $selectedCenterId);
                        break;
                    }
                }
            }

            if (!$selectedCenterId) {
                return response()->json([
                    'status' => false,
                    'message' => 'No valid linked centers found for this user',
                    'data' => null,
                ], 404);
            }

            $center = Center::find($selectedCenterId);

            return response()->json([
                'status' => true,
                'message' => 'Selected center fetched successfully',
                'data' => [
                    'selected_center_id' => $selectedCenterId,
                    'selected_center_source' => $selectionSource,
                    'center' => $center,
                ],
            ]);
        }
        
        public function universalDashboard(Request $request)
        {
            $auth = Auth::user();

            if (!$auth) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $usertype = strtolower((string) ($auth->userType ?? ''));
            $isSuperadmin = ((string) ($auth->admin ?? '')) === '1' || $usertype === 'superadmin' || $usertype === 'centeradmin';
            $isStaff = $usertype === 'staff';

            if (!$isSuperadmin && !$isStaff) {
                return response()->json([
                    'status' => false,
                    'message' => 'This endpoint is only for superadmin and staff users.',
                    'userType' => $auth->userType ?? null,
                    'endpoint' => 'universal-dashboard'
                ], 403);
            }

            $centerid = $request->input('centerid')
                ?? $request->input('center_id')
                ?? $request->header('X-Center-Id');

            if ($centerid === null || $centerid === '') {
                return response()->json([
                    'status' => false,
                    'message' => 'Center id is required',
                ], 400);
            }

            if (!filter_var($centerid, FILTER_VALIDATE_INT)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid center id',
                ], 400);
            }

            $centerid = (int) $centerid;

            $hasCenterData = Room::where('centerid', $centerid)->exists()
                || Child::where('centerid', $centerid)->exists()
                || AnnouncementsModel::where('centerid', $centerid)->exists()
                || PubicHoliday_Model::where('centerid', $centerid)->exists();

            if (!$hasCenterData) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid center id',
                ], 404);
            }

            $requestedMonth = $request->input('month')
                ?? $request->input('month_id')
                ?? $request->input('month_number');

            $month = null;

            if ($requestedMonth !== null && $requestedMonth !== '') {
                if (!filter_var($requestedMonth, FILTER_VALIDATE_INT)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid month',
                    ], 400);
                }

                $month = (int) $requestedMonth;

                if ($month < 1 || $month > 12) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid month',
                    ], 400);
                }
            }

            $roomIds = collect();

            if ($isStaff) {
                $authId = $auth->id ?? null;

                if (!$authId) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Unable to resolve staff identity',
                    ], 403);
                }

                $roomIds = RoomStaff::query()
                    ->join('room', 'room.id', '=', 'room_staff.roomid')
                    ->where('room_staff.staffid', $authId)
                    ->where('room.centerid', $centerid)
                    ->pluck('room_staff.roomid')
                    ->filter()
                    ->unique()
                    ->values();

                if ($roomIds->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No rooms found for this staff member in the selected center',
                    ], 403);
                }
            }

            $children = $isStaff
                ? Child::whereIn('room', $roomIds)->get()
                : Child::where('centerid', $centerid)->get();

            if ($month !== null) {
                $children = $this->filterCollectionByMonth($children, $month, function ($child) {
                    return $child->dob ?? null;
                });
            }

            $childIds = $children->pluck('id')->filter()->unique()->values();

            $holidays = $this->getUniversalHolidays($centerid);

            if ($month !== null) {
                $holidays = $this->filterCollectionByMonth($holidays, $month, function ($holiday) {
                    return $holiday['date'] ?? null;
                });
            }

            $events = $this->getUniversalEvents($centerid, $isStaff, $childIds);

            if ($month !== null) {
                    $events = $this->filterCollectionByMonth($events, $month, function ($event) {
                        return $event['date'] ?? null;
                    });
            }

            $birthdays = $children->map(function ($child) {
                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'lastname' => $child->lastname ?? '',
                    'dob' => $child->dob,
                    'birthdayDate' => $this->birthdayCalendarDate($child->dob),
                    'age' => $this->calculateAge($child->dob),
                    'imageUrl' => $child->imageUrl ?? '',
                ];
            })->values();

            $holidays = $holidays->map(function ($holiday) {
                return [
                    'id' => $holiday['id'] ?? null,
                    'date' => $holiday['date'] ?? null,
                    'occasion' => $holiday['occasion'] ?? 'Holiday',
                    'state' => $holiday['state'] ?? '',
                ];
            })->values();

            $events = $events->map(function ($event) {
                return [
                    'id' => $event['id'] ?? null,
                    'title' => $event['title'] ?? '',
                    'date' => $event['date'] ?? null,
                    'type' => $event['type'] ?? 'event',
                    'text' => $event['text'] ?? '',

                    'imageUrl' => $event['imageUrl'] ?? '',
                    'imagePath' => $event['imagePath'] ?? '',

                    //  returning all media
                    'announcementMedia' => $event['announcementMedia'] ?? [],];
                        })->values();

            return response()->json([
                'status' => true,
                'message' => 'Universal dashboard fetched successfully',
                'data' => [
                    'birthdays' => $birthdays,
                    'holidays' => $holidays,
                    'events' => $events,
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

        private function filterCollectionByMonth(Collection $items, int $month, callable $dateResolver): Collection
        {
            return $items->filter(function ($item) use ($month, $dateResolver) {
                $date = $dateResolver($item);

                if (empty($date)) {
                    return false;
                }

                try {
                    return Carbon::parse($date)->month === $month;
                } catch (\Exception $e) {
                    return false;
                }
            })->values();
        }

        private function getUniversalEvents(int $centerid, bool $isStaff, Collection $childIds): Collection
        {
            $query = AnnouncementsModel::query()
                ->where('centerid', $centerid)
                ->where('status', 'sent');

            if ($isStaff) {
                if ($childIds->isEmpty()) {
                    return collect();
                }

                $announcementIds = AnnouncementChildModel::whereIn('childid', $childIds)
                    ->pluck('aid')
                    ->filter()
                    ->unique();

                if ($announcementIds->isEmpty()) {
                    return collect();
                }

                $query->whereIn('id', $announcementIds);
            }

            return $query->orderBy('id', 'desc')
                ->get()
                ->map(function ($announcement) {
                    return $this->formatEventForDashboard($announcement);
                });
        }

        private function getUniversalHolidays(int $centerid): Collection
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


        // private function formatEventForDashboard($announcement): array
        // {
        //     return [
        //         'id' => $announcement->id,
        //         'title' => $announcement->title,
        //         'text' => $this->cleanText($announcement->text) ?? '',
        //         'status' => $announcement->status ?? '',
        //         'announcementMedia' => $announcement->announcementMedia ?? '',
        //         'type' => $announcement->type ?? '',
        //         'date' => $this->safeFormatDate($announcement->eventDate ?? $announcement->createdAt, 'Y-m-d'),
        //     ];
        // }

        private function formatEventForDashboard($announcement): array
        {
            $media = json_decode($announcement->announcementMedia, true) ?? [];
            $media = array_map(function ($url) {
                return str_replace(
                    'https://mydiaree.com.au',
                    'https://api.mydiaree.com.au',
                    $url
                );
            }, $media);

            $imageUrl = $media[0] ?? '';

            $imagePath = '';

            if (!empty($imageUrl)) {
                $imagePath = ltrim(parse_url($imageUrl, PHP_URL_PATH), '/');
            }

            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'text' => $this->cleanText($announcement->text) ?? '',
                'status' => $announcement->status ?? '',
                'type' => $announcement->type ?? '',
                'date' => $this->safeFormatDate(
                    $announcement->eventDate ?? $announcement->createdAt,
                    'Y-m-d'
                ),

                'imageUrl' => $imageUrl,
                'imagePath' => $imagePath,

                // optional
                'announcementMedia' => $media,
            ];
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