<?php

namespace App\Http\Middleware;

use App\Models\WifiIP_Model;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckOfficeWifi
{
    // public function handle(Request $request, Closure $next)
    // {
    //     $user = Auth::user();
    //     if ($user && ($user->wifi_status == 1 ||  $user->type === 'Superadmin')) {
    //         return $next($request);
    //     }
    //     $allowedIps = WifiIP_Model::where('status', 1)->pluck('wifi_ip')->toArray();
    //     $currentIp = $request->ip();
    //     if (!in_array($currentIp, $allowedIps)) {
    //         Auth::logout();
    //         return redirect('/login')->withErrors([
    //             'ip' => 'You must be connected to office Wi-Fi to use the system.',
    //         ]);
    //     }
    //     return $next($request);
    // }


    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Superadmin bypass
        if ($user && $user->userType === 'Superadmin') {
            return $next($request);
        }

        // Staff WiFi access check
        if ($user && $user->wifi_status == 1) {
            // Agar expiry time set hai aur abhi cross ho gaya hai
            if ($user->wifi_access_until && now()->greaterThan($user->wifi_access_until)) {
                // Auto expire
                $user->wifi_status = 0;
                $user->wifi_access_until = null;
                $user->save();

                Auth::logout();
                return redirect('/login')->withErrors([
                    'wifi' => 'Your Login Access has expired.',
                ]);
            }

            return $next($request); // Access allow
        }

        // Allowed IP check
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
