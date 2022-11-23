<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Admin as Middleware;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        //if (auth()->user()!=null && (auth()->user()->level == 0 || auth()->user()->level == 1)) {
        if (auth()->user()!=null && auth()->user()->level == 0 ) {
            return $next($request);
        }
        if (auth()->user()!=null && auth()->user()->level == 1 ) {
            return redirect('/admin');
        }else{
            return redirect('/');
        }
    }
}
