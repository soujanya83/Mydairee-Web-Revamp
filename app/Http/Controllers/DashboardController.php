<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementsModel;
use App\Models\Child;
use App\Models\RecipeModel;
use App\Models\User;
use App\Models\Room;
use App\Models\Usercenter;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\AnnouncementChildModel;
use Carbon\Carbon;

class DashboardController extends BaseController
{


    function university()
    {
        $usertype = Auth::user()->userType;

        $totalUsers = User::count();
        $totalSuperadmin = User::where('userType', 'Superadmin')->count();
        $totalStaff = User::where('userType', 'Staff')->count();
        $totalParent = User::where('userType', 'Parent')->count();
        $totalCenter = Usercenter::count();
        $totalRooms = Room::count();
        $totalRecipes = RecipeModel::count();
        if ($usertype == 'Parent') {
            return view('dashboard.parents', compact('totalSuperadmin', 'totalParent', 'totalStaff', 'totalUsers', 'totalCenter', 'totalRooms', 'totalRecipes'));
        } else {
            return view('dashboard.university', compact('totalSuperadmin', 'totalParent', 'totalStaff', 'totalUsers', 'totalCenter', 'totalRooms', 'totalRecipes'));
        }
    }
    public function getEvents()
    {
        $auth = Auth::user();
        $userid = $auth->userid;
        $usertype = $auth->userType;

        if ($usertype === 'Parent') {
            // 1. Get all children for this parent
            $childIds = Child::where('user_id', $userid)->pluck('id');

            // 2. Get announcement IDs linked to these children
            $announcementIds = AnnouncementChildModel::whereIn('childid', $childIds)
                ->pluck('aid');

            // 3. Fetch only announcements for these IDs
            $announcements = AnnouncementsModel::whereIn('id', $announcementIds)->get();
        } else {
            // Not a parent → fetch all announcements
            $announcements = AnnouncementsModel::all();
        }

        $events = $announcements->map(function ($announcement) {
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
        $usertype = $auth->userType;

        if ($usertype === 'parents') {
            // Show only children of the logged-in parent
            $children = Child::where('user_id', $userid)->get();
        } else {
            // Show all children for other user types (admin, teacher, etc.)
            $children = Child::all();
        }

        $data = [
            'status' => true,
            'message' => 'Children fetched successfully',
            'events' => $children
        ];

        return response()->json($data);
    }
}
