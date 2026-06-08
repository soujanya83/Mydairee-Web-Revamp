<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccidentsModel;
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
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;


class AccidentsController extends Controller
{


   public function mernCreateAccident(Request $request)
{
    DB::beginTransaction();

    try {
        $validator = Validator::make($request->all(), [
            'accident_id' => 'nullable|integer',
            'childid' => 'required',
            'incident_date' => 'required|date',
            'incident_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = Auth::user();
        $addedBy = $user->userid ?? $user->id ?? null;

        $data = [
            'centerid' => $request->centerid ?? '',
            'roomid' => $request->roomid ?? '',
            'person_name' => $request->person_name ?? '',
            'person_role' => $request->person_role ?? '',
            'service_name' => $request->service_name ?? '',
            'made_record_date' => $request->made_record_date ?? '',
            'made_record_time' => $request->made_record_time ?? '',
            'childid' => $request->childid,
            'child_name' => $request->child_name ?? '',
            'child_dob' => $request->child_dob ?? '',
            'child_age' => $request->child_age ?? '',
            'child_gender' => $request->child_gender ?? '',
            'incident_date' => $request->incident_date,
            'incident_time' => $request->incident_time,
            'incident_location' => $request->incident_location ?? '',
            'location_of_incident' => $request->location_of_incident ?? '',
            'witness_name' => $request->witness_name ?? '',
            'witness_date' => $request->witness_date ?? '',
            'details_injury' => $request->details_injury ?? '',
            'circumstances_leading' => $request->circumstances_leading ?? '',
            'circumstances_child_missingd' => $request->circumstances_child_missingd ?? '',
            'circumstances_child_removed' => $request->circumstances_child_removed ?? '',
            'remarks' => $request->accident_remarks ?? ($request->remarks ?? ''),
            'action_taken' => $request->action_taken ?? '',
            'emrg_serv_attend' => $request->emrg_serv_attend ?? 'No',
            'emrg_serv_time' => $request->emrg_serv_time ?? '',
            'emrg_serv_arrived' => $request->emrg_serv_arrived ?? '',
            'med_attention' => $request->med_attention ?? 'No',
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
            'add_notes' => $request->add_notes ?? '',
            'ack_incident' => $request->boolean('ack_incident') ? 1 : 0,
            'ack_injury' => $request->boolean('ack_injury') ? 1 : 0,
            'ack_trauma' => $request->boolean('ack_trauma') ? 1 : 0,
            'ack_illness' => $request->boolean('ack_illness') ? 1 : 0,
            'added_by' => $addedBy,
        ];

        if (!empty($request->accident_id)) {
            $accident = AccidentsModel::findOrFail($request->accident_id);
            $accident->update($data);
            $this->replaceAccidentImages($accident, $request);
            $this->saveIllnessData($accident->id, $request);
            $message = 'Accident updated successfully';
        } else {
            $data['added_at'] = now();
            $accident = AccidentsModel::create($data);
            $this->saveAccidentImages($accident, $request);
            $this->saveIllnessData($accident->id, $request);
            $message = 'Accident created successfully';
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'accident_id' => $accident->id,
            ],
        ], 200);

    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage(),
        ], 500);
    }
}
  private function saveAccidentImages($accident, $request)
{
    $imageMappings = [
        'made_person_sign' => 'madePersonSign',
        'witness_sign' => 'witnessSign',
        'injury_image' => 'injuryImage',
        'final_sign' => 'finalSign',
    ];

    foreach ($imageMappings as $field => $prefix) {
        if (!empty($request->$field)) {
            $filename = $prefix . '-' . $accident->id . '.png';
            $relativePath = 'uploads/accidents/' . $filename;
            $absoluteDir = public_path('uploads/accidents');

            File::ensureDirectoryExists($absoluteDir);

            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->$field);
            $binary = base64_decode($imageData, true);

            if ($binary === false) {
                throw new \Exception("Invalid base64 image for {$field}");
            }

            File::put(public_path($relativePath), $binary);
            $accident->$field = url($relativePath);
        }
    }

    $accident->save();
}

  private function saveIllnessData($accidentId, Request $request)
{
    AccidentIllnessModel::where('accident_id', $accidentId)->delete();

    return AccidentIllnessModel::create([
        'accident_id' => $accidentId,
        'abrasion' => $request->boolean('abrasion') ? 1 : 0,
        'allergic_reaction' => $request->boolean('allergic_reaction') ? 1 : 0,
        'amputation' => $request->boolean('amputation') ? 1 : 0,
        'anaphylaxis' => $request->boolean('anaphylaxis') ? 1 : 0,
        'asthma' => $request->boolean('asthma') ? 1 : 0,
        'bite_wound' => $request->boolean('bite_wound') ? 1 : 0,
        'broken_bone' => $request->boolean('broken_bone') ? 1 : 0,
        'burn' => $request->boolean('burn') ? 1 : 0,
        'choking' => $request->boolean('choking') ? 1 : 0,
        'concussion' => $request->boolean('concussion') ? 1 : 0,
        'crush' => $request->boolean('crush') ? 1 : 0,
        'cut' => $request->boolean('cut') ? 1 : 0,
        'drowning' => $request->boolean('drowning') ? 1 : 0,
        'eye_injury' => $request->boolean('eye_injury') ? 1 : 0,
        'electric_shock' => $request->boolean('electric_shock') ? 1 : 0,
        'infectious_disease' => $request->boolean('infectious_disease') ? 1 : 0,
        'high_temperature' => $request->boolean('high_temperature') ? 1 : 0,
        'ingestion' => $request->boolean('ingestion') ? 1 : 0,
        'internal_injury' => $request->boolean('internal_injury') ? 1 : 0,
        'poisoning' => $request->boolean('poisoning') ? 1 : 0,
        'rash' => $request->boolean('rash') ? 1 : 0,
        'respiratory' => $request->boolean('respiratory') ? 1 : 0,
        'seizure' => $request->boolean('seizure') ? 1 : 0,
        'sprain' => $request->boolean('sprain') ? 1 : 0,
        'stabbing' => $request->boolean('stabbing') ? 1 : 0,
        'tooth' => $request->boolean('tooth') ? 1 : 0,
        'venomous_bite' => $request->boolean('venomous_bite') ? 1 : 0,
        'other' => $request->boolean('other') ? 1 : 0,
        'remarks' => $request->illness_remarks ?? ($request->remarks ?? '')
    ]);
}

