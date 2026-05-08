<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->is('api') || $request->is('api/*')) {
                $bearerToken = $request->bearerToken();

                return response()->json([
                    'status' => 'error',
                    'reason' => blank($bearerToken) ? 'login_issue' : 'token_issue',
                    'message' => blank($bearerToken)
                        ? 'User is not logged in. Bearer token is missing.'
                        : 'Bearer token is invalid, expired, or revoked.',
                ], 401);
            }

            return redirect()->route('login'); // or return abort(401);
        }

        return $next($request);
    }
}
