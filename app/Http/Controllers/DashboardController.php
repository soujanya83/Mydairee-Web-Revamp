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
