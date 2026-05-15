<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ReEnrolment;
use App\Models\User;
use App\Mail\ReEnrollmentInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PDF;

class ReEnrolmentController extends Controller
{
    public function createForm()
    {
        return response()->json([
            'status' => true,
            'message' => 'Re-enrollment form metadata fetched successfully',
            'data' => [
                'current_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'requested_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'session_options' => [
                    '9_hours' => '9 Hours (8:30am - 5:30pm)',
                    '10_hours_8_6' => '10 Hours (8:00am - 6:00pm)',
                    '10_hours_8_30_6_30' => '10 Hours (8:30am - 6:30pm)',
                    'full_day' => 'Full Day (7:00am - 6:30pm)',
                ],
                'kinder_programs' => [
                    '3_year_old' => '3-year-old Kinder',
                    '4_year_old' => '4-year-old Kinder',
                    'unfunded' => 'Unfunded Kinder (3-5 years)',
                    'not_attending' => 'Not attending Kinder at Nextgen',
                ],
            ],
        ]);
    }

    public function storeForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_name' => 'required|string|max:255',
            'child_dob' => 'required|date',
            'parent_email' => 'required|email|max:255',
            'current_days' => 'nullable|array',
            'current_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday',
            'requested_days' => 'nullable|array',
            'requested_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday',
            'session_option' => 'nullable|string|in:9_hours,10_hours_8_6,10_hours_8_30_6_30,full_day',
            'kinder_program' => 'nullable|string|in:3_year_old,4_year_old,unfunded,not_attending',
            'finishing_child_name' => 'nullable|string|max:255',
            'last_day' => 'nullable|date',
            'holiday_dates' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $reEnrolment = ReEnrolment::create([
                'child_name' => $request->child_name,
                'child_dob' => $request->child_dob,
                'parent_email' => $request->parent_email,
                'current_days' => $request->current_days ?? [],
                'requested_days' => $request->requested_days ?? [],
                'session_option' => $request->session_option,
                'kinder_program' => $request->kinder_program ?? 'not_attending',
                'finishing_child_name' => $request->finishing_child_name,
                'last_day' => $request->last_day,
                'holiday_dates' => $request->holiday_dates,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Re-enrollment submitted successfully!',
                'data' => $reEnrolment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving your re-enrollment. Please try again.',
            ], 500);
        }
    }

    public function dashboard()
    {
        $reEnrolments = ReEnrolment::query()
            ->orderByDesc('created_at')
            ->paginate(12);

        $reEnrolments->getCollection()->transform(function (ReEnrolment $reEnrolment) {
            return [
                'id' => $reEnrolment->id,
                'initial' => strtoupper(substr((string) $reEnrolment->child_name, 0, 1)),
                'child_name' => $reEnrolment->child_name,
                'child_dob' => $reEnrolment->child_dob?->format('d M Y'),
                'parent_email' => $reEnrolment->parent_email,
                'current_days' => $reEnrolment->current_days_formatted,
                'requested_days' => $reEnrolment->requested_days_formatted,
                'session_option' => $reEnrolment->session_option_display,
                'kinder_program' => $reEnrolment->kinder_program_display,
                'finishing_child_name' => $reEnrolment->finishing_child_name,
                'last_day' => $reEnrolment->last_day?->format('d M Y'),
                'holiday_dates' => $reEnrolment->holiday_dates,
                'submitted_at' => $reEnrolment->created_at?->format('d M Y H:i'),
                'processed_at' => $reEnrolment->processed_at ?? null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Re-enrollment dashboard fetched successfully',
            'data' => [
                're_enrolments' => $reEnrolments,
                'stats' => [
                    'totalEnrollments' => ReEnrolment::count(),
                    'completedEnrollments' => ReEnrolment::whereNotNull('processed_at')->count(),
                    'pendingEnrollments' => ReEnrolment::whereNull('processed_at')->count(),
                    'thisWeekEnrollments' => ReEnrolment::whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek(),
                    ])->count(),
                ],
            ],
        ]);
    }

    // Controller Function
