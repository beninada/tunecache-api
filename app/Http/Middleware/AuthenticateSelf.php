<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateSelf
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $loggedInUser = Auth::user();
        $requestedUserId = $request->route('user_id');

        if (!$loggedInUser || !$requestedUserId || $requestedUserId != $loggedInUser->id) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}
