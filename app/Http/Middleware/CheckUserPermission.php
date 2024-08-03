<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissionType
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permissionType)
    {
        $user = Auth::user();

        if ($user && $user->permissions()->where('type', $permissionType)->exists()) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
