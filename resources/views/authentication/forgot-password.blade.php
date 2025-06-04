@extends('layout.authentication')
@section('title', 'Forget Password')


@section('content')

<div class="vertical-align-wrap">
    <div class="vertical-align-middle auth-main">
        <div class="auth-box">
            <div class="top">
                <img src="{{ asset('assets/img/MYDIAREE-new-logo.png') }}" alt="Lucid" style="background-color: aliceblue;padding: 10px;
    width: 180px;">
            </div>
            <div class="card">
                <div class="header">
                    <p class="lead">Recover my password</p>
                </div>
                <div class="body">
                    <p>Please enter your email address below to receive instructions(OTP) for resetting password.</p>
                    <form class="form-auth-small" action="{{route('reset_password')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="control-label">Enter Email</label>
                            <input type="email" class="form-control" id="email" placeholder="" name="email">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">RESET PASSWORD</button>
                        <div class="bottom">
                            <span class="helper-text">Know your password? <a
                                    href="{{route('authentication.login')}}">Login</a></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
