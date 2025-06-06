@extends('layout.authentication')
@section('title', 'Set New Password')


@section('content')

<div class="vertical-align-wrap" style="">
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
            <div class="card" style="background-image: url('{{ asset('assets/img/doodle1.png') }}')">
                <div class="header">
                    <p class="lead">Set New Password</p>
                </div>
                <div class="body">
                    <form class="form-auth-small" action="{{ route('reset_password.update') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="control-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                                required>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="password" class="control-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"
                                style="position:absolute; top:38px; right:15px; cursor:pointer;"></span>
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="password_confirmation" class="control-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                            <span toggle="#password_confirmation" class="fa fa-fw fa-eye field-icon toggle-password"
                                style="position:absolute; top:38px; right:15px; cursor:pointer;"></span>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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
<script>
    document.querySelectorAll('.toggle-password').forEach(function (icon) {
        icon.addEventListener('click', function () {
            const input = document.querySelector(this.getAttribute('toggle'));
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>

@stop
