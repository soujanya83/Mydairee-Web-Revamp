<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccidentsModel;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Usercenter;
use App\Models\Center;
use App\Models\Room;
use App\Models\Child;
use App\Models\Childparent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Login;
use App\Models\Accident;
use App\Models\AccidentIllnessModel;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;




class AccidentsController extends Controller
{

    public function AccidentDelete(Request $r)
    {
        $r->validate([
            'accidentid' => 'required|integer'
        ]);

        // Find the accident record
        $accident = AccidentsModel::find($r->accidentid);

        if (!$accident) {
            return response()->json([
                'status' => false,
                'message' => 'Accident record not found'
            ], 404);
        }

        // Map DB columns to file name prefixes
        $imageMappings = [
            'person_sign' => "personSign",
            'witness_sign' => "witnessSign",
            'injury_image' => "injuryImage",
            'responsible_person_sign' => "personInchargeSign",
            'nominated_supervisor_sign' => "supervisorSign",
        ];

        // Delete each related image file if exists
        foreach ($imageMappings as $field => $prefix) {
            if (!empty($accident->$field)) {
                $oldPath = public_path("uploads/accidents/" . basename($accident->$field));
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
        }

        // Delete related illness records
        AccidentIllnessModel::where('accident_id', $r->accidentid)->delete();

        // Delete accident record
        $accident->delete();

        return redirect()->route('Accidents.list');
    }


    public function filterByChild(Request $request)
    {
        $user = Auth::user();
        $userid = $user->userid;
        $userType = $user->userType;
        $centerid = Session('user_center_id');

        if (empty($centerid)) {
            $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();
            $centerid = $centerId;
        }

        // Fetch centers
        if ($userType === "Superadmin") {
            $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        // Handle room selection
        $room = null;
        if (empty($request->roomid)) {
            $centerRoom = Room::where('centerid', $centerid)->first();
            $roomid = $centerRoom->id ?? null;
            $roomname = $centerRoom->name ?? '';
            $roomcolor = $centerRoom->color ?? '';
            $centerRooms = Room::where('centerid', $centerid)->get();
        } else {
            $roomid = $request->roomid;
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $centerRooms = Room::where('centerid', $centerid)->get();
        }

        if ($room) {
            $roomid = $room->id;
            $roomname = $room->name;
            $roomcolor = $room->color;
        }

        $date = !empty($request->date)
            ? date('Y-m-d', strtotime($request->date))
            : date('Y-m-d');

        // Build accident query
        if ($userType == "Parent") {
            $accQuery = AccidentsModel::select(
                'accidents.id',
                'accidents.child_name',
                'accidents.child_gender',
                'accidents.roomid',
                'accidents.incident_date',
                'accidents.ack_parent_name',
                'accidents.added_by'
            )
                ->join('childparent', 'childparent.childid', '=', 'accidents.childid')
                ->where('childparent.parentid', $userid)
                ->where('centerid', Session('user_center_id'))
                ->where('roomid', $roomid)
                ->orderBy('added_at', 'desc');
        } else {
            $accQuery = AccidentsModel::select(
                'id',
                'child_name',
                'child_gender',
                'roomid',
                'incident_date',
                'ack_parent_name',
                'added_by'
            )
                ->orderBy('added_at', 'desc');

            if (Session('user_center_id') !== null) {
                $accQuery->where('centerid', Session('user_center_id'));
            }
            if ($roomid !== null) {
                $accQuery->where('roomid', $roomid);
            }
        }

        // ✅ Filter by child name if provided
        if ($request->filled('child_name')) {
            $accQuery->where('child_name', 'like', '%' . $request->child_name . '%');
        }

        $accArr = $accQuery->get();

        // Add username for each accident
        foreach ($accArr as $acc) {
            $userData = User::find($acc->added_by);
            $acc->username = $userData->name ?? 'Unknown';
        }

        $childs = Child::where('room', $roomid)->where('status', 'Active')->get();

        $permission = Permission::where('userid', Auth::user()->userid)->where('centerid', $centerid)->first();

        // Return JSON
        return response()->json([
            'centerid'       => $centerid,
            'date'           => $date,
            'roomid'         => $roomid,
            'roomname'       => $roomname,
            'roomcolor'      => $roomcolor,
            'rooms'          => $centerRooms,
            'childs'         => $childs,
            'accidents'      => $accArr,
            'centers'        => $centers,
            'selectedCenter' => $request->centerid,
            'permission' => $permission
        ]);
    }



    public function AccidentsList(Request $request)
    {
        $user = Auth::user();
        $userid = $user->userid;
        $userType = $user->userType;
        $centerid = Session('user_center_id');

        if (empty($centerid)) {
            $centerId = Usercenter::where('userid', $userid)->pluck('centerid')->first();
            $centerid =  $centerId;
        }

        if ($userType === "Superadmin") {
            $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        $room = null;
        if (empty($request->roomid)) {
            $centerRoom = Room::where('centerid', $centerid)->first();
            $roomid = $centerRoom->id ?? null;
            $roomname = $centerRoom->name ?? '';
            $roomcolor = $centerRoom->color ?? '';
            $centerRooms = Room::where('centerid', $centerid)->get();
            $selectedRoom = Room::where('id', $roomid)->first();
        } else {
            $roomid = $request->roomid;
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $centerRooms = Room::where('centerid', $centerid)->get();
            $selectedRoom = Room::where('id', $roomid)->first();
        }

        if (Auth::user()->userType == "Parent") {

            $roomid = $request->roomid;
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $childids = Childparent::where('parentid', $userid)->pluck('childid');
            $roomids = Child::whereIn('id', $childids)->pluck('room');
            $centerRooms = Room::whereIn('id',  $roomids)->get();
            $selectedRoom = Room::where('id', $roomid)->first();
        }

        if ($room) {
            $roomid = $room->id;
            $roomname = $room->name;
            $roomcolor = $room->color;
        }



        $date = !empty($request->date)
            ? date('Y-m-d', strtotime($request->date))
            : date('Y-m-d');

        if ($userType == "Parent") {
            $accArr = AccidentsModel::select('accidents.id', 'accidents.child_name', 'accidents.child_gender', 'accidents.roomid', 'accidents.incident_date', 'accidents.ack_parent_name', 'accidents.added_by')
                ->join('childparent', 'childparent.childid', '=', 'accidents.childid')
                ->where('childparent.parentid', $userid)

                ->orderBy('added_at', 'desc')
                ->paginate(12); // paginated
        } else {
            if ($roomid === null) {
                $accArr = AccidentsModel::select('id', 'child_name', 'child_gender', 'roomid', 'incident_date', 'ack_parent_name', 'added_by')
                    ->orderBy('added_at', 'desc')->paginate(12);
            } else {
                $accArr = AccidentsModel::select('id', 'child_name', 'child_gender', 'roomid', 'incident_date', 'ack_parent_name', 'added_by')
                    ->where('roomid', $roomid)
                    ->orderBy('added_at', 'desc')
                    ->paginate(12);
            }
        }

        foreach ($accArr as $acc) {
            $user = User::find($acc->added_by);
            $acc->username = $user->name ?? 'Unknown';
        }

        $childs = Child::where('room', $roomid)->get();

        $permission = Permission::where('userid', Auth::user()->userid)->where('centerid', $centerid)->first();

        return view('Accidents.List', [
            'centerid'   => $centerid,
            'date'       => $date,
            'roomid'     => $roomid,
            'roomname'   => $roomname,
            'roomcolor'  => $roomcolor,
            'rooms'      => $centerRooms,
            'childs'     => $childs,
            'accidents'  => $accArr,
            'centers'    => $centers,
            'selectedCenter' => $request->centerid,
            'selectedRoom' =>   $selectedRoom,
            'permission' => $permission
        ]);
    }


    public function getCenterRooms(Request $request)
    {

        $userId = Auth::user()->userid;
        $centerid = Session('user_center_id');

        // Fetch rooms from DB
        $rooms = Room::where('centerid', $centerid)->get();

        return response()->json([
            'Status' => 'SUCCESS',
            'Rooms' => $rooms
        ]);
    }




    public function getAccidentDetails(Request $request)
    {
        $userid = Auth::user()->userid;
        $accidentId = $request->id ?? null;
        $centerid = Session('user_center_id');
        if (!$accidentId) {
            return redirect()->back()->with('msg', 'Error! Accident id could not be fetched. Please try again');
        }
        $accident = AccidentsModel::with(['child', 'addedByUser'])
            ->find($accidentId);
        $accidentIllness = AccidentIllnessModel::where('accident_id', $accidentId)->first();

        if ($accidentIllness) {
            $illnessData = $accidentIllness->toArray();
            $illnessData['illness_id'] = $illnessData['id']; // rename 'id' to 'illness_id'
            unset($illnessData['id']); // remove original 'id'

            // Merge illness data into $accident
            foreach ($illnessData as $key => $value) {
                $accident->$key = $value;
            }
        }
        if (!$accident) {
            return redirect()->back()->with('error', 'No Accident Details present');
        }

        return view('Accidents.view', [

            'AccidentInfo' => $accident,
            'accidentId' => $accidentId
        ]);
    }

    public function sendEmail(Request $request)
    {
        $htmlContent = $request->html_content;
        $studentId = $request->student_id;

        // Validate input
        if (!$htmlContent || !$studentId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required parameters'
            ]);
        }

        // Fetch parent IDs using Eloquent
        $parentIds = Childparent::where('childid', $studentId)->pluck('parentid');;

        if ($parentIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No parent found for the given student ID'
            ]);
        }

