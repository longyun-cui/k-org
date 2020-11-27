<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    echo("【url()->full()】  --:--  ".url()->full().'<br>');
    echo("【url()->current()】  --:--  ".url()->current().'<br>');
    echo("【url()->previous()】  --:--  ".url()->previous().'<br>');
    echo("【request()->url()】  --:--  ".request()->url().'<br>');
    echo("【request()->path()】  --:--  ".request()->path().'<br>');
    echo("【request()->getUri()】  --:--  ".request()->getUri().'<br>');
    echo("【request()->getRequestUri()】  --:--  ".request()->getRequestUri().'<br>');
    dd();

    return view('welcome');
});


/*
 * Common 通用功能
 */
Route::group(['prefix' => 'common'], function () {

    $controller = "CommonController";

    // 验证码
    Route::match(['get','post'], 'change_captcha', $controller.'@change_captcha');

    //
    Route::get('dataTableI18n', function () {
        return trans('pagination.i18n');
    });
});




/*
 * 管理员
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    require(__DIR__ . '/Admin/route.php');
});


/*
 * 组织
 */
Route::group(['prefix' => 'org', 'namespace' => 'Org'], function () {
    require(__DIR__ . '/Org/route.php');
});


/*
 * 赞助商
 */
Route::group(['prefix' => 'sponsor', 'namespace' => 'Sponsor'], function () {
    require(__DIR__ . '/Sponsor/route.php');
});


/*
 * 组织
 */
Route::group(['namespace' => 'Frontend'], function () {
    require(__DIR__ . '/Frontend/route.php');
});





/*
 * Root Frontend
 */
Route::group(['namespace' => 'Front'], function () {

});




/*
 * auth
 */
Route::match(['get','post'], 'login', 'Home\AuthController@user_login');
Route::match(['get','post'], 'logout', 'Home\AuthController@user_logout');
Route::match(['get','post'], 'register', 'Home\AuthController@user_register');
Route::match(['get','post'], 'activation', 'Home\AuthController@activation');




/*
 * Home Backend
 */
Route::group(['prefix' => 'home', 'namespace' => 'Home'], function () {

    /*
     * 需要登录
     */
    Route::group(['middleware' => ['home','notification']], function () {

        $controller = 'HomeController';

        Route::get('/404', $controller.'@view_404');

        Route::get('/', $controller.'@index');



        // 【info】
        Route::group(['prefix' => 'info'], function () {

            $controller = 'HomeController';

            Route::get('index', $controller.'@info_index');
            Route::match(['get','post'], 'edit', $controller.'@infoEditAction');

            Route::match(['get','post'], 'password/reset', $controller.'@passwordResetAction');

        });


        // 内容
        Route::group(['prefix' => 'item'], function () {

            $controller = 'ItemController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('share', $controller.'@shareAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');


            // 内容管理
            Route::group(['prefix' => 'content'], function () {

                $controller = 'ItemController';

                Route::match(['get','post'], '/', $controller.'@content_viewIndex');
                Route::match(['get','post'], '/menutype', $controller.'@content_menutype_viewIndex');
                Route::match(['get','post'], '/timeline', $controller.'@content_timeline_viewIndex');

                Route::match(['get','post'], 'edit', $controller.'@content_editAction');
                Route::match(['get','post'], 'edit/menutype', $controller.'@content_menutype_editAction');
                Route::match(['get','post'], 'edit/timeline', $controller.'@content_timeline_editAction');

                Route::post('get', $controller.'@content_getAction');
                Route::post('delete', $controller.'@content_deleteAction');
                Route::post('enable', $controller.'@content_enableAction');
                Route::post('disable', $controller.'@content_disableAction');

            });

            // 时间线类型
            Route::group(['prefix' => 'point'], function () {

                $controller = 'PointController';

                Route::match(['get','post'], '/', $controller.'@viewList');
                Route::get('create', $controller.'@createAction');
                Route::match(['get','post'], 'edit', $controller.'@editAction');
                Route::match(['get','post'], 'list', $controller.'@viewList');
                Route::post('delete', $controller.'@deleteAction');
                Route::post('enable', $controller.'@enableAction');
                Route::post('disable', $controller.'@disableAction');

            });

            Route::get('select2_menus', $controller.'@select2_menus');

        });


        // 作者
        Route::group(['prefix' => 'course'], function () {

            $controller = 'CourseController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');

            // 作者
            Route::group(['prefix' => 'content'], function () {

                $controller = 'CourseController';

                Route::match(['get','post'], '/', $controller.'@course_content_view_index');
                Route::match(['get','post'], 'edit', $controller.'@course_content_editAction');
                Route::post('get', $controller.'@course_content_getAction');
                Route::post('delete', $controller.'@course_content_deleteAction');
            });

            Route::get('select2_menus', $controller.'@select2_menus');

        });



        // 收藏
        Route::group(['prefix' => 'collect'], function () {

            $controller = 'OtherController';

            Route::match(['get','post'], 'course/list', $controller.'@collect_course_viewList');
            Route::match(['get','post'], 'chapter/list', $controller.'@collect_chapter_viewList');
            Route::post('course/delete', $controller.'@collect_course_deleteAction');
            Route::post('chapter/delete', $controller.'@collect_chapter_deleteAction');

        });

        // 点赞
        Route::group(['prefix' => 'favor'], function () {

            $controller = 'OtherController';

            Route::match(['get','post'], 'course/list', $controller.'@favor_course_viewList');
            Route::match(['get','post'], 'chapter/list', $controller.'@favor_chapter_viewList');
            Route::post('course/delete', $controller.'@favor_course_deleteAction');
            Route::post('chapter/delete', $controller.'@favor_chapter_deleteAction');

        });

        // 消息
        Route::group(['prefix' => 'notification'], function () {

            $controller = 'NotificationController';

            Route::get('comment', $controller.'@comment');
            Route::get('favor', $controller.'@favor');

        });


    });

});




