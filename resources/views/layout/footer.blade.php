<footer class="footer-bar" style="background-image: url('{{ asset('assets/img/doodleold.jpg') }}')">
    <div class="container-fluid text-center py-2">
        <small>&copy; {{ date('Y') }} Mydiaree. All rights reserved.</small>

        @if(Auth::user()->wifi_access_until != null)
        <span style="color:#dc3545;margin-left:300px"><b>Access Expires:</b></span>
        <b>{{ \Carbon\Carbon::parse(Auth::user()->wifi_access_until)->format('d M Y, h:i A') }}</b>
        @endif
    </div>
</footer>

<style>
    .footer-bar {
        background-color: #fff;
        border-top: 0px solid #e5e5e5;
        position: fixed;
        bottom: 5px;
        left: 5px;
        width: 99.3%;
        z-index: 999;
    }
</style>
