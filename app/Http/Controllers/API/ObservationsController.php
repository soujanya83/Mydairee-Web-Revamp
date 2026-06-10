<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Center;
use App\Models\Usercenter;
use App\Models\Child;
use App\Models\Childparent;
use App\Models\DevMilestone;
use App\Models\DevMilestoneMain;
use App\Models\DevMilestoneSub;
use App\Models\EYLFActivity;
use App\Models\EYLFOutcome;
use App\Models\EYLFSubActivity;
use App\Models\MontessoriActivity;
use App\Models\MontessoriSubActivity;
use App\Models\MontessoriSubject;
use App\Models\Observation;
use App\Models\ObservationChild;
use App\Models\ObservationComment;
use App\Models\ObservationDevMilestoneSub;
use App\Models\ObservationEYLF;
use App\Models\ObservationLink;
use App\Models\ObservationMedia;
use App\Models\Reflection;
use App\Models\SnapshotMedia;
use App\Models\SnapshotChild;
use App\Models\Snapshot;
use App\Models\ObservationMontessori;
use App\Models\Permission;
use App\Models\ProgramPlanTemplateDetailsAdd;
use App\Models\Userprogressplan;
use App\Models\Room;
use App\Models\RoomStaff;
use App\Models\SeenObservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\ObservationAdded;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Mail;
use App\Models\ObservationStaff;

class ObservationsController extends Controller
{
    // List comments for an observation
    public function listComments($observationId)
    {
        $observation = Observation::find($observationId);
        if (!$observation) {
            return response()->json(['status' => false, 'message' => 'Observation not found.'], 404);
        }
        $comments = $observation->comments()->with('user:id,name')->orderBy('created_at', 'asc')->get();
        return response()->json(['status' => true, 'comments' => $comments]);
    }

    // Add a comment to an observation
    public function addComment(Request $request, $observationId)
    {
        $validator = Validator::make($request->all(), [
            'comments' => 'required|string|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }
        $observation = Observation::find($observationId);
        if (!$observation) {
            return response()->json(['status' => false, 'message' => 'Observation not found.'], 404);
        }
        $comment = ObservationComment::create([
            'observationId' => $observationId,
            'userId' => Auth::id(),
            'comments' => $request->comments,
        ]);
        return response()->json(['status' => true, 'message' => 'Comment added successfully.', 'comment' => $comment]);
    }

    // Delete a comment for an observation
    public function deleteComment($observationId, $commentId)
    {
        $observation = Observation::find($observationId);
        if (!$observation) {
            return response()->json(['status' => false, 'message' => 'Observation not found.'], 404);
        }

        $comment = ObservationComment::find($commentId);
        if (!$comment || intval($comment->observationId) !== intval($observationId)) {
            return response()->json(['status' => false, 'message' => 'Comment not found for this observation.'], 404);
        }

        $user = Auth::user();

        // Allow deletion if the user is the comment author or a staff/superadmin
        $isAuthor = $comment->userId == Auth::id();
        $isPrivileged = in_array($user->userType ?? '', ['Superadmin', 'Staff']);

        if (! $isAuthor && ! $isPrivileged) {
            return response()->json(['status' => false, 'message' => 'Unauthorized to delete this comment.'], 403);
        }

        try {
            $comment->delete();
            return response()->json(['status' => true, 'message' => 'Comment deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Delete comment failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to delete comment.'], 500);
        }
    }


        /**
     * Share  observation via email (API endpoint)
     */
    public function shareObservationApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_email' => 'required|email',
            'obsId' => 'required|integer',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = $request->recipient_email;
        $message = $request->message ?? 'Please check the report link below.';
        $obsId = $request->obsId;

        try {
            // Generate the shareable URL (web route)
            $url = route('sharelink', $obsId);

            // Send email
            Mail::send([], [], function ($mail) use ($email, $url, $message, $obsId) {
                $mail->from('mydairee47@gmail.com', 'Observation Report')
                    ->to($email)
                    ->subject('Observation Report - ' . $obsId)
                    ->html("
                        <p>{$message}</p>
                        <p>You can view the observation by clicking the link below:</p>
                        <p><a href='{$url}' target='_blank'>{$url}</a></p>
                    ");
            });

            return response()->json([
                'status' => true,
                'message' => 'Observation shared successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error occurred while sending the email: ' . $e->getMessage()
            ], 500);
        }
    }


    public function refine(Request $request)
    {
        $text = $request->input('text');
        // dd($text);

        if (!$text) {
            return response()->json([
                'status' => 'error',
                'message' => 'No text provided.'
            ]);
        }

        $refinedText = $this->callAIRefiner($text);
        // dd($refinedText);



        if (!$refinedText) {
            return response()->json([
                'status' => false,
                'refined_text' => '',
                'message' => 'Error! try again'
            ]);
        }

        return response()->json([
            'status' => true,
            'refined_text' => $refinedText
        ]);
    }
    
    
    public function print_snapshots($id)
    {
        $authId   = Auth::id();
        $centerid = session('user_center_id');

        if (Auth::user()->userType === "Superadmin" || Auth::user()->userType === "Centeradmin") {
            $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers   = Center::whereIn('id', $centerIds)->get();
            $snapshot = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('id', $id)
                ->first();
        } elseif (Auth::user()->userType === "Staff") {
            $centers = Center::where('id', $centerid)->get();
            $snapshot = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('createdBy', $authId)
                ->where('id', $id)
                ->first();
        } else { // Parent
            $centers = Center::where('id', $centerid)->get();
            $childIds   = Childparent::where('parentid', $authId)->pluck('childid');
            $snapshotIds = SnapshotChild::whereIn('childid', $childIds)
                ->pluck('snapshotid')
                ->unique()
                ->toArray();
            $snapshot = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->whereIn('id', $snapshotIds)
                ->where('id', $id)
                ->first();
        }

        if (!$snapshot) {
            return response()->json([
                'status' => false,
                'message' => 'Snapshot not found or not accessible.'
            ], 404);
        }

        // Rooms mapping
        $roomIds = !empty($snapshot->roomids) ? explode(',', $snapshot->roomids) : [];
        $rooms   = Room::whereIn('id', $roomIds)->get();
        $roomNames = $rooms->pluck('name')->implode(', ');

        // Permissions
        $permissions = Permission::where('userid', Auth::user()->userid)->first();

        // Render the Blade view to HTML
        $htmlContent = view('snapshots.printSnapshots', compact('snapshot', 'centers', 'permissions', 'rooms', 'roomNames') + ['isPdf' => true])->render();

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($htmlContent)->setPaper('a4', 'portrait');

        // Get first child's name (if available)
        $childName = 'UnknownChild';
        if ($snapshot->children && count($snapshot->children) > 0) {
            $child = $snapshot->children[0];
            if (isset($child->child)) {
                $childName = trim(preg_replace('/\s+/', '_', $child->child->name . '_' . ($child->child->lastname ?? '')));
            } elseif (isset($child->name)) {
                $childName = trim(preg_replace('/\s+/', '_', $child->name . '_' . ($child->lastname ?? '')));
            }
        }
        $date = $snapshot->created_at ? \Carbon\Carbon::parse($snapshot->created_at)->format('d-m-Y') : now()->format('d-m-Y');
        $filename = 'Snapshot_' . $childName . '_' . $date . '.pdf';
        $pdfPath = public_path('reports/' . $filename);

        // Make sure the directory exists
        if (!file_exists(public_path('reports'))) {
            mkdir(public_path('reports'), 0777, true);
        }

        // Save the PDF to public/reports/
        file_put_contents($pdfPath, $pdf->output());

        // Return the PDF as a download response
        if (file_exists($pdfPath)) {
            return response()->download($pdfPath, $filename, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate PDF file.'
            ], 500);
        }
    }

    private function callAIRefiner($text)
    {
        $apiKey = 'sk-d1febdfb38e3491391e5ca4ce911be5c'; // replace with your key
        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post('https://api.deepseek.com/chat/completions', [
            "model" => "deepseek-chat",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Refine the text and correct its grammar. Make it professional. Add relevant content if needed. Only return the modified text without any explanation."
                ],
                [
                    "role" => "user",
                    "content" => $text
                ]
            ]
        ]);

        $json = $response->json();

