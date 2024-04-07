<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

use App\Models\K\K_User;

use Auth, Response;

class OrgMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::guard('org')->check()) // 未登录
        {
            return redirect(env('DOMAIN_WWW'));
//            $return["status"] = false;
//            $return["log"] = "admin-no-login";
//            $return["msg"] = "请先登录";
//            return Response::json($return);
        }
        else
        {
            $user = Auth::guard('org')->user();
            view()->share('org_data', $user);
        }
        return $next($request);

    }
}
