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