        return $json['choices'][0]['message']['content'] ?? $text;
    }





    //    public function index(Request $request)
    // {

    //         $validator = Validator::make($request->all(), [
    //         'center_id' => 'required|integer|min:1'
    //     ], [
    //         'center_id.required' => 'Center ID is required',
    //         'center_id.integer'  => 'Center ID must be an integer.',
    //         'center_id.min'      => 'Center ID must be greater than 0.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Validation failed.',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     $validated = $validator->validated();

    //     $centerid = $validated['center_id'];
    //        $authId   = Auth::user()->id;



    //     if (Auth::user()->userType == "Superadmin") {
    //         $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
    //         $centers   = Center::whereIn('id', $centerIds)->get();
    //     } else {
    //         $centers = Center::where('id', $centerid)->get();
    //     }

    //     if (Auth::user()->userType == "Superadmin") {
    //         $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
    //             ->where('centerid', $centerid)
    //             ->orderBy('id', 'desc')
    //             ->get();
    //     } elseif (Auth::user()->userType == "Staff") {
    //         $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
    //             ->where('userId', $authId)
    //             ->orderBy('id', 'desc')
    //             ->get();
    //     } else {
    //         $childIds = Childparent::where('parentid', $authId)->pluck('childid');
    //         $observationIds = ObservationChild::whereIn('childId', $childIds)
    //             ->pluck('observationId')
    //             ->unique()
    //             ->toArray();

    //         $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
    //             ->whereIn('id', $observationIds)
    //             ->where('status','Published')
    //             ->orderBy('id', 'desc')
    //             ->get();
    //     }

    //     return response()->json([
    //         'success'      => true,
    //         'observations' => $observations,
    //         'centers'      => $centers,
    //     ]);
    // }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'center_id' => 'required|integer|min:1',
            'per_page'  => 'nullable|integer|min:1' // optional pagination size
        ], [
            'center_id.required' => 'Center ID is required',
            'center_id.integer'  => 'Center ID must be an integer.',
            'center_id.min'      => 'Center ID must be greater than 0.',
            'per_page.integer'   => 'Per page must be an integer.',
            'per_page.min'       => 'Per page must be greater than 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $centerid  = $validated['center_id'];
        $perPage   = $validated['per_page'] ?? 10; // default to 10 items per page
        $authId    = Auth::user()->id;

        // Fetch centers based on role
        if (Auth::user()->userType == "Superadmin") {
            $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers   = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        // Fetch observations based on role
        if (Auth::user()->userType == "Superadmin") {
            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        } elseif (Auth::user()->userType == "Staff") {
            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('userId', $authId)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        } else {
            $childIds = Childparent::where('parentid', $authId)->pluck('childid');
            $observationIds = ObservationChild::whereIn('childId', $childIds)
                ->pluck('observationId')
                ->unique()
                ->toArray();

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->whereIn('id', $observationIds)
                ->where('status', 'Published')
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        }

        return response()->json([
            'success'      => true,
            'observations' => $observations,
            'centers'      => $centers,
        ]);
    }

    public function mernindex(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'center_id' => 'required|integer|min:1',
            'per_page'  => 'nullable|integer|min:1' // optional pagination size
        ], [
            'center_id.required' => 'Center ID is required',
            'center_id.integer'  => 'Center ID must be an integer.',
            'center_id.min'      => 'Center ID must be greater than 0.',
            'per_page.integer'   => 'Per page must be an integer.',
            'per_page.min'       => 'Per page must be greater than 0.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $centerid  = $validated['center_id'];
        $perPage   = $validated['per_page'] ?? 10; // default to 10 items per page
        $user      = Auth::user();
        $authId    = $user->id;
        $selectedChildId = null;
        $selectedChildSource = null;

        // Fetch centers based on role
        if ($user->userType == "Superadmin") {
            $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers   = Center::whereIn('id', $centerIds)->get();
        } else {
            $centers = Center::where('id', $centerid)->get();
        }

        // Fetch observations based on role
        if ($user->userType == "Superadmin") {
            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('centerid', $centerid)
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        } elseif ($user->userType == "Staff") {

            $taggedObservationIds = ObservationStaff::where('userid', $authId)
                ->pluck('observationId')
                ->toArray();

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where(function ($query) use ($authId, $taggedObservationIds) {
                    $query->where('userId', $authId)
                        ->orWhereIn('id', $taggedObservationIds);
                })
                ->orderBy('id', 'desc')
                ->paginate($perPage);
        } else {
            $childIds = Childparent::where('parentid', $authId)->pluck('childid')->values();
            $requestedChildId = $request->input('child_id', $request->input('childid'));
            $savedChildId = User::where('userid', $authId)->value('selectedchildreanid');

            if (!empty($requestedChildId) && trim((string) $requestedChildId) !== '') {
                $requestedChildId = (int) $requestedChildId;

                if (!$childIds->contains($requestedChildId)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'This child does not belong to this parent',
                    ], 403);
                }

                $selectedChildId = $requestedChildId;
                $selectedChildSource = 'request';
            }

            if (!$selectedChildId && !empty($savedChildId) && $childIds->contains((int) $savedChildId)) {
                $selectedChildId = (int) $savedChildId;
                $selectedChildSource = 'saved';
            }

            if (!$selectedChildId && $childIds->isNotEmpty()) {
                $selectedChildId = (int) $childIds->first();
                $selectedChildSource = 'fallback';
            }

            if (!$selectedChildId) {
                return response()->json([
                    'status' => false,
                    'message' => 'No affiliated child found for this parent.',
                    'observations' => [],
                ]);
            }

            $observationIds = ObservationChild::whereIn('childId', [$selectedChildId])
                ->pluck('observationId')
                ->unique()
                ->toArray();

            if (empty($observationIds)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No observations found for the selected child.',
                    'observations' => [],
                ]);
            }

            $observations = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->whereIn('id', $observationIds)
                ->where('status', 'Published')
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            if ($observations->total() === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'No observations found for the selected child.',
                    'observations' => [],
                ]);
            }
        }

        $selectionMeta = $user->userType === 'Parent'
            ? [
                'selectedChildId' => $selectedChildId,
                'selectedChildSource' => $selectedChildSource,
            ]
            : [];

        return response()->json([
            'success'      => true,
           
            //'centers'      => $centers,
             'observations' => $observations,   
        ] + $selectionMeta);
    }

    public function mernapplyFilters(Request $request)
    {
        //    dd($request->all());
        try {

            $validator = Validator::make($request->all(), [
                'center_id' => 'required|integer|min:1',
                'per_page'  => 'nullable|integer|min:1',
                'page'      => 'nullable|integer|min:1',
                'child_search' => 'nullable|string',
                'created_by_search' => 'nullable|string',
                'search_by_title' => 'nullable|string',
            ], [
                'center_id.required' => 'Center ID is required.',
                'center_id.integer'  => 'Center ID must be an integer.',
                'center_id.min'      => 'Center ID must be greater than 0.',
                'per_page.integer'   => 'Per page must be an integer.',
                'per_page.min'       => 'Per page must be greater than 0.',
                'page.integer'       => 'Page must be an integer.',
                'page.min'           => 'Page must be greater than 0.',
            ]);

            // If validation fails, return response
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Get validated center_id
            $validated = $validator->validated();
            $centerid = $validated['center_id'];
            $perPage = $validated['per_page'] ?? max((int) $request->input('per_page', 10), 1);
            $page = $validated['page'] ?? max((int) $request->input('page', 1), 1);
            $selectedChildId = null;
            $selectedChildSource = null;
            $user = Auth::user();
            $parentChildIds = collect();


            $query = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('centerid', $centerid);

            if ($user->userType === 'Parent') {
                $parentChildIds = Childparent::where('parentid', $user->id)->pluck('childid')->values();
                $requestedChildId = $request->input('child_id', $request->input('childid'));
                $savedChildId = User::where('userid', $user->id)->value('selectedchildreanid');

                if (!empty($requestedChildId) && trim((string) $requestedChildId) !== '') {
                    $requestedChildId = (int) $requestedChildId;

                    if (!$parentChildIds->contains($requestedChildId)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'This child does not belong to this parent',
                        ], 403);
                    }

                    $selectedChildId = $requestedChildId;
                    $selectedChildSource = 'request';
                }

                if (!$selectedChildId && !empty($savedChildId) && $parentChildIds->contains((int) $savedChildId)) {
                    $selectedChildId = (int) $savedChildId;
                    $selectedChildSource = 'saved';
                }

                if (!$selectedChildId && $parentChildIds->isNotEmpty()) {
                    $selectedChildId = (int) $parentChildIds->first();
                    $selectedChildSource = 'fallback';
                }

                if (!$selectedChildId) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No affiliated child found for this parent.',
                        'observations' => [],
                        'selectedChildId' => null,
                        'selectedChildSource' => null,
                    ]);
                }

                $observationIds = ObservationChild::where('childId', $selectedChildId)
                    ->pluck('observationId')
                    ->unique()
                    ->toArray();

                if (empty($observationIds)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No observations found for the selected child.',
                        'observations' => [],
                        'selectedChildId' => $selectedChildId,
                        'selectedChildSource' => $selectedChildSource,
                    ]);
                }

                $query->whereIn('id', $observationIds);
            }
            // Status filter - parents see Published only
            if ($user->userType === 'Parent') {
                $query->where('status', 'Published');
            } else {
                if ($request->has('observations') && !empty($request->observations)) {
                    $statusFilters = $request->observations;
                    if (!in_array('All', $statusFilters)) {
                        $query->whereIn('status', $statusFilters);
                    }
                }
            }

            // Date filter
            if ($request->has('added') && !empty($request->added)) {
                $dateFilters = $request->added;
                foreach ($dateFilters as $dateFilter) {
                    switch ($dateFilter) {
                        case 'Today':
                            $query->whereDate('created_at', Carbon::today());
                            break;
                        case 'This Week':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfWeek(),
                                Carbon::now()->endOfWeek()
                            ]);
                            break;
                        case 'This Month':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfMonth(),
                                Carbon::now()->endOfMonth()
                            ]);
                            break;
                        case 'Custom':
                            if ($request->fromDate && $request->toDate) {
                                $fromDate = Carbon::parse($request->fromDate)->startOfDay();
                                $toDate = Carbon::parse($request->toDate)->endOfDay();
                                $query->whereBetween('created_at', [$fromDate, $toDate]);
                            }
                            break;
                    }
                }
            }

            // Child filter
            if ($request->has('childs') && !empty($request->childs)) {
                $childIds = $request->childs;

                // Get observation IDs that have the selected children
                $observationIds = ObservationChild::whereIn('childId', $childIds)
                    ->pluck('observationId')
                    ->unique()
                    ->toArray();
                if (!empty($observationIds)) {
                    $query->whereIn('id', $observationIds);
                } else {
                    // If no observations found for selected children, return empty result
                    $query->where('id', 0);
                }
            }

            // Author filter
            if ($request->has('authors') && !empty($request->authors)) {
                $authorFilters = $request->authors;

                // 🚫 Skip filter if "Any" is selected
                if (!in_array('Any', $authorFilters)) {

                    // ✅ If "Me" is selected
                    if (in_array('Me', $authorFilters)) {
                        $query->where('userId', Auth::id());
                    }
                    // ✅ If specific staff IDs are selected (as string IDs)
                    else {
                        $query->whereIn('userId', $authorFilters);
                    }
                }
            }

            // Child name search filter
            if ($request->filled('child_search')) {
                $childSearch = trim((string) $request->input('child_search'));

                if ($childSearch !== '') {
                    $query->whereHas('child.child', function ($childQuery) use ($childSearch) {
                        $childQuery->where('name', 'like', '%' . $childSearch . '%')
                            ->orWhere('lastname', 'like', '%' . $childSearch . '%')
                            ->orWhereRaw("CONCAT(COALESCE(name, ''), ' ', COALESCE(lastname, '')) LIKE ?", ['%' . $childSearch . '%']);
                    });
                }
            }

            // Observation title search filter
            if ($request->filled('search_by_title')) {
                $titleSearch = trim((string) $request->input('search_by_title'));

                if ($titleSearch !== '') {
                    $query->where(function ($titleQuery) use ($titleSearch) {
                        $titleQuery->where('title', 'like', '%' . $titleSearch . '%')
                                ->orWhere('obestitle', 'like', '%' . $titleSearch . '%');
                    });
                }
            }

            // Creator name search filter
            if ($request->filled('created_by_search')) {
                $creatorSearch = trim((string) $request->input('created_by_search'));

                if ($creatorSearch !== '') {
                    $query->whereHas('user', function ($creatorQuery) use ($creatorSearch) {
                        $creatorQuery->where('name', 'like', '%' . $creatorSearch . '%');
                    });
                }
            }
            if ($user->userType === 'Staff') {
                                $authId = Auth::id();

                // Include observations where the staff is the creator OR explicitly tagged via ObservationStaff
                $staffObservationIds = \App\Models\ObservationStaff::where('userid', $authId)
                    ->pluck('observationId')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $query->where(function ($q) use ($authId, $staffObservationIds) {
                    $q->where('userId', $authId);

                    if (!empty($staffObservationIds)) {
                        $q->orWhereIn('id', $staffObservationIds);
                    }

                    // Also support legacy comma-separated `tagged_staff` field on observations
                    $q->orWhereRaw('FIND_IN_SET(?, tagged_staff)', [$authId]);
                });
            }
            // Apply user-specific filters based on role
            // $user = Auth::user();
            // if ($user->userType === 'Parent') {
            //     // Parents can only see observations of their children
            //     $userChildIds = $user->children()->pluck('id')->toArray();
            //     if (!empty($userChildIds)) {
            //         $parentObservationIds = ObservationChild::whereIn('childId', $userChildIds)
            //             ->pluck('observationId')
            //             ->unique()
            //             ->toArray();
            //         if (!empty($parentObservationIds)) {
            //             $query->whereIn('id', $parentObservationIds);
            //         } else {
            //             $query->where('id', 0);
            //         }
            //     }
            // } elseif ($user->userType === 'Teacher') {
            //     // Teachers can see observations of children in their classes
            //     // Adjust this logic based on your relationship structure
            //     $teacherChildIds = $user->teacherChildren()->pluck('id')->toArray();
            //     if (!empty($teacherChildIds)) {
            //         $teacherObservationIds = ObservationChild::whereIn('childId', $teacherChildIds)
            //             ->pluck('observationId')
            //             ->unique()
            //             ->toArray();
            //         if (!empty($teacherObservationIds)) {
            //             $query->whereIn('id', $teacherObservationIds);
            //         } else {
            //             $query->where('id', 0);
            //         }
            //     }
            // }

            // Order by latest first
            $query->orderBy('created_at', 'desc');

            // Get the filtered observations with pagination
            $observations = $query->paginate($perPage, ['*'], 'page', $page);

            // Format the observations for response
            $formattedObservations = $observations->getCollection()->map(function ($observation) {
                return [
                    'id' => $observation->id,
                    'title' => html_entity_decode($observation->title ?? ''),
                    'obestitle' => $observation->obestitle ?? '',
                    'status' => $observation->status,
                    'media' => $observation->media->values(),
                    'mediaType' => $observation->observationsMediaType,
                    'userName' => $observation->user->name ?? 'Unknown',
                    'date_added' => Carbon::parse($observation->created_at)->format('d.m.Y'),
                    'created_at' => $observation->created_at->format('Y-m-d H:i:s'),
                    'seen' => $observation->seen->map(function ($seen) {
                        if ($seen->user && $seen->user->userType === 'Parent') {
                            return [
                                'name' => $seen->user->name,
                                'imageUrl' => $seen->user->imageUrl,
                                'gender' => $seen->user->gender,
                            ];
                        }
                        return null;
                    })->filter(),
                ];
            });

            $observations->setCollection($formattedObservations);

            return response()->json([
                'status' => 'success',
                'message' => 'Filters applied successfully',
                'observations' => $observations,
                'userRole' => Auth::user()->userType,
                'count' => $observations->total(),
                'pagination' => [
                    'current_page' => $observations->currentPage(),
                    'per_page' => $observations->perPage(),
                    'total' => $observations->total(),
                    'last_page' => $observations->lastPage(),
                    'from' => $observations->firstItem(),
                    'to' => $observations->lastItem(),
                ],
                'filters' => [
                    'child_search' => $request->input('child_search', ''),
                    'created_by_search' => $request->input('created_by_search', ''),
                    'search_by_title' => $request->input('search_by_title', ''),
                ],
                'selectedChildId' => $user->userType === 'Parent' ? $selectedChildId : null,
                'selectedChildSource' => $user->userType === 'Parent' ? $selectedChildSource : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function applyFilters(Request $request)
    {
        //    dd($request->all());
        try {

            $validator = Validator::make($request->all(), [
                'center_id' => 'required|integer|min:1',
                'per_page'  => 'nullable|integer|min:1',
                'page'      => 'nullable|integer|min:1',
                'child_search' => 'nullable|string',
                'created_by_search' => 'nullable|string',
            ], [
                'center_id.required' => 'Center ID is required.',
                'center_id.integer'  => 'Center ID must be an integer.',
                'center_id.min'      => 'Center ID must be greater than 0.',
                'per_page.integer'   => 'Per page must be an integer.',
                'per_page.min'       => 'Per page must be greater than 0.',
                'page.integer'       => 'Page must be an integer.',
                'page.min'           => 'Page must be greater than 0.',
            ]);

            // If validation fails, return response
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            // Get validated center_id
            $validated = $validator->validated();
            $centerid = $validated['center_id'];
            $perPage = $validated['per_page'] ?? max((int) $request->input('per_page', 10), 1);
            $page = $validated['page'] ?? max((int) $request->input('page', 1), 1);


            $query = Observation::with(['user', 'child', 'media', 'Seen.user'])
                ->where('centerid', $centerid);
            // Status filter
            if ($request->has('observations') && !empty($request->observations)) {
                $statusFilters = $request->observations;
                if (!in_array('All', $statusFilters)) {
                    $query->whereIn('status', $statusFilters);
                }
            }

            // Date filter
            if ($request->has('added') && !empty($request->added)) {
                $dateFilters = $request->added;
                foreach ($dateFilters as $dateFilter) {
                    switch ($dateFilter) {
                        case 'Today':
                            $query->whereDate('created_at', Carbon::today());
                            break;
                        case 'This Week':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfWeek(),
                                Carbon::now()->endOfWeek()
                            ]);
                            break;
                        case 'This Month':
                            $query->whereBetween('created_at', [
                                Carbon::now()->startOfMonth(),
                                Carbon::now()->endOfMonth()
                            ]);
                            break;
                        case 'Custom':
                            if ($request->fromDate && $request->toDate) {
                                $fromDate = Carbon::parse($request->fromDate)->startOfDay();
                                $toDate = Carbon::parse($request->toDate)->endOfDay();
                                $query->whereBetween('created_at', [$fromDate, $toDate]);
                            }
                            break;
                    }
                }
            }

            // Child filter
            if ($request->has('childs') && !empty($request->childs)) {
                $childIds = $request->childs;

                // Get observation IDs that have the selected children
                $observationIds = ObservationChild::whereIn('childId', $childIds)
                    ->pluck('observationId')
                    ->unique()
                    ->toArray();
                if (!empty($observationIds)) {
                    $query->whereIn('id', $observationIds);
                } else {
                    // If no observations found for selected children, return empty result
                    $query->where('id', 0);
                }
            }

            // Author filter
            if ($request->has('authors') && !empty($request->authors)) {
                $authorFilters = $request->authors;

                // 🚫 Skip filter if "Any" is selected
                if (!in_array('Any', $authorFilters)) {

                    // ✅ If "Me" is selected
                    if (in_array('Me', $authorFilters)) {
                        $query->where('userId', Auth::id());
                    }
                    // ✅ If specific staff IDs are selected (as string IDs)
                    else {
                        $query->whereIn('userId', $authorFilters);
                    }
                }
            }

            // Child name search filter
            if ($request->filled('child_search')) {
                $childSearch = trim((string) $request->input('child_search'));

                if ($childSearch !== '') {
                    $query->whereHas('child.child', function ($childQuery) use ($childSearch) {
                        $childQuery->where('name', 'like', '%' . $childSearch . '%')
                            ->orWhere('lastname', 'like', '%' . $childSearch . '%')
                            ->orWhereRaw("CONCAT(COALESCE(name, ''), ' ', COALESCE(lastname, '')) LIKE ?", ['%' . $childSearch . '%']);
                    });
                }
            }

            // Creator name search filter
            if ($request->filled('created_by_search')) {
                $creatorSearch = trim((string) $request->input('created_by_search'));

                if ($creatorSearch !== '') {
                    $query->whereHas('user', function ($creatorQuery) use ($creatorSearch) {
                        $creatorQuery->where('name', 'like', '%' . $creatorSearch . '%');
                    });
                }
            }


            $user = Auth::user();
            if ($user->userType === 'Staff') {
                $query->where('userId', Auth::id());
            }
            // Apply user-specific filters based on role
            // $user = Auth::user();
            // if ($user->userType === 'Parent') {
            //     // Parents can only see observations of their children
            //     $userChildIds = $user->children()->pluck('id')->toArray();
            //     if (!empty($userChildIds)) {
            //         $parentObservationIds = ObservationChild::whereIn('childId', $userChildIds)
            //             ->pluck('observationId')
            //             ->unique()
            //             ->toArray();
            //         if (!empty($parentObservationIds)) {
            //             $query->whereIn('id', $parentObservationIds);
            //         } else {
            //             $query->where('id', 0);
            //         }
            //     }
            // } elseif ($user->userType === 'Teacher') {
            //     // Teachers can see observations of children in their classes
            //     // Adjust this logic based on your relationship structure
            //     $teacherChildIds = $user->teacherChildren()->pluck('id')->toArray();
            //     if (!empty($teacherChildIds)) {
            //         $teacherObservationIds = ObservationChild::whereIn('childId', $teacherChildIds)
            //             ->pluck('observationId')
            //             ->unique()
            //             ->toArray();
            //         if (!empty($teacherObservationIds)) {
            //             $query->whereIn('id', $teacherObservationIds);
            //         } else {
            //             $query->where('id', 0);
            //         }
            //     }
            // }

            // Order by latest first
            $query->orderBy('created_at', 'desc');

            // Get the filtered observations with pagination
            $observations = $query->paginate($perPage, ['*'], 'page', $page);

            // Format the observations for response
            $formattedObservations = $observations->getCollection()->map(function ($observation) {
                return [
                    'id' => $observation->id,
                    'title' => html_entity_decode($observation->title ?? ''),
                    'obestitle' => $observation->obestitle ?? '',
                    'status' => $observation->status,
                    'media' => $observation->media->values(),
                    'mediaType' => $observation->observationsMediaType,
                    'userName' => $observation->user->name ?? 'Unknown',
                    'date_added' => Carbon::parse($observation->created_at)->format('d.m.Y'),
                    'created_at' => $observation->created_at->format('Y-m-d H:i:s'),
                    'seen' => $observation->seen->map(function ($seen) {
                        if ($seen->user && $seen->user->userType === 'Parent') {
                            return [
                                'name' => $seen->user->name,
                                'imageUrl' => $seen->user->imageUrl,
                                'gender' => $seen->user->gender,
                            ];
                        }
                        return null;
                    })->filter(),
                ];
            });

            $observations->setCollection($formattedObservations);

            return response()->json([
                'status' => 'success',
                'message' => 'Filters applied successfully',
                'observations' => $observations,
                'userRole' => Auth::user()->userType,
                'count' => $observations->total(),
                'pagination' => [
                    'current_page' => $observations->currentPage(),
                    'per_page' => $observations->perPage(),
                    'total' => $observations->total(),
                    'last_page' => $observations->lastPage(),
                    'from' => $observations->firstItem(),
                    'to' => $observations->lastItem(),
                ],
                'filters' => [
                    'child_search' => $request->input('child_search', ''),
                    'created_by_search' => $request->input('created_by_search', ''),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get children list for filter modal
     */
    public function getChildren(Request $request)
    {
        try {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'center_id' => 'nullable|integer',
                'roomid'    => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Access validated data safely
            $validated = $validator->validated();
            $centerid = $validated['center_id'] ?? null;
            $roomid   = $validated['roomid'] ?? null;

            $children = collect();
            $message  = '';

            // Fetch children by center
            if ($centerid) {
                    if ($user->userType === 'Superadmin' || $user->userType === 'Centeradmin') {
                        $children = $this->getChildrenForSuperadmin($centerid);
                } elseif ($user->userType === 'Staff') {
                    $children = $this->getChildrenForStaff($centerid);
                } else {
                    $children = $this->getChildrenForParent($centerid);
                }
                $message = "Children retrieved from center successfully";
            }

            // Fetch children by room (overrides center filter if both are present)
            if ($roomid) {
                $children = child::whereIn('room', $roomid)->get();
                $message = "Children retrieved from room successfully";
            }

            return response()->json([
                'children' => $children,
                'status'   => true,
                'message'  => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while applying filters',
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    private function getChildrenForSuperadmin($centerid)
    {
        $authId = Auth::user()->id;
        // $centerid = center_id');


        // Get all room IDs for the center
        $roomIds = Room::where('centerid', $centerid)->pluck('id');

        // Get all children in those rooms
        $children = Child::whereIn('room', $roomIds)->get();

        return $children;
    }


    private function getChildrenForStaff()
    {
        $authId = Auth::user()->id;
        $centerid = Session('user_center_id');

        // Get room IDs from RoomStaff where staff is assigned
        $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');

        // Get room IDs where user is the owner (userId matches)
        $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');

        // Merge both collections and remove duplicates
        $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique();

        // Get all children in those rooms
        $children = Child::whereIn('room', $allRoomIds)->get();

        return $children;
    }

    private function getChildrenForParent($centerid)
    {
        $authId = Auth::user()->id;
        // $centerid = Sessicenter_id');

        $childids = Childparent::where('parentid', $authId)->pluck('childid');

        $children = Child::whereIn('id', $childids)->get();

        return $children;
    }

    private function getStaffForSuperadmin($center_id)
    {
        $authId = Auth::user()->id;


        // Get all room IDs for the center
        $usersid = Usercenter::where('centerid', $center_id)->pluck('userid')->toArray();

        // Exclude current user and Superadmins
        $staff = User::whereIn('id', $usersid)
            ->where('userType', 'Staff')
            ->get();

        return $staff;
    }


    public function getStaff(Request $request)
    {
        try {
            $user = Auth::user();
            $children = collect();
            $staff = collect();
            $validator = Validator::make($request->all(), [
                'center_id' => 'nullable|integer',
                'roomid' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Access validated data
            $validated = $validator->validated();
            $centerid = $validated['center_id'] ?? null;
            $roomid = $validated['roomid'] ?? null;

            if ($centerid) {

                if ($user->userType === 'Superadmin' || $user->userType === 'Centeradmin' || $user->userType === 'Staff') {
                    $staff = $this->getStaffForSuperadmin($centerid);
                }
            }

            if ($roomid) {
                $roomstaff = RoomStaff::whereIn('roomid', $roomid)->pluck('staffid');
                $staff = User::whereIn('userid', $roomstaff)->get();
            }



            // elseif($user->userType === 'Staff'){
            // $children = $this->getChildrenForStaff();
            // }else{
            // $children = $this->getChildrenForParent();
            // }

            return response()->json([
                'staff' => $staff,
                'status' => 'true',
                'message' => 'Staff retrived successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    private function getroomsforSuperadmin($centerid)
    {
        $authId = Auth::user()->userid;

        $rooms = Room::where('centerid', $centerid)->get();
        return $rooms;
    }

    private function getroomsforStaff($centerid)
    {
        $authId = Auth::user()->id;

        $roomIdsFromStaff = RoomStaff::where('staffid', $authId)->pluck('roomid');
        $roomIdsFromOwner = Room::where('userId', $authId)->pluck('id');
        $allRoomIds = $roomIdsFromStaff->merge($roomIdsFromOwner)->unique()->filter()
        ->values();

        // Filter rooms by centerid as well
        $rooms = Room::whereIn('id', $allRoomIds)
            ->where('centerid', $centerid)
            ->get();
        return $rooms;
    }



    public function getrooms(Request $request)
    {
        try {
            $user = Auth::user();
            $rooms = collect();

            $validator = Validator::make($request->all(), [
                'center_id' => 'required|integer|exists:centers,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $centerid = $validator->validated()['center_id'];

            if ($user->userType === 'Superadmin' || $user->userType === 'Centeradmin') {
                $rooms = $this->getroomsforSuperadmin($centerid);
            } else {
                $rooms = $this->getroomsforStaff($centerid);
            }

            return response()->json([
                'rooms' => $rooms,
                'status' => true,
                'message' => 'Rooms retrived successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Filter error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while applying filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function storepage(Request $request)
    {
        $authId = Auth::user()->id;
        $centerid = $request->center_id;
        $id = $request->id;
        $activeTab = 'observation';
        $activesubTab = 'MONTESSORI';

        if (Auth::user()->userType == "Superadmin") {
            $center = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();
            $centers = Center::whereIn('id', $center)->get();
            // For Superadmin, fetch all staff for the center
            $staff = $centerid ? $this->getStaffForSuperadmin($centerid) : collect();
        } else {
            $centers = Center::where('id', $centerid)->get();
            // For Staff, fetch staff for the center (could be just self or all staff in center)
            $staff = $centerid ? $this->getStaffForSuperadmin($centerid) : collect();
        }
        // dd($id);
        $observation = null;
        if ($id) {
            $observation = Observation::with(['media', 'child.child', 'montessoriLinks', 'eylfLinks', 'devMilestoneSubs', 'links'])->find($id);
            //   $observation = Observation::with([
            //     'media',
            //     'child.child',
            //     'montessoriLinks',
            //     'eylfLinks',
            //     'devMilestoneSubs',
            //     'links.linkedObservation:id,title,status'
            // ])->find($id);

        }

        $childrens = $observation
            ? $observation->child->pluck('child')->filter()
            : collect();


        $rooms = collect();

        if ($observation && $observation->room) {
            $roomIds = explode(',', $observation->room); // Convert comma-separated string to array
            $rooms = Room::whereIn('id', $roomIds)->get();
        }

        $subjects = MontessoriSubject::with(['activities.subActivities'])->get();
        $outcomes = EYLFOutcome::with('activities.subActivities')->get();
        $milestones = DevMilestone::with('mains.subs')->get();


        return response()->json([
            'status' => true,
            'message' => $id ? 'Observation loaded successfully.' : 'Create new observation.',
            'data' => [
                'centers'     => $centers,
                'observation' => $observation,
                'childrens'   => $childrens->values(),
                'rooms'       => $rooms,
                'activeTab'   => $activeTab,
                'activesubTab' => $activesubTab,
                'subjects'    => $subjects,
                'outcomes'    => $outcomes,
                'milestones'  => $milestones,
                'staff'       => $staff,
            ]
        ]);
    }

    public function storeMontessoriData(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'observationId' => 'required|exists:observation,id',
            'subactivities' => 'required|array|min:1',
            'subactivities.*.idSubActivity' => 'required|integer',
            'subactivities.*.assesment' => 'required|in:Introduced,Working,Completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            // Delete old entries for this observation
            ObservationMontessori::where('observationId', $validated['observationId'])->delete();

            // Insert new subactivities
            foreach ($validated['subactivities'] as $entry) {
                ObservationMontessori::create([
                    'observationId' => $validated['observationId'],
                    'idSubActivity' => $entry['idSubActivity'],
                    'assesment'     => $entry['assesment'],
                    'idExtra'       => 0, // optional/default field
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Montessori data saved successfully.',
                'id' => $validated['observationId'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function storeEylfData(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'observationId' => 'required|exists:observation,id',
            'subactivityIds' => 'required|array|min:1',
            'subactivityIds.*' => 'integer|exists:eylfsubactivity,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        try {
            // Delete previous EYLF links for this observation
            ObservationEYLF::where('observationId', $validated['observationId'])->delete();

            // Insert new subactivities
            foreach ($validated['subactivityIds'] as $subId) {
                $activityId = EYLFSubActivity::find($subId)->activityid ?? null;

                if ($activityId) {
                    ObservationEYLF::create([
                        'observationId'      => $validated['observationId'],
                        'eylfSubactivityId'  => $subId,
                        'eylfActivityId'     => $activityId,
                    ]);
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'EYLF data saved successfully.',
                'id'      => $validated['observationId'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    public function storeDevMilestone(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'observationId' => 'required|exists:observation,id',
            'selections' => 'required|array|min:1',
            'selections.*.idSub' => 'required|integer',
            'selections.*.assessment' => 'required|in:Introduced,Working towards,Achieved',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Delete old milestone records
        ObservationDevMilestoneSub::where('observationId', $validated['observationId'])->delete();

        // Insert new milestones
        foreach ($validated['selections'] as $sel) {
            ObservationDevMilestoneSub::create([
                'observationId'   => $validated['observationId'],
                'devMilestoneId'  => $sel['idSub'],
                'assessment'      => $sel['assessment']
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Development milestones saved successfully.',
            'id' => $validated['observationId']
        ]);
    }


    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'observationId' => 'required|exists:observation,id',
            'status'        => 'required|in:Published,Draft',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $observation = Observation::find($validated['observationId']);
        $observation->status = $validated['status'];
        $observation->save();

        // Only send notification if status is set to Published (case-insensitive)
        // if (strtolower($observation->status) === 'published') {
        //     // Get all children attached to this observation
        //     $selectedChildren = \App\Models\ObservationChild::where('observationId', $observation->id)->pluck('childId')->toArray();
        //     $authId = $observation->userId;
        //         // Push notification (deep linking)
        //         $service = app(\App\Services\Firebase\FirebaseNotificationService::class);

        //         // Collect tagged staff IDs from ObservationStaff table (userid) and legacy tagged_staff column
        //         $staffFromTable = \App\Models\ObservationStaff::where('observationId', $observation->id)
        //             ->pluck('userid')
        //             ->filter()
        //             ->unique()
        //             ->values()
        //             ->all();

        //         $staffFromColumn = [];
        //         if (!empty($observation->tagged_staff)) {
        //             $staffFromColumn = array_values(array_filter(array_map('trim', explode(',', $observation->tagged_staff))));
        //         }

        //         $combinedStaff = array_values(array_unique(array_merge($staffFromTable, $staffFromColumn)));

        //         \App\Http\Controllers\API\DeviceController::notifyParentsModuleCreated(
        //             $selectedChildren,
        //             'observation',
        //             $observation->id,
        //             $authId,
        //             $service,
        //             null,
        //             [],
        //             $combinedStaff
        //         );
        //     // Laravel notification (database/email)
        //     foreach ($selectedChildren as $childId) {
        //         $parentRelations = \App\Models\Childparent::where('childid', $childId)->get();
        //         foreach ($parentRelations as $relation) {
        //             $parentUser = \App\Models\User::find($relation->parentid);
        //             if ($parentUser) {
        //                 $parentUser->notify(new \App\Notifications\ObservationAdded($observation));
        //             }
        //         }
        //     }
        // }


        return response()->json([
            'status'  => true,
            'message' => 'Observation status updated successfully.',
            'id'      => $observation->id,
        ]);
    }


    public function view($id)
    {
        // Properly validate the route parameter
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:observation,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Use validated ID
        $validated = $validator->validated();
        $id = $validated['id'];

        // Fetch observation with relationships
        $observation = Observation::with([
            'media',
            'child.child',
            'montessoriLinks.subActivity.activity.subject',
            'eylfLinks.subActivity.activity.outcome',
            'devMilestoneSubs.devMilestone.main',
            'devMilestoneSubs.devMilestone.milestone'
        ])->findOrFail($id);

        // Get tagged staff IDs
        $staffIds = array_filter(explode(',', $observation->tagged_staff));

        // Fetch staff details
        $taggedStaff = User::whereIn('id', $staffIds)
            ->select('id', 'name', 'imageUrl')
            ->get()
            ->map(function ($staff) {
                return [
                    'id' => $staff->id,
                    'name' => $staff->name,
                    'imageUrl' => $staff->imageUrl
                        ? asset($staff->imageUrl)
                        : null,
                ];
            });

        // Append to response
        $observation->tagged_staff_details = $taggedStaff;

        return response()->json([
            'status'  => true,
            'message' => 'Observation data fetched successfully.',
            'data'    => $observation,
        ]);
    }


    public function linkobservationdata(Request $request)
    {
        // Validate center_id
        $validator = Validator::make($request->all(), [
            'center_id' => 'required|integer|exists:centers,id',  // Ensure center_id exists in your centers table
        ]);

        // dd('here');

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $authId = Auth::user()->id;
        $centerid = $validator->validated()['center_id'];
        $search = $request->query('search');
        $obsId = $request->query('obsId'); // Optional current observation ID

        // Get observations matching search and center
        $observations = Observation::with(['media', 'child.child', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('obestitle', 'like', "%{$search}%");
            })
            ->where('centerid', $centerid)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd( $observations);

        // Get linked observation IDs if obsId is passed
        $linkedIds = [];
        if (!empty($obsId)) {
            $linkedIds = ObservationLink::where('observationId', $obsId)
                ->where('linktype', 'OBSERVATION')
                ->get();
        }

        return response()->json([
            'status'       => true,
            'observations' => $observations,
            'linked_ids'   => $linkedIds
        ]);
    }


    public function storelinkobservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obsId' => 'required|exists:observation,id',
            'observation_ids' => 'required|array',
            'observation_ids.*' => 'exists:observation,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        $observationId = $request->input('obsId');

        ObservationLink::where('observationId', $observationId)
            ->where('linktype', 'OBSERVATION')
            ->delete();

        $linkIds = $request->input('observation_ids');

        foreach ($linkIds as $linkId) {
            ObservationLink::create([
                'observationId' => $observationId,
                'linkid' => $linkId,
                'linktype' => 'OBSERVATION',
            ]);
        }

        $data = [
            'id' => $observationId
        ];

        return response()->json(['status' => true, 'message' => 'Links saved successfully.', 'data' => $data]);
    }

    public function linkreflectiondata(Request $request)
    {
        //  dd('here');
        $authId = Auth::user()->id;
        $centerid = $request->center_id;
        $search = $request->query('search');
        $obsId = $request->query('obsId'); // Current observation ID for checking existing links

        $reflections = Reflection::with(['creator', 'center', 'children.child', 'media', 'staff.staff', 'Seen.user'])
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->where('centerid', $centerid)
            ->orderBy('created_at', 'desc')
            ->get();

        $linkedIds = ObservationLink::where('observationId', $obsId)
            ->where('linktype', 'REFLECTION')

            ->get();

        return response()->json([
            'reflections' => $reflections,
            'linked_ids' => $linkedIds
        ]);
    }

    public function storelinkreflection(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'obsId' => 'required|exists:observation,id',
                'reflection_ids' => 'required|array|min:1',
                'reflection_ids.*' => 'exists:reflection,id', // Adjust table name if needed
            ], [
                'obsId.required' => 'Observation ID is required.',
                'obsId.exists'   => 'Observation not found.',
                'reflection_ids.required' => 'At least one reflection is required.',
                'reflection_ids.array' => 'Reflection IDs must be an array.',
                'reflection_ids.min' => 'Please select at least one reflection.',
                'reflection_ids.*.exists' => 'One or more reflections are invalid.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $observationId = $request->input('obsId');
            $linkIds = $request->input('reflection_ids', []);

            // Extra fallback: Check if observation actually exists (even after validation)
            $observation = Observation::find($observationId);
            if (!$observation) {
                return response()->json([
                    'status' => false,
                    'message' => 'Observation not found.'
                ], 404);
            }

            // Remove existing links
            ObservationLink::where('observationId', $observationId)
                ->where('linktype', 'REFLECTION')
                ->delete();

            // Extra fallback: If no new reflection IDs given
            if (empty($linkIds)) {
                return response()->json([
                    'status' => true,
                    'message' => 'All reflection links removed. No new reflections provided.',
                    'id' => $observationId
                ], 200);
            }

            // Save new links
            foreach ($linkIds as $linkId) {
                ObservationLink::create([
                    'observationId' => $observationId,
                    'linkid' => $linkId,
                    'linktype' => 'REFLECTION',
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Reflection links saved successfully.',
                'id' => $observationId
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Fallback: DB error
            return response()->json([
                'status' => false,
                'message' => 'Database error while saving reflections.',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Fallback: Unexpected error
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function linkprogramplandata(Request $request)
    {
        // dd('here');
        $authId = Auth::user()->id;
        $centerid = $request->center_id;
        $search = $request->query('search');
        $obsId = $request->query('obsId'); // Current observation ID for checking existing links

        // Month names for search
        $monthNames = [
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'december' => 12
        ];

        $programPlans = ProgramPlanTemplateDetailsAdd::with(['room', 'creator'])
            ->when($search, function ($query, $search) use ($monthNames) {
                $searchLower = strtolower($search);
                $monthNumber = null;

                // Check if search matches any month name
                foreach ($monthNames as $monthName => $monthNum) {
                    if (strpos($monthName, $searchLower) !== false) {
                        $monthNumber = $monthNum;
                        break;
                    }
                }

                if ($monthNumber) {
                    return $query->where('months', $monthNumber);
                }

                // If no month found, search in year as well
                return $query->where('year', 'like', "%{$search}%");
            })
            ->where('status', 'Published')
            ->where('centerid', $centerid)
            ->orderBy('created_at', 'desc')
            ->get();

        $linkedIds = ObservationLink::where('observationId', $obsId)
            ->where('linktype', 'PROGRAMPLAN')

            ->get();

        return response()->json([
            'program_plans' => $programPlans,
            'linked_ids' => $linkedIds
        ]);
    }

    public function storelinkprogramplan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'obsId' => 'required|exists:observation,id',
                'programplanids' => 'required|array|min:1',
                'programplanids.*' => 'exists:programplantemplatedetailsadd,id',
            ], [
                'obsId.required' => 'Observation ID is required.',
                'obsId.exists'   => 'Observation not found.',
                'programplanids.required' => 'At least one program plan is required.',
                'programplanids.array' => 'Program plan IDs must be an array.',
                'programplanids.min' => 'Please select at least one program plan.',
                'programplanids.*.exists' => 'One or more program plan IDs are invalid.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $observationId = $request->input('obsId');
            $linkIds = $request->input('programplanids', []);

            // Extra fallback: Ensure observation exists (redundant check after validation)
            $observation = Observation::find($observationId);
            if (!$observation) {
                return response()->json([
                    'status' => false,
                    'message' => 'Observation not found.'
                ], 404);
            }

            // Remove existing program plan links
            ObservationLink::where('observationId', $observationId)
                ->where('linktype', 'PROGRAMPLAN')
                ->delete();

            // Fallback: If no new program plans provided
            if (empty($linkIds)) {
                return response()->json([
                    'status' => true,
                    'message' => 'All program plan links removed. No new program plans provided.',
                    'id' => $observationId
                ], 200);
            }

            // Use DB transaction: if one insert fails, rollback all
            DB::beginTransaction();
            foreach ($linkIds as $linkId) {
                ObservationLink::create([
                    'observationId' => $observationId,
                    'linkid' => $linkId,
                    'linktype' => 'PROGRAMPLAN',
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Program Plan links saved successfully.',
                'id' => $observationId
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack(); // rollback transaction on DB error
            return response()->json([
                'status' => false,
                'message' => 'Database error while saving program plans.',
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack(); // rollback transaction on general error
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // public function store(Request $request)
    // {
    //     // Get PHP upload limits
    //     $uploadMaxSize = min(
    //         $this->convertToBytes(ini_get('upload_max_filesize')),
    //         $this->convertToBytes(ini_get('post_max_size'))
    //     );

    //     // Validate input
    //     $validator = Validator::make($request->all(), [
    //         'selected_rooms'   => 'required',
    //         'obestitle'        => 'required|string|max:255',
    //         'title'            => 'required|string|max:255',
    //         'notes'            => 'required|string',
    //         'reflection'       => 'required|string',
    //         'child_voice'      => 'required|string',
    //         'future_plan'      => 'required|string',
    //         'selected_children'=> 'required|string',
    //         'media'            => 'required|array|min:1',
    //         'media.*'          => "file|mimes:jpeg,png,jpg,gif,webp|max:" . intval($uploadMaxSize / 1024), // Convert to KB
    //     ], [
    //         'media.required' => 'At least one media file is required.',
    //         'media.*.max' => 'Each file must be smaller than ' . ($uploadMaxSize / 1024 / 1024) . 'MB.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $authId = Auth::user()->id;
    //         $centerid = Session('user_center_id');

    //         $observation = new Observation();
    //         $observation->room = $request->input('selected_rooms');
    //         $observation->obestitle = $request->input('obestitle');
    //         $observation->title = $request->input('title');
    //         $observation->notes = $request->input('notes');
    //         $observation->userId = $authId;
    //         $observation->centerid = $centerid;
    //         $observation->reflection = $request->input('reflection');
    //         $observation->child_voice = $request->input('child_voice');
    //         $observation->future_plan = $request->input('future_plan');
    //         $observation->save();

    //         $observationId = $observation->id;

    //         $selectedChildren = explode(',', $request->input('selected_children'));
    //         foreach ($selectedChildren as $childId) {
    //             if (trim($childId) !== '') {
    //                 ObservationChild::create([
    //                     'observationId' => $observationId,
    //                     'childId' => trim($childId),
    //                 ]);
    //             }
    //         }

    //         $manager = new ImageManager(new GdDriver());

    //         foreach ($request->file('media') as $file) {
    //             if ($file->isValid()) {
    //                 $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $destinationPath = public_path('uploads/Observation');

    //                 if (!file_exists($destinationPath)) {
    //                     mkdir($destinationPath, 0777, true);
    //                 }

    //                 if ($file->getSize() > 1024 * 1024) {
    //                     $image = $manager->read($file->getPathname());
    //                     $image->scale(1920);
    //                     $image->save($destinationPath . '/' . $filename, 75);
    //                 } else {
    //                     $file->move($destinationPath, $filename);
    //                 }

    //                 ObservationMedia::create([
    //                     'observationId' => $observationId,
    //                     'mediaUrl' => 'uploads/Observation/' . $filename,
    //                     'mediaType' => $file->getClientMimeType(),
    //                 ]);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json(['status' => 'success', 'message' => 'Observation saved successfully.','id' => $observationId]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Observation Store Failed: ' . $e->getMessage());

    //         return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
    //     }
    // }


    public function store(Request $request)
    {

        $uploadMaxSize = min(
            $this->convertToBytes(ini_get('upload_max_filesize')),
            $this->convertToBytes(ini_get('post_max_size'))
        );

        $isEdit = $request->filled('id');

        $normalizedInput = $request->all();
        foreach (['selected_rooms', 'selected_children', 'selected_staff'] as $field) {
            if (isset($normalizedInput[$field]) && is_array($normalizedInput[$field])) {
                $normalizedInput[$field] = implode(',', array_filter($normalizedInput[$field], static function ($value) {
                    return $value !== null && $value !== '';
                }));
            }
        }

        $rules = [
            'selected_rooms'    => 'required|string',
            'obestitle'         => 'required|string',
            'title'             => 'nullable|string',
            'notes'             => 'nullable|string',
            'reflection'        => 'nullable|string',
            'child_voice'       => 'nullable|string',
            'future_plan'       => 'nullable|string',
            'implementation'    => 'nullable|string',
            'selected_children' => 'required|string',
            'selected_staff'    => 'nullable|string',
            'status'            => 'nullable|in:Published,Draft',
            'publishIntent'     => 'nullable|in:Published,Draft',
        ];

        if (!$isEdit) {
            $rules['media'] = 'nullable|array|min:1';
        } else {
            $rules['media'] = 'nullable|array';
        }

        $rules['media.*'] = "file|mimes:jpeg,png,jpg,gif,webp,mp4|max:" . intval($uploadMaxSize / 1024);

        $messages = [
            'media.required' => 'At least one media file is required.',
            'media.*.max' => 'Each file must be smaller than ' . ($uploadMaxSize / 1024 / 1024) . 'MB.',
        ];

        $validator = Validator::make($normalizedInput, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $authId = Auth::user()->id;
            $centerid = $request->center_id;

            $observation = $isEdit
                ? Observation::findOrFail($request->id)
                : new Observation();

            $observation->room         = $validated['selected_rooms'];
            $observation->obestitle    = $validated['obestitle'];
            $observation->title        = $validated['title'] ?? '';
            $observation->notes        = $validated['notes'] ?? '';
            $observation->reflection   = $validated['reflection'] ?? '';
            $observation->child_voice  = $validated['child_voice'] ?? '';
            $observation->future_plan  = $validated['future_plan'] ?? '';
            $observation->implementation = $validated['implementation'] ?? '';
            $observation->tagged_staff = $validated['selected_staff'] ?? '';
            $observation->userId       = $authId;
            $observation->centerid     = $centerid;
            $observation->status       = $request->input('status', $request->input('publishIntent', 'Draft'));
            $observation->save();

            $observationId = $observation->id;

            // Replace all existing ObservationChild records
            ObservationChild::where('observationId', $observationId)->delete();

            $selectedChildren = explode(',', $validated['selected_children']);
            foreach ($selectedChildren as $childId) {
                if (trim($childId) !== '') {
                    ObservationChild::create([
                        'observationId' => $observationId,
                        'childId' => trim($childId),
                    ]);
                }
            }

            
            \App\Models\ObservationStaff::where('observationId', $observationId)->delete();
            $selectedStaff = explode(',', $validated['selected_staff']);
            foreach ($selectedStaff as $userid) {
                if (trim($userid) !== '') {
                    \App\Models\ObservationStaff::create([
                        'observationId' => $observationId,
                        'userid' => trim($userid),
                    ]);
                }
            }

            // Process uploaded media only if present
            if ($request->hasFile('media')) {
                $manager = new ImageManager(new GdDriver());

                foreach ($request->file('media') as $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('uploads/Observation');

                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }

                        if (Str::startsWith($file->getMimeType(), 'image') && $file->getSize() > 1024 * 1024) {
                            $image = $manager->read($file->getPathname());
                            $image->scale(1920);
                            $image->save($destinationPath . '/' . $filename, 75);
                        } else {
                            $file->move($destinationPath, $filename);
                        }

                        ObservationMedia::create([
                            'observationId' => $observationId,
                            'mediaUrl' => 'uploads/Observation/' . $filename,
                            'mediaType' => $file->getClientMimeType(),
                        ]);
                    }
                }
            }


            DB::commit();

            // Debug log for notification trigger
            // Log::info('[Observation Store] Notification check', [
            //     'observation_id' => $observationId,
            //     'status' => $observation->status,
            //     'request_status' => $request->input('status'),
            //     'selectedChildren' => $selectedChildren,
            //     'authId' => $authId,
            // ]);

            // Send notification to all parents of the attached children ONLY if published
            // if (!empty($selectedChildren) && strtolower($observation->status ?? '') === 'published') {
            //     Log::info('NOTIFICATION_TRIGGER_CHECK', [
            //         'module' => 'observation',
            //         'status' => $observation->status,
            //         'childIds' => $selectedChildren,
            //         'staffIds' => $selectedStaff ?? [],
            //         'observation_id' => $observationId,
            //     ]);

            //     $service = app(\App\Services\Firebase\FirebaseNotificationService::class);
            //     \App\Http\Controllers\API\DeviceController::notifyParentsModuleCreated(
            //         $selectedChildren,
            //         'observation',
            //         $observationId,
            //         $authId,
            //         $service,
            //         null,
            //         [],
            //         $selectedStaff ?? []
            //     );
            // }

            return response()->json([
                'status' => true,
                'message' => $isEdit ? 'Observation updated successfully.' : 'Observation saved successfully.',
                'id' => $observationId
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Observation Store/Update Failed: ' . $e->getMessage());

            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }


    // Helper to convert ini values to bytes
    private function convertToBytes($value)
    {
        $unit = strtolower(substr($value, -1));
        $bytes = (int) $value;

        switch ($unit) {
            case 'g':
                $bytes *= 1024 * 1024 * 1024;
                break;
            case 'm':
                $bytes *= 1024 * 1024;
                break;
            case 'k':
                $bytes *= 1024;
                break;
        }

        return $bytes;
    }



    public function destroyimage(Request $request, $id = null)
    {
        $id = $id ?? $request->input('id') ?? $request->route('id');

        if (empty($id) || !is_numeric($id)) {
            return response()->json(['status' => false, 'message' => 'Media id is required.'], 422);
        }

        $mediaItems = ObservationMedia::where('id', $id)->get();

        if ($mediaItems->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Media not found.'], 404);
        }

        foreach ($mediaItems as $media) {
            if (file_exists(public_path($media->mediaUrl))) {
                @unlink(public_path($media->mediaUrl));
            }

            $media->delete();
        }

        return response()->json(['status' => true, 'message' => 'selected media deleted for this observation.']);
    }


    // print page not rendering properly
    public function print(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $id = $validator->validated()['id'];

            // Fetch data
            $observation = Observation::with([
                'media',
                'child.child',
                'montessoriLinks.subActivity.activity.subject',
                'eylfLinks.subActivity.activity.outcome',
                'devMilestoneSubs.devMilestone.main',
                'devMilestoneSubs.devMilestone.milestone',
                'user'
            ])->findOrFail($id);

            // Add this null check in your controller before generating PDF
            if (!$observation) {
                return response()->json([
                    'status' => false,
                    'message' => 'Observation not found.',
                ], 404);
            }


            // Mark as seen (for parents)
            $user = Auth::user();
            if ($user && $user->userType === 'Parent') {
                SeenObservation::firstOrCreate([
                    'user_id' => $user->id,
                    'observation_id' => $id
                ]);
            }

            // Get room names
            $roomNames = Room::whereIn('id', explode(',', $observation->room))
                ->pluck('name')
                ->implode(', ');

            // Better room handling
            $roomNames = 'N/A';
            if ($observation->room) {
                $roomIds = array_filter(explode(',', $observation->room));
                if (!empty($roomIds)) {
                    $roomNames = Room::whereIn('id', $roomIds)
                        ->pluck('name')
                        ->filter()
                        ->implode(', ') ?: 'N/A';
                }
            }
            // Generate PDF
            $htmlContent = view('observations.apiPrint', compact('observation', 'roomNames'))->render();
            $pdf = Pdf::loadHTML($htmlContent)->setPaper('a4', 'landscape');
            // Get first child's name (if available)
            $childName = 'UnknownChild';
            if ($observation->child && count($observation->child) > 0) {
                $childObj = $observation->child[0]->child ?? null;
                if ($childObj) {
                    $childName = trim(preg_replace('/\s+/', '_', $childObj->name . '_' . ($childObj->lastname ?? '')));
                }
            }
            $date = $observation->created_at ? \Carbon\Carbon::parse($observation->created_at)->format('d-m-Y') : now()->format('d-m-Y');
            $fileName = 'Observation_' . $childName . '_' . $date . '.pdf';
            $pdfPath = public_path('reports/' . $fileName);

            // Make sure the directory exists
            if (!file_exists(public_path('reports'))) {
                mkdir(public_path('reports'), 0777, true);
            }

            // Save the PDF to public/reports/
            file_put_contents($pdfPath, $pdf->output());

            // Return the PDF as a download response
            if (file_exists($pdfPath)) {
                return response()->download($pdfPath, $fileName, [
                    'Content-Type' => 'application/pdf',
                ])->deleteFileAfterSend(true);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to generate PDF file.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error occurred while generating the PDF.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        // Delete observation
    public function destroy($id)
    {
        $observation = Observation::find($id);
        if (!$observation) {
            return response()->json(['status' => false, 'message' => 'Observation not found.'], 404);
        }

        $observation->deleted_by = Auth::id();
        $observation->save();
        // Soft delete the observation only; related rows stay available for recycle bin display.
        $observation->delete();
        return response()->json(['status' => true, 'message' => 'Observation deleted successfully.']);
    }

















    public function snapshotindex(Request $request)
    {
        $authId = Auth::user()->id;
        $user = Auth::user();
        $centerid = $request->query('centerid', $request->input('centerid'));

        // Fallback for Parent
        if ($user->usertype == "Parent") {
            $centerid = Usercenter::where('userid', $authId)->value('centerid');
        }

        // Fallback if still empty
        if (empty($centerid)) {
            $centerid = Usercenter::where('userid', $authId)->value('centerid');
        }

        $snapshots = collect();
        $centers = collect();

        // Role-specific fetching
        if ($user->userType == "Superadmin") {
            $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();

            if (!empty($centerIds)) {
                $centersQuery = Center::whereIn('id', $centerIds);
                if (!empty($centerid)) {
                    $centersQuery->where('id', $centerid);
                }
                $centers = $centersQuery->get();

                $snapshotQuery = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                    ->whereIn('centerid', $centerIds);

                if (!empty($centerid)) {
                    $snapshotQuery->where('centerid', $centerid);
                }

                $snapshots = $snapshotQuery->orderBy('id', 'desc')->get();
            }
        } elseif ($user->userType == "Staff") {
            if (!empty($centerid)) {
                $centers = Center::where('id', $centerid)->get();
            }

            $snapshotQuery = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('createdBy', $authId);

            if (!empty($centerid)) {
                $snapshotQuery->where('centerid', $centerid);
            }

            $snapshots = $snapshotQuery->orderBy('id', 'desc')->get();
        } else { // Parent
            if (!empty($centerid)) {
                $centers = Center::where('id', $centerid)->get();
            }
            $childids = Childparent::where('parentid', $authId)->pluck('childid');

            if ($childids->isNotEmpty()) {
                $snapshotid = SnapshotChild::whereIn('childid', $childids)
                    ->pluck('snapshotid')
                    ->unique()
                    ->toArray();

                if (!empty($snapshotid)) {
                    $snapshotQuery = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                        ->whereIn('id', $snapshotid)
                        ->where('status', "Published");

                    if (!empty($centerid)) {
                        $snapshotQuery->where('centerid', $centerid);
                    }

                    $snapshots = $snapshotQuery->orderBy('id', 'desc')->get();
                }
            }
        }

        // Response if no centers
        if ($centers->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No centers found for this user',
                'snapshots' => [],
                'centers' => []
            ]);
        }

        // Response if no snapshots
        if ($snapshots->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No snapshots found for the selected center',
                'snapshots' => [],
                'centers' => $centers
            ]);
        }

        // Rooms collection
        $allRoomIds = $snapshots->pluck('roomids')
            ->flatMap(fn($roomids) => explode(',', $roomids))
            ->unique()
            ->filter();

        $rooms = $allRoomIds->isNotEmpty()
            ? Room::whereIn('id', $allRoomIds)->get()->keyBy('id')
            : collect();

        // Attach room data
        $snapshots->transform(function ($snapshot) use ($rooms) {
            $roomIds = array_filter(explode(',', $snapshot->roomids));
            $snapshot->rooms = $rooms->only($roomIds)->values();
            return $snapshot;
        });

        // Success response
        return response()->json([
            'status' => true,
            'message' => 'Snapshots fetched successfully',
            'snapshots' => $snapshots,
            'centers' => $centers
        ]);
    }

    public function mernsnapshotindex(Request $request)
    {
        $authId = Auth::user()->id;
        $user = Auth::user();
        $centerid = $request->query('centerid', $request->input('centerid'));
        $roomId = $request->input('room_id', $request->input('roomid'));
        $perPage = max((int) $request->input('per_page', 10), 1);
        $selectedChildId = null;
        $selectedChildSource = null;

        // Fallback for Parent
        if ($user->usertype == "Parent") {
            $centerid = Usercenter::where('userid', $authId)->value('centerid');
        }

        // Fallback if still empty
        if (empty($centerid)) {
            $centerid = Usercenter::where('userid', $authId)->value('centerid');
        }

        $snapshots = collect();
        $centers = collect();

        // Role-specific fetching
        if ($user->userType == "Superadmin") {
            $centerIds = Usercenter::where('userid', $authId)->pluck('centerid')->toArray();

            if (!empty($centerIds)) {
                $centersQuery = Center::whereIn('id', $centerIds);
                if (!empty($centerid)) {
                    $centersQuery->where('id', $centerid);
                }
                $centers = $centersQuery->get();

                $snapshotQuery = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                    ->whereIn('centerid', $centerIds);

                if (!empty($centerid)) {
                    $snapshotQuery->where('centerid', $centerid);
                }

                $this->applySnapshotFilters($snapshotQuery, $request);
                $this->applySnapshotRoomFilter($snapshotQuery, $roomId);

                $snapshots = $snapshotQuery->orderBy('id', 'desc')->paginate($perPage);
            }
        } elseif ($user->userType == "Staff") {
            if (!empty($centerid)) {
                $centers = Center::where('id', $centerid)->get();
            }

            $snapshotQuery = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where(function ($q) use ($authId) {
                    $q->where('createdBy', $authId)
                    ->orWhereRaw('FIND_IN_SET(?, educators)', [$authId]);
                });

            if (!empty($centerid)) {
                $snapshotQuery->where('centerid', $centerid);
            }

            $this->applySnapshotFilters($snapshotQuery, $request);
            $this->applySnapshotRoomFilter($snapshotQuery, $roomId);

            $snapshots = $snapshotQuery->orderBy('id', 'desc')->paginate($perPage);
        } else { // Parent
            if (!empty($centerid)) {
                $centers = Center::where('id', $centerid)->get();
            }
            $childids = Childparent::where('parentid', $authId)->pluck('childid')->values();
            $requestedChildId = $request->input('child_id', $request->input('childid'));
            $savedChildId = User::where('userid', $authId)->value('selectedchildreanid');

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
                    $selectedChildRoom = Room::where('id', $selectedChild->room)->first();

                    if ($selectedChildRoom) {
                        $roomId = $selectedChildRoom->id;
                    }
                }
            }

            if (!$selectedChildId) {
                return response()->json([
                    'status' => false,
                    'message' => 'No affiliated child found for this parent.',
                    'snapshots' => [],
                    'selectedChildId' => null,
                    'selectedChildSource' => null,
                ]);
            }

            $snapshotIds = SnapshotChild::where('childid', $selectedChildId)
                ->pluck('snapshotid')
                ->unique()
                ->toArray();

            if (empty($snapshotIds)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No snapshots found for the selected child.',
                    'snapshots' => [],
                ]);
            }

            $snapshotQuery = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->whereIn('id', $snapshotIds)
                ->where('status', "Published");

            if (!empty($centerid)) {
                $snapshotQuery->where('centerid', $centerid);
            }

            $this->applySnapshotFilters($snapshotQuery, $request);
            $this->applySnapshotRoomFilter($snapshotQuery, $roomId);

            $snapshots = $snapshotQuery->orderBy('id', 'desc')->paginate($perPage);
        }

        // Response if no centers
        if ($centers->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No centers found for this user',
                'snapshots' => []
                // 'centers' => []
            ]);
        }

        // Response if no snapshots
        if ($snapshots->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No snapshots found for the selected center',
                'snapshots' => []
                // 'centers' => $centers
            ]);
        }

        $snapshotItems = $snapshots instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $snapshots->getCollection()
            : $snapshots;

        // Rooms collection
        $allRoomIds = $snapshotItems->pluck('roomids')
            ->flatMap(fn($roomids) => explode(',', $roomids))
            ->unique()
            ->filter();

        $rooms = $allRoomIds->isNotEmpty()
            ? Room::whereIn('id', $allRoomIds)->get()->keyBy('id')
            : collect();

        // Attach room data
        $snapshotItems->transform(function ($snapshot) use ($rooms) {
            $roomIds = array_filter(explode(',', $snapshot->roomids));
            $snapshot->rooms = $rooms->only($roomIds)->values();
            return $snapshot;
        });

        if ($snapshots instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $snapshots->setCollection($snapshotItems);
        } else {
            $snapshots = $snapshotItems;
        }

        // Success response
        $selectionMeta = ($user->usertype ?? $user->userType) === 'Parent'
            ? [
                'selectedChildId' => $selectedChildId ?? null,
                'selectedChildSource' => $selectedChildSource ?? null,
            ]
            : [];

        return response()->json([
            'status' => true,
            'message' => 'Snapshots fetched successfully',
            'snapshots' => $snapshots,
            // 'centers' => $centers,
            // 'pagination' => $snapshots instanceof \Illuminate\Pagination\LengthAwarePaginator ? [
            //     'current_page' => $snapshots->currentPage(),
            //     'per_page' => $snapshots->perPage(),
            //     'total' => $snapshots->total(),
            //     'last_page' => $snapshots->lastPage(),
            // ] : null,
        ] + $selectionMeta);
    }

    public function mernsnapshotapplyFilters(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'center_id' => 'required|integer|min:1',
                'per_page'  => 'nullable|integer|min:1',
                'page'      => 'nullable|integer|min:1',
                'search'    => 'nullable|string',
                'title'     => 'nullable|string',
                'status'    => 'nullable|string',
                'date'      => 'nullable|array',
                'date.*'    => 'nullable|string',
                'fromDate'  => 'nullable|date',
                'toDate'    => 'nullable|date',
                'author'    => 'nullable|string',
                'child_name'=> 'nullable|string',
                'room_id'   => 'nullable|integer|min:1',
                'roomid'    => 'nullable|integer|min:1',
                'child_id'  => 'nullable|integer|min:1',
                'childid'   => 'nullable|integer|min:1',
            ], [
                'center_id.required' => 'Center ID is required.',
                'center_id.integer'  => 'Center ID must be an integer.',
                'center_id.min'      => 'Center ID must be greater than 0.',
                'per_page.integer'   => 'Per page must be an integer.',
                'per_page.min'       => 'Per page must be greater than 0.',
                'page.integer'       => 'Page must be an integer.',
                'page.min'           => 'Page must be greater than 0.',
                'fromDate.date'      => 'From date must be a valid date.',
                'toDate.date'        => 'To date must be a valid date.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();
            $centerid = $validated['center_id'];
            $perPage = $validated['per_page'] ?? max((int) $request->input('per_page', 10), 1);
            $page = $validated['page'] ?? max((int) $request->input('page', 1), 1);
            $childName = trim((string) $request->input('child_name', ''));
            $roomId = $request->input('room_id', $request->input('roomid'));
            $selectedChildId = null;
            $selectedChildSource = null;
            $user = Auth::user();

            $query = Snapshot::with(['creator', 'center', 'children.child', 'media'])
                ->where('centerid', $centerid);

            if ($user->userType === 'Parent') {
                $parentChildIds = Childparent::where('parentid', $user->id)->pluck('childid')->values();
                $requestedChildId = $request->input('child_id', $request->input('childid'));
                $savedChildId = User::where('userid', $user->id)->value('selectedchildreanid');

                if (!empty($requestedChildId) && trim((string) $requestedChildId) !== '') {
                    $requestedChildId = (int) $requestedChildId;

                    if (!$parentChildIds->contains($requestedChildId)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'This child does not belong to this parent',
                        ], 403);
                    }

                    $selectedChildId = $requestedChildId;
                    $selectedChildSource = 'request';
                }

                if (!$selectedChildId && !empty($savedChildId) && $parentChildIds->contains((int) $savedChildId)) {
                    $selectedChildId = (int) $savedChildId;
                    $selectedChildSource = 'saved';
                }

                if (!$selectedChildId && $parentChildIds->isNotEmpty()) {
                    $selectedChildId = (int) $parentChildIds->first();
                    $selectedChildSource = 'fallback';
                }

                if (!$selectedChildId) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No affiliated child found for this parent.',
                        'snapshots' => [],
                        'selectedChildId' => null,
                        'selectedChildSource' => null,
                    ]);
                }

                $snapshotIds = SnapshotChild::where('childid', $selectedChildId)
                    ->pluck('snapshotid')
                    ->unique()
                    ->toArray();

                if (empty($snapshotIds)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'No snapshots found for the selected child.',
                        'snapshots' => [],
                        'selectedChildId' => $selectedChildId,
                        'selectedChildSource' => $selectedChildSource,
                    ]);
                }

                $query->whereIn('id', $snapshotIds)
                    ->where('status', 'Published');
            }

            if ($user->userType === 'Staff') {
                    $authId = Auth::id();

                    $query->where(function ($q) use ($authId) {

                        // Created by me
                        $q->where('createdBy', $authId)

                        // Tagged as educator
                        ->orWhereRaw('FIND_IN_SET(?, educators)', [$authId]);
                    });

            }

            $filterRequest = $request->duplicate();
            $filterRequest->request->remove('child_name');
            $filterRequest->query->remove('child_name');

            $this->applySnapshotFilters($query, $filterRequest);
            $this->applySnapshotRoomFilter($query, $roomId);

            if ($childName !== '') {
                $query->whereHas('children.child', function ($childQuery) use ($childName) {
                    $childQuery->where(function ($nameQuery) use ($childName) {
                        $nameQuery->where('name', 'like', '%' . $childName . '%')
                            ->orWhere('lastname', 'like', '%' . $childName . '%')
                            ->orWhereRaw("CONCAT(COALESCE(name, ''), ' ', COALESCE(lastname, '')) LIKE ?", ['%' . $childName . '%']);

                        if (is_numeric($childName)) {
                            $nameQuery->orWhere('id', (int) $childName);
                        }
                    });
                });

                $page = 1;
            }

            $query->orderBy('id', 'desc');

            $snapshots = $query->paginate($perPage, ['*'], 'page', $page);

            $snapshotItems = $snapshots->getCollection();

            $allRoomIds = $snapshotItems->pluck('roomids')
                ->flatMap(fn($roomids) => explode(',', (string) $roomids))
                ->unique()
                ->filter();

            $rooms = $allRoomIds->isNotEmpty()
                ? Room::whereIn('id', $allRoomIds)->get()->keyBy('id')
                : collect();

            $snapshotItems->transform(function ($snapshot) use ($rooms) {
                $roomIds = array_filter(explode(',', (string) $snapshot->roomids));
                $snapshot->rooms = $rooms->only($roomIds)->values();
                return $snapshot;
            });

            $snapshots->setCollection($snapshotItems);

            return response()->json([
                'status' => 'success',
                'message' => 'Snapshot filters applied successfully',
                'filters' => [
                    'search' => $request->input('search', ''),
                    'title' => $request->input('title', ''),
                    'status' => $request->input('status', ''),
                    'date' => $request->input('date', ''),
                    'author' => $request->input('author', ''),
                    'child_name' => $request->input('child_name', ''),
                    'room_id' => $roomId,
                ],
                'snapshots' => $snapshots,
                'userRole' => $user->userType,
                'count' => $snapshots->total(),
                'pagination' => [
                    'current_page' => $snapshots->currentPage(),
                    'per_page' => $snapshots->perPage(),
                    'total' => $snapshots->total(),
                    'last_page' => $snapshots->lastPage(),
                    'from' => $snapshots->firstItem(),
                    'to' => $snapshots->lastItem(),
                ],
                
                'selectedChildId' => $user->userType === 'Parent' ? $selectedChildId : null,
                'selectedChildSource' => $user->userType === 'Parent' ? $selectedChildSource : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Snapshot filter error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while applying snapshot filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function applySnapshotFilters($query, Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $title = trim((string) $request->input('title', ''));
        $status = trim((string) $request->input('status', ''));
        $dateFilters = $request->input('date', []);
        $dateFilters = is_array($dateFilters) ? $dateFilters : [$dateFilters];
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $author = trim((string) $request->input('author', ''));
        $childName = trim((string) $request->input('child_name', ''));

        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('children.child', function ($childQuery) use ($search) {
                        $childQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('lastname', 'like', '%' . $search . '%')
                            ->orWhereRaw("CONCAT(COALESCE(name, ''), ' ', COALESCE(lastname, '')) LIKE ?", ['%' . $search . '%']);
                    });

                try {
                    $searchDate = Carbon::parse($search)->toDateString();
                    $searchQuery->orWhereDate('created_at', $searchDate);
                } catch (\Exception $e) {
                    // Ignore non-date search strings.
                }
            });
        }

        if ($title !== '') {
            $query->where(function ($titleQuery) use ($title) {
                $titleQuery->where('title', 'like', '%' . $title . '%');
            });
        }

        if ($status !== '') {
            $query->where('status', 'like', '%' . $status . '%');
        }

        if (!empty($dateFilters)) {
            foreach ($dateFilters as $dateFilter) {
                switch ($dateFilter) {
                    case 'Today':
                        $query->whereDate('created_at', Carbon::today());
                        break;
                    case 'This Week':
                        $query->whereBetween('created_at', [
                            Carbon::now()->startOfWeek(),
                            Carbon::now()->endOfWeek(),
                        ]);
                        break;
                    case 'This Month':
                        $query->whereBetween('created_at', [
                            Carbon::now()->startOfMonth(),
                            Carbon::now()->endOfMonth(),
                        ]);
                        break;
                    case 'Custom':
                        if ($fromDate && $toDate) {
                            $query->whereBetween('created_at', [
                                Carbon::parse($fromDate)->startOfDay(),
                                Carbon::parse($toDate)->endOfDay(),
                            ]);
                        }
                        break;
                }
            }
        }

        if ($author !== '') {
            $authorLower = strtolower($author);

            if ($authorLower === 'any') {
                // No author filter.
            } elseif ($authorLower === 'me') {
                $query->where('createdBy', Auth::id());
            } elseif (is_numeric($author)) {
                $query->where('createdBy', (int) $author);
            } else {
                $query->whereHas('creator', function ($creatorQuery) use ($author) {
                    $creatorQuery->where('name', 'like', '%' . $author . '%');
                });
            }
        }

        if ($childName !== '') {
            $query->whereHas('children.child', function ($childQuery) use ($childName) {
                $childQuery->where('name', 'like', '%' . $childName . '%')
                    ->orWhere('lastname', 'like', '%' . $childName . '%')
                    ->orWhereRaw("CONCAT(COALESCE(name, ''), ' ', COALESCE(lastname, '')) LIKE ?", ['%' . $childName . '%']);
            });
        }
    }

    private function applySnapshotRoomFilter($query, $roomId)
    {
        if (!empty($roomId)) {
            $query->whereRaw('FIND_IN_SET(?, roomids)', [$roomId]);
        }
    }


    public function snapshotindexstorepage(Request $request)
    {
        // Manual validation with Validator
        $validator = Validator::make($request->all(), [
            'center_id' => 'required|integer|exists:centers,id',
            'id'        => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $authId   = Auth::user()->id;
        $centerid = $request->center_id;
        $id       = $request->id;

        $reflection = null;
        $childrens  = collect();
        $rooms      = collect();
        $educators  = collect();

        if ($id) {
            $reflection = Snapshot::with(['creator', 'center', 'children', 'media'])->find($id);

            if ($reflection) {
                // Children
                $childrens = $reflection->children->pluck('child')->filter();

                // Rooms
                if (!empty($reflection->roomids)) {
                    $roomIds = explode(',', $reflection->roomids);
                    $rooms   = Room::whereIn('id', $roomIds)->get();
                }

                // Educators
                if (!empty($reflection->educators)) {
                    $educatorsIds = explode(',', $reflection->educators);
                    $educators    = User::whereIn('userid', $educatorsIds)->get();
                }
            }
        }

        // EYLF Outcomes (with activities + subActivities)
        $outcomes = EYLFOutcome::with('activities.subActivities')->get();

        return response()->json([
            'status'     => true,
            'authId'     => $authId,
            'centerId'   => $centerid,
            'reflection' => $reflection ?? null,
            'children'   => $childrens ?? [],
            'rooms'      => $rooms ?? [],
            'educators'  => $educators ?? [],
            'outcomes'   => $outcomes ?? []
        ]);
    }



    public function snapshotstore(Request $request)
    {
           
        // ✅ Check PHP upload limits
        $uploadMaxSize = min(
            $this->convertToBytes(ini_get('upload_max_filesize')),
            $this->convertToBytes(ini_get('post_max_size'))
        );

        $isEdit = $request->filled('id');

     

        // ✅ Validation Rules
        $rules = [
            'selected_rooms'    => 'required|string',
            'title'             => 'required|string|max:255',
            'about'             => 'nullable|string',
            'selected_children' => 'required|string',
            'selected_staff'    => 'required|string',
        ];

        if (!$isEdit) {
            $rules['media'] = 'required|array|min:1';
        } else {
            $rules['media'] = 'nullable|array';
        }

        $rules['media.*'] = "file|mimes:jpeg,png,jpg,gif,webp,mp4|max:" . intval($uploadMaxSize / 1024);

        $messages = [
            'media.required' => 'At least one media file is required.',
            'media.*.max'    => 'Each file must be smaller than ' . round($uploadMaxSize / 1024 / 1024, 2) . 'MB.',
            'title.required' => 'Title is required.',
        ];
      

        $validator = Validator::make($request->all(), $rules, $messages);
            // dd($validator);
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        $authId   = Auth::user()->userid; // working field
        $centerid = $request->center_id ?? null;

        // ✅ Create or Update Snapshot
        $snapshot = Snapshot::find($request->id);

        if ($isEdit && !$snapshot) {
            return response()->json([
                'status'  => false,
                'message' => 'Snapshot not found.',
            ], 404);
        }

        // ✅ Assign Snapshot Data

        $status = $request->input('publishIntent', $request->input('status', 'Draft'));
        $normalizedSnapshotStatus = strcasecmp($status, 'published') === 0 ? 'Published' : 'Draft';

        if (!$isEdit && !$snapshot) {
            // Create new snapshot
            $snapshot = Snapshot::create([
                'title'     => $validated['title'],
                'about'     => $validated['about'] ?? '',
                'centerid'  => $centerid,
                'createdBy' => $authId,
                'educators' => $validated['selected_staff'],
                'status'    => $normalizedSnapshotStatus,
            ]);

            // Update roomids after creation
            $snapshot->roomids = $validated['selected_rooms'];
            $snapshot->save();
        } else {
            // Fetch existing snapshot for update
            $snapshot = Snapshot::findOrFail($request->id);

            // Assign updated values
            $snapshot->title     = $validated['title'];
            $snapshot->about     = $validated['about'];
            $snapshot->centerid  = $centerid;
            $snapshot->createdBy = $authId;
            $snapshot->educators = $validated['selected_staff'];
            $snapshot->roomids   = $validated['selected_rooms'];
            $snapshot->status    = $normalizedSnapshotStatus;

            $snapshot->save();
        }




        $snapshotId = $snapshot->id;

        // ✅ Handle selected children
        SnapshotChild::where('snapshotid', $snapshotId)->delete();
        $selectedChildren = explode(',', $validated['selected_children']);
        foreach ($selectedChildren as $childId) {
            if (trim($childId) !== '') {
                SnapshotChild::create([
                    'snapshotid' => $snapshotId,
                    'childid'    => trim($childId),
                ]);
            }
        }

        // ✅ Handle media uploads
        if ($request->hasFile('media')) {
            $manager = new ImageManager(new Driver());
            $destinationPath = public_path('uploads/Snapshots');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            foreach ($request->file('media') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Compress large images
                    if (Str::startsWith($file->getMimeType(), 'image') && $file->getSize() > 1024 * 1024) {
                        $image = $manager->read($file->getPathname());
                        $image->scale(1920);
                        $image->save($destinationPath . '/' . $filename, 75);
                    } else {
                        $file->move($destinationPath, $filename);
                    }

                    SnapshotMedia::create([
                        'snapshotid' => $snapshotId,
                        'mediaUrl'   => 'uploads/Snapshots/' . $filename,
                        'mediaType'  => $file->getClientMimeType(),
                    ]);
                }
            }
        }

        DB::commit();

        // Extract educators/staff IDs for notification
        $educatorsArr = !empty($validated['selected_staff']) 
            ? array_filter(array_map('trim', explode(',', (string)$validated['selected_staff'])))
            : [];

        // Send notification to all parents of the attached children ONLY if published
        if (!empty($selectedChildren) && strcasecmp($snapshot->status ?? '', 'Published') === 0) {
            Log::info('SNAPSHOT_FCM_TRIGGER', [
                'snapshot_id' => $snapshotId,
                'status' => $snapshot->status,
                'child_ids' => $selectedChildren,
                'staff_ids' => $educatorsArr,
            ]);

            $service = app(\App\Services\Firebase\FirebaseNotificationService::class);
            \App\Http\Controllers\API\DeviceController::notifyParentsModuleCreated(
                $selectedChildren,
                'snapshot',
                $snapshotId,
                $authId,
                $service,
                null,
                [],
                $educatorsArr
            );
        }

        return response()->json([
            'status'  => true,
            'message' => $isEdit ? 'Snapshot updated successfully.' : 'Snapshot saved successfully.',
            'id'      => $snapshotId,
        ], 200);
    }





    public function snapshotdestroyimage($id)
    {
        try {
            // ✅ Check if media exists
            $media = SnapshotMedia::find($id);

            if (!$media) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Media not found.',
                ], 404);
            }

            $filePath = public_path($media->mediaUrl);

            // ✅ Delete file if exists
            if (file_exists($filePath)) {
                try {
                    @unlink($filePath);
                } catch (\Exception $e) {
                    // Log error but continue with DB deletion
                    Log::warning("Failed to delete file: {$filePath}. Error: " . $e->getMessage());
                }
            } else {
                // Optional fallback: notify that file is already missing
                Log::info("File already missing: {$filePath}");
            }

            // ✅ Delete DB record
            if (!$media->delete()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to delete media record.',
                ], 500);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Media deleted successfully.',
                'id'      => $id,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Snapshot destroy image failed: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => config('app.debug') ? $e->getMessage() : null, // hide details in production
            ], 500);
        }
    }



    public function snapshotupdateStatus(Request $request)
    {
        // Manual validation using Validator
        $validator = Validator::make($request->all(), [
            'reflectionId' => 'required|exists:snapshot,id',
            'status'       => 'required|in:Published,Draft',
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Update status
        $reflection = Snapshot::find($request->reflectionId);
        $reflection->status = $request->status;
        $reflection->save();

        return response()->json([
            'status'  => true,
            'message' => 'Snapshot status updated successfully',
            'id'      => $reflection->id
        ], 200);
    }


    public function snapshotsdelete($id)
    {
        try {
            // ✅ Find snapshot
            $snapshot = Snapshot::find($id);

            if (!$snapshot) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Snapshot not found.',
                ], 404);
            }

            // Soft delete only; keep related rows and files for recovery.
            $snapshot->deleted_by = Auth::id();
            $snapshot->save();
            $snapshot->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Snapshot deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error("Snapshot delete failed: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong while deleting snapshot.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
