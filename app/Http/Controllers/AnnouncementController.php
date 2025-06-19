<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\support\Facades\Auth;
use App\Models\AnnouncementChildModel;
use App\Models\User;
use App\Models\Childparent;
use App\Models\PermissionsModel;
use App\Models\AnnouncementsModel;
use App\Models\Usercenter;
use App\Models\Center;
use Illuminate\Support\Facades\DB;
use App\Models\Child;
use Carbon\Carbon;


class AnnouncementController extends Controller
{
 public function list(Request $request)
{
    $centerId = Session::get('user_center_id');
      if(Auth::user()->userType == "Superadmin"){
  
    $user = Auth::user();
    $userId = $user->userid;
    $userType = $user->userType;

    $announcements = collect();
    $permissions = null;

      $center = Usercenter::where('userid', $userId)->pluck('centerid')->toArray();
 
    $centers = Center::whereIn('id', $center)->get();
     }
     else{
    $centers = Center::where('id', $centerId)->get();
     }

    if ($userType === 'Staff') {

        if (is_null($userId) && !is_null($centerId)) {
            $announcements = AnnouncementsModel::where('centerid', $centerId)
                ->orderByDesc('id')
                ->get();
        } else {
            $announcements = AnnouncementsModel::where('createdBy', $userId)
                ->where('centerid', $centerId)
                ->orderByDesc('id')
                ->get();
        }

        $permissions = PermissionsModel::where('userid', $userId)
            ->where('centerid', $centerId)
            ->get();

    } elseif ($userType === 'Superadmin') {

        if (is_null($userId) && !is_null($centerId)) {
            $announcements = AnnouncementsModel::where('centerid', $centerId)
                ->orderByDesc('id')
                ->get();
        } else {
            $announcements = AnnouncementsModel::where('createdBy', $userId)
                ->where('centerid', $centerId)
                ->orderByDesc('id')
                ->get();
        }

    } else {
        // For parents or other roles
        $childs = ChildParent::where('parentid', $userId)->get();

        foreach ($childs as $child) {
            $childId = $child->childid;

            $results = AnnouncementsModel::select('announcement.*')
                ->join('announcementchild', 'announcement.id', '=', 'announcementchild.aid')
                ->where('announcementchild.childid', $childId)
                ->orderByDesc('announcement.id')
                ->get();

            $announcements = $announcements->merge($results);
        }
    }

    // Attach createdBy name
    foreach ($announcements as $announcement) {
        $creator = User::where('userid', $announcement->createdBy)->first();
        $announcement->createdBy = $creator->name ?? 'Not Available';
    }

    // dd($userType);

    return view('Announcement.list', [
        'records' => $announcements,
        'permissions' => $permissions,
        'centers' => $centers,
        'selectedCenter' => $centerId,
        'userType' => $userType
    ]);
}

// public function AnnouncementCreate(Request $request)
// {
//     $announcement = null;
//      $centerid = Session('user_center_id');

//     // Get center ID
//     $centerid = $request->query('centerid');
//     if (!$centerid) {
//         $center = session('centerIds');
//         $centerid = $center[0]->id ?? null;
//     }



//     // get children


//     if (isset($updatedHeaders['X-Device-Id'], $updatedHeaders['X-Token'])) {
       

      

//         if ($json && $res && $res->userid == $json->userid) {
//             $childrenList = [];
//             // $childs = Children::getChildsFromCenter($json->centerid);
//           $childs = DB::table('child as c')
//     ->join('room as r', 'c.room', '=', 'r.id')
//     ->select('c.*', 'r.*', 'c.name as name', 'c.id as childid')
//     ->where('r.centerid', $centerid)
//     ->get();

//         	$checkChildInAnmnt = $this->db->get_where('announcementchild', array("aid"=>$annid,"childid"=>$childid));

//             foreach ($childs as $childobj) {
//                 $dob = Carbon::parse($childobj->dob);
//                 $now = Carbon::now();
//                 $childrenList[] = [
//                     'childid' => $childobj->childid,
//                     'name' => $childobj->name . " " . $childobj->lastname,
//                     'imageUrl' => $childobj->imageUrl,
//                     'dob' => $dob->format('d-m-Y'),
//                     'age' => $dob->diff($now)->format('%y years %m months'),
//                     'gender' => $childobj->gender,
//                     'checked' => isset($json->annId) && $checkChildInAnmnt
//                 ];


//             }

//             // Groups
//             $groupsList = [];
//             // $childGroups = Children::getChildGroups($json->centerid);
//             if ($centerid) {
// 			$childGroups = $this->db->get_where('child_group',array('centerid'=>$centerid));
// 		}else{
// 			$childGroups = $this->db->get('child_group');
// 		}
//             foreach ($childGroups as $group) {
//                 $groupChildren = [];
//                 // $groupChilds = Children::getChildsFromGroups($group->id);
//                 $groupChilds = Child::join('child_group_member', 'child.id', '=', 'child_group_member.child_id')
//     ->where('child_group_member.group_id', $groupId)
//     ->select('child.*', 'child_group_member.*') // Adjust as needed
//     ->get();

//                 foreach ($groupChilds as $child) {
//                     $dob = Carbon::parse($child->dob);
//                     $groupChildren[] = [
//                         'childid' => $child->id,
//                         'name' => $child->name . " " . $child->lastname,
//                         'imageUrl' => $child->imageUrl,
//                         'dob' => $dob->format('d-m-Y'),
//                         'age' => $dob->diff($now)->format('%y years %m months'),
//                         'gender' => $child->gender,
//                         'checked' => isset($json->annId) && $checkChildInAnmnt  ];
//                 }
//                 $groupsList[] = [
//                     'groupid' => $group->id,
//                     'name' => $group->name,
//                     'childrens' => $groupChildren
//                 ];
//             }

//             // Rooms
//             $roomsList = [];
    
//            $rooms = DB::table('room')
//     ->where('centerid', $centerid)
//     ->get();
//             foreach ($rooms as $room) {
//                 $roomChildren = [];
             
//                 $roomChilds = Child::join('room', 'child.room', '=', 'room.id')
//     ->where('room.id', $roomid)
//     ->select('child.*', 'room.*', 'child.id as childid', 'child.name as name')
//     ->get();
//                 foreach ($roomChilds as $child) {
//                     $dob = Carbon::parse($child->dob);
//                     $roomChildren[] = [
//                         'childid' => $child->childid,
//                         'name' => $child->name . " " . $child->lastname,
//                         'imageUrl' => $child->imageUrl,
//                         'dob' => $dob->format('d-m-Y'),
//                         'age' => $dob->diff($now)->format('%y years %m months'),
//                         'gender' => $child->gender,
//                         'checked' => isset($json->annId) && $checkChildInAnmnt];
//                 }
//                 $roomsList[] = [
//                     'roomid' => $room->id,
//                     'name' => $room->name,
//                     'childrens' => $roomChildren
//                 ];
//             }


//         } else {
            
//         }
//     }

  
//     // get children ends 


//         if (Auth::user()->userType === 'Superadmin') {
//             $permissions = null;
//         } else {

//             // Custom method
//                 $permissions = Permission::where('userid', Auth::user()->userid)
//             ->where('centerid', $centerid)
//             ->get();

         
        
    


           
//         }


  
    

    

//     // Optional fallback for other statuses


//     return view('Announcement.create', compact('announcement','centerid'));
// }

public function AnnouncementCreate(Request $request)
{
    $announcement = null;
    $centerid = $request->centerid;

    $Childrens = [];
    $Groups = [];
    $Rooms = [];


        // Children List
        $childs = DB::table('child as c')
            ->join('room as r', 'c.room', '=', 'r.id')
            ->select('c.*', 'r.*', 'c.name as name', 'c.id as childid')
            ->where('r.centerid', $centerid)
            ->get();

        $now = Carbon::now();

        foreach ($childs as $childobj) {
            $dob = Carbon::parse($childobj->dob);
            $checked = false;
            if (isset($json->annId)) {
                $check = DB::table('announcementchild')
                    ->where('aid', $json->annId)
                    ->where('childid', $childobj->childid)
                    ->exists();
                $checked = $check;
            }

          $Childrens[] = (object) [
    'childid' => $childobj->childid,
    'name' => $childobj->name . ' ' . $childobj->lastname,
    'imageUrl' => $childobj->imageUrl,
    'dob' => $dob->format('d-m-Y'),
    'age' => $dob->diff($now)->format('%y years %m months'),
    'gender' => $childobj->gender,
    'checked' => $checked
];
            
        }
        // Groups
        $childGroups = DB::table('child_group')
            ->when($centerid, fn($q) => $q->where('centerid', $centerid))
            ->get();

        foreach ($childGroups as $group) {
            $groupChildren = [];
            $groupChilds = DB::table('child')
                ->join('child_group_member', 'child.id', '=', 'child_group_member.child_id')
                ->where('child_group_member.group_id', $group->id)
                ->select('child.*')
                ->get();

            foreach ($groupChilds as $child) {
                $dob = Carbon::parse($child->dob);
                $checked = false;
                if (isset($json->annId)) {
                    $check = DB::table('announcementchild')
                        ->where('aid', $json->annId)
                        ->where('childid', $child->id)
                        ->exists();
                    $checked = $check;
                }

                $groupChildren[] =  (object) [
                    'childid' => $child->id,
                    'name' => $child->name . ' ' . $child->lastname,
                    'imageUrl' => $child->imageUrl,
                    'dob' => $dob->format('d-m-Y'),
                    'age' => $dob->diff($now)->format('%y years %m months'),
                    'gender' => $child->gender,
                    'checked' => $checked
                ];
            }

            $Groups[] = (object) [
                'groupid' => $group->id,
                'name' => $group->name,
                'Childrens' => $groupChildren
            ];
        }

        // dd($Groups);

        // Rooms
        $rooms = DB::table('room')->where('centerid', $centerid)->get();

        foreach ($rooms as $room) {
            $roomChildren = [];
            $roomChilds = DB::table('child as c')
                ->join('room as r', 'c.room', '=', 'r.id')
                ->where('r.id', $room->id)
                ->select('c.*', 'r.*', 'c.id as childid', 'c.name as name')
                ->get();

            foreach ($roomChilds as $child) {
                $dob = Carbon::parse($child->dob);
                $checked = false;
                if (isset($json->annId)) {
                    $check = DB::table('announcementchild')
                        ->where('aid', $json->annId)
                        ->where('childid', $child->childid)
                        ->exists();
                    $checked = $check;
                }

                $roomChildren[] = (object) [
                    'childid' => $child->childid,
                    'name' => $child->name . ' ' . $child->lastname,
                    'imageUrl' => $child->imageUrl,
                    'dob' => $dob->format('d-m-Y'),
                    'age' => $dob->diff($now)->format('%y years %m months'),
                    'gender' => $child->gender,
                    'checked' => $checked
                ];
            }

            $Rooms[] = (object) [
                'roomid' => $room->id,
                'name' => $room->name,
                'Childrens' => $roomChildren
            ];
        }

    // Permissions
    $permissions = Auth::user()->userType === 'Superadmin'
        ? null
        : PermissionsModel::where('userid', Auth::user()->userid)
            ->where('centerid', $centerid)
            ->get();

            // dd($Rooms);

    return view('Announcement.create', compact(
        'announcement',
        'centerid',
        'Childrens',
        'Groups',
        'Rooms',
        'permissions'
    ));
}



public function addNew()
	{
		if($this->session->has_userdata('LoginId')){
			if (isset($_GET['centerid'])) {
		    	$centerid = strip_tags(trim(stripslashes($_GET['centerid'])));
		    }else{
		    	$center = $this->session->userdata("centerIds");
				$centerid = $center[0]->id;
		    }
		    $data['centerid'] = $centerid;
			$data['userid'] = $this->session->userdata('LoginId');
			$url = BASE_API_URL."Announcements/getChildRecords/";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'X-Device-Id: '.$this->session->userdata('X-Device-Id'),
				'X-Token: '.$this->session->userdata('AuthToken')
			));

			$server_output = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);			
			if($httpcode == 200){
				$jsonOutput = json_decode($server_output);
				curl_close ($ch);
				$usertype = $this->session->userdata('UserType');
				if ($usertype == "Superadmin") {
					$jsondata = [];
					$jsondata = $jsonOutput;
					$jsondata->Permissions = NULL;
				} else {
					$jsondata = $jsonOutput;
					$perInfo = $this->getPermission($data['userid'],$data['centerid']);
					$permissions = json_decode($perInfo);
					$jsondata->Permissions = $permissions->Permissions;
				}
				$jsondata->centerid = $centerid;
			    $this->load->view('announcementForm_v3',$jsondata);
			}
			
			if($httpcode == 401){ redirect('Welcome'); }

		}else{
			redirect('Welcome');
		}
	}

    public function AnnouncementStore(){
            die('here');
    }

}
