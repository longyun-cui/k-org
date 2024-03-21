<?php


/*
 * 前端
 */
Route::group([], function () {


    // 不存在的域名
    Route::fallback(function(){

        if(Auth::check())
        {
            $this->auth_check = 1;
            $this->me = Auth::user();
            view()->share('me',$this->me);
        }
        else $this->auth_check = 0;
        view()->share('auth_check',$this->auth_check);

        return response()->view(env('TEMPLATE_K_WWW').'errors.404');
    });


    // 注册登录
    Route::group([], function () {

        $controller = "AuthController";

        Route::match(['get','post'], 'login', $controller.'@login');
        Route::match(['get','post'], 'logout', $controller.'@logout');

    });



    $controller = "WWWIndexController";

    Route::match(['get', 'post'],'login-link', $controller."@login_link");

    Route::match(['get', 'post'],'record/share', $controller."@record_share");





    /*
     * 微信
     */
    Route::group(['prefix' => 'weixin'], function () {

        $wxController = "WeixinController";

        Route::match(['get', 'post'],'auth/MP_verify_0m3bPByLDcHKLvIv.txt', function () {
            return "0m3bPByLDcHKLvIv";
        });

        Route::match(['get', 'post'],'auth/MP_verify_eTPw6Fu85pGY5kiV.txt', function () {
            return "eTPw6Fu85pGY5kiV";
        });

        Route::match(['get', 'post'],'auth/MP_verify_enRXVHgfnjolnsIN.txt', function () {
            return "enRXVHgfnjolnsIN";
        });

        Route::match(['get', 'post'],'auth', $wxController."@weixin_auth");
        Route::match(['get', 'post'],'login', $wxController."@weixin_login");


        Route::match(['get', 'post'],'gongzhonghao', $wxController."@gongzhonghao");
        Route::match(['get', 'post'],'root', $wxController."@root");
        Route::match(['get', 'post'],'test', $wxController."@test");

    });



    Route::group(['middleware' => ['wx.share','notification']], function () {

        $controller = "WWWIndexController";

        Route::get('/', $controller.'@view_index');
        Route::get('/root-1', $controller.'@view_index');

        Route::get('/introduction', $controller.'@view_introduction');

        Route::get('/item-list', $controller.'@view_item_list');
        Route::get('/item/{id?}', $controller.'@view_item');

        Route::get('/organization-list', $controller.'@view_organization_list');
        Route::get('/organization/{id?}', $controller.'@view_organization');
        Route::get('/organization/{id?}/introduction', $controller.'@view_organization_introduction');
//        Route::get('/org/{id?}/item-list', $controller.'@view_org_item_list');

//        Route::get('/org/{id?}/article', $controller.'@view_org_article');
//        Route::get('/org/{id?}/activity', $controller.'@view_org_activity');
//        Route::get('/org/{id?}/sponsor', $controller.'@view_org_sponsor');


        Route::get('u/{id?}', $controller.'@view_user');
        Route::get('user/{id?}', $controller.'@view_user');
        Route::get('user/{id?}/introduction', $controller.'@view_user_introduction');
        Route::get('user/{id?}/original', $controller.'@view_user_original');
        Route::get('user/{id?}/follow', $controller.'@view_user_follow');
        Route::get('user/{id?}/fans', $controller.'@view_user_fans');


        Route::get('tag/{q?}', $controller.'@view_tag');


        Route::group(['middleware' => ['login.turn']], function () {

            $controller = "WWWIndexController";

            Route::get('/org-register', $controller.'@operate_org_register');


            Route::get('/home/notification', $controller.'@view_home_notification');

            Route::group(['middleware' => 'notification'], function () {

                $controller = "WWWIndexController";

                Route::get('/home/mine/original', $controller.'@view_home_mine_original');

                Route::get('/home/mine/todo_list', $controller.'@view_home_mine_todolist');
                Route::get('/home/mine/schedule', $controller.'@view_home_mine_schedule');

                Route::get('/home/mine/collection', $controller.'@view_home_mine_collection');
                Route::get('/home/mine/favor', $controller.'@view_home_mine_favor');

                Route::get('/home/mine/discovery', $controller.'@view_home_mine_discovery');
                Route::get('/home/mine/follow', $controller.'@view_home_mine_follow');
                Route::get('/home/mine/circle', $controller.'@view_home_mine_circle');

                // 添加&编辑
                Route::get('/home/mine/item/create', $controller.'@view_home_mine_item_create');
                Route::match(['get','post'], '/home/mine/item/edit', $controller.'@view_home_mine_item_edit');
                Route::match(['get','post'], '/home/mine/item/edit/menutype', $controller.'@view_home_mine_item_edit_menutype');
                Route::match(['get','post'], '/home/mine/item/edit/timeline', $controller.'@view_home_mine_item_edit_timeline');


                Route::get('/home/relation/follow', $controller.'@view_relation_follow');
                Route::get('/home/relation/fans', $controller.'@view_relation_fans');


                Route::get('/my-info/index', $controller.'@view_my_info_index');
                Route::match(['get','post'], '/my-info/edit', $controller.'@view_my_info_edit');

                Route::get('/my-follow', $controller.'@view_my_follow');
                Route::get('/my-favor', $controller.'@view_my_favor');
                Route::get('/my-notification', $controller.'@view_my_notification');





                /*s
                 * 个人信息
                 */
                // 基本信息-基本资料
                Route::get('/mine/my-profile-info-index', $controller.'@view_my_profile_info_index');
                Route::match(['get','post'], '/mine/my-profile-info-edit', $controller.'@operate_my_profile_info_edit');
                // 图文介绍
                Route::get('/mine/my-profile-intro-index', $controller.'@view_my_profile_intro_index');
                Route::match(['get','post'], '/mine/my-profile-intro-edit', $controller.'@operate_my_profile_intro_edit');
                // 名片
                Route::get('/mine/my-card-index', $controller.'@view_my_card_index');
                Route::match(['get','post'], '/mine/my-card-edit', $controller.'@view_my_card_edit');

                // 名片夹 & 我的关注 & 我的粉丝
                Route::get('/mine/my-cards-case', $controller.'@view_my_cards_case');
                Route::get('/mine/my-follow', $controller.'@view_my_follow');
                Route::get('/mine/my-fans', $controller.'@view_my_fans');

                // 收藏 & 点赞
                Route::get('/mine/my-collection', $controller.'@view_my_collection');
                Route::get('/mine/my-favor', $controller.'@view_my_favor');
                //
                Route::get('/mine/my-notification', $controller.'@view_my_notification');

            });

        });

    });


    Route::group(['middleware' => ['login']], function () {

        $controller = "WWWIndexController";


        // 获取日程
        Route::post('ajax/get/schedule', $controller.'@ajax_get_schedule');

        // 收藏
        Route::post('item/add/collection', $controller.'@item_add_collection');
        Route::post('item/remove/collection', $controller.'@item_remove_collection');

        // 删除
        Route::post('item/delete', $controller.'@item_delete');

        // 转发
        Route::post('item/forward', $controller.'@item_forward');

        // 点赞
        Route::post('item/add/favor', $controller.'@item_add_favor');
        Route::post('item/remove/favor', $controller.'@item_remove_favor');

        // 待办事
        Route::post('item/add/todolist', $controller.'@item_add_todolist');
        Route::post('item/remove/todolist', $controller.'@item_remove_todolist');

        // 日程
        Route::post('item/add/schedule', $controller.'@item_add_schedule');
        Route::post('item/remove/schedule', $controller.'@item_remove_schedule');


        Route::post('item/comment/save', $controller.'@item_comment_save');
        Route::post('item/reply/save', $controller.'@item_reply_save');

        Route::post('item/comment/favor/save', $controller.'@item_comment_favor_save');
        Route::post('item/comment/favor/cancel', $controller.'@item_comment_favor_cancel');

        Route::post('user/relation/add', $controller.'@user_relation_add');
        Route::post('user/relation/remove', $controller.'@user_relation_remove');

    });

    Route::post('item/comment/get', $controller.'@item_comment_get');
    Route::post('item/comment/get_html', $controller.'@item_comment_get_html');
    Route::post('item/reply/get', $controller.'@item_reply_get');


});
