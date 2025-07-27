@extends('layout.authentication')
@section('title', 'Register')


@section('content')





<div class="vertical-align-wrap">



    <div class="vertical-align-middle auth-main">

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-10px">
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
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-10px">
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
            <div class="card">

                <div class="body">
                    <form id="superadminForm" class="form-auth-small" enctype="multipart/form-data"
                        action="{{ route('center_store') }}" method="post">
                        @csrf
                        <h6 class="mb-4 text-center"><u>Center Details</u></h6>
                        <div class="form-group">
                            <label for="name" class="control-label">Center Name</label>
                            <input type="text" class="form-control" id="name" name="center_name"
                                value="{{ old('center_name') }}">
                            @error('center_name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="signup-email" class="control-label">Street Address</label>
                            <input type="text" class="form-control" id="signup-email" name="street_address"
                                value="{{ old('street_address') }}">
                            @error('street_address')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city" class="control-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                            @error('city')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="state" class="control-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                            @error('state')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="zipcode" class="control-label">Zipcode</label>
                            <input type="text" class="form-control" id="zipcode" name="zipcode"
                                value="{{ old('zipcode') }}" pattern="\d*" maxlength="10"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('zipcode')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>

                        <div class="bottom text-center mt-3">
                            <span class="helper-text">Already have an account?
                                <a href="{{ route('authentication.login') }}">Login</a>
                            </span>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

