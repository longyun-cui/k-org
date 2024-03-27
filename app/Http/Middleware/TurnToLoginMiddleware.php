<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Administrator;
use Auth, Response, URL, Input;

class TurnToLoginMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if(!Auth::check()) // 未登录
        {
            $state = urlencode(url()->full());
//            $state  = url()->previous();

            if(is_weixin())
            {
//                return redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1bb8231a70478cba&redirect_uri=http%3A%2F%2Fsoftdoc.cn%2Fweixin%2Fauth&response_type=code&scope=snsapi_userinfo&state='.$url.'#wechat_redirect');
                $app_id = env('WECHAT_LOOKWIT_APPID');
                $app_secret = env('WECHAT_LOOKWIT_SECRET');
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri=http%3A%2F%2Fwww.k-org.cn%2Fweixin%2Fauth&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
                return redirect($url);
            }
            else
            {
//                return redirect('https://open.weixin.qq.com/connect/qrconnect?appid=wxaf993c7aace04371&redirect_uri=http%3A%2F%2Fsoftdoc.cn%2Fweixin%2Flogin&response_type=code&scope=snsapi_login&state='.$url.'#wechat_redirect');
                $app_id = env('WECHAT_WEBSITE_K_APPID');
                $app_secret = env('WECHAT_WEBSITE_K_SECRET');
                $url = "https://open.weixin.qq.com/connect/qrconnect?appid={$app_id}&redirect_uri=http%3A%2F%2Fwww.k-org.cn%2Fweixin%2Flogin&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
                return redirect($url);
            }
        }
        return $next($request);

    }
}
