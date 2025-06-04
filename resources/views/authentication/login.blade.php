@extends('layout.authentication')
@section('title', 'Login')


@section('content')

<div class="vertical-align-wrap">
    <div class="vertical-align-middle auth-main">

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-22px">
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
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-22px">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="auth-box">
            <div class="top">
                <img src="{{url('/')}}/assets/img/MYDIAREE-new-logo.png" alt="Lucid"
                    style="background-color: aliceblue;padding: 10px;width: 180px;">
            </div>
            <div class="card">
                <div class="header">
                    <p class="lead">Login to your account</p>
                </div>
                <div class="body">
                    <form class="form-auth-small" action="{{ route('user_login') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="signin-email" class="control-label">Email</label>
                            <input type="email" class="form-control" id="signin-email" name="email"
                                value="{{ old('email') }}">
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="signin-password" class="control-label">Password</label>
                            <input type="password" class="form-control" id="signin-password" name="password">
                            <span toggle="#signin-password" class="fa fa-fw fa-eye toggle-password"
                                style="position:absolute; top:38px; right:15px; cursor:pointer;"></span>

                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group clearfix">
                            <label class="fancy-checkbox element-left">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>Remember me</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">LOGIN</button>

                        <div class="bottom">
                            <span class="helper-text m-b-10">
                                <i class="fa fa-lock"></i>
                                <a href="{{ route('authentication.forgot-password') }}">Forgot password?</a>
                            </span>
                            <span>
                                Don't have an account?
                                <a href="{{ route('authentication.register') }}">Register</a>
                            </span>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function (eyeIcon) {
            eyeIcon.addEventListener('click', function () {
                const input = document.querySelector(this.getAttribute('toggle'));
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    });
</script>

@stop
