<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

use App\Models\K\K_Notification;

use Auth, Response;

class SponsorNotificationMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        // 执行动作
        $me = Auth::guard('sponsor')->user();
        $count = K_Notification::where(['owner_id'=>$me->id,'is_read'=>0])->whereIn('notification_category',[9,11])->count();
        view()->share('sponsor_notification_count', $count);

        return $next($request);
    }
}
