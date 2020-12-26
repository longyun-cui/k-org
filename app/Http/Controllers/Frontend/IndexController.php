<?php

namespace App\Http\Controllers\Frontend;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Frontend\IndexRepository;


class IndexController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new IndexRepository;
    }


    public function view_root()
    {
        return $this->repo->view_root(request()->all());
    }




    // 【K】【】
    public function login_link()
    {
        $state  = url()->previous();
        if(is_weixin())
        {
            $app_id = env('WECHAT_SOFT_ORG_APPID');
            $app_secret = env('WECHAT_SOFT_ORG_SECRET');
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri=http%3A%2F%2Fk-org.cn%2Fweixin%2Fauth&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
            return redirect($url);

        }
        else
        {
            $app_id = env('WECHAT_WEBSITE_K_APPID');
            $app_secret = env('WECHAT_WEBSITE_K_SECRET');
            $url = "https://open.weixin.qq.com/connect/qrconnect?appid={$app_id}&redirect_uri=http%3A%2F%2Fk-org.cn%2Fweixin%2Flogin&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
            return redirect($url);
        }
    }




    // 【K】【基本信息】返回
    public function view_my_info_index()
    {
        return $this->repo->view_my_info_index();
    }

    // 【K】【基本信息】编辑
    public function view_my_info_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_my_info_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_my_info_save(request()->all());
    }




    // 【内容列表】
    public function view_item_list()
    {
        return $this->repo->view_item_list(request()->all());
    }
    // 【内容详情】
    public function view_item($id=0)
    {
        return $this->repo->view_item(request()->all(),$id);
    }



    // 【我的关注】
    public function view_my_follow()
    {
        return $this->repo->view_my_follow(request()->all());
    }
    // 【我的粉丝】
    public function view_my_fans()
    {
        return $this->repo->view_my_fans(request()->all());
    }


    // 【我的粉丝】
    public function view_my_favor()
    {
        return $this->repo->view_my_favor(request()->all());
    }


    // 【我的粉丝】
    public function view_my_notification()
    {
        return $this->repo->view_my_notification(request()->all());
    }






    public function view_user($id=0)
    {
        return $this->repo->view_user(request()->all(),$id);
    }
    public function view_user_introduction($id=0)
    {
        return $this->repo->view_user_introduction(request()->all(),$id);
    }
    public function view_user_original($id=0)
    {
        return $this->repo->view_user_original(request()->all(),$id);
    }
    public function view_user_follow($id=0)
    {
        return $this->repo->view_user_follow(request()->all(),$id);
    }
    public function view_user_fans($id=0)
    {
        return $this->repo->view_user_fans(request()->all(),$id);
    }




    // 【机构列表】
    public function view_organization_list()
    {
        return $this->repo->view_organization_list(request()->all());
    }

    // 【机构首页】
    public function view_org($id=0)
    {
        return $this->repo->view_org(request()->all(),$id);
    }

    // 【机构介绍页】
    public function view_organization_introduction($id=0)
    {
        return $this->repo->view_organization_introduction(request()->all(),$id);
    }

    // 【机构内容列表】
    public function view_org_item_list($id=0)
    {
        return $this->repo->view_org_item_list(request()->all(),$id);
    }














    // 【我的原创】
    public function view_home_mine_original()
    {
        return $this->repo->view_home_mine_original(request()->all());
    }

    // 【我的待办事】
    public function view_home_mine_todolist()
    {
        return $this->repo->view_home_mine_todolist(request()->all());
    }
    // 【我的日程】
    public function view_home_mine_schedule()
    {
        return $this->repo->view_home_mine_schedule(request()->all());
    }
    // 【收藏内容】
    public function view_home_mine_collection()
    {
        return $this->repo->view_home_mine_collection(request()->all());
    }
    // 【点赞内容】
    public function view_home_mine_favor()
    {
        return $this->repo->view_home_mine_favor(request()->all());
    }
    // 【发现】
    public function view_home_mine_discovery()
    {
        return $this->repo->view_home_mine_discovery(request()->all());
    }
    // 【我的关注】
    public function view_home_mine_follow()
    {
        return $this->repo->view_home_mine_follow(request()->all());
    }
    // 【我的好友圈】
    public function view_home_mine_circle()
    {
        return $this->repo->view_home_mine_circle(request()->all());
    }




    // 【我的好友圈】
    public function view_home_notification()
    {
        return $this->repo->view_home_notification(request()->all());
    }




    // 【添加关注】
    public function user_relation_add()
    {
        return $this->repo->user_relation_add(request()->all());
    }
    // 【取消关注】
    public function user_relation_remove()
    {
        return $this->repo->user_relation_remove(request()->all());
    }




    // 【添加关注】
    public function view_relation_follow()
    {
        return $this->repo->view_relation_follow(request()->all());
    }
    // 【取消关注】
    public function view_relation_fans()
    {
        return $this->repo->view_relation_fans(request()->all());
    }




    // 【ajax】【获取日程】
    public function ajax_get_schedule()
    {
        return $this->repo->ajax_get_schedule(request()->all());
    }



    // 【创建】
    public function view_home_mine_item_create()
    {
        return $this->repo->view_home_mine_item_create();
    }
    // 【编辑&存储】
    public function view_home_mine_item_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_home_mine_item_edit();
        else if (request()->isMethod('post')) return $this->repo->home_mine_item_save(request()->all());
    }


    // 【目录类型】编辑视图
    public function view_home_mine_item_edit_menutype()
    {
        return $this->repo->view_home_mine_item_edit_menutype(request()->all());
    }
    // 【时间线】编辑视图
    public function view_home_mine_item_edit_timeline()
    {
        return $this->repo->view_home_mine_item_edit_timeline(request()->all());
    }


    // 【目录类型】存储
    public function home_mine_item_menutype_save()
    {
        if(request()->isMethod('get')) return $this->repo->view_home_mine_item_edit_menutype(request()->all());
        else if (request()->isMethod('post')) return $this->repo->home_mine_item_menutype_save(request()->all());
    }
    // 【时间线】存储
    public function home_mine_item_timeline_save()
    {
        if(request()->isMethod('get')) return $this->repo->view_home_mine_item_edit_timeline(request()->all());
        else if (request()->isMethod('post')) return $this->repo->home_mine_item_timeline_save(request()->all());
    }




    // 【删除】
    public function item_delete()
    {
        return $this->repo->item_delete(request()->all());
    }

    // 【点赞】
    public function item_add_favor()
    {
        return $this->repo->item_add_this(request()->all(),1);
    }
    public function item_remove_favor()
    {
        return $this->repo->item_remove_this(request()->all(),1);
    }

    // 【收藏】
    public function item_add_collection()
    {
        return $this->repo->item_add_this(request()->all(),21);
    }
    public function item_remove_collection()
    {
        return $this->repo->item_remove_this(request()->all(),21);
    }

    // 【待办事】
    public function item_add_todolist()
    {
        return $this->repo->item_add_this(request()->all(),31);
    }
    public function item_remove_todolist()
    {
        return $this->repo->item_remove_this(request()->all(),31);
    }

    // 【日程】
    public function item_add_schedule()
    {
        return $this->repo->item_add_this(request()->all(),32);
    }
    public function item_remove_schedule()
    {
        return $this->repo->item_remove_this(request()->all(),32);
    }


    // 【转发】
    public function item_forward()
    {
        return $this->repo->item_forward(request()->all());
    }




    // 收藏
    public function item_collect_save()
    {
        return $this->repo->item_collect_save(request()->all());
    }
    public function item_collect_cancel()
    {
        return $this->repo->item_collect_cancel(request()->all());
    }


    // 点赞
    public function item_favor_save()
    {
        return $this->repo->item_favor_save(request()->all());
    }
    public function item_favor_cancel()
    {
        return $this->repo->item_favor_cancel(request()->all());
    }




    // 评论
    public function item_comment_save()
    {
        return $this->repo->item_comment_save(request()->all());
    }
    public function item_comment_get()
    {
        return $this->repo->item_comment_get(request()->all());
    }
    public function item_comment_get_html()
    {
        return $this->repo->item_comment_get_html(request()->all());
    }


    // 回复
    public function item_reply_save()
    {
        return $this->repo->item_reply_save(request()->all());
    }
    public function item_reply_get()
    {
        return $this->repo->item_reply_get(request()->all());
    }


    // 评论点赞
    public function item_comment_favor_save()
    {
        return $this->repo->item_comment_favor_save(request()->all());
    }
    public function item_comment_favor_cancel()
    {
        return $this->repo->item_comment_favor_cancel(request()->all());
    }




}
