<?php

namespace App\Http\Middleware\K;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth, Response;

class KSuperLoginMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $type)
    {
        if(!Auth::guard('super')->check()) // 未登录
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
            $me = Auth::guard('super')->user();
            view()->share('me_super', $me);
        }
        return $next($request);
    }
}
