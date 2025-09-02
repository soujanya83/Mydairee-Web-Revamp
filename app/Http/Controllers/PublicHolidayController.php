<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PubicHoliday_Model;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{
    function add_public_holiday()
    {
       $holidayData= PubicHoliday_Model::latest()->get();
        return view('holiday.add_public_holiday',compact('holidayData'));
    }
}
