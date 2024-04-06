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



Route::group(['domain'=>env('DOMAIN_ROOT')], function(){
    Route::get('{all}', function(){
        return Redirect::away(env('DOMAIN_WWW').ltrim(Request::path(),'/'),301);
    })->where('all','.*');
});




/*
 * Common 通用功能
 */
Route::group(['prefix'=>'common'], function () {

    $controller = "CommonController";

    // 验证码
    Route::match(['get','post'], 'change_captcha', $controller.'@change_captcha');

    //
    Route::get('dataTableI18n', function () {
        return trans('pagination.i18n');
    });
});




/*
 * SUPER
 */
Route::group(['domain'=>'super.'.env('DOMAIN_ROOT'), 'namespace'=>'Super'], function () {
    require(__DIR__ . '/Super/super-route.php');
});


/*
 * ORG
 */
Route::group(['domain'=>'org.'.env('DOMAIN_ROOT'), 'namespace'=>'Org'], function () {
    require(__DIR__ . '/Org/org-route.php');
});


/*
 * WWW
 */
Route::group(['domain'=>'www.'.env('DOMAIN_ROOT'), 'namespace'=>'WWW'], function () {
    require(__DIR__ . '/WWW/www-route.php');
});


