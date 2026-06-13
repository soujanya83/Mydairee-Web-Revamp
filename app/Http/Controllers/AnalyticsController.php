<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementsModel;
use App\Models\Observation;
use App\Models\ObservationStaff;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Reflection;
use App\Models\ReflectionStaff;
use App\Models\Snapshot;
use App\Models\User;
use App\Models\Usercenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function analytics(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $centerId = $request->center_id;

        if (empty($centerId)) {
            return response()->json([
                'status' => false,
                'message' => 'Center ID is required'
            ], 422);
        }
        $hasAccess = Usercenter::where('userid', $user->id)
            ->where('centerid', $centerId)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'status' => false,
                'message' => 'You are not assigned to this center'
            ], 403);
        }
        $allowedCenters = [$centerId];

        if ($user->userType === 'Staff') {$data = $this->getUserAnalytics($user->id,[$centerId]);

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [
                    'summary' => $data,
                    'monthlyTrend' => [],
                    'recentActivities' => [],
                ],
            ]);
        }

        if (in_array($user->userType, ['Centeradmin', 'Superadmin'])) {

            $allowedCenters = [$centerId];

            $allUserIds = Usercenter::whereIn('centerid', $allowedCenters)
                ->pluck('userid')
                ->unique()
                ->toArray();

            $users = User::whereIn('id', $allUserIds)->get();

            $totalObservations = Observation::whereIn('centerid', $allowedCenters)->count();

            $totalReflections = Reflection::whereIn('centerid', $allowedCenters)->count();

            $totalSnapshots = Snapshot::whereIn('centerid', $allowedCenters)->count();

            $totalProgramPlans = ProgramPlanTemplateDetailsAdd::whereIn('centerid', $allowedCenters)->count();

            $totalAnnouncements = AnnouncementsModel::whereIn('centerid', $allowedCenters)->count();

            $staffContributors = [];
            $centerAdminContributors = [];
            $superAdminContributors = [];

            foreach ($users as $member) {

                $analytics = $this->getUserAnalytics(
                    $member->id,
                    $allowedCenters
                );

                $totalCreated =
                    $analytics['observations']['created']
                    + $analytics['reflections']['created']
                    + $analytics['snapshots']['created']
                    + $analytics['programPlans']['created']
                    + $analytics['announcements']['created'];

                   
                $totalTagged =
                    $analytics['observations']['tagged']
                    + $analytics['reflections']['tagged']
                    + $analytics['snapshots']['tagged']
                    + $analytics['programPlans']['tagged']
                    + $analytics['announcements']['tagged'];

                $totalInvolved =
                    $analytics['observations']['involved']
                    + $analytics['reflections']['involved']
                    + $analytics['snapshots']['involved']
                    + $analytics['programPlans']['involved']
                    + $analytics['announcements']['involved'];

                $modules = [];
                if (
                        $member->userType === 'Staff'
                    ) {
                        // Staff should always show all modules
                        $modules = [
                            'observations' => $analytics['observations'],
                            'reflections' => $analytics['reflections'],
                            'snapshots' => $analytics['snapshots'],
                            'programPlans' => $analytics['programPlans'],
                            'announcements' => $analytics['announcements'],
                        ];
                    } else {


                    if ($analytics['observations']['created'] > 0) {
                        $modules['observations'] = $analytics['observations'];
                    }

                    if ($analytics['reflections']['created'] > 0) {
                        $modules['reflections'] = $analytics['reflections'];
                    }

                    if ($analytics['snapshots']['created'] > 0) {
                        $modules['snapshots'] = $analytics['snapshots'];
                    }

                    if ($analytics['programPlans']['created'] > 0) {
                        $modules['programPlans'] = $analytics['programPlans'];
                    }

                    if ($analytics['announcements']['created'] > 0) {
                        $modules['announcements'] = $analytics['announcements'];
                    }
                    }
                    $contributor = [
                        'userId' => $member->id,
                        'name' => $member->name,
                        'userType' => $member->userType,

                        'modules' => $modules,

                        'totalCreated' => $totalCreated,
                        'totalTagged' => $totalTagged,
                        'totalInvolved' => $totalInvolved,
                    ];

                if ($member->userType === 'Staff') {
                    $staffContributors[] = $contributor;
                } elseif ($member->userType === 'Centeradmin') {
                    // Show only if created something
                        if ($totalCreated > 0) {
                            $centerAdminContributors[] = $contributor;
                        }
                } elseif ($member->userType === 'Superadmin') {
                    // Show only if created something
                        if ($totalCreated > 0) {
                            $superAdminContributors[] = $contributor;
                        }
                }
            }

            usort($staffContributors, fn ($a, $b) => $b['totalInvolved'] <=> $a['totalInvolved']);
            usort($centerAdminContributors, fn ($a, $b) => $b['totalInvolved'] <=> $a['totalInvolved']);
            usort($superAdminContributors, fn ($a, $b) => $b['totalInvolved'] <=> $a['totalInvolved']);

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [

                    'summary' => [
                        'totalStaff' => User::whereIn('id', $allUserIds)
                            ->where('userType', 'Staff')
                            ->count(),

                        'totalObservations' => $totalObservations,
                        'totalReflections' => $totalReflections,
                        'totalSnapshots' => $totalSnapshots,
                        'totalProgramPlans' => $totalProgramPlans,
                        'totalAnnouncements' => $totalAnnouncements,
                    ],

                    'topContributors' => [
                        'staff' => $staffContributors,
                        'centerAdmins' => $centerAdminContributors,
                        'superAdmins' => $superAdminContributors,
                    ],

                    'monthlyTrend' => [],
                    'recentActivities' => [],
                ],
            ]);

            
        }

        return response()->json([
            'status' => false,
            'message' => 'User type not supported',
        ]);
    }

    private function getUserAnalytics(
                    $userId,
                    $allowedCenters = [],
                    $dateFilter = null,
                    $startDate = null,
                    $endDate = null,
                    $roomId = null
                )
    {
        /*
        |--------------------------------------------------------------------------
        | OBSERVATIONS
        |--------------------------------------------------------------------------
        */
        $observationCreatedQuery = Observation::where('userId', $userId)
            ->whereIn('centerid', $allowedCenters);

        if (!empty($roomId)) {
            $observationCreatedQuery->whereRaw(
                'FIND_IN_SET(?, room)',
                [$roomId]
            );
        }

        $this->applyDateFilter(
            $observationCreatedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'created_at'
        );

        $observationCreated = $observationCreatedQuery->count();

        $observationTaggedQuery = ObservationStaff::where('userid', $userId)
            ->whereHas('observation', function ($q) use (
                $allowedCenters,
                $roomId,
                $dateFilter,
                $startDate,
                $endDate
            ) {
                $q->whereIn('centerid', $allowedCenters);

                if (!empty($roomId)) {
                    $q->whereRaw(
                        'FIND_IN_SET(?, room)',
                        [$roomId]
                    );
                }

                $this->applyDateFilter(
                    $q,
                    $dateFilter,
                    $startDate,
                    $endDate,
                    'created_at'
                );
            });

        $observationTagged = $observationTaggedQuery->count();


        /*
        |--------------------------------------------------------------------------
        | REFLECTIONS
        |--------------------------------------------------------------------------
        */
        $reflectionCreatedQuery = Reflection::where('createdBy', $userId)
            ->whereIn('centerid', $allowedCenters);

        if (!empty($roomId)) {
            $reflectionCreatedQuery->whereRaw(
                'FIND_IN_SET(?, roomids)',
                [$roomId]
            );
        }

        $this->applyDateFilter(
            $reflectionCreatedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'createdAt'
        );

        $reflectionCreated = $reflectionCreatedQuery->count();

        $reflectionIdsQuery = Reflection::whereIn(
            'centerid',
            $allowedCenters
        );

        if (!empty($roomId)) {
            $reflectionIdsQuery->whereRaw(
                'FIND_IN_SET(?, roomids)',
                [$roomId]
            );
        }

        $this->applyDateFilter(
            $reflectionIdsQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'createdAt'
        );

        $reflectionIds = $reflectionIdsQuery->pluck('id');

        $reflectionTagged = ReflectionStaff::where(
            'staffid',
            $userId
        )
            ->whereIn('reflectionid', $reflectionIds)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | SNAPSHOTS
        |--------------------------------------------------------------------------
        */
        $snapshotCreatedQuery = Snapshot::where(
            'createdBy',
            $userId
        )
            ->whereIn('centerid', $allowedCenters);

        if (!empty($roomId)) {
            $snapshotCreatedQuery->whereRaw(
                'FIND_IN_SET(?, roomids)',
                [$roomId]
            );
        }

        $this->applyDateFilter(
            $snapshotCreatedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'created_at'
        );

        $snapshotCreated = $snapshotCreatedQuery->count();

        $snapshotTaggedQuery = Snapshot::whereRaw(
            'FIND_IN_SET(?, educators)',
            [$userId]
        )
            ->whereIn('centerid', $allowedCenters);

        if (!empty($roomId)) {
            $snapshotTaggedQuery->whereRaw(
                'FIND_IN_SET(?, roomids)',
                [$roomId]
            );
        }

        $this->applyDateFilter(
            $snapshotTaggedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'created_at'
        );

        $snapshotTagged = $snapshotTaggedQuery->count();


        /*
        |--------------------------------------------------------------------------
        | PROGRAM PLANS
        |--------------------------------------------------------------------------
        */
        $planCreatedQuery = ProgramPlanTemplateDetailsAdd::where(
            'created_by',
            $userId
        )
            ->whereIn('centerid', $allowedCenters);

        if (!empty($roomId)) {
            $planCreatedQuery->where('room_id', $roomId);
        }

        $this->applyDateFilter(
            $planCreatedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'created_at'
        );

        $planCreated = $planCreatedQuery->count();

        $planTaggedQuery = ProgramPlanTemplateDetailsAdd::whereRaw(
            'FIND_IN_SET(?, educators)',
            [$userId]
        )
            ->whereIn('centerid', $allowedCenters);

        if (!empty($roomId)) {
            $planTaggedQuery->where('room_id', $roomId);
        }

        $this->applyDateFilter(
            $planTaggedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'created_at'
        );

        $planTagged = $planTaggedQuery->count();


        /*
        |--------------------------------------------------------------------------
        | ANNOUNCEMENTS
        |--------------------------------------------------------------------------
        */
        $announcementCreatedQuery = AnnouncementsModel::where(
            'createdBy',
            $userId
        )
            ->whereIn('centerid', $allowedCenters);

        $this->applyDateFilter(
            $announcementCreatedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'createdAt'
        );

        $announcementCreated = $announcementCreatedQuery->count();

        $announcementTaggedQuery = AnnouncementsModel::whereIn(
            'audience',
            ['all', 'staff']
        )
            ->whereIn('centerid', $allowedCenters);

        $this->applyDateFilter(
            $announcementTaggedQuery,
            $dateFilter,
            $startDate,
            $endDate,
            'createdAt'
        );

        $announcementTagged = $announcementTaggedQuery->count();


        return [
            'observations' => [
                'created' => $observationCreated,
                'tagged' => $observationTagged,
                'involved' => $observationCreated + $observationTagged,
            ],

            'reflections' => [
                'created' => $reflectionCreated,
                'tagged' => $reflectionTagged,
                'involved' => $reflectionCreated + $reflectionTagged,
            ],

            'snapshots' => [
                'created' => $snapshotCreated,
                'tagged' => $snapshotTagged,
                'involved' => $snapshotCreated + $snapshotTagged,
            ],

            'programPlans' => [
                'created' => $planCreated,
                'tagged' => $planTagged,
                'involved' => $planCreated + $planTagged,
            ],

            'announcements' => [
                'created' => $announcementCreated,
                'tagged' => $announcementTagged,
                'involved' => $announcementCreated + $announcementTagged,
            ],
        ];
    }


    private function applyDateFilter($query, $date, $startDate = null, $endDate = null,  $column = 'created_at')
    {
        if (empty($date) || $date === 'all') {
            return $query;
        }

        switch ($date) {

            case 'today':
                $query->whereDate($column, today());
                break;

            case 'this_week':
                $query->whereBetween(
                    $column,
                    [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]
                );
                break;

            case 'this_month':
                $query->whereYear($column, now()->year)
                    ->whereMonth($column, now()->month);
                break;

            case 'custom':

                if ($startDate && $endDate) {
                    $query->whereBetween(
                        $column,
                        [
                            $startDate . ' 00:00:00',
                            $endDate . ' 23:59:59'
                        ]
                    );
                }

                break;
        }

        return $query;
    }


    public function analyticsFilter(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $centerId = $request->center_id;
        $dateFilter = $request->date_filter;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if (empty($centerId)) {
            return response()->json([
                'status' => false,
                'message' => 'Center ID is required'
            ], 422);
        }
        $hasAccess = Usercenter::where('userid', $user->id)
            ->where('centerid', $centerId)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'status' => false,
                'message' => 'You are not assigned to this center'
            ], 403);
        }

        $allowedCenters = [$centerId];


        if ($user->userType === 'Staff') 
        {

            $analytics = $this->getUserAnalytics(
                $user->id,
                [$centerId],
                $dateFilter,
                $startDate,
                $endDate,
                $request->room_id
            );

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [
                    'summary' => $analytics,
                    'monthlyTrend' => [],
                    'recentActivities' => [],
                ],
            ]);
        }

        if (in_array($user->userType, ['Centeradmin', 'Superadmin']))
        {
                $search = $request->search;
                $scope = $request->scope;
                $roomId = $request->room_id;
                $authorId = $request->author_id;

                $allUserIds = Usercenter::where('centerid', $centerId)
                ->pluck('userid')
                ->unique()
                ->toArray();

                $users = User::whereIn('id', $allUserIds);

                if (!empty($search)) {
                $users->where('name', 'like', '%' . $search . '%');
                }

                if (!empty($scope) && $scope != 'All') {
                $users->where('userType', $scope);
                }

                if (!empty($authorId)) {
                $users->where('id', $authorId);
                }

                $users = $users->get();

                $staffContributors = [];
                $centerAdminContributors = [];
                $superAdminContributors = [];

                $totalObservations = 0;
                $totalReflections = 0;
                $totalSnapshots = 0;
                $totalProgramPlans = 0;
                $totalAnnouncements = 0;

                foreach ($users as $member) {

            
                $analytics = $this->getUserAnalytics(
                    $member->id,
                    [$centerId],
                    $dateFilter,
                    $startDate,
                    $endDate,
                    $roomId
                );

                $totalCreated =
                    $analytics['observations']['created']
                    + $analytics['reflections']['created']
                    + $analytics['snapshots']['created']
                    + $analytics['programPlans']['created']
                    + $analytics['announcements']['created'];

                $totalTagged =
                    $analytics['observations']['tagged']
                    + $analytics['reflections']['tagged']
                    + $analytics['snapshots']['tagged']
                    + $analytics['programPlans']['tagged']
                    + $analytics['announcements']['tagged'];

                $totalInvolved =
                    $analytics['observations']['involved']
                    + $analytics['reflections']['involved']
                    + $analytics['snapshots']['involved']
                    + $analytics['programPlans']['involved']
                    + $analytics['announcements']['involved'];

                $totalObservations += $analytics['observations']['created'];
                $totalReflections += $analytics['reflections']['created'];
                $totalSnapshots += $analytics['snapshots']['created'];
                $totalProgramPlans += $analytics['programPlans']['created'];
                $totalAnnouncements += $analytics['announcements']['created'];

                $modules = [];

                if ($member->userType === 'Staff') {

                    $modules = [
                        'observations' => $analytics['observations'],
                        'reflections' => $analytics['reflections'],
                        'snapshots' => $analytics['snapshots'],
                        'programPlans' => $analytics['programPlans'],
                        'announcements' => $analytics['announcements'],
                    ];

                } else {

                    if ($analytics['observations']['created'] > 0) {
                        $modules['observations'] = $analytics['observations'];
                    }

                    if ($analytics['reflections']['created'] > 0) {
                        $modules['reflections'] = $analytics['reflections'];
                    }

                    if ($analytics['snapshots']['created'] > 0) {
                        $modules['snapshots'] = $analytics['snapshots'];
                    }

                    if ($analytics['programPlans']['created'] > 0) {
                        $modules['programPlans'] = $analytics['programPlans'];
                    }

                    if ($analytics['announcements']['created'] > 0) {
                        $modules['announcements'] = $analytics['announcements'];
                    }
                }

                $contributor = [
                    'userId' => $member->id,
                    'name' => $member->name,
                    'userType' => $member->userType,
                    'modules' => $modules,
                    'totalCreated' => $totalCreated,
                    'totalTagged' => $totalTagged,
                    'totalInvolved' => $totalInvolved,
                ];

                if ($member->userType === 'Staff') {

                    $staffContributors[] = $contributor;

                } elseif ($member->userType === 'Centeradmin') {

                    if ($totalCreated > 0) {
                        $centerAdminContributors[] = $contributor;
                    }

                } elseif ($member->userType === 'Superadmin') {

                    if ($totalCreated > 0) {
                        $superAdminContributors[] = $contributor;
                    }
                }
            

                }

                usort($staffContributors, fn($a, $b) =>
                $b['totalInvolved'] <=> $a['totalInvolved']
                );

                usort($centerAdminContributors, fn($a, $b) =>
                $b['totalInvolved'] <=> $a['totalInvolved']
                );

                usort($superAdminContributors, fn($a, $b) =>
                $b['totalInvolved'] <=> $a['totalInvolved']
                );

                return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => [

                    'summary' => [
                        'totalStaff' => User::whereIn('id', $allUserIds)
                            ->where('userType', 'Staff')
                            ->count(),

                        'totalObservations' => $totalObservations,
                        'totalReflections' => $totalReflections,
                        'totalSnapshots' => $totalSnapshots,
                        'totalProgramPlans' => $totalProgramPlans,
                        'totalAnnouncements' => $totalAnnouncements,
                    ],

                    'topContributors' => [
                        'staff' => $staffContributors,
                        'centerAdmins' => $centerAdminContributors,
                        'superAdmins' => $superAdminContributors,
                    ],

                    'monthlyTrend' => [],
                    'recentActivities' => [],
                ]

                ]);

        }






    }
}