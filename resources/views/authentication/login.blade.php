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
            <div class="card" style="background-image: url('{{ asset('assets/img/doodle1.png') }}')">
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



<!-- Enhanced Stylized Login Notice -->
<div class="container-fluid">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8 col-lg-6">
            <div class="alert alert-dismissible fade show border-0 shadow-lg enhanced-notice" id="loginNotice">
                <button type="button" class="close text-white enhanced-close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                
                <!-- Decorative elements -->
                <div class="notice-decoration">
                    <div class="floating-particle particle-1"></div>
                    <div class="floating-particle particle-2"></div>
                    <div class="floating-particle particle-3"></div>
                </div>
                
                <div class="d-flex align-items-start position-relative">
                    <div class="notice-icon mr-4">
                        <div class="icon-wrapper">
                            <i class="fas fa-bullhorn fa-2x"></i>
                            <div class="icon-glow"></div>
                        </div>
                    </div>
                    <div class="notice-content flex-grow-1">
                        <div class="header-section mb-3">
                            <h5 class="font-weight-bold mb-2 text-white notice-title">
                                <i class="fas fa-heart mr-2 beating-heart" style="color: #ff6b6b;"></i>
                                Dear Parents/Guardians
                            </h5>
                            <div class="title-underline"></div>
                        </div>
                        
                        <div class="welcome-section mb-3">
                            <p class="mb-2 lead welcome-text">
                                Welcome to the <span class="highlight-text">new MyDiaree!</span>
                                <i class="fas fa-sparkles ml-2 text-warning"></i>
                            </p>
                            <p class="mb-3 feature-text">
                                It's <span class="feature-highlight">faster</span>, 
                                <span class="feature-highlight">fresher</span>, and 
                                <span class="feature-highlight">easier to use</span> — 
                                and your login details are still the same.
                            </p>
                        </div>
                        
                        <div class="help-section p-4 mt-3">
                            <div class="d-flex align-items-center">
                                <div class="help-icon-wrapper mr-3">
                                    <i class="fas fa-headset fa-lg"></i>
                                    <div class="help-icon-pulse"></div>
                                </div>
                                <div class="help-content">
                                    <div class="help-title">Having difficulty logging in?</div>
                                    <div class="help-subtitle">Just check in with our reception team.</div>
                                </div>
                            </div>
                            <div class="contact-cta mt-2">
                                <i class="fas fa-phone-alt mr-2"></i>
                                <span>We're here to help!</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom accent -->
                <div class="notice-accent"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Notice Styling */
.enhanced-notice {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    background-size: 200% 200%;
    border-radius: 20px !important;
    color: white;
    position: relative;
    overflow: hidden;
    animation: slideInDown 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
               gradientShift 6s ease-in-out infinite;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Enhanced Close Button */
.enhanced-close {
    opacity: 0.9;
    font-size: 1.5rem;
    transition: all 0.3s ease;
    z-index: 10;
    position: relative;
}

.enhanced-close:hover {
    opacity: 1;
    transform: scale(1.1) rotate(90deg);
    color: #ff6b6b !important;
}

/* Decorative Floating Particles */
.notice-decoration {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    pointer-events: none;
    overflow: hidden;
}

.floating-particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
}

.particle-1 {
    width: 8px;
    height: 8px;
    top: 20%;
    right: 15%;
    animation-delay: 0s;
}

.particle-2 {
    width: 6px;
    height: 6px;
    top: 60%;
    right: 25%;
    animation-delay: 2s;
}

.particle-3 {
    width: 10px;
    height: 10px;
    top: 40%;
    right: 5%;
    animation-delay: 4s;
}

/* Icon Enhancements */
.icon-wrapper {
    position: relative;
    display: inline-block;
}

.icon-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

/* Title Styling */
.notice-title {
    font-size: 1.3rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    letter-spacing: 0.5px;
}

.title-underline {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #ff6b6b, #ffd93d);
    border-radius: 2px;
    animation: expandWidth 1s ease-out 0.5s both;
}

/* Beating Heart Animation */
.beating-heart {
    animation: heartbeat 1.5s ease-in-out infinite;
}

/* Welcome Section */
.welcome-text {
    font-size: 1.15rem;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    line-height: 1.4;
}

.highlight-text {
    background: linear-gradient(45deg, #ffd93d, #ff6b6b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    text-shadow: none;
}

.feature-text {
    font-size: 1rem;
    line-height: 1.5;
    opacity: 0.95;
}

.feature-highlight {
    background: rgba(255, 217, 61, 0.3);
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
    border-bottom: 2px solid #ffd93d;
}

/* Help Section Enhanced */
.help-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-left: 4px solid #ffd93d;
    backdrop-filter: blur(5px);
    transition: all 0.3s ease;
}

.help-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.help-icon-wrapper {
    position: relative;
    display: inline-block;
}

.help-icon-wrapper i {
    color: #ffd93d;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

.help-icon-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    background: rgba(255, 217, 61, 0.3);
    border-radius: 50%;
    animation: helpPulse 2s ease-in-out infinite;
}

.help-title {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 2px;
    color: #fff;
}

.help-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
    color: #f8f9fa;
}

.contact-cta {
    font-size: 0.85rem;
    opacity: 0.8;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

/* Bottom Accent */
.notice-accent {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #ffd93d, #667eea, #764ba2);
    background-size: 300% 100%;
    animation: colorFlow 3s ease-in-out infinite;
}

/* Animations */
@keyframes slideInDown {
    0% {
        transform: translateY(-100px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes gradientShift {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.7;
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 0.3;
    }
}

@keyframes pulse {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.6;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.2);
        opacity: 0.3;
    }
}

@keyframes heartbeat {
    0%, 100% {
        transform: scale(1);
    }
    25% {
        transform: scale(1.1);
    }
    50% {
        transform: scale(1);
    }
    75% {
        transform: scale(1.05);
    }
}

@keyframes expandWidth {
    0% {
        width: 0;
    }
    100% {
        width: 60px;
    }
}

@keyframes helpPulse {
    0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 0.6;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.3);
        opacity: 0.2;
    }
}

@keyframes colorFlow {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .enhanced-notice {
        margin: 0 10px;
        border-radius: 15px !important;
    }
    
    .notice-content {
        padding-left: 0;
    }
    
    .d-flex.align-items-start {
        flex-direction: column;
        text-align: center;
    }
    
    .notice-icon {
        margin: 0 auto 1rem auto;
    }
    
    .help-section {
        padding: 1.5rem !important;
    }
    
    .welcome-text {
        font-size: 1rem;
    }
    
    .notice-title {
        font-size: 1.1rem;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .enhanced-notice {
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
}
</style>





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
