<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckOfficeWifi
{
    public function handle(Request $request, Closure $next)
    {
        $allowedIps = ['192.168.0.105', '152.59.191.38']; // add LAN + public IP
        $currentIp = $request->ip();

        if (!in_array($currentIp, $allowedIps)) {
            Auth::logout();
            return redirect('/login')->withErrors(['ip' => 'Access only allowed from office Wi-Fi.']);
        }





        if (!in_array($currentIp, $allowedIps)) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'ip' => 'You must be connected to office Wi-Fi (152.59.191.38) to use the system.',
            ]);
        }

        return $next($request);
    }
}
