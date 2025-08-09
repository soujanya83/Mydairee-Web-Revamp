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
    $userid = $auth->userid;
    $usertype = $auth->userType;

    // Base query
    $query = AnnouncementsModel::query();

    if ($usertype === 'Parent') {
        // 1. Get all children for this parent
        $childIds = Child::where('user_id', $userid)->pluck('id');

        // 2. Get announcement IDs linked to these children
        $announcementIds = AnnouncementChildModel::whereIn('childid', $childIds)
            ->pluck('aid');

        // 3. Filter announcements for these IDs
        $query->whereIn('id', $announcementIds);
    }

    // 4. Fetch announcements & format for JSON
    $events = $query->get()->map(function ($announcement) {
        return [
            'id'                => $announcement->id,
            'title'             => $announcement->title,
            'text'              => $announcement->text ?? '',
            'status'            => $announcement->status ?? '',
            'announcementMedia' => $announcement->announcementMedia ?? '',
            'eventDate'         => $announcement->eventDate
                                    ? Carbon::parse($announcement->eventDate)->format('Y-m-d')
                                    : null,
            'createdAt'         => $announcement->createdAt
                                    ? Carbon::parse($announcement->createdAt)->format('Y-m-d H:i:s')
                                    : null,
            'start'             => $announcement->eventDate
                                    ? Carbon::parse($announcement->eventDate)->format('Y-m-d')
                                    : Carbon::parse($announcement->createdAt)->format('Y-m-d'),
        ];
    });

    return response()->json([
        'status'  => true,
        'message' => 'Events fetched successfully',
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
