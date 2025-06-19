<?php

namespace App\Http\Controllers;

use App\Models\RecipeModel;
use App\Models\User;
use App\Models\Room;
use App\Models\Usercenter;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{


    function university()
    {
        $totalUsers=User::count();
        $totalSuperadmin=User::where('userType','Superadmin')->count();
        $totalStaff=User::where('userType','Staff')->count();
        $totalParent=User::where('userType','Parent')->count();
        $totalCenter=Usercenter::count();
        $totalRooms=Room::count();
        $totalRecipes=RecipeModel::count();

        return view('dashboard.university',compact('totalSuperadmin','totalParent','totalStaff','totalUsers','totalCenter','totalRooms','totalRecipes'));
    }


}
