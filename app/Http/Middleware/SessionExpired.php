<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionExpired
{
    public function handle($request, Closure $next)
    {
        // if (Auth::check()) {
        //     $lastActivity = session('last_activity_time');

        //     if ($lastActivity && Carbon::now()->diffInMinutes($lastActivity) > config('session.lifetime')) {
        //         Auth::logout();
        //         session()->flush();
        //         return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        //     }

        //     session(['last_activity_time' => Carbon::now()]);
        // }

        // return $next($request);
    }
}
