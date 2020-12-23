<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

use App\Models\K\K_Notification;

use Auth, Response;

class NotificationMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        // 执行动作
        $me = Auth::user();
        $count = K_Notification::where(['is_read'=>0,'notification_category'=>11,'user_id'=>$me->id])->count();
        if(!$count) $count = '';
        view()->share('notification_count', $count);

        return $next($request);
    }
}
