<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // kiểm tra có sesssion user_id chưa??
        if (!Session::has('user_id') || Session::get('user_id') == null) {
            return Redirect::to('admin/login');
        } 
        return $next($request);
    }
}
