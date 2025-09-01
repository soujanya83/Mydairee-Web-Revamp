<?php

namespace App\Http\Middleware;

use App\Models\WifiIP_Model;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckOfficeWifi
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->wifi_status == 1) {
            return $next($request);
        }
        $allowedIps = WifiIP_Model::where('status', 1)->pluck('wifi_ip')->toArray();
        $currentIp = $request->ip();
        if (!in_array($currentIp, $allowedIps)) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'ip' => 'You must be connected to office Wi-Fi to use the system.',
            ]);
        }
        return $next($request);
    }
}
