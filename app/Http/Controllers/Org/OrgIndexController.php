<?php
namespace App\Http\Controllers\Org;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\K\KUser;
use App\Models\K\KItem;

use App\Repositories\Org\OrgIndexRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class OrgIndexController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new OrgIndexRepository;
    }


    // 返回【主页】视图
    public function view_index()
    {
        return $this->repo->view_index();
    }

    // 返回【主页】视图
    public function view_404()
    {
        return $this->repo->view_404();
    }

    //
    public function view_mine_my_advertising_list()
    {
        return $this->repo->view_mine_my_advertising_list();
    }




    // 【用户】用户-登录
    public function operate_login_user()
    {
        if(!Auth::guard('org')->check()) // 未登录
        {
            return Response(response_error([],"请先登录！"));
//            $return["status"] = false;
//            $return["log"] = "admin-no-login";
//            $return["msg"] = "请先登录";
//            return Response::json($return);
        }
        else
        {
            $id = request('id',0);
            $me = Auth::guard('org')->user();
            $me_id = $me->id;
            if($id != $me_id) return Response(response_error([],"账号异常，请刷新页面重试！"));

            Auth::login($me,true);
            return response_success();
        }
    }




    /*
     * 用户基本信息
     */




    // 【基本信息】返回
    public function view_my_info_index()
    {
        return $this->repo->view_my_info_index();
    }
    // 【基本信息】编辑
    public function operate_my_info_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_my_info_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_my_info_save(request()->all());
    }

    // 【基本信息】返回
    public function view_my_info_introduction_index()
    {
        return $this->repo->view_my_info_introduction_index();
    }
    // 【基本信息】编辑
    public function operate_my_info_introduction_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_my_info_introduction_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_my_info_introduction_save(request()->all());
    }

    // 【基本信息】修改密码
    public function operate_my_info_password_reset()
    {
        if(request()->isMethod('get')) return $this->repo->view_my_info_password_reset();
        else if (request()->isMethod('post')) return $this->repo->operate_my_info_password_reset_save(request()->all());
    }




    // 【基本信息】返回
    public function view_info_index()
    {
        return $this->repo->view_info_index();
    }

    // 【基本信息】编辑
    public function operate_info_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_info_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_info_save(request()->all());
    }

    // 【基本信息】修改密码
    public function operate_info_password_reset()
    {
        if(request()->isMethod('get')) return $this->repo->view_info_password_reset();
        else if (request()->isMethod('post')) return $this->repo->operate_info_password_reset_save(request()->all());
    }




    /*
     * 用户基本信息
     */

    // 【基本信息】返回
    public function view_introduction_index()
    {
        return $this->repo->view_introduction_index();
    }

    // 【基本信息】编辑
    public function operate_introduction_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_introduction_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_introduction_save(request()->all());
    }




    // 【基本信息】编辑
    public function operate_my_card_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_my_card_edit();
        else if (request()->isMethod('post')) return $this->repo->operate_my_card_save(request()->all());
    }







    // 【我的消息】
    public function view_mine_my_notification()
    {
        return $this->repo->view_mine_my_notification(request()->all());
    }



    // 【用户】SELECT2 USER
    public function operate_mine_select2_user()
    {
        return $this->repo->operate_mine_select2_user(request()->all());
    }



    /*
     * 用户系统
     */
    // 【用户】【成员】返回-列表
    public function view_mine_my_member_list()
    {
        return $this->repo->view_mine_my_member_list(request()->all());
    }
    // 【用户】【粉丝】返回-列表
    public function view_mine_my_fans_list()
    {
        return $this->repo->view_mine_my_fans_list(request()->all());
    }
    // 【用户】【粉丝】返回-列表
    public function view_mine_my_follow_list()
    {
        return $this->repo->view_mine_my_follow_list(request()->all());
    }
    // 【用户】【赞助商】返回-列表
    public function view_mine_my_sponsor_list()
    {
        return $this->repo->view_mine_my_sponsor_list(request()->all());
    }




    // 【赞助商】添加
    public function operate_mine_sponsor_add()
    {
        if(request()->isMethod('get')) return $this->repo->view_mine_sponsor_add(request()->all());
        else if (request()->isMethod('post')) return $this->repo->operate_mine_sponsor_add_save(request()->all());
    }
    // 【赞助商】删除
    public function operate_mine_sponsor_delete()
    {
        return $this->repo->operate_mine_sponsor_delete(request()->all());
    }
    // 【赞助商】关闭
    public function operate_mine_sponsor_close()
    {
        return $this->repo->operate_mine_sponsor_close(request()->all());
    }
    // 【赞助商】开启
    public function operate_mine_sponsor_open()
    {
        return $this->repo->operate_mine_sponsor_open(request()->all());
    }




    // 【成员】添加
    public function operate_mine_fans_remove()
    {
        return $this->repo->operate_mine_fans_remove(request()->all());
    }


    // 【成员】添加
    public function operate_mine_member_add()
    {
        return $this->repo->operate_mine_member_add(request()->all());
    }
    // 【成员】移除
    public function operate_mine_member_remove()
    {
        return $this->repo->operate_mine_member_remove(request()->all());
    }














    // 【用户】修改-密码
    public function operate_user_change_password()
    {
        return $this->repo->operate_user_change_password(request()->all());
    }

    // 【用户】SELECT2
    public function operate_business_select2_user()
    {
        return $this->repo->operate_business_select2_user(request()->all());
    }


    // 【用户】【成员】返回-列表
    public function view_user_my_member_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_my_member_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_user_my_member_list_datatable(request()->all());
    }
    // 【用户】【粉丝】返回-列表
    public function view_user_my_fans_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_my_fans_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_user_my_fans_list_datatable(request()->all());
    }
    // 【用户】【赞助商】返回-列表
    public function view_user_my_sponsor_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_my_sponsor_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_user_my_sponsor_list_datatable(request()->all());
    }




    // 【用户】【赞助商】返回-列表
    public function view_user_relation_sponsor_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_user_relation_sponsor_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_user_relation_sponsor_list_datatable(request()->all());
    }

    // 【代理商】删除
    public function operate_user_sponsor_relation()
    {
        return $this->repo->operate_user_sponsor_relation(request()->all());
    }

    // 【代理商】删除
    public function operate_user_sponsor_relation_bulk()
    {
        return $this->repo->operate_user_sponsor_relation_bulk(request()->all());
    }




    /*
     * 统计
     */

    // 【统计】
    public function view_statistic_index()
    {
        return $this->repo->view_statistic_index();
    }
    // 【统计】
    public function view_statistic_item()
    {
        return $this->repo->view_statistic_item(request()->all());
    }















    /*
     * 内容管理
     */
    // 【ITEM】添加
    public function operate_mine_item_item_create()
    {
        if(request()->isMethod('get')) return $this->repo->view_mine_item_item_create(request()->all());
        else if (request()->isMethod('post')) return $this->repo->operate_mine_item_item_save(request()->all());
    }
    // 【ITEM】编辑
    public function operate_mine_item_item_edit()
    {
        if(request()->isMethod('get')) return $this->repo->view_mine_item_item_edit(request()->all());
        else if (request()->isMethod('post')) return $this->repo->operate_mine_item_item_save(request()->all());
    }




    /*
     * 内容
     */
    // 【内容】返回-列表-视图
    public function show_item_item_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_item_item_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_item_item_list_datatable(request()->all());
    }
    // 【全部内容】返回-列表-视图
    public function show_item_all_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_item_all_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_item_all_list_datatable(request()->all());
    }
    // 【活动】返回-列表-视图
    public function show_item_article_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_item_article_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_item_article_list_datatable(request()->all());
    }
    // 【活动】返回-列表-视图
    public function show_item_activity_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_item_activity_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_item_activity_list_datatable(request()->all());
    }
    // 【广告】返回-列表-视图
    public function show_item_advertising_list()
    {
        if(request()->isMethod('get')) return $this->repo->view_item_advertising_list(request()->all());
        else if(request()->isMethod('post')) return $this->repo->get_item_advertising_list_datatable(request()->all());
    }




    // 【内容】获取-详情
    public function operate_item_item_get()
    {
        return $this->repo->operate_item_item_get(request()->all());
    }
    // 【内容】删除
    public function operate_item_item_delete()
    {
        return $this->repo->operate_item_item_delete(request()->all());
    }
    // 【内容】发布
    public function operate_mine_item_item_publish()
    {
        return $this->repo->operate_mine_item_item_publish(request()->all());
    }

    // 【内容】广告-设置
    public function operate_item_ad_set()
    {
        return $this->repo->operate_item_ad_set(request()->all());
    }
    // 【内容】广告-取消
    public function operate_item_ad_cancel()
    {
        return $this->repo->operate_item_ad_cancel(request()->all());
    }














}
