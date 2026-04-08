<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    
    public function handle(Request $request, Closure $next, $role): Response
{
    if (auth()->check() && auth()->user()->role === $role) {
        return $next($request);
    }

    // If they try to go where they don't belong, send them back
    return redirect('/')->with('error', 'Unauthorized access.');
}
}
