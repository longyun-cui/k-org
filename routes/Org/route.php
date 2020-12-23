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


    $controller = "IndexController";

    Route::match(['get','post'], '/login-user/', $controller.'@operate_login_user');


    // 后台管理，需要登录
    Route::group(['middleware' => 'org'], function () {


        Route::fallback(function() {
            return response()->view(env('TEMPLATE_ADMIN').'org.errors.404');
        });

        Route::get('/404', function () {
            return view(env('TEMPLATE_ADMIN').'org.errors.404');
        });


        $controller = "IndexController";

        Route::get('/', $controller.'@index');
        Route::get('index', $controller.'@index');


        /*
         * info
         */
        Route::match(['get','post'], '/info/', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/index', $controller.'@view_info_index');
        Route::match(['get','post'], '/info/edit', $controller.'@operate_info_edit');
        Route::match(['get','post'], '/info/password-reset', $controller.'@operate_info_password_reset');


        /*
         * introduction
         */
        Route::match(['get','post'], '/introduction/', $controller.'@view_introduction_index');
        Route::match(['get','post'], '/introduction/index', $controller.'@view_introduction_index');
        Route::match(['get','post'], '/introduction/edit', $controller.'@operate_introduction_edit');




        /*
         * user
         */
        Route::match(['get','post'], '/user/select2_sponsor', $controller.'@operate_user_select2_sponsor');


        Route::match(['get','post'], '/user/my-member-list', $controller.'@view_user_my_member_list');
        Route::match(['get','post'], '/user/my-fans-list', $controller.'@view_user_my_fans_list');
        Route::match(['get','post'], '/user/my-sponsor-list', $controller.'@view_user_my_sponsor_list');

        Route::match(['get','post'], '/user/sponsor-delete', $controller.'@operate_user_sponsor_delete');
        Route::match(['get','post'], '/user/sponsor-close', $controller.'@operate_user_sponsor_close');
        Route::match(['get','post'], '/user/sponsor-open', $controller.'@operate_user_sponsor_open');

        Route::match(['get','post'], '/user/relation-sponsor-list', $controller.'@view_user_relation_sponsor_list');
        Route::match(['get','post'], '/user/sponsor-relation', $controller.'@operate_user_sponsor_relation');
        Route::match(['get','post'], '/user/sponsor-relation-bulk', $controller.'@operate_user_sponsor_relation_bulk');




        Route::match(['get','post'], '/user/agent', $controller.'@view_user_agent');
        Route::match(['get','post'], '/user/client', $controller.'@view_user_client');

        Route::match(['get','post'], '/user/agent/client-list', $controller.'@view_user_agent_client_list');
        Route::match(['get','post'], '/user/client/keyword-list', $controller.'@view_user_client_keyword_list');

        Route::match(['get','post'], '/user/agent-create', $controller.'@operate_user_agent_create');
        Route::match(['get','post'], '/user/agent-edit', $controller.'@operate_user_agent_edit');

        Route::match(['get','post'], '/user/agent-recharge', $controller.'@operate_user_agent_recharge');
        Route::match(['get','post'], '/user/agent-recharge-limit-close', $controller.'@operate_user_agent_recharge_limit_close');
        Route::match(['get','post'], '/user/agent-recharge-limit-open', $controller.'@operate_user_agent_recharge_limit_open');
        Route::match(['get','post'], '/user/agent-sub-agent-close', $controller.'@operate_user_agent_sub_agent_close');
        Route::match(['get','post'], '/user/agent-sub-agent-open', $controller.'@operate_user_agent_sub_agent_open');

        Route::match(['get','post'], '/user/agent-delete', $controller.'@operate_user_agent_delete');
        Route::match(['get','post'], '/user/client-delete', $controller.'@operate_user_client_delete');




        /*
         * item
         */
        // item-list
        Route::match(['get','post'], '/item/item-list', $controller.'@show_item_item_list');
        Route::match(['get','post'], '/item/item-all-list', $controller.'@show_item_all_list');

        Route::match(['get','post'], '/item/item-article-list', $controller.'@show_item_article_list');
        Route::match(['get','post'], '/item/item-activity-list', $controller.'@show_item_activity_list');
        Route::match(['get','post'], '/item/item-advertising-list', $controller.'@show_item_advertising_list');


        // item
        Route::match(['get','post'], '/item/item-create', $controller.'@operate_item_item_create');
        Route::match(['get','post'], '/item/item-edit', $controller.'@operate_item_item_edit');
        // item-article
        Route::match(['get','post'], '/item/article-create', $controller.'@operate_item_article_create');
        Route::match(['get','post'], '/item/article-edit', $controller.'@operate_item_article_edit');
        // item-activity
        Route::match(['get','post'], '/item/activity-create', $controller.'@operate_item_activity_create');
        Route::match(['get','post'], '/item/activity-edit', $controller.'@operate_item_activity_edit');
        // item-advertising
        Route::match(['get','post'], '/item/advertising-create', $controller.'@operate_item_advertising_create');
        Route::match(['get','post'], '/item/advertising-edit', $controller.'@operate_item_advertising_edit');


        Route::match(['get','post'], '/item/item-get', $controller.'@operate_item_item_get');
        Route::match(['get','post'], '/item/item-delete', $controller.'@operate_item_item_delete');
        Route::match(['get','post'], '/item/item-publish', $controller.'@operate_item_item_publish');
        Route::match(['get','post'], '/item/item-ad-set', $controller.'@operate_item_ad_set');
        Route::match(['get','post'], '/item/item-ad-cancel', $controller.'@operate_item_ad_cancel');




        /*
         * notification
         */
        // notification-list
        Route::match(['get','post'], '/notification/notification-all-list', $controller.'@show_notification_all_list');








        Route::match(['get','post'], '/business/site-review', $controller.'@operate_business_site_review');
        Route::match(['get','post'], '/business/site-review-bulk', $controller.'@operate_business_site_review_bulk');

        Route::match(['get','post'], '/business/site-todo-delete', $controller.'@operate_business_site_todo_delete');
        Route::match(['get','post'], '/business/site-todo-delete-bulk', $controller.'@operate_business_site_todo_delete_bulk');

        Route::match(['get','post'], '/business/site-get', $controller.'@operate_business_site_get');
        Route::match(['get','post'], '/business/site-delete', $controller.'@operate_business_site_delete');
        Route::match(['get','post'], '/business/site-stop', $controller.'@operate_business_site_stop');
        Route::match(['get','post'], '/business/site-start', $controller.'@operate_business_site_start');
        Route::match(['get','post'], '/business/site-edit', $controller.'@operate_business_site_edit');




        // keyword
        Route::match(['get','post'], '/business/keyword-search', $controller.'@operate_keyword_search');
        Route::match(['get','post'], '/business/keyword-recommend', $controller.'@operate_keyword_recommend');
        Route::match(['get','post'], '/business/keyword-search-export', $controller.'@operate_keyword_search_export');

        Route::match(['get','post'], '/business/keyword-list', $controller.'@view_business_keyword_list');
        Route::match(['get','post'], '/business/keyword-today', $controller.'@view_business_keyword_today_list');
        Route::match(['get','post'], '/business/keyword-today-newly', $controller.'@view_business_keyword_today_newly_list');
        Route::match(['get','post'], '/business/keyword-anomaly', $controller.'@view_business_keyword_anomaly_list');
        Route::match(['get','post'], '/business/keyword-todo', $controller.'@view_business_keyword_todo_list');
        Route::match(['get','post'], '/business/keyword-detect-record', $controller.'@view_business_keyword_detect_record');


        Route::match(['get','post'], '/business/keyword-review', $controller.'@operate_business_keyword_review');
        Route::match(['get','post'], '/business/keyword-review-bulk', $controller.'@operate_business_keyword_review_bulk');

        Route::match(['get','post'], '/business/keyword-todo-delete', $controller.'@operate_business_keyword_todo_delete');
        Route::match(['get','post'], '/business/keyword-todo-delete-bulk', $controller.'@operate_business_keyword_todo_delete_bulk');

        Route::match(['get','post'], '/business/keyword-get', $controller.'@operate_business_keyword_get');
        Route::match(['get','post'], '/business/keyword-delete', $controller.'@operate_business_keyword_delete');
        Route::match(['get','post'], '/business/keyword-delete-bulk', $controller.'@operate_business_keyword_delete_bulk');
        Route::match(['get','post'], '/business/keyword-stop', $controller.'@operate_business_keyword_stop');
        Route::match(['get','post'], '/business/keyword-start', $controller.'@operate_business_keyword_start');

        Route::match(['get','post'], '/business/keyword-detect-create-rank', $controller.'@operate_business_keyword_detect_create_rank');
        Route::match(['get','post'], '/business/keyword-detect-set-rank', $controller.'@operate_business_keyword_detect_set_rank');
        Route::match(['get','post'], '/business/keyword-detect-set-rank-bulk', $controller.'@operate_business_keyword_detect_set_rank_bulk');


        Route::match(['get','post'], '/business/download/', $controller.'@operate_download');
        Route::match(['get','post'], '/business/download/keyword-today', $controller.'@operate_download_keyword_today');
        Route::match(['get','post'], '/business/download/keyword-detect', $controller.'@operate_download_keyword_detect');




        /*
         * finance
         */
        Route::match(['get','post'], '/finance/overview', $controller.'@view_finance_overview');
        Route::match(['get','post'], '/finance/overview-month', $controller.'@view_finance_overview_month');
        Route::match(['get','post'], '/finance/recharge-record', $controller.'@view_finance_recharge_record');
        Route::match(['get','post'], '/finance/expense-record', $controller.'@view_finance_expense_record');
        Route::match(['get','post'], '/finance/expense-record-daily', $controller.'@view_finance_expense_record_daily');
        Route::match(['get','post'], '/finance/freeze-record', $controller.'@view_finance_freeze_record');




        /*
         * notice
         */
        Route::match(['get','post'], '/notice/notice-create', $controller.'@operate_notice_notice_create');
        Route::match(['get','post'], '/notice/notice-edit', $controller.'@operate_notice_notice_edit');

        Route::match(['get','post'], '/notice/notice-list', $controller.'@view_notice_notice_list');
        Route::match(['get','post'], '/notice/my-notice-list', $controller.'@view_notice_my_notice_list');
        Route::match(['get','post'], '/notice/notice-get', $controller.'@operate_notice_notice_get');
        Route::match(['get','post'], '/notice/notice-push', $controller.'@operate_notice_notice_push');
        Route::match(['get','post'], '/notice/notice-delete', $controller.'@operate_notice_notice_delete');



    });


});
