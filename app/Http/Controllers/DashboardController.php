<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{


    function university()
    {
        return view('dashboard.university');
    }


}
