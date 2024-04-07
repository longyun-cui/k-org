<?php
namespace App\Repositories\Org;

use App\Models\K\K_User;
use App\Models\K\K_UserExt;
use App\Models\K\K_Item;
use App\Models\K\K_Pivot_User_Relation;
use App\Models\K\K_Notification;
use App\Models\K\K_Record;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Blade;
use QrCode, Excel;

class OrgIndexRepository {

    private $env;
    private $auth_check;
    private $me;
    private $me_admin;
    private $model;
    private $modelUser;
    private $modelItem;
    private $repo;
    private $view_blade_root;
    private $view_blade_404;
    public function __construct()
    {
        $this->modelUser = new K_User;
        $this->modelItem = new K_Item;

        $this->view_blade_root = env('TEMPLATE_K_ORG');
        $this->view_blade_404 = env('TEMPLATE_K_ORG').'errors.404';

        Blade::setEchoFormat('%s');
        Blade::setEchoFormat('e(%s)');
        Blade::setEchoFormat('nl2br(e(%s))');
    }


    public function get_me()
    {
        if(Auth::guard("org")->check())
        {
            $this->auth_check = 1;
            $this->me = Auth::guard("org")->user();
            $this->me_admin = Auth::guard("org_admin")->user();
            view()->share('me',$this->me);

            if(Auth::guard("org_admin")->check())
            {
                $this->me_admin = Auth::guard("org_admin")->user();
            }
            else
            {
                $this->me_admin = $this->me;
            }
        }
        else $this->auth_check = 0;

        view()->share('auth_check',$this->auth_check);
    }




    // 返回（后台）主页视图
    public function view_index()
    {
        $this->get_me();
        $me = $this->me;
        $me_id = $me->id;

        $item_query = K_Item::with([
                'owner',
                'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
            ])
            ->where(['owner_id'=>$me_id]);

        $item_type = request('item-type','default');
        if($item_type == 'unpublished')
        {
            $item_query->where('is_published',0);
            $return['menu_active_for_root'] = 'active';
            $return['menu_active_for_item_unpublished'] = 'active';
        }
        else
        {
            $return['menu_active_for_root'] = 'active';
            $return['menu_active_for_item_all'] = 'active';
        }

        $item_list = $item_query

            ->orderBy('is_published')
            ->orderByDesc('updated_at')
            ->paginate(20);

        $return['item_list'] = $item_list;

        $view_blade = env('TEMPLATE_K_ORG').'entrance.index';
        return view($view_blade)->with($return);
    }

    // 返回（后台）主页视图
    public function view_404()
    {
        $this->get_me();
        $me = $this->me;
        $me_id = $me->id;

        $view_blade = env('TEMPLATE_K_ORG').'errors.404';
        return view($view_blade);
    }

    // 广告
    public function view_mine_my_advertising_list()
    {
        $this->get_me();
        $me = $this->me;
        $me_id = $me->id;


        $item_query = K_Item::with([
            'owner',
            'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
        ])
            ->where(['item_type'=>88,'owner_id'=>$me_id]);

        $item_type = request('item-type','default');
        if($item_type == 'unpublished')
        {
            $item_query->where('item_active',0);
            $return['menu_active_for_unpublished'] = 'active';
        }
        else
        {
            $return['menu_active_for_my_advertising'] = 'active';
        }

        $item_list = $item_query->orderby('id','desc')->paginate(20);

        $return['item_list'] = $item_list;

        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-advertising';
        return view($view_blade)->with($return);
    }




    /*
     * 用户基本信息
     */