/*
 * Org-Admin Backend
 */
Route::group(['prefix' => 'org-admin', 'namespace' => 'OrgAdmin'], function () {


    /*
     * auth
     */
    $authController = 'AuthController';
    Route::match(['get','post'], 'login', $authController.'@org_admin_login');
    Route::match(['get','post'], 'logout', $authController.'@org_admin_logout');
    Route::match(['get','post'], 'register', $authController.'@org_admin_register');
    Route::match(['get','post'], 'activation', $authController.'@org_admin_activation');


    /*
     * 需要登录
     */
    Route::group(['middleware' => ['org-admin']], function () {

        $controller = 'HomeController';

        Route::get('/', $controller.'@root');
        Route::get('/404', $controller.'@view_404');


        // 【info】
        Route::group(['prefix' => 'info'], function () {

            $controller = 'HomeController';

            Route::get('index', $controller.'@info_index');
            Route::match(['get','post'], 'edit', $controller.'@infoEditAction');

            Route::match(['get','post'], 'password/reset', $controller.'@passwordResetAction');

        });


        // 内容
        Route::group(['prefix' => 'item'], function () {

            $controller = 'ItemController';

            Route::get('/', $controller.'@index');
            Route::get('create', $controller.'@createAction');
            Route::match(['get','post'], 'edit', $controller.'@editAction');
            Route::match(['get','post'], 'list', $controller.'@viewList');
            Route::post('delete', $controller.'@deleteAction');
            Route::post('share', $controller.'@shareAction');
            Route::post('enable', $controller.'@enableAction');
            Route::post('disable', $controller.'@disableAction');


            Route::get('select2_menus', $controller.'@select2_menus');

        });





        // 收藏
        Route::group(['prefix' => 'collect'], function () {

            $controller = 'OtherController';

            Route::match(['get','post'], 'course/list', $controller.'@collect_course_viewList');
            Route::match(['get','post'], 'chapter/list', $controller.'@collect_chapter_viewList');
            Route::post('course/delete', $controller.'@collect_course_deleteAction');
            Route::post('chapter/delete', $controller.'@collect_chapter_deleteAction');

        });

        // 点赞
        Route::group(['prefix' => 'favor'], function () {

            $controller = 'OtherController';

            Route::match(['get','post'], 'course/list', $controller.'@favor_course_viewList');
            Route::match(['get','post'], 'chapter/list', $controller.'@favor_chapter_viewList');
            Route::post('course/delete', $controller.'@favor_course_deleteAction');
            Route::post('chapter/delete', $controller.'@favor_chapter_deleteAction');

        });

        // 消息
        Route::group(['prefix' => 'notification'], function () {

            $controller = 'NotificationController';

            Route::get('comment', $controller.'@comment');
            Route::get('favor', $controller.'@favor');

        });


    });

});

