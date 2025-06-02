@extends('layout.authentication')
@section('title', 'Register')


@section('content')

<div class="vertical-align-wrap">
    <div class="vertical-align-middle auth-main">
        <div class="auth-box">
            <div class="top">
                <img src="{{url('/')}}/assets/img/MYDIAREE-new-logo.png" style="background-color: aliceblue;padding: 10px;
    width: 180px;" alt="Lucid">
            </div>
            <div class="card">

                <div class="body">

                    <form id="superadminForm" class="form-auth-small" enctype="multipart/form-data" action="{{ route('create_superadmin') }}">
                        @csrf
                        <!-- Superadmin Details Section -->
                        <h6 class="mb-3">Superadmin Details</h6>
                        <div class="form-group">
                            <label for="name" class="control-label sr-only">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="username" class="control-label sr-only">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="contactNo" class="control-label sr-only">Contact No</label>
                            <input type="tel" class="form-control" id="contactNo" name="contactNo"
                                placeholder="Enter phone no" required>
                        </div>
                        <div class="form-group">
                            D.O.B.<label for="dob" class="control-label sr-only">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" placeholder="Date of Birth"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="emailid" class="control-label sr-only">Email ID</label>
                            <input type="email" class="form-control" id="emailid" name="emailid"
                                placeholder="Your email" required>
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label sr-only">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                        </div>

                        <div class="form-group">
                            <label for="gender" class="control-label sr-only">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="MALE">Male</option>
                                <option value="FEMALE">Female</option>
                                <option value="OTHERS">Other</option>
                            </select>
                        </div>

                        <div class="form-group" style="display: none">
                            <label for="title" class="control-label sr-only">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                        </div>

                        <div class="form-group">
                            Profile Image: <label for="imageUrl" class="control-label sr-only">Profile Image</label>
                            <input type="file" class="form-control" id="imageUrl" name="imageUrl" accept="image/*">
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
