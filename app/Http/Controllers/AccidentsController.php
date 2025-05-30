<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccidentsModel;
use Illuminate\Http\Request;
use DB;
class AccidentsController extends Controller
{
    function index(){

       $data=AccidentsModel::get();
       dd($data);
    }
}
