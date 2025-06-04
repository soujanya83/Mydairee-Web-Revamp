@extends('layout.authentication')
@section('title', 'Set New Password')


@section('content')

<div class="vertical-align-wrap">
    <div class="vertical-align-middle auth-main">

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin-top:-220px">
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
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:-220px">
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
                    <p class="lead">Set New Password</p>
                </div>
                <div class="body">
                    <form class="form-auth-small" action="{{ route('reset_password.update') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="control-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="control-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="control-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                            @error('password_confirmation')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">Update Password</button>
                    </form>



                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('password_confirmation').addEventListener('input', function () {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    if (password !== confirm) {
        this.setCustomValidity('Passwords do not match.');
    } else {
        this.setCustomValidity('');
    }
});
</script>

@stop