private function replaceAccidentImages($accident, $request)
{
    $imageMappings = [
        'made_person_sign' => 'madePersonSign',
        'witness_sign' => 'witnessSign',
        'injury_image' => 'injuryImage',
        'final_sign' => 'finalSign',
    ];

    foreach ($imageMappings as $field => $prefix) {
        if (!empty($request->$field)) {
            if (!empty($accident->$field)) {
                $oldRelative = str_replace(url('/') . '/', '', $accident->$field);
                $oldAbsolute = public_path($oldRelative);
                if (File::exists($oldAbsolute)) {
                    File::delete($oldAbsolute);
                }
            }

            $filename = $prefix . '-' . $accident->id . '-' . time() . '.png';
            $relativePath = 'uploads/accidents/' . $filename;
            $absolutePath = public_path($relativePath);

            File::ensureDirectoryExists(public_path('uploads/accidents'));

            $imageData = preg_replace('#^data:image/\w+;base64,#i', '', $request->$field);
            $binary = base64_decode($imageData, true);

            if ($binary === false) {
                throw new \Exception("Invalid base64 image for {$field}");
            }

            File::put($absolutePath, $binary);
            $accident->$field = url($relativePath);
        }
    }

    $accident->save();
}

    public function AccidentDelete(Request $r)
    {
        // ✅ Validate request with Validator
        $validator = Validator::make($r->all(), [
            'accidentid' => 'required|integer|exists:accidents,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Find accident record
            $accident = AccidentsModel::find($r->accidentid);

            if (!$accident) {
                return response()->json([
                    'status' => false,
                    'message' => 'Accident record not found'
                ], 404);
            }

            // Map DB columns to file name prefixes
            $imageMappings = [
                'person_sign'              => "personSign",
                'witness_sign'             => "witnessSign",
                'injury_image'             => "injuryImage",
                'responsible_person_sign'  => "personInchargeSign",
                'nominated_supervisor_sign'=> "supervisorSign",
            ];

            // ✅ Delete related image files if exist
            foreach ($imageMappings as $field => $prefix) {
                if (!empty($accident->$field)) {
                    $oldPath = public_path("uploads/accidents/" . basename($accident->$field));
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
            }

            // ✅ Delete related illness records
            AccidentIllnessModel::where('accident_id', $r->accidentid)->delete();

            // ✅ Delete accident record
            $accident->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Accident record deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while deleting accident record',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function AccidentsList(Request $request)
    {
        $user = Auth::user();
        // $user = User::where('userid',$request->userid)->first();
        $userid = $user->userid;
        $userType = $user->userType;
        $centerid = $request->centerid;

        // Fallback: get first assigned center if not present
        if (empty($centerid)) {
            $centerid = Usercenter::where('userid', $userid)->pluck('centerid')->first();
        }

        // Get list of centers
        if ($userType === "Superadmin" || $userType === "Centeradmin") {
            $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        // Get room info
        $roomid = $request->roomid;
        if (empty($roomid)) {
            $centerRoom = Room::where('centerid', $centerid)->first();
            $roomid = $centerRoom->id ?? null;
            $roomname = $centerRoom->name ?? '';
            $roomcolor = $centerRoom->color ?? '';
            $selectedRoom = Room::where('id', $roomid)->first();
                $centerRooms = Room::where('centerid', $centerid)->get();
        } else {
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $selectedRoom = Room::where('id', $roomid)->first();
                $centerRooms = Room::where('centerid', $centerid)->get();
        }

        if(Auth::user()->userType == "Parent"){

            $roomid = $request->roomid;
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $childids = Childparent::where('parentid',$userid)->pluck('childid');
            $roomids = Child::whereIn('id',$childids)->pluck('room');
            $centerRooms = Room::whereIn('id',  $roomids)->get();
            $selectedRoom = Room::where('id', $roomid)->first();
        }

    

        $date = !empty($request->date)
            ? date('Y-m-d', strtotime($request->date))
            : date('Y-m-d');

        // Get accidents data
        if ($userType == "Parent") {
            $accArr = AccidentsModel::select('accidents.id', 'accidents.child_name', 'accidents.child_gender', 'accidents.roomid', 'accidents.incident_date', 'accidents.ack_parent_name', 'accidents.added_by')
                ->join('childparent', 'childparent.childid', '=', 'accidents.childid')
                ->where('childparent.parentid', $userid)
                ->get();
        } else {
            $query = AccidentsModel::select('id', 'child_name', 'child_gender', 'roomid', 'incident_date', 'ack_parent_name', 'added_by');
            if (!empty($roomid)) {
                $query->where('roomid', $roomid);
            }
            $accArr = $query->get();
        }

        // Append username to each accident
        foreach ($accArr as $acc) {
            $addedByUser = User::find($acc->added_by);
            $acc->username = $addedByUser->name ?? 'Unknown';
        }

        $childs = Child::where('room', $roomid)->get();

        return response()->json([
            'success'      => true,
            'message'      => 'Accident list fetched successfully.',
            'data'         => [
                'centerid'        => $centerid,
                'date'            => $date,
                'roomid'          => $roomid,
                'roomname'        => $roomname,
                'roomcolor'       => $roomcolor,
                //'rooms'           => $centerRooms,
                //'childs'          => $childs,
                'accidents'       => $accArr,
                //'centers'         => $centers,
                'selectedCenter'  => $request->centerid,
            ]
        ]);
    }

    public function mernAccidentsList(Request $request)
    {
        $user = Auth::user();
        // $user = User::where('userid',$request->userid)->first();
        $userid = $user->userid;
        $userType = $user->userType;
        $centerid = $request->centerid;
        $selectedChildId = null;
        $selectedChildSource = null;

        // Fallback: get first assigned center if not present
        if (empty($centerid)) {
            $centerid = Usercenter::where('userid', $userid)->pluck('centerid')->first();
        }

        // Get list of centers
        if ($userType === "Superadmin" || $userType === "Centeradmin") {
            $centerIds = Usercenter::where('userid', $userid)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        // Get room info
        $roomid = $request->roomid;
        if (empty($roomid)) {
            $centerRoom = Room::where('centerid', $centerid)->first();
            $roomid = $centerRoom->id ?? null;
            $roomname = $centerRoom->name ?? '';
            $roomcolor = $centerRoom->color ?? '';
            $selectedRoom = Room::where('id', $roomid)->first();
                $centerRooms = Room::where('centerid', $centerid)->get();
        } else {
            $room = Room::find($roomid);
            $roomname = $room->name ?? '';
            $roomcolor = $room->color ?? '';
            $selectedRoom = Room::where('id', $roomid)->first();
                $centerRooms = Room::where('centerid', $centerid)->get();
        }

        if(Auth::user()->userType == "Parent"){

            $childids = Childparent::where('parentid', $userid)->pluck('childid')->values();
            $requestedChildId = $request->input('child_id', $request->input('childid'));
            $savedChildId = User::where('userid', $userid)->value('selectedchildreanid');

            if (!empty($requestedChildId) && trim((string) $requestedChildId) !== '') {
                $requestedChildId = (int) $requestedChildId;

                if (!$childids->contains($requestedChildId)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'This child does not belong to this parent',
                    ], 403);
                }

                $selectedChildId = $requestedChildId;
                $selectedChildSource = 'request';
            }

            if (!$selectedChildId && !empty($savedChildId) && $childids->contains((int) $savedChildId)) {
                $selectedChildId = (int) $savedChildId;
                $selectedChildSource = 'saved';
            }

            if (!$selectedChildId && $childids->isNotEmpty()) {
                $selectedChildId = (int) $childids->first();
                $selectedChildSource = 'fallback';
            }

            if ($selectedChildId) {
                $selectedChild = Child::where('id', $selectedChildId)->first();

                if ($selectedChild) {
                    $roomid = $selectedChild->room;
                    $room = Room::find($roomid);
                    $roomname = $room->name ?? '';
                    $roomcolor = $room->color ?? '';
                    $selectedRoom = $room;
                }
            }

            $roomids = Child::whereIn('id', $childids)->pluck('room');
            $centerRooms = Room::whereIn('id', $roomids)->get();
        }

    

        $date = !empty($request->date)
            ? date('Y-m-d', strtotime($request->date))
            : date('Y-m-d');

        // Get accidents data
        if ($userType == "Parent") {
            $accQuery = AccidentsModel::select('accidents.id', 'accidents.child_name', 'accidents.child_gender', 'accidents.roomid', 'accidents.incident_date', 'accidents.ack_parent_name', 'accidents.added_by')
                ->join('childparent', 'childparent.childid', '=', 'accidents.childid')
                ->where('childparent.parentid', $userid);

            if ($selectedChildId) {
                $accQuery->where('accidents.childid', $selectedChildId);
            }

            if (!empty($roomid)) {
                $accQuery->where('accidents.roomid', $roomid);
            }

            $accArr = $accQuery->get();
        } else {
            $query = AccidentsModel::select('id', 'child_name', 'child_gender', 'roomid', 'incident_date', 'ack_parent_name', 'added_by');
            if (!empty($roomid)) {
                $query->where('roomid', $roomid);
            }
            $accArr = $query->get();
        }

        // Append username to each accident
        foreach ($accArr as $acc) {
            $addedByUser = User::find($acc->added_by);
            $acc->username = $addedByUser->name ?? 'Unknown';
        }

        $childs = Child::where('room', $roomid)->get();

        $selectionMeta = $userType === 'Parent'
            ? [
                'selectedChildId' => $selectedChildId,
                'selectedChildSource' => $selectedChildSource,
            ]
            : [];

        return response()->json([
            'success'      => true,
            'message'      => 'Accident list fetched successfully.',
            'data'         => [
                'centerid'        => $centerid,
                'date'            => $date,
                'roomid'          => $roomid,
                'roomname'        => $roomname,
                'roomcolor'       => $roomcolor,
                //'rooms'           => $centerRooms,
                //'childs'          => $childs,
                'accidents'       => $accArr,
                //'centers'         => $centers,
                'selectedCenter'  => $request->centerid,
            ] + $selectionMeta
        ]);
    }

    public function getCenterRooms(Request $request)
    {

        // $userId = Auth::user()->userid;
        $centerid = $request->centerid;

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
        // $userid = $request->userid;
        $accidentId = $request->id ?? null;
        $centerid = $request->centerid;

        // Accident ID missing
        if (!$accidentId) {
            return response()->json([
                'status' => false,
                'message' => 'Error! Accident ID is missing.'
            ], 400);
        }

        // Fetch accident with child and added user relations
        $accident = AccidentsModel::with(['child', 'addedByUser'])->find($accidentId);

        if (!$accident) {
            return response()->json([
                'status' => false,
                'message' => 'No Accident Details present.'
            ], 404);
        }

        // Attach illness data if exists
        $accidentIllness = AccidentIllnessModel::where('accident_id', $accidentId)->first();
        if ($accidentIllness) {
            $illnessData = $accidentIllness->toArray();
            $illnessData['illness_id'] = $illnessData['id'];
            unset($illnessData['id']);

            foreach ($illnessData as $key => $value) {
                $accident->$key = $value;
            }
        }
    
        // Return structured JSON
        return response()->json([
            'status' => true,
            'message' => 'Accident details retrieved successfully.',
            'data' => $accident
        ]);
    }

    public function sendEmail(Request $request)
    {
        $accidentId = $request->id;
        // $htmlContent = $request->html_content;
        $studentId = $request->student_id;

        if (!$accidentId) {
            return response()->json([
                'status' => false,
                'message' => 'Error! Accident ID is missing.'
            ], 400);
        }

        $accident = AccidentsModel::with(['child', 'addedByUser'])->find($accidentId);

        if (!$accident) {
            return response()->json([
                'status' => false,
                'message' => 'No Accident Details present.'
            ], 404);
        }

        // Attach illness data if exists
        $accidentIllness = AccidentIllnessModel::where('accident_id', $accidentId)->first();
        if ($accidentIllness) {
            $illnessData = $accidentIllness->toArray();
            $illnessData['illness_id'] = $illnessData['id'];
            unset($illnessData['id']);

            foreach ($illnessData as $key => $value) {
                $accident->$key = $value;
            }
        }

        // Validate input
        if (!$studentId) {
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
            $htmlContent = view('Accidents.pdf', ['AccidentInfo'=>$accident])->render();
            if (!$htmlContent || !$studentId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required parameters'
            ]);
        }
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
        $data = $request->all();

        // dd($data);

        // Add Auth/session fields
        $request->merge([
            'userid' => Auth::user()->userid,
            'username' => Auth::user()->name,
            'centerid' => $request->centerid,
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
        $updateResponse = $this->updateAccident($request); // you must return accidentId from this if needed
            // If updateAccident returned a Response (validation fail or 404)
            if ($updateResponse instanceof \Illuminate\Http\JsonResponse) {
                return $updateResponse; // return immediately
            }
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

            if($request->id){
                return response()->json([
            'success' => true,
            'message' => 'Accident record updated successfully.',
            'accident_id' => $accidentId
        ]);
            }
        return response()->json([
            'success' => true,
            'message' => 'Accident record saved successfully.',
            'accident_id' => $accidentId
        ]);

    }

    public function updateAccident($request)
    {
        //   dd($request->id);
      $accident = AccidentsModel::find($request->id);

        if (!$accident) {
            return response()->json([
                'status' => false,
                'message' => 'Accident record not found.'
            ], 404);
        }
    

     $accident =  $accident->update([
            'centerid' => $request->centerid ?? '',
            'roomid' => $request->roomid ?? '',
            'person_name' => $request->person_name ?? '',
            'person_role' => $request->person_role ?? '',
            'date' => $request->date ?? '',
            'time' => $request->time ?? '',
            'childid' => $request->childid ?? '',
            'child_name' => $request->child_name ?? '',
            'child_dob' => $request->child_dob ?? '',
            'child_age' => $request->child_age ?? '',
            'child_gender' => $request->gender ?? '',
            'incident_date' => $request->incident_date ?? '',
            'incident_time' => $request->incident_time ?? '',
            'incident_location' => $request->incident_location ?? '',
            'witness_name' => $request->witness_name ?? '',
            'witness_date' => $request->witness_date ?? null,
            'gen_actyvt' => $request->gen_actyvt ?? '',
            'cause' => $request->cause ?? '',
            'illness_symptoms' => $request->illness_symptoms ?? '',
            'missing_unaccounted' => $request->missing_unaccounted ?? '',
            'taken_removed' => $request->taken_removed ?? '',
            'action_taken' => $request->action_taken ?? '',
            'emrg_serv_attend' => $request->has('emrg_serv_attend') ? 'Yes' : 'No',
            'med_attention' => $request->has('med_attention') ? 'Yes' : 'No',
            'med_attention_details' => $request->med_attention_details ?? '',
            'prevention_step_1' => $request->prevention_step_1 ?? '',
            'prevention_step_2' => $request->prevention_step_2 ?? '',
            'prevention_step_3' => $request->prevention_step_3 ?? '',
            'parent1_name' => $request->parent1_name ?? '',
            'contact1_method' => $request->contact1_method ?? '',
            'contact1_date' => $request->contact1_date ?? null,
            'contact1_time' => $request->contact1_time ?? '',
            'contact1_made' => $request->contact1_made ? 'Yes' : 'No',
            'contact1_msg' => $request->contact1_msg ? 'Yes' : 'No',
            'parent2_name' => $request->parent2_name ?? '',
            'contact2_method' => $request->contact2_method ?? '',
            'contact2_date' => $request->contact2_date ?? null,
            'contact2_time' => $request->contact2_time ?? '',
            'contact2_made' => $request->contact2_made ? 'Yes' : 'No',
            'contact2_msg' => $request->contact2_msg ? 'Yes' : 'No',
            'responsible_person_name' => $request->responsible_person_name ?? '',
            'responsible_person_sign' => $request->responsible_person_sign ?? '',
            'rp_internal_notif_date' => $request->rp_internal_notif_date ?? null,
            'rp_internal_notif_time' => $request->rp_internal_notif_time ?? '',
            'nominated_supervisor_sign' => $request->nominated_supervisor_sign ?? '',
            'nominated_supervisor_name' => $request->nominated_supervisor_name ?? '',
            'nominated_supervisor_date' => !empty($request->nsv_date) ? $request->nsv_date : null,
            'nominated_supervisor_time' => $request->nsv_time ?? '',
            'ext_notif_other_agency' => $request->otheragency ?? '',
            'enor_date' => $request->enor_date ?? null,
            'enor_time' => $request->enor_time ?? '',
            'ext_notif_regulatory_auth' => $request->Regulatoryauthority ?? '',
            'enra_date' => $request->enra_date ?? null,
            'enra_time' => $request->enra_time ?? '',
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
            'date' => $request->date ?? '',
            'time' => $request->time ?? '',
            'childid' => $request->childid ?? '',
            'child_name' => $request->child_name ?? '',
            'child_dob' => $request->child_dob ?? '',
            'child_age' => $request->child_age ?? '',
            'child_gender' => $request->gender ?? '',
            'incident_date' => $request->incident_date ?? '',
            'incident_time' => $request->incident_time ?? '',
            'incident_location' => $request->incident_location ?? '',
            'witness_name' => $request->witness_name ?? '',
            'witness_date' => $request->witness_date ?? null,
            'gen_actyvt' => $request->gen_actyvt ?? '',
            'cause' => $request->cause ?? '',
            'illness_symptoms' => $request->illness_symptoms ?? '',
            'missing_unaccounted' => $request->missing_unaccounted ?? '',
            'taken_removed' => $request->taken_removed ?? '',
            'action_taken' => $request->action_taken ?? '',
            'emrg_serv_attend' => $request->has('emrg_serv_attend') ? 'Yes' : 'No',
            'med_attention' => $request->has('med_attention') ? 'Yes' : 'No',
            'med_attention_details' => $request->med_attention_details ?? '',
            'prevention_step_1' => $request->prevention_step_1 ?? '',
            'prevention_step_2' => $request->prevention_step_2 ?? '',
            'prevention_step_3' => $request->prevention_step_3 ?? '',
            'parent1_name' => $request->parent1_name ?? '',
            'contact1_method' => $request->contact1_method ?? '',
            'contact1_date' => $request->contact1_date ?? null,
            'contact1_time' => $request->contact1_time ?? '',
            'contact1_made' => $request->contact1_made ? 'Yes' : 'No',
            'contact1_msg' => $request->contact1_msg ? 'Yes' : 'No',
            'parent2_name' => $request->parent2_name ?? '',
            'contact2_method' => $request->contact2_method ?? '',
            'contact2_date' => $request->contact2_date ?? null,
            'contact2_time' => $request->contact2_time ?? '',
            'contact2_made' => $request->contact2_made ? 'Yes' : 'No',
            'contact2_msg' => $request->contact2_msg ? 'Yes' : 'No',
            'responsible_person_name' => $request->responsible_person_name ?? '',
            'responsible_person_sign' => $request->responsible_person_sign ?? '',
            'rp_internal_notif_date' => $request->rp_internal_notif_date ?? null,
            'rp_internal_notif_time' => $request->rp_internal_notif_time ?? '',
            'nominated_supervisor_sign' => $request->nominated_supervisor_sign ?? '',
            'nominated_supervisor_name' => $request->nominated_supervisor_name ?? '',
            'nominated_supervisor_date' => !empty($request->nsv_date) ? $request->nsv_date : null,
            'nominated_supervisor_time' => $request->nsv_time ?? '',
            'ext_notif_other_agency' => $request->otheragency ?? '',
            'enor_date' => $request->enor_date ?? null,
            'enor_time' => $request->enor_time ?? '',
            'ext_notif_regulatory_auth' => $request->Regulatoryauthority ?? '',
            'enra_date' => $request->enra_date ?? null,
            'enra_time' => $request->enra_time ?? '',
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
        $user = Auth::user();
        $centerid = $request->centerid ?: Usercenter::where('userid', $user->userid)->value('centerid');
        $roomid = $request->roomid;

        $children = Child::where('centerid', $centerid)
            ->where('status', 'Active')
            ->orderBy('name')
            ->get()
            ->map(function ($child) {
                $age = $child->dob ? Carbon::parse($child->dob)->age : 'N/A';
                $child->details = "{$child->name} - {$age} Years";
                return $child;
            });

        $staffUsers = User::whereIn('id', Usercenter::where('centerid', $centerid)->pluck('userid'))
            ->where('userType', 'Staff')
            ->orderBy('name')
            ->get(['id', 'name', 'title', 'userType']);

        $rooms = Room::where('centerid', $centerid)->orderBy('name')->get();

        return response()->json([
            'status' => true,
            'message' => 'Accident creation data fetched successfully.',
            'data' => [
                'Childrens' => $children,
                'staffUsers' => $staffUsers,
                'rooms' => $rooms,
                'centerid' => $centerid,
                'roomid' => $roomid,
                'default_date' => Carbon::now()->format('Y-m-d'),
                'default_time' => Carbon::now()->format('H:i'),
            ]
        ]);
    }

    public function getChildDetails(Request $request)
    {
     $id = $request->childid;
        $child = Child::where('id', $id)->first();

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
        $centerid = $request->centerid;
        $roomid = $request->roomid;

        if (!$accidentId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error! Accident ID could not be fetched. Please try again.',
            ], 400);
        }

        $accident = AccidentsModel::with(['child', 'addedByUser'])->find($accidentId);

        if (!$accident) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Accident Details present.',
            ], 404);
        }

        $accidentIllness = AccidentIllnessModel::where('accident_id', $accidentId)->first();

        if ($accidentIllness) {
            $illnessData = $accidentIllness->toArray();
            $illnessData['illness_id'] = $illnessData['id'];
            unset($illnessData['id']);

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

        return response()->json([
            'status' => 'success',
            'accident' => $accident,
            'children' => $children,
            'roomid' => $roomid,
            'centerid' => $centerid,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $accidentId = $request->id;
        $studentId = $request->student_id;
        if (!$accidentId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required accident id'
            ], 400);
        }

        $accident = AccidentsModel::with(['child', 'addedByUser'])->find($accidentId);
        if (!$accident) {
            return response()->json([
                'success' => false,
                'message' => 'No Accident Details present.'
            ], 404);
        }

        // Attach illness data if exists
        $accidentIllness = AccidentIllnessModel::where('accident_id', $accidentId)->first();
        if ($accidentIllness) {
            $illnessData = $accidentIllness->toArray();
            $illnessData['illness_id'] = $illnessData['id'];
            unset($illnessData['id']);
            foreach ($illnessData as $key => $value) {
                $accident->$key = $value;
            }
        }

        // Generate PDF
        $htmlContent = view('Accidents.pdf', ['AccidentInfo' => $accident])->render();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($htmlContent)->setPaper('a4', 'portrait');
        $filename = 'student_report_' . ($studentId ?? $accident->childid ?? 'unknown') . '_' . now()->format('Ymd_His') . '.pdf';

        // Return PDF as download response
        return $pdf->download($filename);
    }
}
