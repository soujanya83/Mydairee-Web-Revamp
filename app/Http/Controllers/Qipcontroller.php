<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\support\Facades\Auth;
use App\Models\Usercenter;
use App\Models\Center;
use App\Models\Room;
use App\Models\Qip;
use App\Models\Qiparea;
use App\Models\QipDescussionBoard;
use App\Models\QipElement;
use App\Models\QipElementsIssues;
use App\Models\QipELementsProgressNotes;
use App\Models\QipImprovementPlan;
use App\Models\QiplElementsComments;
use App\Models\QipLinks;
use App\Models\QipNationalLaw;
use App\Models\QipStandard;
use App\Models\QipStandardUser;
use App\Models\SelfAssessment;
use App\Models\SelfAssessmentUser;
use App\Models\ChildParent;
use App\Models\Userprogressplan;
use App\Models\MontessoriSubject;
use App\Models\Child;
use App\Models\RoomStaff;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Services\AuthTokenService; // Custom service to verify token
use App\Models\DailyDiaryModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;


class Qipcontroller extends Controller
{
    public function index(Request $request)
    {

        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');
        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }
        if (Auth::user()->userType == "Superadmin") {
            $SelfAssessment = Qip::where('centerId', $centerid)
                ->orderBy('id', 'desc')->get();
        } else {
            $SelfAssessment = Qip::where('created_by', $authId)
                ->orderBy('id', 'desc')->get();
        }
        return view('Qip.index', compact('centers', 'SelfAssessment'));
    }



    public function getrooms5()
    {
        try {
            $user = Auth::user();
            $rooms = collect();

            if ($user->userType === 'Superadmin') {
                $rooms = $this->getroomsforSuperadmin();
            } else {
                $rooms = $this->getroomsforStaff();
            }

            return $rooms;
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    private function getroomsforSuperadmin()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $rooms = Room::where('centerid', $centerid)->get();
        return $rooms;
    }

    private function getroomsforStaff()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');

        // Get room IDs where user is the owner (userId matches)
        $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');

        // Merge both collections and remove duplicates
        $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique();

        $rooms = Room::where('id', $allRoomIds)->get();
        return $rooms;
    }


    public function addnew(Request $request)
    {
        $authId = Auth::user()->id;
        $centerId = session('user_center_id');
        $id = $request->query('id');

        if ($id) {
            $qip = Qip::findOrFail($id); // Load existing data
        } else {
            $qip = new Qip();
            $qip->centerId = $centerId;
            $qip->name = 'Create By ' . Carbon::now()->format('F Y'); // e.g., July 2025
            $qip->created_by = $authId;
            $qip->save();

            return redirect()->route('qip.addnew', ['id' => $qip->id]); // Redirect to show form with new ID
        }

        $Qip_area = Qiparea::all();


        return view('Qip.addnew', compact('qip', 'Qip_area'));
    }


    public function updateName(Request $request)
    {
        $qip = Qip::findOrFail($request->id);
        $qip->name = $request->name;
        $qip->save();

        return response()->json(['status' => 'success']);
    }


    public function viewArea($id, $area)
    {
        $qip = Qip::findOrFail($id);
        $Qip_area = Qiparea::where('id', $area)->get();
        $all_areas = Qiparea::all();
        $qipStandard = QipStandard::with(['elements'])->where('areaId', $area)->get();
        $QipDescussionBoard = QipDescussionBoard::with(['user'])
            ->where('qipid', $id)
            ->where('areaid', $area)
            ->orderBy('id', 'desc')
            ->get();
        return view('Qip.standard_element', compact('qip', 'Qip_area', 'all_areas', 'qipStandard', 'QipDescussionBoard'));
    }


    public function sendDiscussion(Request $request)
    {
        $request->validate([
            'qipid' => 'required|integer',
            'areaid' => 'required|integer',
            'commentText' => 'required|string',
        ]);

        $comment = QipDescussionBoard::create([
            'qipid' => $request->qipid,
            'areaid' => $request->areaid,
            'commentText' => $request->commentText,
            'added_by' => auth()->id(),
        ]);

        $comment->load('user');

        return response()->json([
            'status' => 'success',
            'comment' => $comment
        ]);
    }
}
