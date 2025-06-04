@extends('layout.authentication')
@section('title', 'Register')


@section('content')





<div class="vertical-align-wrap">



    <div class="vertical-align-middle auth-main">

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-65px">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-65px">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif


        <div class="auth-box">
            <div class="top">
                <img src="{{url('/')}}/assets/img/MYDIAREE-new-logo.png" style="background-color: aliceblue;padding: 10px;
                                width: 180px;" alt="Lucid">
            </div>
            <div class="card" style="    width: 700px;">

                <div class="body">

                    <form id="superadminForm" class="form-auth-small" enctype="multipart/form-data"
                        action="{{ route('create_superadmin') }}" method="post">
                        @csrf

                        <h6 class="mb-5"><u>Superadmin Details</u></h6>

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="control-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        value="{{ old('name') }}">
                                </div>

                                <div class="form-group">
                                    <label for="emailid" class="control-label">Email ID</label>
                                    <input type="email" class="form-control" id="emailid" name="emailid" required
                                        value="{{ old('emailid') }}">
                                </div>

                                <div class="form-group">
                                    <label for="contactNo" class="control-label">Contact No</label>
                                    <input type="tel" class="form-control" id="contactNo" name="contactNo" required
                                        value="{{ old('contactNo') }}">
                                </div>

                                <div class="form-group">
                                    <label for="dob" class="control-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dob" name="dob" required
                                        value="{{ old('dob') }}">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group position-relative">
                                    <label for="username" class="control-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required
                                        autocomplete="off" value="{{ old('username') }}">
                                    <ul class="list-group mt-1" id="usernameSuggestions"
                                        style="display: none; position: absolute; z-index: 1000; width: 100%;"></ul>
                                    <small id="usernameError" class="text-danger"></small>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    {{-- Do NOT pre-fill password fields for security reasons --}}
                                </div>

                                <div class="form-group">
                                    <label for="gender" class="control-label">Gender</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="MALE" {{ old('gender')=='MALE' ? 'selected' : '' }}>Male</option>
                                        <option value="FEMALE" {{ old('gender')=='FEMALE' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="OTHERS" {{ old('gender')=='OTHERS' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group" style="display: none">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ old('title') }}">
                                </div>

                                <div class="form-group">
                                    <label for="imageUrl" class="control-label">Profile Image</label>
                                    <input type="file" class="form-control" id="imageUrl" name="imageUrl"
                                        accept="image/*">
                                    {{-- File inputs cannot retain old value --}}
                                </div>
                            </div>
                        </div>

                        <!-- Center Details Section -->
                        {{-- <h6 class="mb-3 mt-4">Center Details</h6>

                        <div class="form-group">
                            <label for="centerName" class="control-label sr-only">Center Name</label>
                            <input type="text" class="form-control" id="centerName" name="centerName"
                                placeholder="Center Name" required>
                        </div>

                        <div class="form-group">
                            <label for="adressStreet" class="control-label sr-only">Street Address</label>
                            <input type="text" class="form-control" id="adressStreet" name="adressStreet"
                                placeholder="Street Address" required>
                        </div>

                        <div class="form-group">
                            <label for="addressCity" class="control-label sr-only">City</label>
                            <input type="text" class="form-control" id="addressCity" name="addressCity"
                                placeholder="City" required>
                        </div>

                        <div class="form-group">
                            <label for="addressState" class="control-label sr-only">State</label>
                            <input type="text" class="form-control" id="addressState" name="addressState"
                                placeholder="State" required>
                        </div>

                        <div class="form-group">
                            <label for="addressZip" class="control-label sr-only">ZIP Code</label>
                            <input type="text" class="form-control" id="addressZip" name="addressZip"
                                placeholder="ZIP Code" required>
                        </div> --}}

                        <button type="submit" class="btn btn-primary btn-lg btn-block">REGISTER</button>

                        <div class="bottom mt-3">
                            <span class="helper-text">
                                Already have an account?
                                <a href="{{ route('authentication.login') }}">Login</a>
                            </span>
                        </div>
                    </form>




                    {{-- <form class="form-auth-small">
                        <div class="form-group">
                            <label for="name" class="control-label sr-only">Name</label>
                            <input type="email" class="form-control" id="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="signup-email" class="control-label sr-only">Email</label>
                            <input type="email" class="form-control" id="signup-email" placeholder="Your email">
                        </div>
                        <div class="form-group">
                            <label for="phoneno" class="control-label sr-only">Phone No.</label>
                            <input type="tel" class="form-control" id="phoneno" placeholder="Enter phone no">
                        </div>
                        <div class="form-group">
                            <label for="dob" class="control-label sr-only">DOB.</label>
                            <input type="date" class="form-control" id="dob" placeholder="Your DOB">
                        </div>
                        <div class="form-group">
                            <label for="state" class="control-label sr-only">State</label>
                            <input type="text" class="form-control" id="state" placeholder="Enter state">
                        </div>
                        <div class="form-group">
                            <label for="zipcode" class="control-label sr-only">Zipcode</label>
                            <input type="text" class="form-control" id="zipcode" placeholder="Enter zip code">
                        </div>

                        <div class="form-group">
                            <label for="signup-password" class="control-label sr-only">Password</label>
                            <input type="password" class="form-control" id="signup-password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">REGISTER</button>
                        <div class="bottom">
                            <span class="helper-text">Already have an account? <a
                                    href="{{route('authentication.login')}}">Login</a></span>
                        </div>
                    </form> --}}

                </div>
            </div>
        </div>
    </div>
</div>

@stop
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const nameInput = document.getElementById("name");
        const usernameInput = document.getElementById("username");
        const suggestionsBox = document.getElementById("usernameSuggestions");
        const usernameError = document.getElementById("usernameError");

        let currentSuggestions = [];

        // Prevent space in username
        usernameInput.addEventListener("keydown", function (e) {
            if (e.key === " ") e.preventDefault();
        });

        // Show suggestions only when Username field is clicked
        usernameInput.addEventListener("focus", function () {
            const name = nameInput.value.trim();

            if (name.length < 3) return;

            fetch(`/username-suggestions?name=${encodeURIComponent(name)}`)
                .then(response => response.json())
                .then(data => {
                    currentSuggestions = data;
                    suggestionsBox.innerHTML = '';
                    if (data.length === 0) {
                        suggestionsBox.style.display = "none";
                        return;
                    }

                    data.forEach(username => {
                        const li = document.createElement("li");
                        li.className = "list-group-item list-group-item-action";
                        li.textContent = username;
                        li.style.cursor = "pointer";
                        li.onclick = () => {
                            usernameInput.value = username;
                            suggestionsBox.style.display = "none";
                            checkUsernameUnique(username);
                        };
                        suggestionsBox.appendChild(li);
                    });

                    suggestionsBox.style.display = "block";
                });
        });

        // Hide suggestion box on click outside
        document.addEventListener("click", function (e) {
            if (!suggestionsBox.contains(e.target) && e.target !== usernameInput) {
                suggestionsBox.style.display = "none";
            }
        });

        // Live check if username exists when typing manually
    usernameInput.addEventListener("input", function () {
    const username = usernameInput.value.trim();

    suggestionsBox.style.display = "none"; // 👈 closes dropdown when typing

    if (/\s/.test(username)) {
        usernameError.textContent = "Username cannot contain spaces.";
        return;
    }

    if (username.length >= 3) {
        checkUsernameUnique(username);
    } else {
        usernameError.textContent = "";
    }
});

        function checkUsernameUnique(username) {
            fetch(`/check-username-exists?username=${encodeURIComponent(username)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        usernameError.textContent = "Username already taken.";
                    } else {
                        usernameError.textContent = "";
                    }
                });
        }
    });
</script>
