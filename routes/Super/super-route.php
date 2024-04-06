<?php


/*
 * SUPER 超级管理员 - 默认
 */
// 注册登录
Route::group([], function () {


    // 不存在的域名
    Route::fallback(function(){
        return response()->view(env('TEMPLATE_K_SUPER_FRONT').'errors.404');
    });

    $controller = "SuperIndexController";

    Route::get('/', $controller.'@view_index');


});




/*
 * SUPER 超级管理员 - 管理员后台
 */
Route::group(['prefix'=>'admin'], function () {


    // 不存在的域名
    Route::fallback(function(){
        return response()->view(env('TEMPLATE_K_SUPER_ADMIN').'errors.404');
    });


    // 注册登录
    Route::group([], function () {

        $controller = "SuperAuthController";

        Route::match(['get','post'], 'login', $controller.'@login');
        Route::match(['get','post'], 'logout', $controller.'@logout');

    });


    // 后台管理，需要登录
    Route::group(['middleware' => 'k.super.login:turn'], function () {


        $controller = "SuperAdminController";

        Route::get('/sql-init', $controller.'@sql_init');

        Route::get('/', $controller.'@view_admin_index');




        /*
         * info
         */
        Route::match(['get','post'], '/info/', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/index', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/edit', $controller.'@operate_info_edit');
        Route::match(['get','post'], '/info/password-reset', $controller.'@operate_info_password_reset');




        /*
         * select2
         */
        Route::match(['get','post'], '/select2_user', $controller.'@operate_select2_user');




        /*
         * user
         */
        Route::match(['get','post'], '/user/select2_user', $controller.'@operate_user_select2_user');

        Route::match(['get','post'], '/user/user-create', $controller.'@operate_user_user_create');
        Route::match(['get','post'], '/user/user-edit', $controller.'@operate_user_user_edit');

        Route::match(['get','post'], '/user/user-list', $controller.'@view_user_list');
        Route::match(['get','post'], '/user/user-list-for-individual', $controller.'@view_user_list_for_individual');
        Route::match(['get','post'], '/user/user-list-for-org', $controller.'@view_user_list_for_org');

        Route::match(['get','post'], '/user/user-login', $controller.'@operate_user_login');
        Route::match(['get','post'], '/user/user-login-for-individual', $controller.'@operate_user_login_for_individual');
        Route::match(['get','post'], '/user/user-login-for-org', $controller.'@operate_user_login_for_org');

        Route::match(['get','post'], '/user/org-delete', $controller.'@operate_user_org_delete');

        Route::match(['get','post'], '/user/user-admin-disable', $controller.'@operate_user_admin_disable');
        Route::match(['get','post'], '/user/user-admin-enable', $controller.'@operate_user_admin_enable');


        Route::match(['get','post'], '/user/change-password', $controller.'@operate_user_change_password');

        // 编辑-信息
        Route::post('/user/user-info-text-set', $controller.'@operate_user_info_text_set');
        Route::post('/user/user-info-time-set', $controller.'@operate_user_info_time_set');
        Route::post('/user/user-info-radio-set', $controller.'@operate_user_info_option_set');
        Route::post('/user/user-info-select-set', $controller.'@operate_user_info_option_set');
        Route::post('/user/user-info-select2-set', $controller.'@operate_user_info_option_set');




        /*
         * business
         */
        // item
        Route::match(['get','post'], '/item/item-list', $controller.'@view_item_item_list');
        Route::match(['get','post'], '/item/item-list-for-all', $controller.'@view_item_list_for_all');
        Route::match(['get','post'], '/item/item-list-for-article', $controller.'@view_item_list_for_article');
        Route::match(['get','post'], '/item/item-list-for-activity', $controller.'@view_item_list_for_activity');
        Route::match(['get','post'], '/item/item-list-for-advertising', $controller.'@view_item_list_for_advertising');

        Route::match(['get','post'], '/item/item-list-for-mine', $controller.'@view_item_list_for_mine');


        Route::match(['get','post'], '/item/item-create', $controller.'@operate_item_item_create');
        Route::match(['get','post'], '/item/item-edit', $controller.'@operate_item_item_edit');


        Route::match(['get','post'], '/item/item-get', $controller.'@operate_item_item_get');
        Route::match(['get','post'], '/item/item-delete', $controller.'@operate_item_item_delete');
        Route::match(['get','post'], '/item/item-restore', $controller.'@operate_item_item_restore');
        Route::match(['get','post'], '/item/item-delete-permanently', $controller.'@operate_item_item_delete_permanently');
        Route::match(['get','post'], '/item/item-publish', $controller.'@operate_item_item_publish');

        Route::match(['get','post'], '/item/item-admin-disable', $controller.'@operate_item_admin_disable');
        Route::match(['get','post'], '/item/item-admin-enable', $controller.'@operate_item_admin_enable');

        // 订单-基本信息
        Route::post('/item/item-info-text-set', $controller.'@operate_item_item_info_text_set');
        Route::post('/item/item-info-time-set', $controller.'@operate_item_item_info_time_set');
        Route::post('/item/item-info-radio-set', $controller.'@operate_item_item_info_option_set');
        Route::post('/item/item-info-select-set', $controller.'@operate_item_item_info_option_set');
        Route::post('/item/item-info-select2-set', $controller.'@operate_item_item_info_option_set');
        Route::post('/item/item-info-client-set', $controller.'@operate_item_item_info_client_set');
        Route::post('/item/item-info-car-set', $controller.'@operate_item_item_info_car_set');
        // 订单-附件
        Route::post('/item/item-info-attachment-set', $controller.'@operate_item_item_info_attachment_set');
        Route::post('/item/item-info-attachment-delete', $controller.'@operate_item_item_info_attachment_delete');








        /*
         * business
         */
        // notification
        Route::match(['get','post'], '/notification/notification-list-for-all', $controller.'@view_notification_list_for_all');








        /*
         * statistic
         */
        Route::match(['get','post'], '/statistic', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/index', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/statistic-index', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/statistic-user', $controller.'@view_statistic_user');
        Route::match(['get','post'], '/statistic/statistic-item', $controller.'@view_statistic_item');
        Route::match(['get','post'], '/statistic/statistic-list', $controller.'@view_statistic_list');







    });


});
