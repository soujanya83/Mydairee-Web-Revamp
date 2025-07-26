<?php

namespace App\Http\Controllers;

use App\Models\AnnouncementsModel;
use App\Models\Child;
use App\Models\RecipeModel;
use App\Models\User;
use App\Models\Room;
use App\Models\Usercenter;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{


    function university()
    {
        $totalUsers = User::count();
        $totalSuperadmin = User::where('userType', 'Superadmin')->count();
        $totalStaff = User::where('userType', 'Staff')->count();
        $totalParent = User::where('userType', 'Parent')->count();
        $totalCenter = Usercenter::count();
        $totalRooms = Room::count();
        $totalRecipes = RecipeModel::count();

        return view('dashboard.university', compact('totalSuperadmin', 'totalParent', 'totalStaff', 'totalUsers', 'totalCenter', 'totalRooms', 'totalRecipes'));
    }
    public function getEvents()
    {
        $events = [
            ['title' => 'Sample Event', 'start' => '2025-07-04'],
            // Add your events from DB here
        ];

        $events = AnnouncementsModel::all();

        $data = [
            'status' => true,
            'message' => 'events fetched successfully',
            'events' => $events
        ];

        return response()->json($data);
    }


    public function getUser()
    {
        $events = [
            ['title' => 'Sample Event', 'start' => '2025-07-04'],
            // Add your events from DB here
        ];

        $events = Child::all();

        $data = [
            'status' => true,
            'message' => 'childs fetched successfully',
            'events' => $events
        ];

        return response()->json($data);
    }

}
