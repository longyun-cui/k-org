<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,


        'k.user.login' => \App\Http\Middleware\K\KUserLoginMiddleware::class,
        'k.super.login' => \App\Http\Middleware\K\KSuperLoginMiddleware::class,
        'k.admin.login' => \App\Http\Middleware\K\KAdminLoginMiddleware::class,


        'wx.share' => \App\Http\Middleware\WXShareMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'org' => \App\Http\Middleware\OrgMiddleware::class,
        'org.notification' => \App\Http\Middleware\OrgNotificationMiddleware::class,
        'sponsor' => \App\Http\Middleware\SponsorMiddleware::class,
        'sponsor.notification' => \App\Http\Middleware\SponsorNotificationMiddleware::class,
        'home' => \App\Http\Middleware\HomeMiddleware::class,
        'notification' => \App\Http\Middleware\NotificationMiddleware::class,
        'login' => \App\Http\Middleware\LoginMiddleware::class,
        'login.turn' => \App\Http\Middleware\TurnToLoginMiddleware::class,

        'org-admin' => \App\Http\Middleware\OrgAdminMiddleware::class,
    ];
}
