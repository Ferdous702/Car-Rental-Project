<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        //$user = Auth::user();
        //return response()->json($user);
        $user = $request->user();
        // Check if the user's role matches the required role
        if ($role === 'admin' && $user->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        if ($role === 'customer' && $user->role !== 'customer') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
