<?php


/*
 * 超级后台
 */
Route::group([], function () {


    // 登录&注册
    Route::group([], function () {

        $controller = "AuthController";

        Route::match(['get','post'], 'login', $controller.'@login');
        Route::match(['get','post'], 'logout', $controller.'@logout');
        Route::match(['get','post'], 'register', $controller.'@register');

    });


    $controller = "OrgIndexController";

    Route::match(['get','post'], '/login-user/', $controller.'@operate_login_user');




    // 后台管理，需要登录
    Route::group(['middleware' => ['org','org.notification']], function () {


        // 不存在的域名
        Route::fallback(function() {
            return response()->view(env('TEMPLATE_K_ORG').'errors.404');
        });


        $controller = "OrgIndexController";

        Route::get('/', $controller.'@view_index');
        Route::get('/404', $controller.'@view_404');


        /*
         * info
         */
        Route::get('/mine/my-info-index', $controller.'@view_my_info_index');
        Route::match(['get','post'], '/mine/my-info-edit', $controller.'@operate_my_info_edit');

        Route::get('/mine/my-info-introduction-index', $controller.'@view_my_info_introduction_index');
        Route::match(['get','post'], '/mine/my-info-introduction-edit', $controller.'@operate_my_info_introduction_edit');

        Route::match(['get','post'], '/mine/my-info-password-reset', $controller.'@operate_my_info_password_reset');

        Route::match(['get','post'], '/info/', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/index', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/edit', $controller.'@operate_info_edit');
        Route::match(['get','post'], '/info/password-reset', $controller.'@operate_info_password_reset');





        Route::match(['get','post'], '/mine/my-card-edit', $controller.'@operate_my_card_edit');
        /*
         * user
         */
        Route::match(['get','post'], '/user/select2_sponsor', $controller.'@operate_user_select2_sponsor');


        Route::match(['get','post'], '/user/my-member-list', $controller.'@view_user_my_member_list');
        Route::match(['get','post'], '/user/my-fans-list', $controller.'@view_user_my_fans_list');
        Route::match(['get','post'], '/user/my-follow-list', $controller.'@view_user_my_follow_list');
        Route::match(['get','post'], '/user/my-sponsor-list', $controller.'@view_user_my_sponsor_list');

        Route::match(['get','post'], '/user/sponsor-delete', $controller.'@operate_user_sponsor_delete');
        Route::match(['get','post'], '/user/sponsor-close', $controller.'@operate_user_sponsor_close');
        Route::match(['get','post'], '/user/sponsor-open', $controller.'@operate_user_sponsor_open');

        Route::match(['get','post'], '/user/relation-sponsor-list', $controller.'@view_user_relation_sponsor_list');
        Route::match(['get','post'], '/user/sponsor-relation', $controller.'@operate_user_sponsor_relation');
        Route::match(['get','post'], '/user/sponsor-relation-bulk', $controller.'@operate_user_sponsor_relation_bulk');

        Route::match(['get','post'], '/user/member-add', $controller.'@operate_user_member_add');
        Route::match(['get','post'], '/user/member-remove', $controller.'@operate_user_member_remove');
        Route::match(['get','post'], '/user/fans-remove', $controller.'@operate_user_fans_remove');




        Route::match(['get','post'], '/mine/user/select2_sponsor', $controller.'@operate_mine_user_select2_sponsor');


        Route::match(['get','post'], '/mine/user/my-member-list', $controller.'@view_mine_user_my_member_list');
        Route::match(['get','post'], '/mine/user/my-fans-list', $controller.'@view_mine_user_my_fans_list');
        Route::match(['get','post'], '/mine/user/my-sponsor-list', $controller.'@view_mine_user_my_sponsor_list');

        Route::match(['get','post'], '/mine/user/sponsor-delete', $controller.'@operate_mine_user_sponsor_delete');
        Route::match(['get','post'], '/mine/user/sponsor-close', $controller.'@operate_mine_user_sponsor_close');
        Route::match(['get','post'], '/mine/user/sponsor-open', $controller.'@operate_mine_user_sponsor_open');

        Route::match(['get','post'], '/mine/user/relation-sponsor-list', $controller.'@view_mine_user_relation_sponsor_list');
        Route::match(['get','post'], '/mine/user/sponsor-relation', $controller.'@operate_mine_user_sponsor_relation');
        Route::match(['get','post'], '/mine/user/sponsor-relation-bulk', $controller.'@operate_mine_user_sponsor_relation_bulk');

        Route::match(['get','post'], '/mine/user/member-add', $controller.'@operate_mine_user_member_add');
        Route::match(['get','post'], '/mine/user/member-remove', $controller.'@operate_mine_user_member_remove');
        Route::match(['get','post'], '/mine/user/fans-remove', $controller.'@operate_mine_user_fans_remove');





        /*
         * item
         */


        // item
        Route::match(['get','post'], '/mine/item/item-create', $controller.'@operate_mine_item_item_create');
        Route::match(['get','post'], '/mine/item/item-edit', $controller.'@operate_mine_item_item_edit');
        Route::match(['get','post'], '/mine/item/item-publish', $controller.'@operate_mine_item_item_publish');


        // item-list
        Route::match(['get','post'], '/item/item-list', $controller.'@show_item_item_list');
        Route::match(['get','post'], '/item/item-all-list', $controller.'@show_item_all_list');

        Route::match(['get','post'], '/item/item-article-list', $controller.'@show_item_article_list');
        Route::match(['get','post'], '/item/item-activity-list', $controller.'@show_item_activity_list');
        Route::match(['get','post'], '/item/item-advertising-list', $controller.'@show_item_advertising_list');


        Route::match(['get','post'], '/item/item-get', $controller.'@operate_item_item_get');
        Route::match(['get','post'], '/item/item-delete', $controller.'@operate_item_item_delete');
        Route::match(['get','post'], '/item/item-publish', $controller.'@operate_item_item_publish');
        Route::match(['get','post'], '/item/item-ad-set', $controller.'@operate_item_ad_set');
        Route::match(['get','post'], '/item/item-ad-cancel', $controller.'@operate_item_ad_cancel');


        Route::get('/mine/my-advertising-list', $controller.'@view_mine_my_advertising_list');


        /*
         * statistic
         */
        Route::match(['get','post'], '/statistic', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/index', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/statistic-index', $controller.'@view_statistic_index');
        Route::match(['get','post'], '/statistic/statistic-user', $controller.'@view_statistic_user');
        Route::match(['get','post'], '/statistic/statistic-item', $controller.'@view_statistic_item');




        /*
         * notification
         */
        // notification-list
        Route::match(['get','post'], '/mine/my-notification', $controller.'@view_mine_my_notification');




    });


});
