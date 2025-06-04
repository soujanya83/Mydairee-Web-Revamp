<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use App\Models\Usercenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{




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
        // Validate input
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        $user_id = Auth::user()->id;
        session(['user_id' => $user_id]);
        $centerstatus = User::where('id', $user_id)->where('center_status', 1)->first();
        if ($centerstatus) {
            if (Auth::attempt($credentials, $remember)) {
                return redirect()->route('dashboard.university');
            }
        } else {
            return redirect()->route('create_center');
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

        // Handle image upload
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
}
