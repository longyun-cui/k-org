<?php

namespace App\Http\Middleware\K;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth, Response;

class KAdminLoginMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $type)
    {
        if(!Auth::guard('admin')->check()) // 未登录
        {
            if($type == "turn")
            {
                return redirect('/admin/login');
            }
            else
            {
                $return["status"] = false;
                $return["log"] = "admin-no-login";
                $return["msg"] = "请先登录";
                return Response::json($return);
            }
        }
        else
        {
            $me = Auth::guard('admin')->user();
            view()->share('me_admin', $me);
        }
        return $next($request);
    }
}
