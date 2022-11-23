<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Manager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()!=null && (auth()->user()->level == 0 || auth()->user()->level == 1)) {
            return $next($request);
        }
        return redirect('/');
        //return $next($request);
    }
}
