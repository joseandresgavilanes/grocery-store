<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsNotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->blocked) {
            abort(403, "Blocked");
        }
        return $next($request);
    }

    // public function boot(){
    //     Gate::define('admin', function(User $user)){
    //     //Only admin user can add amin

    //     }

    //     //employee

    //     //user
    // }


}
