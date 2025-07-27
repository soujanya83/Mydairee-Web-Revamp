@extends('layout.authentication')
@section('title', 'Verify OTP')


@section('content')


@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="">
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
<div class="alert alert-success alert-dismissible fade show" role="alert" style="">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
<div class="vertical-align-wrap">
    <div class="vertical-align-middle auth-main">



        <div class="auth-box">
            <div class="top">
                <img src="{{url('/')}}/assets/img/MYDIAREE-new-logo.png" alt="Lucid"
                    style="background-color: aliceblue;padding: 10px;width: 180px;">
            </div>
            <div class="card" style="background-image: url('{{ asset('assets/img/doodle1.png') }}')">
                <div class="header">
                    <p class="lead">Verify OTP </p>
                </div>
                <div class="body">
                    <form class="form-auth-small" action="{{ route('verify_otp.submit') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="otp" class="control-label">Enter OTP</label>
                            <input type="text" class="form-control @error('otp') is-invalid @enderror" id="otp"
                                name="otp" pattern="\d*" maxlength="6"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('otp') }}">

                            {{-- Show validation error --}}
                            @error('otp')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                            {{-- Show custom session error (e.g., "OTP doesn't match") --}}
                            @if (session('otp_error'))
                            <small class="text-danger">{{ session('otp_error') }}</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">Verify OTP</button>
                    </form>
                    <div class="bottom">
                        <span>
                            Didn't receive the OTP?
                            <form action="{{ route('resend_otp') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit"
                                    style="border:none; background:none; color:#007bff; padding:0; margin:0; cursor:pointer;">
                                    Resend
                                </button>
                            </form>
                        </span>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

@stop
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
    const otp = document.getElementById('otp').value;
    if (!/^\d{6}$/.test(otp)) {
        e.preventDefault();
        alert('Please enter a valid 6-digit OTP.');
    }
    });
</script>