// Controller Function
public function getDetails($id = null)
{
    // Missing ID
    if (!$id) {
        return response()->json([
            'status' => false,
            'message' => 'Re-enrollment ID is required',
        ], 400);
    }

    // Record Not Found
    $reEnrolment = ReEnrolment::find($id);

    if (!$reEnrolment) {
        return response()->json([
            'status' => false,
            'message' => 'Re-enrollment record not found',
        ], 404);
    }

    // Success Response
    return response()->json([
        'status' => true,
        'message' => 'Re-enrollment details fetched successfully',
        'data' => [
            'id' => $reEnrolment->id,
            'child_name' => $reEnrolment->child_name,
            'child_dob' => $reEnrolment->child_dob?->format('d M Y'),
            'parent_email' => $reEnrolment->parent_email,
            'current_days' => $reEnrolment->current_days,
            'requested_days' => $reEnrolment->requested_days,
            'session_option' => $reEnrolment->session_option_display,
            'kinder_program' => $reEnrolment->kinder_program_display,
            'finishing_child_name' => $reEnrolment->finishing_child_name,
            'last_day' => $reEnrolment->last_day?->format('d M Y'),
            'holiday_dates' => $reEnrolment->holiday_dates,
            'created_at' => $reEnrolment->created_at?->format('d M Y H:i'),
        ],
    ]);
}

    /**
     * Send re-enrollment link to parents
     */
    public function sendEnrollmentEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'submission_id' => 'nullable|integer|exists:re_enrollments,id',
            'parent_ids' => 'nullable|array',
            'parent_ids.*' => 'integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Require at least one of submission_id or parent_ids
        if (!$request->filled('submission_id') && !$request->filled('parent_ids')) {
            return response()->json([
                'status' => false,
                'message' => 'Provide submission_id or parent_ids to send emails.',
            ], 422);
        }

        $sentCount = 0;
        $failedCount = 0;
        $sentEmails = [];

        try {
            // Case: send to explicit parent IDs (web bulk send)
            if ($request->filled('parent_ids')) {
                $parents = User::whereIn('id', $request->parent_ids)
                    ->where('userType', 'Parent')
                    ->get();

                foreach ($parents as $parent) {
                    try {
                        Mail::to($parent->email)->send(new ReEnrollmentInvitation($parent));
                        $sentCount++;
                        $sentEmails[] = $parent->email;
                        Log::info("Re-enrollment email sent to: {$parent->email}");
                    } catch (\Exception $e) {
                        $failedCount++;
                        Log::error("Failed to send re-enrollment email to {$parent->email}: " . $e->getMessage());
                    }
                }
            }

            // Case: send based on a single submission (fallback)
            if ($request->filled('submission_id')) {
                $reEnrolment = ReEnrolment::find($request->submission_id);

                if (!$reEnrolment) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Re-enrollment submission not found.',
                    ], 404);
                }

                $parentEmail = $reEnrolment->parent_email;
                $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');
                $enrollmentLink = "{$frontendUrl}/re-enrollment/{$reEnrolment->id}";

                try {
                    // If parent exists as a User, use the mailable that accepts User
                    $parentUser = User::where('email', $parentEmail)->orWhere('emailid', $parentEmail)->first();
                    if ($parentUser) {
                        Mail::to($parentUser->email)->send(new ReEnrollmentInvitation($parentUser));
                    } else {
                        // send generic notification view when no User record exists
                        Mail::send('emails.re-enrollment-notification', [
                            'child_name' => $reEnrolment->child_name,
                            'parent_email' => $parentEmail,
                            'enrollment_link' => $enrollmentLink,
                            'submitted_date' => $reEnrolment->created_at?->format('d M Y H:i'),
                        ], function ($message) use ($parentEmail) {
                            $message->to($parentEmail)
                                ->subject('Re-Enrollment Link - Nextgen Childcare Centre');
                        });
                    }

                    $sentCount++;
                    $sentEmails[] = $parentEmail;
                } catch (\Exception $mailException) {
                    $failedCount++;
                    Log::error('Re-enrollment email failed: ' . $mailException->getMessage());
                }
            }

            $message = $failedCount > 0
                ? 'Email campaign completed with some issues'
                : 'All emails sent successfully!';

            return response()->json([
                'status' => true,
                'message' => $message,
                'emails_sent' => $sentCount,
                'failed_count' => $failedCount,
                'sent_emails' => $sentEmails,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error in sendEnrollmentEmail: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while sending the email.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Filter re-enrollment submissions with search and filters
     */
    public function filterSubmissions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'nullable|string|max:255',
            'session_option' => 'nullable|string|in:all,9_hours,10_hours_8_6,10_hours_8_30_6_30,full_day',
            'kinder_program' => 'nullable|string|in:all,3_year_old,4_year_old,unfunded,not_attending',
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $query = ReEnrolment::query();

            // Search by child name or parent email
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('child_name', 'like', "%{$search}%")
                      ->orWhere('parent_email', 'like', "%{$search}%");
                });
            }

            // Filter by session option
            if ($request->filled('session_option') && $request->session_option !== 'all') {
                $query->where('session_option', $request->session_option);
            }

            // Filter by kinder program
            if ($request->filled('kinder_program') && $request->kinder_program !== 'all') {
                $query->where('kinder_program', $request->kinder_program);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Get total count before pagination
            $filteredCount = $query->count();

            // Paginate results
            $perPage = $request->per_page ?? 12;
            $reEnrolments = $query->orderByDesc('created_at')->paginate($perPage);

            // Transform collection
            $reEnrolments->getCollection()->transform(function (ReEnrolment $reEnrolment) {
                return [
                    'id' => $reEnrolment->id,
                    'initial' => strtoupper(substr((string) $reEnrolment->child_name, 0, 1)),
                    'child_name' => $reEnrolment->child_name,
                    'child_dob' => $reEnrolment->child_dob?->format('d M Y'),
                    'parent_email' => $reEnrolment->parent_email,
                    'current_days' => $reEnrolment->current_days_formatted,
                    'requested_days' => $reEnrolment->requested_days_formatted,
                    'session_option' => $reEnrolment->session_option_display,
                    'kinder_program' => $reEnrolment->kinder_program_display,
                    'finishing_child_name' => $reEnrolment->finishing_child_name,
                    'last_day' => $reEnrolment->last_day?->format('d M Y'),
                    'holiday_dates' => $reEnrolment->holiday_dates,
                    'submitted_at' => $reEnrolment->created_at?->format('d M Y H:i'),
                    'processed_at' => $reEnrolment->processed_at ?? null,
                    'status' => $reEnrolment->processed_at ? 'completed' : 'pending',
                ];
            });

            // Calculate stats
            $totalCount = ReEnrolment::count();
            $completedCount = ReEnrolment::whereNotNull('processed_at')->count();
            $pendingCount = ReEnrolment::whereNull('processed_at')->count();
            $thisWeekCount = ReEnrolment::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count();

            return response()->json([
                'status' => true,
                'message' => 'Re-enrollment submissions filtered successfully.',
                'filters' => [
                    'search' => $request->search ?? null,
                    'session_option' => $request->session_option ?? 'all',
                    'kinder_program' => $request->kinder_program ?? 'all',
                    'date_from' => $request->date_from ?? null,
                    'date_to' => $request->date_to ?? null,
                ],
                'stats' => [
                    'total' => $totalCount,
                    'filtered_count' => $filteredCount,
                    'completed' => $completedCount,
                    'pending' => $pendingCount,
                    'this_week' => $thisWeekCount,
                ],
                'data' => $reEnrolments->items(),
                'pagination' => [
                    'total' => $reEnrolments->total(),
                    'count' => $reEnrolments->count(),
                    'per_page' => $reEnrolments->perPage(),
                    'current_page' => $reEnrolments->currentPage(),
                    'last_page' => $reEnrolments->lastPage(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while filtering submissions.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Print re-enrollment submission as PDF
     */
    public function printSubmission($id)
    {
        try {
            $reEnrolment = ReEnrolment::find($id);

            if (!$reEnrolment) {
                return response()->json([
                    'status' => false,
                    'message' => 'Re-enrollment submission not found.',
                ], 404);
            }

            // Generate PDF
            $pdf = PDF::loadView('re-enrollment.print-submission', [
                'reEnrolment' => $reEnrolment,
            ]);

            // Format filename with child name and date
            $childName = str_replace(' ', '_', $reEnrolment->child_name);
            $date = $reEnrolment->created_at->format('Y-m-d');
            $filename = "re-enrollment_{$childName}_{$date}.pdf";

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while generating the PDF.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Returns available form options for API/mobile
    public function formOptions()
    {
        return response()->json([
            [
                'name' => 'Re-enrolment Form',
                'slug' => 'reenrolment',
                'url' => url('/re-enrollment/form')
            ],
            [
                'name' => 'Health Form',
                'slug' => 'health',
                'url' => url('/health/form')
            ]
        ]);
    }
}