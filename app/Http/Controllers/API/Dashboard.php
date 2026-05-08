<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnnouncementsModel;
use App\Models\Child;
use App\Models\RecipeModel;
use App\Models\User;
use App\Models\Room;
use App\Models\Usercenter;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\AnnouncementChildModel;
use App\Models\Childparent;
use Carbon\Carbon;

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

        public function newdashboard(\Illuminate\Http\Request $request)
        {
            $auth = Auth::user();
            $usertype = $auth->userType ?? null;
            $userid = $auth->userid ?? null;

            // 1) Prefer explicit centerid param from request
            // Accept `centerid` or `center_id` from JSON/form-data, or `X-Center-Id` header
            $requestedCenter = $request->input('centerid') ?? $request->input('center_id') ?? $request->header('X-Center-Id');
            $centerid = null;

            if ($requestedCenter !== null) {
                if (!filter_var($requestedCenter, FILTER_VALIDATE_INT)) {
                    return response()->json(['status' => false, 'message' => 'Invalid center id'], 400);
                }
                $centerid = (int) $requestedCenter;

                // Authorization: allow if user is admin or linked to center
                $isAdmin = isset($auth->admin) && $auth->admin == '1';
                $isAssociated = Usercenter::where('userid', $userid)->where('centerid', $centerid)->exists();
                if (!$isAdmin && !$isAssociated) {
                    return response()->json(['status' => false, 'message' => 'Unauthorized for this center'], 403);
                }
            }

            // 2) Fall back to session value (web clients)
            if (!$centerid) {
                $centerid = session('user_center_id');
            }

            // 3) Fall back to user's Usercenter relation (APIs without session)
            if (!$centerid && $userid) {
                $centerid = Usercenter::where('userid', $userid)->value('centerid');
            }

            if (!$centerid) {
                return response()->json(['status' => false, 'message' => 'No center specified or associated with user'], 400);
            }

            $staffusercenter = Usercenter::where('centerid', $centerid)->pluck('userid');

            $totalUsers = User::whereIn('userid', $staffusercenter)->where('status', 'ACTIVE')->count();
            $totalSuperadmin = User::where('admin', '1')->count();
            $totalStaff = User::whereIn('userid', $staffusercenter)->where('userType', 'Staff')->where('status', 'ACTIVE')->count();
            $totalParent = User::whereIn('userid', $staffusercenter)->where('userType', 'Parent')->where('status', 'ACTIVE')->count();
            $totalCenter = Usercenter::where('centerid', $centerid)->where('userid', $userid)->count();
            $totalRooms = Room::where('centerid', $centerid)->where('status', 'Active')->count();
            $totalRecipes = RecipeModel::where('centerid', $centerid)->count();
            $activeChildren = Child::where('centerid', $centerid)->where('status', 'Active')->count();

            return response()->json([
                'status' => true,
                'message' => 'New dashboard (center-scoped) fetched successfully',
                'data' => [
                    'totalUsers'      => $totalUsers,
                    'totalSuperadmin' => $totalSuperadmin,
                    'totalStaff'      => $totalStaff,
                    'totalParent'     => $totalParent,
                    'totalCenter'     => $totalCenter,
                    'totalRooms'      => $totalRooms,
                    'totalRecipes'    => $totalRecipes,
                    'activeChildren'  => $activeChildren,
                    'centerid'        => $centerid,
                ]
            ]);

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

/**
 * ✅ Safely format a date or return null if invalid
 */
private function safeFormatDate($date, $format = 'Y-m-d')
{
    try {
        return $date ? Carbon::parse($date)->format($format) : null;
    } catch (\Exception $e) {
        return null;
    }
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
