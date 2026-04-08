<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
//     public function handle(Request $request, Closure $next)
// {
//     if (auth()->check() && auth()->user()->role === 'admin') {
//         return $next($request);
//     }

//     // If not admin, send them back to the order page with a warning
//     return redirect('/admin/orders')->with('error', 'Access Denied: Admins Only!');
// }
public function handle(Request $request, Closure $next): Response
{
    if (auth()->check() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    // If not admin, send them back to the dashboard or show error
    return redirect('/dashboard')->with('error', 'You do not have admin access.');
}
}
