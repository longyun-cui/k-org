<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

use App\Models\K\K_User;

use Auth, Response;

class SponsorMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('sponsor')->check()) // 未登录
        {
            return redirect('/sponsor/login');
//            $return["status"] = false;
//            $return["log"] = "admin-no-login";
//            $return["msg"] = "请先登录";
//            return Response::json($return);
        }
        else
        {
            $user = Auth::guard('sponsor')->user();
            view()->share('sponsor_data', $user);
        }
        return $next($request);

    }
}