        // Fetch parent email IDs using Eloquent
        $emailIds = User::whereIn('userid', $parentIds)->pluck('emailid');

        if ($emailIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No email found for the parent(s)'
            ]);
        }

        // Generate PDF
        $pdf = Pdf::loadHTML($htmlContent)->setPaper('a4', 'portrait');
        $filename = 'student_report_' . $studentId . '_' . now()->format('Ymd_His') . '.pdf';
        $pdfPath = public_path('reports/' . $filename);

        // Make sure the directory exists
        if (!file_exists(public_path('reports'))) {
            mkdir(public_path('reports'), 0777, true);
        }

        // Save the PDF to public/reports/
        file_put_contents($pdfPath, $pdf->output());

        // Send Emails with attachment
        $emailSentCount = 0;
        foreach ($emailIds as $email) {
            try {

                Mail::send([], [], function ($message) use ($email, $studentId, $pdfPath) {
                    $message->from('mydairee47@gmail.com', 'Accident Report')
                        ->to($email)
                        ->subject('Student Report - ' . $studentId)
                        ->html('Please find attached the latest report for student ID: ' . $studentId)
                        ->attach($pdfPath);
                });


                $emailSentCount++;
            } catch (\Exception $e) {
                Log::error("Failed to send email to $email: " . $e->getMessage());
            }
        }

        // Delete the PDF after sending
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        // Return JSON response
        return response()->json([
            'success' => $emailSentCount > 0,
            'message' => $emailSentCount > 0
                ? 'Report sent successfully to ' . $emailSentCount . ' parent(s)'
                : 'Failed to send emails'
        ]);
    }

    public function saveAccident(Request $request)
    {

        $request->validate([
            'childid' => 'required',
            'incident_date' => 'required',
            'incident_time' => 'required',

        ], [
            'childid.required' => 'Child is required.',
            'incident_date.required' => 'accident date is required',
            'incident_time.required' => 'accident time required'

        ]);

        $data = $request->all();



        // Add Auth/session fields
        $request->merge([
            'userid' => Auth::user()->userid,
            'username' => Auth::user()->name,
            'centerid' => session('user_center_id'),
            'roomid' => $request->roomid,
        ]);

        $targetPath = public_path('assets/media/');
        File::ensureDirectoryExists($targetPath);

        $imageMappings = [
            'person_sign' => "personSign",
            'witness_sign' => "witnessSign",
            'injury_image' => "injuryImage",
            'responsible_person_sign' => "personInchargeSign",
            'nominated_supervisor_sign' => "supervisorSign",
        ];

        // Insert or Update
        if (!empty($request->id)) {
            $this->updateAccident($request); // you must return accidentId from this if needed
            $accidentId = $request->id;
        } else {
            $accidentId = $this->insertAccident($request);
            if (!$accidentId) {
                return redirect()->back()->with('error', 'Failed to save accident record.');
            }
        }

        // Fetch the latest record
        $existingAccident = AccidentsModel::find($accidentId);

        // Handle image uploads
        foreach ($imageMappings as $field => $prefix) {
            if (!empty($data[$field])) {
                $filename = "$prefix-$accidentId.png";
                $relativePath = "uploads/accidents/$filename";
                $absolutePath = public_path($relativePath);

                // Delete old image
                if ($existingAccident && !empty($existingAccident->$field)) {
                    $oldPath = public_path("uploads/accidents/" . basename($existingAccident->$field));
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                // Decode and save image
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data[$field]));
                File::ensureDirectoryExists(public_path('uploads/accidents'));
                File::put($absolutePath, $imageData);

                // Generate full URL to image
                $imageUrl = url($relativePath); // Or use asset($relativePath)

                // Update DB with full URL
                AccidentsModel::where('id', $accidentId)->update([
                    $field => $imageUrl
                ]);

                // Pass accidentId for any follow-up actions
                $request->merge(['id' => $accidentId]);

                if ($accidentId) {
                    $this->updateIllness($request);
                }
            }
        }

        return redirect()->route('Accidents.list')->with('success', 'Accident record saved successfully.');
    }


    public function updateAccident($request)
    {
        $request->validate([
            'id' => 'required|integer|exists:accidents,id',
            // You can add more validation rules here as needed
        ]);

        $accident = AccidentsModel::find($request->id);

        if (!$accident) {
            return response()->json([
                'success' => false,
                'message' => 'Accident record not found.'
            ], 404);
        }



        $accident =  $accident->update([
            'centerid' => $request->centerid ?? '',
            'roomid' => $request->roomid ?? '',
            'person_name' => $request->person_name ?? '',
            'person_role' => $request->person_role ?? '',
            'service_name' => $request->service_name ?? '',
            'made_record_time' => $request->made_record_date ?? '',
            'made_record_time' => $request->made_record_time ?? '',
            'made_person_sign' => $request->made_person_sign ?? '',
            'childid' => $request->childid ?? '',
            'child_name' => $request->child_name ?? '',
            'child_dob' => $request->child_dob ?? '',
            'child_age' => $request->child_age ?? '',
            'child_gender' => $request->gender ?? '',
            'incident_date' => $request->incident_date ?? '',
            'incident_time' => $request->incident_time ?? '',
            'incident_location' => $request->incident_location ?? '',
            'location_of_incident' => $request->location_of_incident ?? '',
            'witness_name' => $request->witness_name ?? '',
            'witness_date' => $request->witness_date ?? null,
            'witness_sign' => $request->witness_sign ?? null,

            'details_injury' => $request->details_injury ?? null,
            'circumstances_leading' => $request->circumstances_leading ?? null,
            'circumstances_child_missingd' => $request->circumstances_child_missingd ?? null,
            'circumstances_child_removed' => $request->circumstances_child_removed ?? null,
            'injury_image' => $request->injury_image ?? null,
            'remarks' => $request->remarks ?? null,
            'action_taken' => $request->action_taken ?? '',
            'emrg_serv_attend' => $request->has('emrg_serv_attend') ? 'Yes' : 'No',
            'emrg_serv_time' => $request->emrg_serv_time ?? '',
            'emrg_serv_arrived' => $request->emrg_serv_arrived ?? '',
            'med_attention' => $request->has('med_attention') ? 'Yes' : 'No',
            'med_attention_details' => $request->med_attention_details ?? '',
            'provideDetails_minimise' => $request->provideDetails_minimise ?? '',
            'parent1_name' => $request->parent1_name ?? '',
            'carers_date' => $request->carers_date ?? '',
            'carers_time' => $request->carers_time ?? '',
            'director_educator_coordinator' => $request->director_educator_coordinator ?? '',
            'educator_date' => $request->educator_date ?? '',
            'educator_time' => $request->educator_time ?? '',
            'other_agency' => $request->other_agency ?? '',
            'other_agency_date' => $request->other_agency_date ?? '',
            'other_agency_time' => $request->other_agency_time ?? '',
            'regulatory_authority' => $request->regulatory_authority ?? '',
            'regulatory_authority_date' => $request->regulatory_authority_date ?? '',
            'regulatory_authority_time' => $request->regulatory_authority_time ?? '',
            'ack_parent_name' => $request->ack_parent_name ?? '',
            'ack_date' => $request->ack_date ?? '',
            'ack_time' => $request->ack_time ?? '',
            'final_sign' => $request->final_sign ?? '',
            'add_notes' => $request->add_notes ?? '',
            'added_by' => $request->userid ?? Auth::user()->userid,
            'added_at' => now()
        ]);

        return $accident;
    }



    public function insertAccident($request)
    {
        $accident = AccidentsModel::create([
            'centerid' => $request->centerid ?? '',
            'roomid' => $request->roomid ?? '',
            'person_name' => $request->person_name ?? '',
            'person_role' => $request->person_role ?? '',
            'service_name' => $request->service_name ?? '',
            'made_record_time' => $request->made_record_date ?? '',
            'made_record_time' => $request->made_record_time ?? '',
            'childid' => $request->childid ?? '',
            'child_name' => $request->child_name ?? '',
            'child_dob' => $request->child_dob ?? '',
            'child_age' => $request->child_age ?? '',
            'child_gender' => $request->gender,
            'incident_date' => $request->incident_date ?? '',
            'incident_time' => $request->incident_time ?? '',
            'incident_location' => $request->incident_location ?? '',
            'location_of_incident' => $request->location_of_incident ?? '',
            'witness_name' => $request->witness_name ?? '',
            'witness_date' => $request->witness_date ?? null,
            'witness_sign' => $request->witness_sign ?? null,
            'details_injury' => $request->details_injury ?? null,
            'circumstances_leading' => $request->circumstances_leading ?? null,
            'circumstances_child_missingd' => $request->circumstances_child_missingd ?? null,
            'circumstances_child_removed' => $request->circumstances_child_removed ?? null,
            'injury_image' => $request->injury_image ?? null,
            'remarks' => $request->remarks ?? null,
            'action_taken' => $request->action_taken ?? '',
            'emrg_serv_attend' => $request->has('emrg_serv_attend') ? 'Yes' : 'No',
            'emrg_serv_time' => $request->emrg_serv_time ?? '',
            'emrg_serv_arrived' => $request->emrg_serv_arrived ?? '',
            'med_attention' => $request->has('med_attention') ? 'Yes' : 'No',
            'med_attention_details' => $request->med_attention_details ?? '',
            'provideDetails_minimise' => $request->provideDetails_minimise ?? '',
            'parent1_name' => $request->parent1_name ?? '',
            'carers_date' => $request->carers_date ?? '',
            'carers_time' => $request->carers_time ?? '',
            'director_educator_coordinator' => $request->director_educator_coordinator ?? '',
            'educator_date' => $request->educator_date ?? '',
            'educator_time' => $request->educator_time ?? '',
            'other_agency' => $request->other_agency ?? '',
            'other_agency_date' => $request->other_agency_date ?? '',
            'other_agency_time' => $request->other_agency_time ?? '',
            'regulatory_authority' => $request->regulatory_authority ?? '',
            'regulatory_authority_date' => $request->regulatory_authority_date ?? '',
            'regulatory_authority_time' => $request->regulatory_authority_time ?? '',
            'ack_parent_name' => $request->ack_parent_name ?? '',
            'ack_date' => $request->ack_date ?? '',
            'ack_time' => $request->ack_time ?? '',
            'final_sign' => $request->final_sign ?? '',
            'add_notes' => $request->add_notes ?? '',
            'added_by' => $request->userid ?? Auth::user()->userid,
            'added_at' => now()
        ]);

        return $accident->id;
    }

    public function updateIllness($data)
    {
        // Delete existing illness records for the accident
        AccidentIllnessModel::where('accident_id', $data->id)->delete();

        // Prepare insert array
        $illnessData = [
            'accident_id' => $data->id,
            'abrasion' => isset($data->abrasion) ? 1 : 0,
            'electric_shock' => isset($data->electric_shock) ? 1 : 0,
            'allergic_reaction' => isset($data->allergic_reaction) ? 1 : 0,
            'high_temperature' => isset($data->high_temperature) ? 1 : 0,
            'amputation' => isset($data->amputation) ? 1 : 0,
            'infectious_disease' => isset($data->infectious_disease) ? 1 : 0,
            'anaphylaxis' => isset($data->anaphylaxis) ? 1 : 0,
            'ingestion' => isset($data->ingestion) ? 1 : 0,
            'asthma' => isset($data->asthma) ? 1 : 0,
            'internal_injury' => isset($data->internal_injury) ? 1 : 0,
            'bite_wound' => isset($data->bite_wound) ? 1 : 0,
            'poisoning' => isset($data->poisoning) ? 1 : 0,
            'broken_bone' => isset($data->broken_bone) ? 1 : 0,
            'rash' => isset($data->rash) ? 1 : 0,
            'burn' => isset($data->burn) ? 1 : 0,
            'respiratory' => isset($data->respiratory) ? 1 : 0,
            'choking' => isset($data->choking) ? 1 : 0,
            'seizure' => isset($data->seizure) ? 1 : 0,
            'concussion' => isset($data->concussion) ? 1 : 0,
            'sprain' => isset($data->sprain) ? 1 : 0,
            'crush' => isset($data->crush) ? 1 : 0,
            'stabbing' => isset($data->stabbing) ? 1 : 0,
            'cut' => isset($data->cut) ? 1 : 0,
            'tooth' => isset($data->tooth) ? 1 : 0,
            'drowning' => isset($data->drowning) ? 1 : 0,
            'venomous_bite' => isset($data->venomous_bite) ? 1 : 0,
            'eye_injury' => isset($data->eye_injury) ? 1 : 0,
            'other' => isset($data->other) ? 1 : 0,
            'remarks' => $data->remarks ?? null
        ];

        // Insert new illness record
        return AccidentIllnessModel::create($illnessData)->id;
    }


    public function create(Request $request)
    {
        $centerid = Session::get('user_center_id');
        $user = Auth::user();
        $roomid = $request->roomid;
        // dd($roomid);
        $children = Child::where('room', $roomid)
            ->where('status', 'Active')
            ->get()
            ->map(function ($child) {
                $age = $child->dob ? Carbon::parse($child->dob)->age : 'N/A';
                $child->details = "{$child->name} - {$age} Years";
                return $child;
            });

        return view('Accidents.create', [
            'Childrens' => $children,
            'centerid' => $centerid,
            'roomid' => $roomid,
        ]);
    }

    public function getChildDetails(Request $request)
    {
        $id = $request->childid;
        $child = Child::where('id', $id)->where('status', 'Active')->first();

        if ($child) {
            return response()->json([
                'Status' => 'SUCCESS',
                'Child' => $child
            ]);
        } else {
            return response()->json([
                'Status' => 'ERROR',
                'Message' => 'Child not found'
            ], 404);
        }
    }


    public function AccidentEdit(Request $request)
    {
        $userid = Auth::user()->userid;
        $accidentId = $request->id ?? null;
        $centerid = Session('user_center_id');

        $roomid = $request->roomid;
        //  dd( $roomid);

        //  Accident ID missing
        if (!$accidentId) {
            return redirect()->back()->with('msg', 'Error! Accident id could not be fetched. Please try again');
        }

        // ✅ Fetch accident via Eloquent
        // $accident = AccidentsModel::with(['child', 'addedByUser']) // assuming relations
        //     ->find($accidentId);

        // dd($accident);
        $accident = AccidentsModel::with(['child', 'addedByUser'])
            ->find($accidentId);

        $accidentIllness = AccidentIllnessModel::where('accident_id', $accidentId)->first();

        if ($accidentIllness) {
            $illnessData = $accidentIllness->toArray();
            $illnessData['illness_id'] = $illnessData['id']; // rename 'id' to 'illness_id'
            unset($illnessData['id']); // remove original 'id'

            // Merge illness data into $accident
            foreach ($illnessData as $key => $value) {
                $accident->$key = $value;
            }
        }



        $children = Child::where('room', $roomid)
            ->where('status', 'Active')
            ->get()
            ->map(function ($child) {
                $age = $child->dob ? Carbon::parse($child->dob)->age : 'N/A';
                $child->details = "{$child->name} - {$age} Years";
                return $child;
            });

        if (!$accident) {
            return redirect()->back()->with('error', 'No Accident Details present');
        }
        // dd($children);

        return view('Accidents.edit', [

            'AccidentInfo' => $accident,
            'Childrens' => $children,
            'roomid' => $request->roomid,
            'centerid' => $request->centerid
        ]);
    }
}
