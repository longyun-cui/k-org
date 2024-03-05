<?php


/*
 * 超级后台
 */
Route::group([], function () {


    // 注册登录
    Route::group([], function () {

        $controller = "AuthController";

        Route::match(['get','post'], 'login', $controller.'@login');
        Route::match(['get','post'], 'logout', $controller.'@logout');

    });


    // 后台管理，需要登录
    Route::group(['middleware' => 'admin'], function () {

        $controller = "IndexController";

        Route::get('/sql-init', $controller.'@sql_init');

        Route::get('/', $controller.'@index');
        Route::get('index', $controller.'@index');


        Route::fallback(function(){
            return response()->view(env('TEMPLATE_ADMIN').'admin.errors.404');
        });


        /*
         * info
         */
        Route::match(['get','post'], '/info/', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/index', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/edit', $controller.'@operate_info_edit');
        Route::match(['get','post'], '/info/password-reset', $controller.'@operate_info_password_reset');




        /*
         * user
         */
        Route::match(['get','post'], '/user/select2_user', $controller.'@operate_user_select2_user');

        Route::match(['get','post'], '/user/user-create', $controller.'@operate_user_user_create');
        Route::match(['get','post'], '/user/user-edit', $controller.'@operate_user_user_edit');

        Route::match(['get','post'], '/user/user-all-list', $controller.'@view_user_all_list');
        Route::match(['get','post'], '/user/user-org-list', $controller.'@view_user_org_list');
        Route::match(['get','post'], '/user/user-sponsor-list', $controller.'@view_user_sponsor_list');
        Route::match(['get','post'], '/user/user-individual-list', $controller.'@view_user_individual_list');

        Route::match(['get','post'], '/user/user-login', $controller.'@operate_user_user_login');
        Route::match(['get','post'], '/user/org-login', $controller.'@operate_user_org_login');
        Route::match(['get','post'], '/user/sponsor-login', $controller.'@operate_user_sponsor_login');

        Route::match(['get','post'], '/user/org-delete', $controller.'@operate_user_org_delete');

        Route::match(['get','post'], '/user/user-admin-disable', $controller.'@operate_user_admin_disable');
        Route::match(['get','post'], '/user/user-admin-enable', $controller.'@operate_user_admin_enable');


        Route::match(['get','post'], '/user/change-password', $controller.'@operate_user_change_password');




        /*
         * business
         */
        // item
        Route::match(['get','post'], '/item/item-list', $controller.'@view_item_item_list');
        Route::match(['get','post'], '/item/item-all-list', $controller.'@view_item_all_list');
        Route::match(['get','post'], '/item/item-article-list', $controller.'@view_item_article_list');
        Route::match(['get','post'], '/item/item-activity-list', $controller.'@view_item_activity_list');
        Route::match(['get','post'], '/item/item-advertising-list', $controller.'@view_item_advertising_list');

        Route::match(['get','post'], '/item/item-my-list', $controller.'@view_item_my_list');


        Route::match(['get','post'], '/item/item-create', $controller.'@operate_item_item_create');
        Route::match(['get','post'], '/item/item-edit', $controller.'@operate_item_item_edit');


        Route::match(['get','post'], '/item/item-get', $controller.'@operate_item_item_get');
        Route::match(['get','post'], '/item/item-delete', $controller.'@operate_item_item_delete');
        Route::match(['get','post'], '/item/item-restore', $controller.'@operate_item_item_restore');
        Route::match(['get','post'], '/item/item-delete-permanently', $controller.'@operate_item_item_delete_permanently');
        Route::match(['get','post'], '/item/item-publish', $controller.'@operate_item_item_publish');

        Route::match(['get','post'], '/item/item-admin-disable', $controller.'@operate_item_admin_disable');
        Route::match(['get','post'], '/item/item-admin-enable', $controller.'@operate_item_admin_enable');





        Route::match(['get','post'], '/user/agent-list', $controller.'@view_user_agent_list');
        Route::match(['get','post'], '/user/client-list', $controller.'@view_user_client_list');

        Route::match(['get','post'], '/user/client-login', $controller.'@operate_user_client_login');

        Route::match(['get','post'], '/user/agent', $controller.'@view_user_agent');
        Route::match(['get','post'], '/user/client', $controller.'@view_user_client');

        Route::match(['get','post'], '/user/agent/client-list', $controller.'@view_user_agent_client_list');
        Route::match(['get','post'], '/user/client/keyword-list', $controller.'@view_user_client_keyword_list');

        Route::match(['get','post'], '/user/agent-recharge', $controller.'@operate_user_agent_recharge');
        Route::match(['get','post'], '/user/agent-recharge-limit-close', $controller.'@operate_user_agent_recharge_limit_close');
        Route::match(['get','post'], '/user/agent-recharge-limit-open', $controller.'@operate_user_agent_recharge_limit_open');
        Route::match(['get','post'], '/user/agent-sub-agent-close', $controller.'@operate_user_agent_sub_agent_close');
        Route::match(['get','post'], '/user/agent-sub-agent-open', $controller.'@operate_user_agent_sub_agent_open');

        Route::match(['get','post'], '/user/client-delete', $controller.'@operate_user_client_delete');




        /*
         * statistic
         */
        Route::match(['get','post'], '/statistic', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/index', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/statistic-index', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/statistic-user', $controller.'@view_statistic_user');
        Route::match(['get','post'], '/statistic/statistic-item', $controller.'@view_statistic_item');
        Route::match(['get','post'], '/statistic/statistic-all-list', $controller.'@view_statistic_all_list');







    });


});
