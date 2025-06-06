<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceDetailsController extends Controller
{
    function create(){
        return view('Service.details');
    }
}
