<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CheckUserPermission
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
        // kiểm tra có sesssion user_id chưa?
        if (!Session::has('user_id')) {
            return Redirect::to('admin/login');
        } else if (Session::get('user_type') != 2) {
            return Redirect::to('admin/dashboard');
        }
        return $next($request);
    }
}
