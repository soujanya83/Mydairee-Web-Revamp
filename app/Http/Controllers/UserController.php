<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use App\Models\ReEnrolment;
use App\Models\Usercenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // ✅ add this

use App\Mail\ReEnrollmentInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{


    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|string'
        ]);

        $user = Auth::user();
        $user->theme = $request->theme;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function store_center(Request $request)
    {
        $userId = Auth::user()->id;

        // Check if center already created
        $center_created = User::where('id', $userId)->where('center_status', 1)->exists();
        if ($center_created) {
            return redirect()->back()
                ->withErrors(['center_status' => 'Your center is already created.'])
                ->withInput();
        }

        // Validate form inputs
        $validator = Validator::make($request->all(), [
            'center_name'     => 'required|string|max:255',
            'street_address'  => 'required|string|max:500',
            'city'            => 'required|string|max:100',
            'state'           => 'required|string|max:100',
            'zipcode'         => 'required|digits_between:4,10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Save center
        $center = Center::create([
            'centerName'     => $request->center_name,
            'adressStreet'   => $request->street_address,
            'addressCity'    => $request->city,
            'addressState'   => $request->state,
            'addressZip'     => $request->zipcode,
            'user_id'        => $userId,
        ]);

        if ($center) {
            User::where('id', $userId)->update(['center_status' => 1]);
            Usercenter::create([
                'userid'   => $userId,
                'centerid' => $center->id,
            ]);
            session(['center_id' => $center->id]);
        }

        return redirect()->route('dashboard.university')->with('success', 'Center registered successfully.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            $plainPassword = $request->password;
            $storedPassword = $user->password;

            $isBcrypt = false;
            $isLegacy = false;

            // Check if it's a bcrypt password
            if (Str::startsWith($storedPassword, '$2y$')) {
                $isBcrypt = Hash::check($plainPassword, $storedPassword);
            } else {
                // Fallback for SHA1 or legacy hash
                $isLegacy = sha1($plainPassword) === $storedPassword;
            }

            if ($isBcrypt || $isLegacy) {
                Auth::login($user, $request->has('remember'));

                // Upgrade legacy password to bcrypt
                if ($isLegacy) {
                    $user->password = Hash::make($plainPassword);
                    $user->save();
                }

                session(['user_id' => $user->id]);

                // If Superadmin
                if ($user->userType === 'Superadmin') {
                    $centerstatus = $user->center_status == 1;
                    if ($centerstatus) {
                        $center = Usercenter::where('userid', $user->id)->first();
                        session(['user_center_id' => $center->centerid ?? null]);
                        return redirect()->route('dashboard.university');
                    } else {
                        return redirect()->route('create_center');
                    }
                }


                // Handle Parent user type
                // if ($user->userType === 'Parent') {
                //     $center = Usercenter::where('userid', $user->id)->first();
                //     session(['user_center_id' => $center->centerid ?? null]);

                //     // Check if parent has seen the login notice
                //     if (!$user->has_seen_login_notice) {
                //         // Mark as seen and save
                //         $user->has_seen_login_notice = true;
                //         $user->save();

                //         // Set session flag to show modal
                //         session(['show_parent_notice' => true]);
                //     }

                //     return redirect()->route('dashboard.university');
                // }



                // All other users
                $center = Usercenter::where('userid', $user->id)->first();
                session(['user_center_id' => $center->centerid ?? null]);
                return redirect()->route('dashboard.university');
            }
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|max:255|unique:users,username|regex:/^\S*$/u', // no spaces
            'contactNo'   => 'required|digits_between:7,15',
            'dob'         => 'required|date',
            'emailid'     => 'required|email|unique:users,emailid',
            'password'    => 'required|string|min:6',
            'gender'      => 'required|in:MALE,FEMALE,OTHERS',
            'imageUrl'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title'       => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $imagePath = null;
        if ($request->hasFile('imageUrl')) {
            $imagePath = $request->file('imageUrl')->store('profile_images', 'public');
        }

        // Create user
        $user = new User();
        $user->name         = $request->name;
        $user->username     = $request->username;
        $user->contactNo    = $request->contactNo;
        $user->dob          = $request->dob;
        $user->email        = $request->emailid;
        $user->emailid      = $request->emailid;
        $user->password     = Hash::make($request->password);
        $user->gender       = $request->gender;
        $user->imageUrl     = $imagePath ?? '';
        $user->title        = $request->title;
        $user->userType     = 'Superadmin';
        $user->status       = 'ACTIVE';
        $user->AuthToken    = Str::random(128);
        $user->deviceid     = '';
        $user->devicetype   = '';
        $user->companyLogo  = '';
        $user->theme        = 1;
        $user->image_position = '';
        $user->created_by   = auth::user()->id ?? 0;

        $user->save();

        // Set userid = id
        $user->userid = $user->id;
        $user->save();

        return redirect()->back()->with('success', 'Superadmin created successfully!');
    }


    public function getUsernameSuggestions(Request $request)
    {
        $name = preg_replace('/\s+/', '', strtolower($request->input('name')));
        $suggestions = [];
        if (strlen($name) < 3) return response()->json([]);
        for ($i = 0; count($suggestions) < 5 && $i < 20; $i++) {
            $rand = rand(100, 999);
            $username = $name . $rand;

            $exists = User::where('username', $username)->exists();
            if (!$exists) {
                $suggestions[] = $username;
            }
        }
        return response()->json($suggestions);
    }

    public function checkUsernameExists(Request $request)
    {
        $username = $request->input('username');
        $exists = User::where('username', $username)->exists();
        return response()->json(['exists' => $exists]);
    }

    function create_center()
    {
        return view('forms.create_center');
    }


    public function createform()
    {
        return view('reenrolment');
    }


    public function storeform(Request $request)
    {
        // Validate the request
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
            'holiday_dates' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create the re-enrollment record
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
                'holiday_dates' => $request->holiday_dates
            ]);

            Log::info('Re-enrollment submitted successfully', [
                'id' => $reEnrolment->id,
                'child_name' => $reEnrolment->child_name,
                'parent_email' => $reEnrolment->parent_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Re-enrollment submitted successfully!',
                'data' => $reEnrolment
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error saving re-enrollment', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving your re-enrollment. Please try again.'
            ], 500);
        }
    }



    public function dashboard()
    {
        $reEnrolments = ReEnrolment::with([])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'totalEnrollments' => ReEnrolment::count(),
            'completedEnrollments' => ReEnrolment::whereNotNull('processed_at')->count(),
            'pendingEnrollments' => ReEnrolment::whereNull('processed_at')->count(),
            'thisWeekEnrollments' => ReEnrolment::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
        ];

        return view('re-enrolmentfetch', array_merge(compact('reEnrolments'), $stats));
    }

    /**
     * Get enrollment details for AJAX request
     */
    public function getDetails(ReEnrolment $reEnrolment)
    {
        return response()->json([
            'id' => $reEnrolment->id,
            'child_name' => $reEnrolment->child_name,
            'child_dob' => $reEnrolment->child_dob->format('d M Y'),
            'parent_email' => $reEnrolment->parent_email,
            'current_days' => $reEnrolment->current_days,
            'requested_days' => $reEnrolment->requested_days,
            'session_option' => $reEnrolment->session_option_display,
            'kinder_program' => $reEnrolment->kinder_program_display,
            'finishing_child_name' => $reEnrolment->finishing_child_name,
            'last_day' => $reEnrolment->last_day ? $reEnrolment->last_day->format('d M Y') : null,
            'holiday_dates' => $reEnrolment->holiday_dates,
            'created_at' => $reEnrolment->created_at->format('d M Y H:i'),
        ]);
    }


    public function privacyPolicy()
    {
        return view('privacy_policy');
    }


    public function getParents()
    {
        try {
            $centerid = Session::get('user_center_id');

            if (!$centerid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Center ID not found in session'
                ], 400);
            }

            // Get all user IDs for this center
            $userIds = UserCenter::where('centerid', $centerid)->pluck('userid');

            // Get all parent users with children count
            $parents = User::whereIn('id', $userIds)
                ->where('userType', 'Parent')
                ->withCount('children')
                ->orderBy('name')
                ->get(['id', 'name', 'email'])
                ->map(function ($parent) {
                    return [
                        'id' => $parent->id,
                        'name' => $parent->name,
                        'email' => $parent->email,
                        'children_count' => $parent->children_count ?? 0
                    ];
                });

            return response()->json([
                'success' => true,
                'parents' => $parents,
                'total_count' => $parents->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching parents: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching parents'
            ], 500);
        }
    }

    /**
     * Send re-enrollment emails to selected parents
     */
    public function sendReEnrollmentEmails(Request $request)
    {
        $request->validate([
            'parent_ids' => 'required|array|min:1',
            'parent_ids.*' => 'integer|exists:users,id'
        ]);

        $sentCount = 0;
        $failedCount = 0;

        $parents = User::whereIn('id', $request->parent_ids)
            ->where('userType', 'Parent')
            ->get();

        foreach ($parents as $parent) {
            try {
                Mail::to($parent->email)->send(new ReEnrollmentInvitation($parent));
                $sentCount++;
                Log::info("Re-enrollment email sent to: {$parent->email}");
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to send re-enrollment email to {$parent->email}: " . $e->getMessage());
            }
        }

        $message = $failedCount > 0
            ? "Email campaign completed with some issues"
            : "All emails sent successfully!";

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'sent_count' => $sentCount,
            'failed_count' => $failedCount
        ]);
    }
}