    /*
     * 用户基本信息
     */
    // 【基本信息】返回视图
    public function view_my_info_index()
    {
        $this->get_me();
        $me = $this->me;
        $return['info'] = $me;
        $return['data'] = $me;
        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-info.my-info-index';
        return view($view_blade)->with($return);
    }
    // 【基本信息】返回-编辑-视图
    public function view_my_info_edit()
    {
        $this->get_me();
        $me = $this->me;
        $return['info'] = $me;
        $return['data'] = $me;
        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-info.my-info-edit';
        return view($view_blade)->with($return);
    }
    // 【基本信息】保存数据
    public function operate_my_info_save($post_data)
    {
        $this->get_me();
        $me = $this->me;

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $mine_data = $post_data;
            unset($mine_data['operate']);
            unset($mine_data['operate_id']);
            $bool = $me->fill($mine_data)->save();
            if($bool)
            {
                // 头像
                if(!empty($post_data["portrait"]))
                {
                    // 删除原文件
                    $mine_portrait_img = $me->portrait_img;
                    if(!empty($mine_portrait_img) && file_exists(storage_resource_path($mine_portrait_img)))
                    {
                        unlink(storage_resource_path($mine_portrait_img));
                    }

                    $result = upload_img_storage($post_data["portrait"],'portrait_for_user_by_user_'.$me->id,'k/unique/portrait_for_user','');
                    if($result["result"])
                    {
                        $me->portrait_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload--portrait_img--file--fail");
                }

            }
            else throw new Exception("insert--item--fail");

            DB::commit();
            return response_success(['id'=>$me->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }


    // 【基本信息-图文介绍】返回视图
    public function view_my_info_introduction_index()
    {
        $this->get_me();
        $me = $this->me;
        $me->load('ext');

        $return['info'] = $me;
        $return['data'] = $me;
        $return['data'] = $me;
        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-info.my-info-introduction-index';
        return view($view_blade)->with($return);
    }
    // 【基本信息-图文介绍】返回-编辑-视图
    public function view_my_info_introduction_edit()
    {
        $this->get_me();
        $me = $this->me;
        $me->load('ext');

        $return['info'] = $me;
        $return['data'] = $me;
        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-info.my-info-introduction-edit';
        return view($view_blade)->with($return);
    }
    // 【基本信息-图文介绍】保存数据
    public function operate_my_info_introduction_save($post_data)
    {
        $this->get_me();
        $me = $this->me;

        $mine = K_UserExt::where('user_id',$me->id)->first();

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $mine_data = $post_data;
            unset($mine_data['operate']);
            unset($mine_data['operate_id']);
            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("update--user_ext--fail");

            DB::commit();
            return response_success(['id'=>$me->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }


    // 【基本信息-密码】返回-修改-视图
    public function view_my_info_password_reset()
    {
        $this->get_me();
        $me = $this->me;
        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-info.my-info-password-reset';
        return view($view_blade)->with(['data'=>$me]);
    }
    // 【基本信息-密码】保存数据
    public function operate_my_info_password_reset_save($post_data)
    {
        $messages = [
            'password_pre.required' => '请输入旧密码',
            'password_new.required' => '请输入新密码',
            'password_confirm.required' => '请输入确认密码',
        ];
        $v = Validator::make($post_data, [
            'password_pre' => 'required',
            'password_new' => 'required',
            'password_confirm' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $password_pre = request()->get('password_pre');
        $password_new = request()->get('password_new');
        $password_confirm = request()->get('password_confirm');

        if($password_new == $password_confirm)
        {
            $me = Auth::guard('staff')->user();
            if(password_check($password_pre,$me->password))
            {
                $me->password = password_encode($password_new);
                $bool = $me->save();
                if($bool) return response_success([], '密码修改成功！');
                else return response_fail([], '密码修改失败！');
            }
            else
            {
                return response_fail([], '原密码有误！');
            }
        }
        else return response_error([],'两次密码输入不一致！');
    }





    // 【基本信息】返回--视图
    public function view_info_index()
    {
        $me = Auth::guard('org')->user();
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.info.index')->with(['data'=>$me]);
    }
    // 【基本信息】返回-编辑-视图
    public function view_info_edit()
    {
        $me = Auth::guard('org')->user();
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.info.edit')->with(['data'=>$me]);
    }
    // 【基本信息】保存-数据
    public function operate_info_save($post_data)
    {
        $me = Auth::guard('org')->user();

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $mine_data = $post_data;
            unset($mine_data['operate']);
            unset($mine_data['operate_id']);
            $bool = $me->fill($mine_data)->save();
            if($bool)
            {
                // 头像
                if(!empty($post_data["portrait"]))
                {
                    // 删除原文件
                    $mine_original_file = $me->portrait_img;
                    if(!empty($mine_original_file) && file_exists(storage_path('resource/'.$mine_original_file)))
                    {
                        unlink(storage_path('resource/'.$mine_original_file));
                    }

                    $result = upload_file_storage($post_data["portrait"]);
                    if($result["result"])
                    {
                        $me->portrait_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload-portrait-img-file-fail");
                }

                // 微信二维码
                if(!empty($post_data["wechat_qr_code"]))
                {
                    // 删除原图片
                    $mine_wechat_qr_code_img = $me->wechat_qr_code_img;
                    if(!empty($mine_wechat_qr_code_img) && file_exists(storage_path("resource/" . $mine_wechat_qr_code_img)))
                    {
                        unlink(storage_path("resource/" . $mine_wechat_qr_code_img));
                    }

                    $result = upload_storage($post_data["wechat_qr_code"]);
                    if($result["result"])
                    {
                        $me->wechat_qr_code_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload--wechat_qr_code--fail");
                }

                // 联系人微信二维码
                if(!empty($post_data["linkman_wechat_qr_code"]))
                {
                    // 删除原图片
                    $mine_linkman_wechat_qr_code_img = $me->linkman_wechat_qr_code_img;
                    if(!empty($mine_linkman_wechat_qr_code_img) && file_exists(storage_path("resource/" . $mine_linkman_wechat_qr_code_img)))
                    {
                        unlink(storage_path("resource/" . $mine_linkman_wechat_qr_code_img));
                    }

                    $result = upload_storage($post_data["linkman_wechat_qr_code"]);
                    if($result["result"])
                    {
                        $me->linkman_wechat_qr_code_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload--linkman_wechat_qr_code--fail");
                }

            }
            else throw new Exception("insert--item--fail");

            DB::commit();
            return response_success(['id'=>$me->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 【基本信息】【密码】返回-修改-视图
    public function view_info_password_reset()
    {
        $me = Auth::guard('org')->user();
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.info.password-reset')->with(['data'=>$me]);
    }
    // 【基本信息】【密码】保存-数据
    public function operate_info_password_reset_save($post_data)
    {
        $messages = [
            'password_pre.required' => '请输入旧密码',
            'password_new.required' => '请输入新密码',
            'password_confirm.required' => '请输入确认密码',
        ];
        $v = Validator::make($post_data, [
            'password_pre' => 'required',
            'password_new' => 'required',
            'password_confirm' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $password_pre = request()->get('password_pre');
        $password_new = request()->get('password_new');
        $password_confirm = request()->get('password_confirm');

        if($password_new == $password_confirm)
        {
            $me = Auth::guard('org')->user();
            if(password_check($password_pre,$me->password))
            {
                $me->password = password_encode($password_new);
                $bool = $me->save();
                if($bool) return response_success([], '密码修改成功！');
                else return response_fail([], '密码修改失败！');
            }
            else
            {
                return response_fail([], '原密码有误！');
            }
        }
        else return response_error([],'两次密码输入不一致！');
    }




    // 【基本信息】返回--视图
    public function view_introduction_index()
    {
        $me = Auth::guard('org')->user();
        $data = K_Item::find($me->introduction_id);
        if(!$data) $data = [];
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.introduction.index')
            ->with(['data'=>$data,'sidebar_me_introduction_active'=>'active menu-open']);
    }
    // 【基本信息】返回-编辑-视图
    public function view_introduction_edit()
    {
        $me = Auth::guard('org')->user();
        $data = K_Item::find($me->introduction_id);
        if(!$data) $data = [];
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.introduction.edit')->with(['data'=>$data]);
    }
    // 【基本信息】保存-数据
    public function operate_introduction_save($post_data)
    {
        $me = Auth::guard('org')->user();

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $mine_data = $post_data;
            unset($mine_data['operate']);
            unset($mine_data['operate_id']);

            if($me->introduction_id == 0)
            {
                $item = new K_Item;
                $mine_data['owner_id'] = $me->id;
                $mine_data['item_category'] = 1;
                $mine_data['item_type'] = 99;
            }
            else
            {
                $item = K_Item::find($me->introduction_id);
//                if(!$item) $item = new K_Item;
            }

            $bool = $item->fill($mine_data)->save();
            if($bool)
            {

                if($me->introduction_id == 0)
                {
                    $me->introduction_id = $item->id;
                    $me->save();
                }

                // 头像
                if(!empty($post_data["cover"]))
                {
                    // 删除原文件
                    $mine_original_file = $item->cover_pic;
                    if(!empty($mine_original_file) && file_exists(storage_path('resource/'.$mine_original_file)))
                    {
                        unlink(storage_path('resource/'.$mine_original_file));
                    }

                    $result = upload_file_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $item->cover_pic = $result["local"];
                        $item->save();
                    }
                    else throw new Exception("upload-cover-pic-file-fail");
                }

            }
            else throw new Exception("insert--item--fail");

            DB::commit();
            return response_success(['id'=>$me->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }




    /*
     * 用户系统
     */
    // 【代理商&用户】【修改密码】
    public function operate_user_change_password($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户ID',
            'user-password.required' => '请输入密码',
            'user-password-confirm.required' => '请输入确认密码',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
            'user-password' => 'required',
            'user-password-confirm' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'change-password') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $password = $post_data["user-password"];
        $confirm = $post_data["user-password-confirm"];
        if($password != $confirm) return response_error([],"两次密码不一致！");

//        if(!password_is_legal($password)) ;
        $pattern = '/^[a-zA-Z0-9]{1}[a-zA-Z0-9]{5,19}$/i';
        if(!preg_match($pattern,$password)) return response_error([],"密码格式不正确！");


        $user = User::find($id);
        if(!$user) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($user->usergroup,['Agent','Agent2','Service'])) return response_error([],"该用户参数有误，你不能操作！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $user->password_1 = $password;
            $user->password = password_encode($password);
            $user->userpass = basic_encrypt($password);
            $user->save();

            $bool = $user->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

            DB::commit();
            return response_success(['id'=>$user->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }









    // 【基本信息-我的名片】返回-主页-视图
    public function view_my_card_index()
    {
        $this->get_me();
        $me = $this->me;
        $user_id = $me->id;

        $type = !empty($post_data['type']) ? $post_data['type'] : 'root';

        $user = K_User::select('*')
            ->with([
                'ext'
            ])
            ->withCount([
//                'items as article_count' => function($query) { $query->where(['item_status'=>1,'item_category'=>1,'item_type'=>1]); },
//                'items as activity_count' => function($query) { $query->where(['item_status'=>1,'item_category'=>1,'item_type'=>11]); },
            ])
            ->find($user_id);


        $is_follow = 0;

        if($this->auth_check)
        {
            $me = $this->me;
            $me_id = $me->id;
            $record["creator_id"] = $me_id;


            if($user_id != $me_id)
            {
                $relation = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
                view()->share(['relation'=>$relation]);
            }

            $relation_with_me = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
            if($relation_with_me &&  in_array($relation_with_me->relation_type,[21,41]))
            {
                $is_follow = 1;
            }
        }
        else
        {
        }


        $condition = request()->all();
        $return['condition'] = $condition;
        $return['data'] = $me;
        $return['is_follow'] = $is_follow;
        $return['menu_active_for_my_card'] = 'active';

        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-profile.my-card-index';
        return view($view_blade)->with($return);
    }
    // 【基本信息-我的名片】返回-编辑-视图
    public function view_my_card_edit()
    {
        $this->get_me();
        $me = $this->me;

        $return['data'] = $me;
        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-profile.my-card-edit';
        return view($view_blade)->with($return);
    }
    // 【基本信息-我的名片】保存-数据
    public function operate_my_card_save($post_data)
    {
        $mine_data = $post_data;
        $this->get_me();
        $me = $this->me;

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            unset($mine_data['operate']);
            $mine_data = $post_data;
            $bool = $me->fill($mine_data)->save();
            if($bool)
            {
                // 头像
                if(!empty($post_data["portrait"]))
                {
                    // 删除原文件
                    $mine_original_file = $me->portrait_img;
                    if(!empty($mine_original_file) && file_exists(storage_resource_path($mine_original_file)))
                    {
                        unlink(storage_resource_path($mine_original_file));
                    }

//                    $result = upload_img_storage($post_data["portrait"],'','root/common');
                    $result = upload_img_storage($post_data["portrait"],'portrait_for_user_by_user_'.$me->id,'k/unique/portrait_for_user','');
                    if($result["result"])
                    {
                        $me->portrait_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload--portrait_img--file--fail");
                }

                // 微信二维码
                if(!empty($post_data["wx_qr_code"]))
                {
                    // 删除原图片
                    $mine_wx_qr_code_img = $me->wx_qr_code_img;
                    if(!empty($mine_wx_qr_code_img) && file_exists(storage_resource_path($mine_wx_qr_code_img)))
                    {
                        unlink(storage_resource_path($mine_wx_qr_code_img));
                    }

                    $result = upload_img_storage($post_data["wx_qr_code"],'','k/common');
                    if($result["result"])
                    {
                        $me->wx_qr_code_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload--wx_qr_code--fail");
                }

                // 联系人微信二维码
                if(!empty($post_data["linkman_wx_qr_code"]))
                {
                    // 删除原图片
                    $mine_wx_qr_code_img = $me->wx_qr_code_img;
                    if(!empty($mine_wx_qr_code_img) && file_exists(storage_resource_path($mine_wx_qr_code_img)))
                    {
                        unlink(storage_resource_path($mine_wx_qr_code_img));
                    }

                    $result = upload_img_storage($post_data["linkman_wx_qr_code"],'','k/common');
                    if($result["result"])
                    {
                        $me->linkman_wx_qr_code_img = $result["local"];
                        $me->save();
                    }
                    else throw new Exception("upload--wx_qr_code--fail");
                }

            }
            else throw new Exception("update--user--fail");

            $ext = K_UserExt::where('user_id',$me->id)->first();
            $mine_data = $post_data;
            $bool = $ext->fill($mine_data)->save();
            if($bool)
            {
            }
            else throw new Exception("update--ext--fail");

            DB::commit();
            return response_success(['id'=>$me->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }







    // 【我的消息】
    public function view_mine_my_notification($post_data)
    {
        $this->get_me();
        $me = $this->me;
        $me_id = $me->id;

        $count = K_Notification::where(['owner_id'=>$me_id,'is_read'=>0])->whereIn('notification_category',[9,11])->count();
        if($count)
        {
            $notification_list = K_Notification::with([
                'source_er',
                'item'=>function($query) {
                    $query->with([
                        'owner',
                        'forward_item'=>function($query) { $query->with(['owner']); }
                    ]);
                },
                'communication'=>function($query) { $query->with(['owner']); },
                'reply'=>function($query) {
                    $query->with([
                        'owner',
                        'reply'=>function($query) { $query->with('owner'); }
                    ]);
                }
            ])
                ->whereIn('notification_category',[9,11])
                ->where(['is_read'=>0])
                ->where(['owner_id'=>$me_id])
                ->orderBy('id','desc')
                ->get();

            view()->share('notification_style', 'new');
            $update_num = K_Notification::where(['owner_id'=>$me_id,'is_read'=>0])->whereIn('notification_category',[9,11])->update(['is_read'=>1]);
        }
        else
        {
            $notification_list = K_Notification::with([
                'source_er',
                'item'=>function($query) {
                    $query->with([
                        'owner',
                        'forward_item'=>function($query) { $query->with(['owner']); }
                    ]);
                },
                'communication'=>function($query) { $query->with(['owner']); },
                'reply'=>function($query) {
                    $query->with([
                        'owner',
                        'reply'=>function($query) { $query->with('owner'); }
                    ]);
                }
            ])
                ->whereIn('notification_category',[9,11])
                ->where(['owner_id'=>$me_id])
                ->orderBy('id','desc')
                ->paginate(20);

            view()->share('notification_style', 'paginate');
        }


//        dd($notification_list->toArray());

//        foreach ($items as $item)
//        {
//            $item->custom_decode = json_decode($item->custom);
//            $item->content_show = strip_tags($item->content);
//            $item->img_tags = get_html_img($item->content);
//        }
//

        $view_data['notification_list'] = $notification_list;
        $view_data['menu_active_of_notification'] = 'active';

        $view_blade = env('TEMPLATE_K_ORG').'entrance.mine.my-notification';
        return view($view_blade)->with($view_data);
    }







    // 【select2】
    public function operate_business_select2_user($post_data)
    {
        $me = Auth::guard('org')->user();
        if(empty($post_data['keyword']))
        {
            $list =User::select(['id','username as text'])
                ->where(['userstatus'=>'正常','status'=>1])
                ->whereIn('usergroup',['Agent','Agent2'])
                ->orderBy('id','desc')
                ->get()
                ->toArray();
        }
        else
        {
            $keyword = "%{$post_data['keyword']}%";
            $list =User::select(['id','username as text'])
                ->where(['userstatus'=>'正常','status'=>1])
                ->whereIn('usergroup',['Agent','Agent2'])
                ->where('sitename','like',"%$keyword%")
                ->orderBy('id','desc')
                ->get()
                ->toArray();
        }
        array_unshift($list, ['id'=>0,'text'=>'【全部代理】']);
        return $list;
    }



    // 【用户】【user-list】【我的成员】
    public function view_mine_user_my_member_list($post_data)
    {
    }
    // 【用户】【user-list】【我的粉丝】
    public function view_mine_user_my_fans_list($post_data)
    {
        $me = Auth::guard("org")->user();

        $query = K_Pivot_User_Relation::select('*')
            ->with('relation_user')
            ->where(['relation_category'=>1,'mine_user_id'=>$me->id])
            ->whereIn('relation_type',['21','71']);
        $query->orderBy("id", "desc");
        $user_list  = $query->paginate(20);

        foreach ($user_list as $user)
        {
            $user->relation_with_me = $user->relation_type;
        }
//        dd($user_list->toArray());

        $return['user_list'] = $user_list;
        $return['menu_active_for_my_fans_list'] = 'active';

        $view_blade = env('TEMPLATE_K_ORG').'entrance.my-follow-list';
        return view($view_blade)->with($return);
    }
    // 【用户】【user-list】【我的赞助商】
    public function view_mine_user_my_sponsor_list($post_data)
    {
    }





    // 【用户】【成员】返回-列表-视图
    public function view_user_my_member_list($post_data)
    {
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.user.user-my-member-list')
            ->with(['sidebar_user_member_list_active'=>'active menu-open']);
    }
    // 【用户】【成员】返回-列表-数据
    public function get_user_my_member_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Pivot_User_Relation::select('*')->with('relation_user')->where(['relation_category'=>11,'relation_type'=>11,'mine_user_id'=>$me->id]);

//        if(!empty($post_data['username'])) $query->where('username', 'like', "%{$post_data['username']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 40;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【用户】【粉丝】返回-列表-视图
    public function view_user_my_fans_list($post_data)
    {
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.user.user-my-fans-list')
            ->with(['sidebar_user_fans_list_active'=>'active menu-open']);
    }
    // 【用户】【粉丝】返回-列表-数据
    public function get_user_my_fans_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Pivot_User_Relation::select('*')->with('mine_user')->where(['relation_category'=>1,'relation_user_id'=>$me->id])
        ->whereIn('relation_type',['21','41']);

//        if(!empty($post_data['username'])) $query->where('username', 'like', "%{$post_data['username']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 40;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【用户】【粉丝】返回-列表-视图
    public function view_user_my_sponsor_list($post_data)
    {
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.user.user-my-sponsor-list')
            ->with(['sidebar_user_sponsor_list_active'=>'active menu-open']);
    }
    // 【用户】【粉丝】返回-列表-数据
    public function get_user_my_sponsor_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Pivot_User_Relation::select('*')->with('relation_user')
            ->where(['mine_user_id'=>$me->id,'relation_category'=>88,'relation_type'=>1]);

        if(!empty($post_data['username']))
        {
            $username = $post_data['username'];
            $query->whereHas('relation_user', function ($query1) use($username) { $query1->where('user.username', 'like', "%{$username}%"); } );
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 40;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("updated_at", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

//        dd($list->toArray());

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }



    // 【用户】【赞助商】返回-列表-视图
    public function view_user_relation_sponsor_list($post_data)
    {
        return view(env('TEMPLATE_ADMIN').'org.admin.entrance.user.relation-sponsor-list')
            ->with(['sidebar_user_relation_sponsor_list_active'=>'active menu-open']);
    }
    // 【用户】【赞助商】返回-列表-数据
    public function get_user_relation_sponsor_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_User::select('*')->where(['user_category'=>1,'user_type'=>88]);

        if(!empty($post_data['username']))
        {
            $query->where('username', 'like', "%{$post_data['username']}%");
        }
        else
        {
            $query->where('username', '不可能存在的赞助商！');
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 40;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("id", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }



    // 【赞助商】关联
    public function operate_user_sponsor_relation($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请选择ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'sponsor-relation') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");

        $pivot_relation = K_Pivot_User_Relation::where(['relation_category'=>88,'relation_type'=>1,'mine_user_id'=>$me->id,'relation_user_id'=>$id])->first();
        if($pivot_relation) return response_error([],"该赞助商已经关联过了！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $mine = new K_Pivot_User_Relation;

            $mine_data['relation_category'] = 88;
            $mine_data['relation_type'] = 1;
            $mine_data['mine_user_id'] = $me->id;
            $mine_data['relation_user_id'] = $id;

            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {

            }
            else throw new Exception("insert--pivot_relation--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 【赞助商】批量关联
    public function operate_user_sponsor_relation_bulk($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'bulk_sponsor_id.required' => '请选择ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'bulk_sponsor_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'sponsor-relation-bulk') return response_error([],"参数有误！");

        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $sponsor_ids = $post_data["bulk_sponsor_id"];
            foreach($sponsor_ids as $key => $sponsor_id)
            {
                if(intval($sponsor_id) !== 0 && !$sponsor_id) return response_error([],"参数ID有误！");
                $pivot_relation = K_Pivot_User_Relation::where(['relation_category'=>88,'mine_user_id'=>$me->id,'relation_user_id'=>$sponsor_id])->first();
                if(!$pivot_relation)
                {
                    $mine = new K_Pivot_User_Relation;

                    $mine_data['relation_category'] = 88;
                    $mine_data['relation_type'] = 1;
                    $mine_data['mine_user_id'] = $me->id;
                    $mine_data['relation_user_id'] = $sponsor_id;

                    $bool = $mine->fill($mine_data)->save();
                    if($bool)
                    {

                    }
                    else throw new Exception("insert--pivot_relation--fail");
                }
//                else return response_error([],"该赞助商已经关联过了！");
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }




    // 【赞助商】删除
    public function operate_user_sponsor_delete($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'sponsor-delete') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");

        $pivot_relation = K_Pivot_User_Relation::find($id);
        if(!$pivot_relation) return response_error([],"该关联不存在，刷新页面重试");
        if($pivot_relation->mine_user_id != $me->id) return response_error([],"该关联有误！");
        if($pivot_relation->relation_type != 88) return response_error([],"非赞助商关联，不能执行该操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            // 删除【用户】
//            $user->pivot_menus()->detach(); // 删除相关目录
            $bool = $pivot_relation->delete();
            if(!$bool) throw new Exception("delete--pivot_relation--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '删除失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 【赞助商】关闭
    public function operate_user_sponsor_close($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'sponsor-close') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");

        $pivot_relation = K_Pivot_User_Relation::find($id);
        if(!$pivot_relation) return response_error([],"该关联不存在，刷新页面重试");
        if($pivot_relation->mine_user_id != $me->id) return response_error([],"该关联有误！");
        if($pivot_relation->relation_category != 88) return response_error([],"非赞助商关联，不能执行该操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["relation_active"] = 9;
            $bool = $pivot_relation->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--pivot_relation--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }
    // 【赞助商】开启
    public function operate_user_sponsor_open($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'sponsor-open') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");

        $pivot_relation = K_Pivot_User_Relation::find($id);
        if(!$pivot_relation) return response_error([],"该关联不存在，刷新页面重试");
        if($pivot_relation->mine_user_id != $me->id) return response_error([],"该关联有误！");
        if($pivot_relation->relation_category != 88) return response_error([],"非赞助商关联，不能执行该操作！");

        $me_sponsor_count = K_Pivot_User_Relation::where(['relation_active'=>1,'relation_type'=>88,'mine_user_id'=>$me->id])->count();
        if($me_sponsor_count >= 3) return response_error([],"启用的赞助商不能超过3个！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["relation_active"] = 1;
            $bool = $pivot_relation->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--pivot_relation--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }




    // 【粉丝】移除
    public function operate_user_fans_remove($post_data)
    {
        $messages = [
            'operate.required' => 'Parameter operate Missing.',
            'pivot_id.required' => 'Parameter pivot_id Missing.',
            'user_id.required' => 'Parameter user_id Missing.',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'pivot_id' => 'required',
            'user_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'fans-remove') return response_error([],"操作参数有误！");

        $me = Auth::guard('org')->user();
        $me_id = $me->id;
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");


        $pivot_id = $post_data["pivot_id"];
        $pivot_relation = K_Pivot_User_Relation::find($pivot_id);
        if(!$pivot_relation) return response_error([],"该关联不存在，刷新页面重试！");
        if($pivot_relation->relation_category != 1) return response_error([],"关联类型有误！");
        if($pivot_relation->relation_user_id != $me->id) return response_error([],"不是我的关联！");

        $user_id = $post_data["user_id"];
        if(intval($user_id) !== 0 && !$user_id) return response_error([],"用户ID参数有误！");
        if($pivot_relation->mine_user_id != $user_id) return response_error([],"关联对象有误！");

        $user = K_User::find($user_id);
        if(!$user) return response_error([],"该用户不存在，刷新页面重试！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $me_relation = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
            if($me_relation)
            {
                if($me_relation->relation_type == 21)
                {
                    $me_relation->relation_type = 41;
                    $me_relation->save();
                }
                else if($me_relation->relation_type == 71)
                {
                    $bool = $me_relation->delete();
                    if(!$bool) throw new Exception("delete--pivot_relation--fail");
                }
                else
                {
//                    $me_relation->relation_type = 93;
//                    $me_relation->save();

                    $bool = $me_relation->delete();
                    if(!$bool) throw new Exception("delete--pivot_relation--fail");
                }
            }

            $me->timestamps = false;
            $me->decrement('fans_num');

            $it_relation = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$user_id,'relation_user_id'=>$me_id])->first();
            if($it_relation)
            {
                if($it_relation->relation_type == 21)
                {
                    $it_relation->relation_type = 71;
                    $it_relation->save();
                }
                else if($it_relation->relation_type == 41)
                {
                    $bool = $it_relation->delete();
                    if(!$bool) throw new Exception("delete--pivot_relation--fail");
                }
                else
                {
//                    $it_relation->relation_type = 94;
//                    $it_relation->save();

                    $bool = $it_relation->delete();
                    if(!$bool) throw new Exception("delete--pivot_relation--fail");
                }
            }
            $user->timestamps = false;
            $user->decrement('follow_num');

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }


    // 【成员】添加
    public function operate_user_member_add($post_data)
    {
        $messages = [
            'operate.required' => 'Parameter operate Missing.',
            'user_id.required' => 'Parameter user_id Missing.',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'user_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'member-add') return response_error([],"操作参数有误！");

        $me = Auth::guard('org')->user();
        $me_id = $me->id;
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");

        $user_id = $post_data["user_id"];
        if(intval($user_id) !== 0 && !$user_id) return response_error([],"用户ID参数有误！");
        $user = K_User::find($user_id);
        if(!$user) return response_error([],"该用户不存在，刷新页面重试！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $relation = K_Pivot_User_Relation::where(['relation_category'=>11,'mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
            if($relation)
            {
                $relation->relation_type = 11;
                $relation->save();
            }
            else
            {
                $relation = new K_Pivot_User_Relation;
                $relation->relation_category = 11;
                $relation->relation_type = 11;
                $relation->mine_user_id = $me_id;
                $relation->relation_user_id = $user_id;
                $relation->save();
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }
    // 【成员】移除
    public function operate_user_member_remove($post_data)
    {
        $messages = [
            'operate.required' => 'Parameter operate Missing.',
            'pivot_id.required' => 'Parameter pivot_id Missing.',
            'user_id.required' => 'Parameter user_id Missing.',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'pivot_id' => 'required',
            'user_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'member-remove') return response_error([],"操作参数有误！");

        $me = Auth::guard('org')->user();
        $me_id = $me->id;
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");


        $pivot_id = $post_data["pivot_id"];
        $pivot_relation = K_Pivot_User_Relation::find($pivot_id);
        if(!$pivot_relation) return response_error([],"该关联不存在，刷新页面重试！");
        if($pivot_relation->relation_category != 11) return response_error([],"关联类型有误！");
        if($pivot_relation->mine_user_id != $me->id) return response_error([],"不是我的关联！");

        $user_id = $post_data["user_id"];
        if(intval($user_id) !== 0 && !$user_id) return response_error([],"用户ID参数有误！");
        if($pivot_relation->relation_user_id != $user_id) return response_error([],"关联对象有误！");

        $user = K_User::find($user_id);
        if(!$user) return response_error([],"该用户不存在，刷新页面重试！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $pivot_relation->delete();
            if(!$bool) throw new Exception("delete--pivot_relation--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }








    /*
     * 统计
     */

    // 【】流量统计
    public function view_statistic_index()
    {
        $me = Auth::guard('org')->user();
        $me_id = $me->id;

        $this_month = date('Y-m');
        $this_month_year = date('Y');
        $this_month_month = date('m');
        $last_month = date('Y-m',strtotime('last month'));
        $last_month_year = date('Y',strtotime('last month'));
        $last_month_month = date('m',strtotime('last month'));


        // 总访问量【统计】
        $all = K_Record::select(
            DB::raw("DATE(FROM_UNIXTIME(created_at)) as date"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m') as month"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%c') as month_0"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%e') as day"),
            DB::raw('count(*) as count')
        )
            ->groupBy(DB::raw("DATE(FROM_UNIXTIME(created_at))"))
            ->whereYear(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_year)
            ->whereMonth(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_month)
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('object_id',$me_id)
            ->get();
        $all = $all->keyBy('day');

        // 首页访问量【统计】
        $rooted = K_Record::select(
            DB::raw("DATE(FROM_UNIXTIME(created_at)) as date"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m') as month"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%c') as month_0"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%e') as day"),
            DB::raw('count(*) as count')
        )
            ->groupBy(DB::raw("DATE(FROM_UNIXTIME(created_at))"))
            ->whereYear(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_year)
            ->whereMonth(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_month)
            ->where(['record_category'=>1,'record_type'=>1,'page_type'=>2,'page_module'=>1])
            ->where('object_id',$me_id)
            ->get();
        $rooted = $rooted->keyBy('day');

        // 介绍页访问量【统计】
        $introduction = K_Record::select(
            DB::raw("DATE(FROM_UNIXTIME(created_at)) as date"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m') as month"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%c') as month_0"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%e') as day"),
            DB::raw('count(*) as count')
        )
            ->groupBy(DB::raw("DATE(FROM_UNIXTIME(created_at))"))
            ->whereYear(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_year)
            ->whereMonth(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_month)
            ->where(['record_category'=>1,'record_type'=>1,'page_type'=>2,'page_module'=>2])
            ->where('object_id',$me_id)
            ->get();
        $introduction = $introduction->keyBy('day');




        // 打开设备类型【占比】
        $open_device_type = K_Record::select('open_device_type',DB::raw('count(*) as count'))
            ->groupBy('open_device_type')
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('object_id',$me_id)
            ->get();
        foreach($open_device_type as $k => $v)
        {
            if($v->open_device_type == 0) $open_device_type[$k]->name = "默认";
            else if($v->open_device_type == 1) $open_device_type[$k]->name = "移动端";
            else if($v->open_device_type == 2)  $open_device_type[$k]->name = "PC端";
        }

        // 打开系统类型【占比】
        $open_system = K_Record::select('open_system',DB::raw('count(*) as count'))
            ->groupBy('open_system')
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('object_id',$me_id)
            ->get();

        // 打开APP类型【占比】
        $open_app = K_Record::select('open_app',DB::raw('count(*) as count'))
            ->groupBy('open_app')
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('object_id',$me_id)
            ->get();




        // 总分享【统计】
        $shared_all = K_Record::select(
            DB::raw("DATE(FROM_UNIXTIME(created_at)) as date"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m') as month"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%c') as month_0"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%e') as day"),
            DB::raw('count(*) as count')
        )
            ->groupBy(DB::raw("DATE(FROM_UNIXTIME(created_at))"))
            ->whereYear(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_year)
            ->whereMonth(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_month)
            ->where(['record_category'=>1,'record_type'=>2])
            ->where('object_id',$me_id)
            ->get();
        $shared_all = $shared_all->keyBy('day');

        // 首页分享【统计】
        $shared_root = K_Record::select(
            DB::raw("DATE(FROM_UNIXTIME(created_at)) as date"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m') as month"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%c') as month_0"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%e') as day"),
            DB::raw('count(*) as count')
        )
            ->groupBy(DB::raw("DATE(FROM_UNIXTIME(created_at))"))
            ->whereYear(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_year)
            ->whereMonth(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_month)
            ->where(['record_category'=>1,'record_type'=>2,'page_type'=>2,'page_module'=>1])
            ->where('object_id',$me_id)
            ->get();
        $shared_root = $shared_root->keyBy('day');




        // 总分享【占比】
        $shared_all_scale = K_Record::select('shared_location',DB::raw('count(*) as count'))
            ->groupBy('shared_location')
            ->where(['record_category'=>1,'record_type'=>2])
            ->where('object_id',$me_id)
            ->get();
        foreach($shared_all_scale as $k => $v)
        {
            if($v->shared_location == 1) $shared_all_scale[$k]->name = "微信好友";
            else if($v->shared_location == 2) $shared_all_scale[$k]->name = "微信朋友圈";
            else if($v->shared_location == 3) $shared_all_scale[$k]->name = "QQ好友";
            else if($v->shared_location == 4) $shared_all_scale[$k]->name = "QQ空间";
            else if($v->shared_location == 5) $shared_all_scale[$k]->name = "腾讯微博";
            else $shared_all_scale[$k]->name = "其他";
        }

        // 首页分享【占比】
        $shared_root_scale = K_Record::select('shared_location',DB::raw('count(*) as count'))
            ->groupBy('shared_location')
            ->where(['record_category'=>1,'record_type'=>2,'page_type'=>1,'page_module'=>1])
            ->where('object_id',$me_id)
            ->get();
        foreach($shared_root_scale as $k => $v)
        {
            if($v->shared_location == 1) $shared_root_scale[$k]->name = "微信好友";
            else if($v->shared_location == 2) $shared_root_scale[$k]->name = "微信朋友圈";
            else if($v->shared_location == 3) $shared_root_scale[$k]->name = "QQ好友";
            else if($v->shared_location == 4) $shared_root_scale[$k]->name = "QQ空间";
            else if($v->shared_location == 5) $shared_root_scale[$k]->name = "腾讯微博";
            else $shared_root_scale[$k]->name = "其他";
        }


        $view_data["all"] = $all;
        $view_data["rooted"] = $rooted;
        $view_data["introduction"] = $introduction;
        $view_data["open_device_type"] = $open_device_type;
        $view_data["open_app"] = $open_app;
        $view_data["open_system"] = $open_system;
        $view_data["shared_all"] = $shared_all;
        $view_data["shared_root"] = $shared_root;
        $view_data["shared_all_scale"] = $shared_all_scale;
        $view_data["shared_root_scale"] = $shared_root_scale;
        $view_data["sidebar_statistic_active"] = 'active';

        $view_blade = env('TEMPLATE_ADMIN').'org.admin.entrance.statistic.statistic-index';
        return view($view_blade)->with($view_data);
    }
    // 【】流量统计
    public function view_statistic_item($post_data)
    {
        $messages = [
            'id.required' => 'id required',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $me = Auth::guard('org')->user();
        $me_id = $me->id;

        $item_id = $post_data["id"];
        $item = K_Item::find($item_id);
        if(!$item) return view(env('TEMPLATE_ADMIN').'org.errors.404');
        if($item->owner_id != $me_id) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $this_month = date('Y-m');
        $this_month_year = date('Y');
        $this_month_month = date('m');
        $last_month = date('Y-m',strtotime('last month'));
        $last_month_year = date('Y',strtotime('last month'));
        $last_month_month = date('m',strtotime('last month'));


        // 访问量【统计】
        $data = K_Record::select(
            DB::raw("DATE(FROM_UNIXTIME(created_at)) as date"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m') as month"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%c') as month_0"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%e') as day"),
            DB::raw('count(*) as count')
        )
            ->groupBy(DB::raw("DATE(FROM_UNIXTIME(created_at))"))
            ->whereYear(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_year)
            ->whereMonth(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_month)
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('item_id',$item_id)
            ->get();
        $data = $data->keyBy('day');




        // 打开设备类型【占比】
        $open_device_type = K_Record::select('open_device_type',DB::raw('count(*) as count'))
            ->groupBy('open_device_type')
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('item_id',$item_id)
            ->get();
        foreach($open_device_type as $k => $v)
        {
            if($v->open_device_type == 0) $open_device_type[$k]->name = "默认";
            else if($v->open_device_type == 1) $open_device_type[$k]->name = "移动端";
            else if($v->open_device_type == 2)  $open_device_type[$k]->name = "PC端";
        }

        // 打开系统类型【占比】
        $open_system = K_Record::select('open_system',DB::raw('count(*) as count'))
            ->groupBy('open_system')
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('item_id',$item_id)
            ->get();

        // 打开APP类型【占比】
        $open_app = K_Record::select('open_app',DB::raw('count(*) as count'))
            ->groupBy('open_app')
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('item_id',$item_id)
            ->get();




        // 分享【统计】
        $shared_data = K_Record::select(
            DB::raw("DATE(FROM_UNIXTIME(created_at)) as date"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m') as month"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%c') as month_0"),
            DB::raw("DATE_FORMAT(FROM_UNIXTIME(created_at),'%e') as day"),
            DB::raw('count(*) as count')
        )
            ->groupBy(DB::raw("DATE(FROM_UNIXTIME(created_at))"))
            ->whereYear(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_year)
            ->whereMonth(DB::raw("DATE(FROM_UNIXTIME(created_at))"),$this_month_month)
            ->where(['record_category'=>1,'record_type'=>2])
            ->where('item_id',$item_id)
            ->get();
        $shared_data = $shared_data->keyBy('day');


        // 总分享【占比】
        $shared_data_scale = K_Record::select('shared_location',DB::raw('count(*) as count'))
            ->groupBy('shared_location')
            ->where(['record_category'=>1,'record_type'=>2])
            ->where('item_id',$item_id)
            ->get();
        foreach($shared_data_scale as $k => $v)
        {
            if($v->shared_location == 1) $shared_data_scale[$k]->name = "微信好友";
            else if($v->shared_location == 2) $shared_data_scale[$k]->name = "微信朋友圈";
            else if($v->shared_location == 3) $shared_data_scale[$k]->name = "QQ好友";
            else if($v->shared_location == 4) $shared_data_scale[$k]->name = "QQ空间";
            else if($v->shared_location == 5) $shared_data_scale[$k]->name = "腾讯微博";
            else $shared_data_scale[$k]->name = "其他";
        }


        $view_data["item"] = $item;
        $view_data["data"] = $data;
        $view_data["open_device_type"] = $open_device_type;
        $view_data["open_app"] = $open_app;
        $view_data["open_system"] = $open_system;
        $view_data["shared_data"] = $shared_data;
        $view_data["shared_data_scale"] = $shared_data_scale;

        $view_blade = env('TEMPLATE_ADMIN').'org.admin.entrance.statistic.statistic-item';
        return view($view_blade)->with($view_data);
    }













    // 【ITEM】返回-添加-视图
    public function view_mine_item_item_create($post_data)
    {

        $this->get_me();
        $me = $this->me;
        $me_id = $me->id;
        $me_admin = $this->me_admin;
        $me_admin_id = $me_admin->id;

        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_K_ORG').'errors.404');

        $item_category = 'item';
        $item_type = 'item';

        $item_type_text = '内容';
        $title_text = '添加'.$item_type_text;
        $list_text = $item_type_text.'列表';

        $item_type = request('item-type','item');
        if($item_type == 'item')
        {
            $item_type_text = '内容';
            $title_text = '添加'.$item_type_text;
            $list_text = $item_type_text.'列表';
        }
        else if($item_type == 'article')
        {
            $item_type_text = '文章';
            $title_text = '添加'.$item_type_text;
            $list_text = $item_type_text.'列表';
        }
        else if($item_type == 'activity')
        {
            $item_type_text = '活动';
            $title_text = '添加'.$item_type_text;
            $list_text = $item_type_text.'列表';
        }
        else if($item_type == 'advertising')
        {
            $item_type_text = '广告';
            $title_text = '添加'.$item_type_text;
            $list_text = $item_type_text.'列表';
        }
        else
        {

        }

        $list_link = '/item/item-list';
        $list_link = '/';

        $return['operate'] = 'create';
        $return['operate_id'] = 0;
        $return['operate_category'] = 'item';
        $return['operate_type'] = $item_type;
        $return['operate_item_category'] = 'item';
        $return['operate_item_type'] = $item_type;
        $return['operate_item_text'] = $item_type_text;
        $return['title_text'] = $title_text;
        $return['list_text'] = $list_text;
        $return['list_link'] = $list_link;

        $view_blade = env('TEMPLATE_K_ORG').'entrance.item.item-edit';
        return view($view_blade)->with($return);
    }
    // 【ITEM】返回-编辑-视图
    public function view_mine_item_item_edit($post_data)
    {
        $this->get_me();
        $me = $this->me;
        $me_id = $me->id;
        $me_admin = $this->me_admin;
        $me_admin_id = $me_admin->id;

        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_K_ORG').'errors.404');

        $item_id = $post_data["item-id"];
        $mine = K_Item::with(['user'])->find($item_id);
        if(!$mine) return view(env('TEMPLATE_K_ORG').'errors.404');
        if($mine->owner_id != $me_id) return view(env('TEMPLATE_K_ORG').'errors.404');
        if($mine->is_published != 0) return view(env('TEMPLATE_K_ORG').'errors.404');


        $item_type = 'item';
        $item_type_text = '内容';
        $title_text = '编辑'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/item/item-list';
        $list_link = '/';

        if($mine->item_type == 1)
        {
            $item_type = 'article';
            $item_type_text = '文章';
            $title_text = '编辑'.$item_type_text;
            $list_text = $item_type_text.'列表';
            $list_link = '/mine/item/item-list-for-article';
        }
        else if($mine->item_type == 11)
        {
            $item_type = 'activity';
            $item_type_text = '活动';
            $title_text = '编辑'.$item_type_text;
            $list_text = $item_type_text.'列表';
            $list_link = '/mine/item/item-list-for-activity';
        }
        else if($mine->item_type == 88)
        {
            $item_type = 'advertising';
            $item_type_text = '广告';
            $title_text = '编辑'.$item_type_text;
            $list_text = $item_type_text.'列表';
            $list_link = '/mine/item/item-list-for-advertising';
        }


        $return['operate'] = 'edit';
        $return['operate_id'] = $item_id;
        $return['operate_category'] = 'item';
        $return['operate_type'] = $item_type;
        $return['operate_item_category'] = 'item';
        $return['operate_item_type'] = $item_type;
        $return['operate_item_text'] = $item_type_text;
        $return['title_text'] = $title_text;
        $return['list_text'] = $list_text;
        $return['list_link'] = $list_link;

        $view_blade = env('TEMPLATE_K_ORG').'entrance.item.item-edit';

        if($item_id == 0)
        {
            $return['operate'] = 'create';
            return view($view_blade)->with($return);
        }
        else
        {
            $mine = K_Item::with(['user'])->find($item_id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                $return['data'] = $mine;
                return view($view_blade)->with($return);
            }
            else return response("该内容不存在！", 404);
        }
    }

    // 【ITEM】保存-数据
    public function operate_mine_item_item_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'title.required' => '请输入标题',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'title' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $this->get_me();
        $me = $this->me;
        $me_id = $me->id;
        $me_admin = $this->me_admin;
        $me_admin_id = $me_admin->id;

//        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");

//        dd($post_data);

        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];
        $operate_item_type = $post_data["operate_item_type"];

        if($operate == 'create') // 添加 ( $id==0，添加一个内容 )
        {
            $mine = new K_Item;
            $post_data["owner_id"] = $me_id;
            $post_data["creator_id"] = $me_admin_id;
            $post_data["item_category"] = 1;

//            if($operate_item_type == 'item') $post_data["item_type"] = 0;
//            else if($operate_item_type == 'article') $post_data["item_type"] = 1;
//            else if($operate_item_type == 'activity') $post_data["item_type"] = 11;
//            else if($operate_item_type == 'advertising') $post_data["item_type"] = 88;

        }
        else if($operate == 'edit') // 编辑
        {
            $mine = K_Item::find($operate_id);
            if(!$mine) return response_error([],"该内容不存在，刷新页面重试！");
        }
        else return response_error([],"参数有误！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }


            $mine_data = $post_data;
            unset($mine_data['operate']);
            unset($mine_data['operate_id']);
            unset($mine_data['category']);
            unset($mine_data['type']);

            if(!empty($post_data['start'])) {
                $mine_data['time_type'] = 1;
                $mine_data['start_time'] = strtotime($post_data['start']);
            }

            if(!empty($post_data['end'])) {
                $mine_data['time_type'] = 1;
                $mine_data['end_time'] = strtotime($post_data['end']);
            }

            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {

                // 封面图片
                if(!empty($post_data["cover"]))
                {
                    // 删除原封面图片
                    $mine_cover_pic = $mine->cover_pic;
                    if(!empty($mine_cover_pic) && file_exists(storage_path("resource/" . $mine_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $mine_cover_pic));
                    }

                    $result = upload_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $mine->cover_pic = $result["local"];
                        $mine->save();
                    }
                    else throw new Exception("upload-cover-fail");

//                    $upload = new CommonRepository();
//                    $result = $upload->upload($post_data["cover"], 'outside-unique-items' , 'cover_item_'.$encode_id);
//                    if($result["status"])
//                    {
//                        $mine->cover_pic = $result["data"];
//                        $mine->save();
//                    }
//                    else throw new Exception("upload-cover-fail");
                }

                // 附件
                if(!empty($post_data["attachment"]))
                {
                    // 删除原附件
                    $mine_cover_pic = $mine->attachment;
                    if(!empty($mine_cover_pic) && file_exists(storage_path("resource/" . $mine_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $mine_cover_pic));
                    }

                    $result = upload_file_storage($post_data["attachment"]);
                    if($result["result"])
                    {
                        $mine->attachment_name = $result["name"];
                        $mine->attachment_src = $result["local"];
                        $mine->save();
                    }
                    else throw new Exception("upload-attachment-fail");
                }

                // 生成二维码
//                $qr_code_path = "resource/unique/qr_code/";  // 保存目录
//                if(!file_exists(storage_path($qr_code_path)))
//                    mkdir(storage_path($qr_code_path), 0777, true);
//                // qr_code 图片文件
//                $url = 'http://www.k-org.cn/item/'.$mine->id;  // 目标 URL
//                $filename = 'qr_code_item_'.$mine->id.'.png';  // 目标 file
//                $qr_code = $qr_code_path.$filename;
//                QrCode::errorCorrection('H')->format('png')->size(640)->margin(0)->encoding('UTF-8')->generate($url,storage_path($qr_code));

            }
            else throw new Exception("insert--item--fail");

            DB::commit();
            return response_success(['id'=>$mine->id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }











    // 【ITEM】获取详情
    public function operate_item_item_get($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入关键词ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'item-get') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('org')->user();
        if($me->user_group != "Manage") return response_error([],"你没有操作权限！");

        $item = Item::find($id);
        return response_success($item,"");

    }
    // 【ITEM】删除
    public function operate_item_item_delete($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入关键词ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'item-delete') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $item = K_Item::find($id);
        if(!$item) return response_error([],"该内容不存在，刷新页面重试！");

        $me = Auth::guard('org')->user();
        if($item->owner_id != $me->id) return response_error([],"你没有操作权限！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if($id == $me->advertising_id)
            {
                $me->timestamps = false;
                $me->advertising_id = 0;
                $bool_0 = $me->save();
                if(!$bool_0) throw new Exception("update--user--fail");
            }

            $mine_cover_pic = $item->cover_pic;
            $mine_attachment_src = $item->attachment_src;
            $mine_content = $item->content;


            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--item--fail");

            DB::commit();


            // 删除原封面图片
            if(!empty($mine_cover_pic) && file_exists(storage_path("resource/" . $mine_cover_pic)))
            {
                unlink(storage_path("resource/" . $mine_cover_pic));
            }

            // 删除原附件
            if(!empty($mine_attachment_src) && file_exists(storage_path("resource/" . $mine_attachment_src)))
            {
                unlink(storage_path("resource/" . $mine_attachment_src));
            }

            // 删除二维码
            if(file_exists(storage_path("resource/unique/qr_code/".'qr_code_item_'.$id.'.png')))
            {
                unlink(storage_path("resource/unique/qr_code/".'qr_code_item_'.$id.'.png'));
            }

            // 删除UEditor图片
            $img_tags = get_html_img($mine_content);
            foreach ($img_tags[2] as $img)
            {
                if (!empty($img) && file_exists(public_path($img)))
                {
                    unlink(public_path($img));
                }
            }
            // 删除UEditor视频
            $video_tags = get_html_video($mine_content);
            foreach ($video_tags[2] as $video)
            {
                if (!empty($video) && file_exists(public_path($video)))
                {
                    unlink(public_path($video));
                }
            }


            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }
    // 【ITEM】推送
    public function operate_mine_item_item_publish($post_data)
    {
        $messages = [
            'operate.required' => 'operate.required',
            'item_id.required' => 'item_id.required',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'item_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'item-publish') return response_error([],"参数有误！");
        $item_id = $post_data["item_id"];
        if(intval($item_id) !== 0 && !$item_id) return response_error([],"参数ID有误！");

        $item = K_Item::find($item_id);
        if(!$item) return response_error([],"该内容不存在，刷新页面重试！");

        $me = Auth::guard('org')->user();
        if($item->owner_id != $me->id) return response_error([],"你没有操作权限！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $item->is_published = 1;
            $item->published_at = time();
            $item->timestamps = false;
            $bool = $item->save();
            if(!$bool) throw new Exception("update--item--fail");

            DB::commit();

            $item_html = $this->get_the_item_html($item);
            return response_success(['item_html'=>$item_html]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }


    // 【ITEM】推送
    public function operate_item_ad_set($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入关键词ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'item-ad-set') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $item = K_Item::find($id);
        if(!$item) return response_error([],"该内容不存在，刷新页面重试！");
        if($item->item_type != 88 ) return response_error([],"该内容不是广告，并不能设置为贴片广告！");
        if($item->active != 1 ) return response_error([],"该内容还未发布，请先发布再设置！");

        $me = Auth::guard('org')->user();
        if($item->owner_id != $me->id) return response_error([],"你没有操作权限！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $me->timestamps = false;
            $me->advertising_id = $id;
            $bool = $me->save();
            if(!$bool) throw new Exception("update--user--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 【ITEM】推送
    public function operate_item_ad_cancel($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入关键词ID',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'item-ad-cancel') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $item = K_Item::find($id);
        if(!$item) return response_error([],"该内容不存在，刷新页面重试！");
        if($item->item_type != 88 ) return response_error([],"该内容不是广告，并不能设置为贴片广告！");

        $me = Auth::guard('org')->user();
        if($item->owner_id != $me->id) return response_error([],"你没有操作权限！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $me->timestamps = false;
            $me->advertising_id = 0;
            $bool = $me->save();
            if(!$bool) throw new Exception("update--user--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }








    // 【内容】返回-内容-HTML
    public function get_the_item_html($item)
    {
        $item->custom = json_decode($item->custom);
        $item_array[0] = $item;
        $return['item_list'] = $item_array;

        // method A
        $item_html = view(env('TEMPLATE_K_COMMON').'component.item-list')->with($return)->__toString();
//        // method B
//        $item_html = view(env('TEMPLATE_DOC_FRONT').'component.item-list')->with($return)->render();
//        // method C
//        $view = view(env('TEMPLATE_DOC_FRONT').'component.item-list')->with($return);
//        $item_html=response($view)->getContent();

        return $item_html;
    }


}