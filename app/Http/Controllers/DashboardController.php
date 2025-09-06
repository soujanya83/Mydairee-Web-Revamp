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
use App\Models\Childparent;
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
           $centerid = session('user_center_id');
        // dd($usertype);

        if ($usertype === 'Parent') {
            // 1. Get all children for this parent

            $childIds = Childparent::where('parentid', $userid)->pluck('childid');

            // 2. Get announcement IDs linked to these children
            $announcementIds = AnnouncementChildModel::whereIn('childid', $childIds)
                ->pluck('aid');

            // 3. Fetch only announcements for these IDs
                    $announcements = AnnouncementsModel::whereIn('id', $announcementIds)
                    ->whereIn('audience', ['all', 'parents'])
                    ->get();

        } else if(Auth::user()->userType == "Staff" || Auth::user()->userType == "Superadmin") {
            // Not a parent → fetch all announcements
            $announcements = AnnouncementsModel::where('centerid',$centerid)->get();

            if(Auth::user()->userType == "Staff"){
                $announcements = AnnouncementsModel::where('centerid', $centerid)
                    
                    ->whereIn('audience', ['all', 'staff'])
                    ->get();

            }

            // dd( $announcements);
        }else{
            $announcements = AnnouncementsModel::all();
        }

        $events = $announcements->map(function ($announcement) {
            return [
                'id'                => $announcement->id,
                'title'             => $announcement->title,
                'text'              => $this->cleanText($announcement->text) ?? '',
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

    
private function cleanText($text)
{
    if (empty($text)) {
        return '';
    }

    // 1. Remove all HTML tags
    $cleanText = strip_tags($text);

    // 2. Decode HTML entities (&amp; → &, &nbsp; → space)
    $cleanText = html_entity_decode($cleanText, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // 3. Remove control / unknown characters
    $cleanText = preg_replace('/[^\P{C}\n]+/u', '', $cleanText);

    // 4. Normalize multiple spaces/newlines
    $cleanText = preg_replace('/\s+/', ' ', $cleanText);

    // 5. Final trim
    return trim($cleanText);
}


    public function getUser()
    {
        $auth = Auth::user();
        $userid = $auth->userid;
        $usertype = $auth->userType;
        //  dd($usertype);

        if ($usertype === 'Parent') {
            // Show only children of the logged-in parent
            $childparent = Childparent::where('parentid', $userid)->pluck('childid');
            $children = Child::wherein('id', $childparent)->where('status','Active')->get();
        } else if($usertype === 'Staff'){
            // Show all children for other user types (admin, teacher, etc.)
                    $children = Child::where('centerid',Session('user_center_id'))->get();
        }else{
                    //  $usercenter = Usercenters::where('userid',Auth::user()->userid)->first();
                    // Superadamin
            $children = Child::where('centerid',Session('user_center_id'))->get();
        }

        $data = [
            'status' => true,
            'message' => 'Children fetched successfully',
            'events' => $children
        ];

        return response()->json($data);
    }
}
