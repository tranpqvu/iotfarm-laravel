<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Student
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
        if (auth()->user()!=null && auth()->user()->level == 2) {
            return $next($request);
        }else{
            if (auth()->user()!=null && (auth()->user()->level == 0 || auth()->user()->level == 1)) {
                return redirect('/admin');
            }else{
                return redirect('/');
            }
        }       
    }
}
