<?php
namespace App\Repositories\Org;

use App\Models\K\K_User;
use App\Models\K\K_Item;
use App\Models\K\K_Pivot_User_Relation;
use App\Models\K\K_Notification;
use App\Models\K\K_Record;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode, Excel;

class IndexRepository {

    private $model;
    private $repo;
    public function __construct()
    {
//        $this->model = new K_User;
    }


    // 返回（后台）主页视图
    public function view_org_index()
    {
        $me = Auth::guard("org")->user();

        return view(env('TEMPLATE_ADMIN').'org.index')
            ->with([
                'index_data'=>[],
                'consumption_data'=>[],
                'insufficient_clients'=>[]
            ]);
    }




    /*
     * 用户基本信息
     */

    // 【基本信息】返回--视图
    public function view_info_index()
    {
        $me = Auth::guard('org')->user();
        return view(env('TEMPLATE_ADMIN').'org.entrance.info.index')->with(['data'=>$me]);
    }

    // 【基本信息】返回-编辑-视图
    public function view_info_edit()
    {
        $me = Auth::guard('org')->user();
        return view(env('TEMPLATE_ADMIN').'org.entrance.info.edit')->with(['data'=>$me]);
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
        return view(env('TEMPLATE_ADMIN').'org.entrance.info.password-reset')->with(['data'=>$me]);
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
        return view(env('TEMPLATE_ADMIN').'org.entrance.introduction.index')
            ->with(['data'=>$data,'sidebar_me_introduction_active'=>'active menu-open']);
    }

    // 【基本信息】返回-编辑-视图
    public function view_introduction_edit()
    {
        $me = Auth::guard('org')->user();
        $data = K_Item::find($me->introduction_id);
        if(!$data) $data = [];
        return view(env('TEMPLATE_ADMIN').'org.entrance.introduction.edit')->with(['data'=>$data]);
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
                $mine_data['item_type'] = 9;
            }
            else
            {
                $item = K_Item::find($me->introduction_id);
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
                    $mine_original_file = $me->cover_pic;
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




    // 【用户】【成员】返回-列表-视图
    public function view_user_my_member_list($post_data)
    {
        return view(env('TEMPLATE_ADMIN').'org.entrance.user.user-my-member-list')
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
        return view(env('TEMPLATE_ADMIN').'org.entrance.user.user-my-fans-list')
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
        return view(env('TEMPLATE_ADMIN').'org.entrance.user.user-my-sponsor-list')
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
        return view(env('TEMPLATE_ADMIN').'org.entrance.user.relation-sponsor-list')
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




    // 【成员】移除
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

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.statistic.statistic-index';
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

        // 打开APP类型【占比】
        $shared_all_scale = K_Record::select('shared_location',DB::raw('count(*) as count'))
            ->groupBy('shared_location')
            ->where(['record_category'=>1,'record_type'=>1])
            ->where('item_id',$item_id)
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

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.statistic.statistic-item';
        return view($view_blade)->with($view_data);
    }










    // 【代理商】返回-详情-视图
    public function view_user_agent($post_data)
    {
        $me = Auth::guard("admin")->user();

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $user = User::find($id);
        if($user)
        {
            if(!in_array($user->usergroup,['Agent','Agent2'])) return response_error([],"该用户不存在，刷新页面试试！");
        }


        $user->fund_total = number_format($user->fund_total);
        $user->fund_balance = number_format($user->fund_balance);

        return view('mt.admin.entrance.user.agent')
            ->with([
                'user_data'=>$user
            ]);
    }
    // 【代理商】返回-客户列表-数据
    public function get_user_agent_client_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $id = $post_data["id"];
        $query = User::select('*')
            ->with('parent','ep','fund')
            ->withCount([
                'sites'=>function ($query) { $query->where('status',1)->whereIn('sitestatus',['优化中','待审核']); },
                'keywords'=>function ($query) { $query->where('status',1)->whereIn('keywordstatus',['优化中','待审核']); }
            ])
            ->where('pid',$id)
            ->where(['userstatus'=>'正常','status'=>1])
            ->whereIn('usergroup',['Service']);

        if(!empty($post_data['username'])) $query->where('username', 'like', "%{$post_data['username']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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
//            $v->fund_total = number_format($v->fund_total);
//            $v->fund_expense = number_format($v->fund_expense);
//            $v->fund_balance = number_format($v->fund_balance);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【代理商】充值
    public function operate_user_agent_recharge($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入用户名',
            'recharge-amount.required' => '请输入用户名',
            'recharge-amount.numeric' => '金额必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'id' => 'required',
            'recharge-amount' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $operate = $post_data["operate"];
        if($operate != 'recharge') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $mine = Auth::guard('admin')->user();
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限");

        $time = date('Y-m-d H:i:s');
        $amount = $post_data['recharge-amount'];
        // 充值金额不能为0
        if($amount == 0) return response_error([],"充值金额不能为0！");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent'])) return response_error([],"该用户不是1级代理商，你不能操作！");

        // 退款金额应该小于资金余额
        if($amount < 0)
        {
            if(($agent->fund_balance + $amount) < 0) return response_error([],"退款金额不能超过该账户余额");
        }


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $FundRechargeRecord = New FundRechargeRecord;
            $FundRechargeRecord_data['owner_id'] = $mine->id;
            $FundRechargeRecord_data['createuserid'] = $mine->id;
            $FundRechargeRecord_data['createusername'] = $mine->username;
            $FundRechargeRecord_data['createtime'] = $time;
            $FundRechargeRecord_data['userid'] = $id;
            $FundRechargeRecord_data['usertype'] = 'admin';
            $FundRechargeRecord_data['status'] = 1;
            $FundRechargeRecord_data['amount'] = $amount;

            $bool = $FundRechargeRecord->fill($FundRechargeRecord_data)->save();
            if($bool)
            {
                $agent->increment('fund_total',$amount);
                $agent->increment('fund_balance',$amount);
            }
            else throw new Exception("insert--fund-record--fail");

            DB::commit();
            return response_success(['id'=>$agent->id]);
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


    // 【代理商】关闭-充值限制
    public function operate_user_agent_recharge_limit_close($post_data)
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
        if($operate != 'recharge-limit-close') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent','Agent2'])) return response_error([],"该用户不是代理商，你不能操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["is_recharge_limit"] = 0;
            $bool = $agent->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

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
    // 【代理商】开启-充值限制
    public function operate_user_agent_recharge_limit_open($post_data)
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
        if($operate != 'recharge-limit-open') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $agent = User::find($id);
        if(!$agent) return response_error([],"该用户不存在，刷新页面重试");
        if(!in_array($agent->usergroup,['Agent','Agent2'])) return response_error([],"该用户不是代理商，你不能操作！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["is_recharge_limit"] = 1;
            $bool = $agent->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--user--fail");

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





    // 【客户】返回-列表-视图
    public function view_user_client_list()
    {
        $me = Auth::guard('admin')->user();

        $agents = User::where(['userstatus'=>'正常','status'=>1])->whereIn('usergroup',['Agent','Agent2'])->orderby('id','desc')->get();

        $insufficient_clients = User::where(['userstatus'=>'正常','status'=>1,'usergroup'=>'Service'])->where('fund_expense_daily','>',0)
            ->whereRaw("fund_balance < (fund_expense_daily * 7)")->get();

        $view_blade = 'mt.admin.entrance.user.client-list';
        return view($view_blade)->with([
            'sidebar_client_list_active'=>'active menu-open',
            'agents'=>$agents,
            'insufficient_clients'=>$insufficient_clients
        ]);
    }
    // 【客户】返回-列表-数据
    public function get_user_client_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = User::select('*')
//        $query = User::select('id','pid','epid','username','usergroup','createtime')
//            ->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
            ->with('parent','ep','fund')
            ->withCount([
                'sites'=>function ($query) { $query->where('status',1)->whereIn('sitestatus',['优化中','待审核']); },
                'keywords'=>function ($query) { $query->where('status',1)->whereIn('keywordstatus',['优化中','待审核']); }
            ])
            ->where(['userstatus'=>'正常','status'=>1])
            ->whereIn('usergroup',['Service']);

        if(!empty($post_data['username'])) $query->where('username', 'like', "%{$post_data['username']}%");
        if(!empty($post_data['agent_id']))
        {
            $agent_id = $post_data['agent_id'];
            $query->whereHas('parent',function ($query1) use ($agent_id)  { $query1->where('id',$agent_id); });
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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
//            $v->fund_total = number_format($v->fund_total);
//            $v->fund_expense = number_format($v->fund_expense);
//            $v->fund_balance = number_format($v->fund_balance);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【客户】返回-详情-视图
    public function view_user_client($post_data)
    {
        $me = Auth::guard("admin")->user();

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！！");

        $user = User::find($id);
        if($user)
        {
            if($user->usergroup != 'Service') return response_error([],"该用户不存在，刷新页面试试！");
        }

        $user_data = $user;


        /*
         * 关键词
         */
        // 今日优化关键词
        $keyword_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])->where('createuserid',$id)->count();
        $user_data->keyword_count = $keyword_count;

        // 今日检测关键词
        $keyword_detect_count = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$id)
            ->count();
        $user_data->keyword_detect_count = $keyword_detect_count;

        // 今日达标关键词
        $keyword_standard_data = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'已达标'])
            ->whereDate('detectiondate',date("Y-m-d"))
            ->where('createuserid',$id)
            ->first(
                array(
                    \DB::raw('COUNT(*) as keyword_standard_count'),
                    \DB::raw('SUM(price) as keyword_standard_cost_sum')
                )
            );
        $user_data->keyword_standard_count = $keyword_standard_data->keyword_standard_count;
        $user_data->keyword_standard_cost_sum = $keyword_standard_data->keyword_standard_cost_sum;


        return view('mt.admin.entrance.user.client')
            ->with([
                'user_data'=>$user_data
            ]);
    }
    // 【客户】返回-关键词列表-数据
    public function get_user_client_keyword_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $id = $post_data["id"];
        $query = SEOKeyword::select('*')->with('creator')->where('createuserid',$id);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
//        if(!empty($post_data['keywordstatus'])) $query->where('keywordstatus', $post_data['keywordstatus'])->where('status', 1);
        if(!empty($post_data['keywordstatus']))
        {
            if($post_data['keywordstatus'] == "默认")
            {
                $query->where('status',1)->whereIn('keywordstatus',['优化中','待审核']);
            }
            else if($post_data['keywordstatus'] == "全部")
            {
            }
            else if($post_data['keywordstatus'] == "已删除")
            {
                $query->where('status','!=',1);
            }
            else
            {
                $query->where(['status'=>1,'keywordstatus'=>$post_data['keywordstatus']]);
            }
        }
        else
        {
            $query->where(['status'=>1,'keywordstatus'=>['优化中','待审核']]);
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

    // 【客户】删除
    public function operate_user_client_delete($post_data)
    {
        $admin = Auth::guard('admin')->user();
        if($admin->usergroup != "Manage") return response_error([],"你没有操作权限");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"id有误，刷新页面试试！");

        $mine = User::find($id);
        if($mine)
        {
            if(!in_array($mine->usergroup,['Service'])) return response_error([],"该用户不是客户");

//        if($mine->fund_balance > 0) return response_error([],"该用户还有余额");

            // 启动数据库事务
            DB::beginTransaction();
            try
            {
                $content = $mine->content;
                $cover_pic = $mine->cover_pic;


                // 删除【站点】
                $deletedRows_1 = SEOSite::where('createuserid', $id)->delete();

                // 删除【关键词】
                $deletedRows_2 = SEOKeyword::where('createuserid', $id)->delete();

                // 删除【关键词检测记录】
                $deletedRows_3 = SEOKeywordDetectRecord::where('ownuserid', $id)->delete();

                // 删除【扣费记录】
                $deletedRows_4 = ExpenseRecord::where('ownuserid', $id)->delete();

                // 删除【用户】
//            $mine->pivot_menus()->detach(); // 删除相关目录
                $bool = $mine->delete();
                if(!$bool) throw new Exception("delete--user--fail");

                DB::commit();

                return response_success([]);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '删除失败，请重试';
//                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([],$msg);
            }

        }
        else return response_error([],'账户不存在，刷新页面试试');
    }













    // 【ITEM】返回-添加-视图
    public function view_item_item_create($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $item_type = 'item';
        $item_type_text = '内容';
        $title_text = '添加'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-all-list';

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';
        return view($view_blade)->with([
            'operate'=>'create',
            'operate_id'=>0,
            'category'=>'item',
            'type'=>$item_type,
            'item_type_text'=>$item_type_text,
            'title_text'=>$title_text,
            'list_text'=>$list_text,
            'list_link'=>$list_link,
        ]);
    }
    // 【ITEM】返回-编辑-视图
    public function view_item_item_edit($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $id = $post_data["id"];
        $mine = K_Item::with(['user'])->find($id);
        if(!$mine) return view(env('TEMPLATE_ADMIN').'org.errors.404');


        $item_type = 'item';
        $item_type_text = '内容';
        $title_text = '编辑'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-list';

        if($mine->item_type == 1)
        {
            $item_type = 'article';
            $item_type_text = '文章';
            $title_text = '编辑'.$item_type_text;
            $list_text = $item_type_text.'列表';
            $list_link = '/org/item/item-article-list';
        }
        else if($mine->item_type == 11)
        {
            $item_type = 'activity';
            $item_type_text = '活动';
            $title_text = '编辑'.$item_type_text;
            $list_text = $item_type_text.'列表';
            $list_link = '/org/item/item-activity-list';
        }
        else if($mine->item_type == 88)
        {
            $item_type = 'advertising';
            $item_type_text = '广告';
            $title_text = '编辑'.$item_type_text;
            $list_text = $item_type_text.'列表';
            $list_link = '/org/item/item-advertising-list';
        }

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';

        if($id == 0)
        {
            return view($view_blade)->with([
                'operate'=>'create',
                'operate_id'=>$id,
                'category'=>'item',
                'type'=>$item_type,
                'item_type_text'=>$item_type_text,
                'title_text'=>$title_text,
                'list_text'=>$list_text,
                'list_link'=>$list_link,
            ]);
        }
        else
        {
            $mine = K_Item::with(['user'])->find($id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with([
                    'operate'=>'edit',
                    'operate_id'=>$id,
                    'category'=>'item',
                    'type'=>$item_type,
                    'item_type_text'=>$item_type_text,
                    'title_text'=>$title_text,
                    'list_text'=>$list_text,
                    'list_link'=>$list_link,
                    'data'=>$mine
                ]);
            }
            else return response("该文章不存在！", 404);
        }
    }

    // 【ITEM】返回-添加-视图
    public function view_item_article_create($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $item_type = 'article';
        $item_type_text = '文章';
        $title_text = '添加'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-article-list';

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';
        return view($view_blade)->with([
            'operate'=>'create',
            'operate_id'=>0,
            'category'=>'item',
            'type'=>$item_type,
            'item_type_text'=>$item_type_text,
            'title_text'=>$title_text,
            'list_text'=>$list_text,
            'list_link'=>$list_link,
        ]);
    }
    // 【ITEM】返回-编辑-视图
    public function view_item_article_edit($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $id = $post_data["id"];
        $mine = K_Item::with(['user'])->find($id);
        if(!$mine) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $item_type = 'article';
        $item_type_text = '文章';
        $title_text = '编辑'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-article-list';

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';

        if($id == 0)
        {
            return view($view_blade)->with([
                'operate'=>'create',
                'operate_id'=>$id,
                'category'=>'item',
                'type'=>$item_type,
                'item_type_text'=>$item_type_text,
                'title_text'=>$title_text,
                'list_text'=>$list_text,
                'list_link'=>$list_link,
            ]);
        }
        else
        {
            $mine = K_Item::with(['user'])->find($id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with([
                    'operate'=>'edit',
                    'operate_id'=>$id,
                    'category'=>'item',
                    'type'=>$item_type,
                    'item_type_text'=>$item_type_text,
                    'title_text'=>$title_text,
                    'list_text'=>$list_text,
                    'list_link'=>$list_link,
                    'data'=>$mine
                ]);
            }
            else return response("该文章不存在！", 404);
        }
    }

    // 【ITEM】返回-添加-视图
    public function view_item_activity_create($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $item_type = 'activity';
        $item_type_text = '活动';
        $title_text = '添加'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-activity-list';

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';
        return view($view_blade)->with([
            'operate'=>'create',
            'operate_id'=>0,
            'category'=>'item',
            'type'=>$item_type,
            'item_type_text'=>$item_type_text,
            'title_text'=>$title_text,
            'list_text'=>$list_text,
            'list_link'=>$list_link,
        ]);
    }
    // 【ITEM】返回-编辑-视图
    public function view_item_activity_edit($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $id = $post_data["id"];
        $mine = K_Item::with(['user'])->find($id);
        if(!$mine) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $item_type = 'activity';
        $item_type_text = '活动';
        $title_text = '编辑'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-activity-list';

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0, 'category'=>'item', 'type'=>'activity']);
        }
        else
        {
            $mine = K_Item::with(['user'])->find($id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with([
                    'operate'=>'edit',
                    'operate_id'=>$id,
                    'category'=>'item',
                    'type'=>$item_type,
                    'item_type_text'=>$item_type_text,
                    'title_text'=>$title_text,
                    'list_text'=>$list_text,
                    'list_link'=>$list_link,
                    'data'=>$mine
                ]);
            }
            else return response("该活动不存在！", 404);
        }
    }

    // 【ITEM】返回-添加-视图
    public function view_item_advertising_create($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $item_type = 'advertising';
        $item_type_text = '广告';
        $title_text = '编辑'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-advertising-list';

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';
        return view($view_blade)->with([
            'operate'=>'create',
            'operate_id'=>0,
            'category'=>'item',
            'type'=>$item_type,
            'item_type_text'=>$item_type_text,
            'title_text'=>$title_text,
            'list_text'=>$list_text,
            'list_link'=>$list_link,
        ]);
    }
    // 【ITEM】返回-编辑-视图
    public function view_item_advertising_edit($post_data)
    {
        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11,88])) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $id = $post_data["id"];
        $mine = K_Item::with(['user'])->find($id);
        if(!$mine) return view(env('TEMPLATE_ADMIN').'org.errors.404');

        $item_type = 'advertising';
        $item_type_text = '广告';
        $title_text = '编辑'.$item_type_text;
        $list_text = $item_type_text.'列表';
        $list_link = '/org/item/item-advertising-list';

        $view_blade = env('TEMPLATE_ADMIN').'org.entrance.item.item-edit';

        if($id == 0)
        {
            return view($view_blade)->with([
                'operate'=>'create',
                'operate_id'=>0,
                'category'=>'item',
                'item_type_text'=>$item_type_text,
                'title_text'=>$title_text,
                'list_text'=>$list_text,
                'list_link'=>$list_link,
            ]);
        }
        else
        {
            $mine = K_Item::with(['user'])->find($id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with([
                    'operate'=>'edit',
                    'operate_id'=>$id,
                    'category'=>'item',
                    'type'=>$item_type,
                    'item_type_text'=>$item_type_text,
                    'title_text'=>$title_text,
                    'list_text'=>$list_text,
                    'list_link'=>$list_link,
                    'data'=>$mine
                ]);
            }
            else return response("该广告不存在！", 404);
        }
    }


    // 【ITEM】保存-数据
    public function operate_item_item_save($post_data)
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

        $me = Auth::guard('org')->user();
        if(!in_array($me->user_type,[11])) return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];
        $type = $post_data["type"];

        if($operate == 'create') // 添加 ( $id==0，添加一个内容 )
        {
            $mine = new K_Item;
            $post_data["owner_id"] = $me->id;
            $post_data["item_category"] = 1;
            if($type == 'item') $post_data["item_type"] = 0;
            else if($type == 'article') $post_data["item_type"] = 1;
            else if($type == 'activity') $post_data["item_type"] = 11;
            else if($type == 'advertising') $post_data["item_type"] = 88;
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
                $qr_code_path = "resource/unique/qr_code/";  // 保存目录
                if(!file_exists(storage_path($qr_code_path)))
                    mkdir(storage_path($qr_code_path), 0777, true);
                // qr_code 图片文件
                $url = 'http://www.k-org.cn/item/'.$mine->id;  // 目标 URL
                $filename = 'qr_code_item_'.$mine->id.'.png';  // 目标 file
                $qr_code = $qr_code_path.$filename;
                QrCode::errorCorrection('H')->format('png')->size(640)->margin(0)->encoding('UTF-8')->generate($url,storage_path($qr_code));

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













    /*
     * 业务系统
     */


    // 【ITEM】返回-列表-视图
    public function view_item_item_list($post_data)
    {
        $item_type = isset($post_data['type']) ? $post_data['type'] : 'all';
        if($item_type == 'all') $sidebar_active = 'sidebar_item_all_list_active';
        else if($item_type == 'article') $sidebar_active = 'sidebar_item_article_list_active';
        else if($item_type == 'activity') $sidebar_active = 'sidebar_item_activity_list_active';
        else if($item_type == 'advertising') $sidebar_active = 'sidebar_item_advertising_list_active';
        else $sidebar_active = 'sidebar_item_item_list_active';

        $view_blade= env('TEMPLATE_ADMIN').'org.entrance.item.item-list';
        return view($view_blade)
            ->with([
                'sidebar_item_active'=>'active',
                $sidebar_active => 'active'
            ]);
    }
    // 【ITEM】获取-列表-数据
    public function get_item_item_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Item::select('*')
            ->with('owner')
            ->where(['item_category'=>1])
            ->where('owner_id',$me->id);

        $item_type = $post_data['type'];
        if($item_type == 'article') $query->where('item_type',1);
        else if($item_type == 'activity') $query->where('item_type',11);
        else if($item_type == 'advertising') $query->where('item_type',88);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            $list[$k]->description = replace_blank($v->description);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 【ITEM】返回-全部内容-视图
    public function view_item_all_list($post_data)
    {
        $view_blade= env('TEMPLATE_ADMIN').'org.entrance.item.item-all-list';
        return view($view_blade)
            ->with([
                'sidebar_item_active'=>'active',
                'sidebar_item_all_list_active'=>'active'
            ]);
    }
    // 【ITEM】获取-全部内容-数据
    public function get_item_all_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Item::select('*')
            ->with('owner')
            ->where(['item_category'=>1])
            ->where('owner_id',$me->id);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            $list[$k]->description = replace_blank($v->description);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【ITEM】返回-广告列表-视图
    public function view_item_article_list($post_data)
    {
        $view_blade= env('TEMPLATE_ADMIN').'org.entrance.item.item-article-list';
        return view($view_blade)
            ->with([
                'sidebar_item_active'=>'active',
                'sidebar_item_article_list_active'=>'active'
            ]);
    }
    // 【ITEM】获取-广告列表-数据
    public function get_item_article_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Item::select('*')
            ->with('owner')
            ->where(['item_category'=>1,'item_type'=>1])
            ->where('owner_id',$me->id);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            $list[$k]->description = replace_blank($v->description);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【ITEM】返回-广告列表-视图
    public function view_item_activity_list($post_data)
    {
        $view_blade= env('TEMPLATE_ADMIN').'org.entrance.item.item-activity-list';
        return view($view_blade)
            ->with([
                'sidebar_item_active'=>'active',
                'sidebar_item_activity_list_active'=>'active'
            ]);
    }
    // 【ITEM】获取-广告列表-数据
    public function get_item_activity_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Item::select('*')
            ->with('owner')
            ->where(['item_category'=>1,'item_type'=>11])
            ->where('owner_id',$me->id);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            $list[$k]->description = replace_blank($v->description);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【ITEM】返回-广告列表-视图
    public function view_item_advertising_list($post_data)
    {
        $view_blade= env('TEMPLATE_ADMIN').'org.entrance.item.item-advertising-list';
        return view($view_blade)
            ->with([
                'sidebar_item_active'=>'active',
                'sidebar_item_advertising_list_active'=>'active'
            ]);
    }
    // 【ITEM】获取-广告列表-数据
    public function get_item_advertising_list_datatable($post_data)
    {
        $me = Auth::guard("org")->user();
        $query = K_Item::select('*')
            ->with('owner')
            ->where(['item_category'=>1,'item_type'=>88])
            ->where('owner_id',$me->id);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
            $list[$k]->description = replace_blank($v->description);

            if($v->id == $me->advertising_id) $list[$k]->adevertising_is_me = 1;
            else $list[$k]->adevertising_is_me = 0;
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
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
    public function operate_item_item_publish($post_data)
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
        if($operate != 'item-publish') return response_error([],"参数有误！");
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
            $item->active = 1;
            $item->published_at = time();
            $bool = $item->save();
            if(!$bool) throw new Exception("update--item--fail");

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














    // 【站点】审核
    public function operate_business_site_review($post_data)
    {
        $mine = Auth::guard('admin')->user();
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"id有误，刷新页面试试！");

        $current_time = date('Y-m-d H:i:s');
        $site_status = $post_data["sitestatus"];
        if(!in_array($site_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");


        $site = SEOSite::find($id);
        if($site)
        {
        }
        else return response_error([],'账户不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $site_data["reviewuserid"] = $mine->id;
            $site_data["reviewusername"] = $mine->username;
            $site_data["reviewdate"] = $current_time;
            $site_data["sitestatus"] = $site_status;

            $bool = $site->fill($site_data)->save();
            if(!$bool) throw new Exception("update--site--fail");

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
    // 【站点】批量审核
    public function operate_business_site_review_bulk($post_data)
    {
        $messages = [
            'bulk_site_id.required' => '请选择站点！',
            'bulk_site_status.required' => '请选择状态！',
        ];
        $v = Validator::make($post_data, [
            'bulk_site_id' => 'required',
            'bulk_site_status' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $site_status = $post_data["bulk_site_status"];
        if(!in_array($site_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $current_time = date('Y-m-d H:i:s');

            $site_ids = $post_data["bulk_site_id"];
            foreach($site_ids as $key => $site_id)
            {
                if(intval($site_id) !== 0 && !$site_id) return response_error([],"id有误，刷新页面试试！");

                $site = SEOSite::where(["id"=>$site_id,"sitestatus"=>"待审核"])->first();
                if(!$site) return response_error([],'账户不存在，刷新页面试试！');

                $site_data["reviewuserid"] = $me->id;
                $site_data["reviewusername"] = $me->username;
                $site_data["reviewdate"] = $current_time;
                $site_data["sitestatus"] = $site_status;

                $bool = $site->fill($site_data)->save();
                if(!$bool) throw new Exception("update--site--fail");
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

    // 【待选站点】删除
    public function operate_business_site_todo_delete($post_data)
    {
        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $item = SEOSite::find($id);
        if(!$item) return response_error([],'站点不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $item->content;
            $cover_pic = $item->cover_pic;

            // 删除【该条目】
//            $item->pivot_menus()->detach(); // 删除相关目录
            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--site--fail");

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
    // 【待选站点】批量删除
    public function operate_business_site_todo_delete_bulk($post_data)
    {
        $messages = [
            'bulk_site_id.required' => '请选择站点！',
        ];
        $v = Validator::make($post_data, [
            'bulk_site_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $site_ids = $post_data["bulk_site_id"];
            foreach($site_ids as $key => $site_id)
            {
                if(intval($site_id) !== 0 && !$site_id) return response_error([],"该站点不存在，刷新页面试试！");

                $item = SEOSite::where(["id"=>$site_id,"sitestatus"=>"待审核"])->first();
                if(!$item) return response_error([],'站点不存在，刷新页面试试！');

                $content = $item->content;
                $cover_pic = $item->cover_pic;

                // 删除【该条目】
//                $item->pivot_menus()->detach(); // 删除相关目录
                $bool = $item->delete();
                if(!$bool) throw new Exception("delete--site--fail");
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


    // 【站点】获取-详情
    public function operate_business_site_get($post_data)
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
        if($operate != 'site-get') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！！");

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $item = SEOSite::find($id);
        if(!$item) return response_error([],"该内容不存在，刷新页面试试！");
        return response_success($item,"");

    }
    // 【站点】删除
    public function operate_business_site_delete($post_data)
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
        if($operate != 'delete-site') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $site = SEOSite::find($id);
        if(!$site) return response_error([],"该站点不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["status"] = 0;
            $bool = $site->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--site--fail");

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
    // 【站点】合作停
    public function operate_business_site_stop($post_data)
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
        if($operate != 'stop-site') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $site = SEOSite::find($id);
        if(!$site) return response_error([],"该站点不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["sitestatus"] = "合作停";
            $bool = $site->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--site--fail");

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
    // 【站点】再合作
    public function operate_business_site_start($post_data)
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
        if($operate != 'start-site') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $site = SEOSite::find($id);
        if(!$site) return response_error([],"该站点不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["sitestatus"] = "优化中";
            $bool = $site->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--site--fail");

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
    // 【站点】保存数据
    public function operate_business_site_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'sitename.required' => '请输入站点名',
            'website.required' => '请输入站点',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'sitename' => 'required',
            'website' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        if($operate != 'site-edit') return response_error([],"参数有误！");
        $id = $post_data["operate_id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！！");

        $site = SEOSite::find($id);
        if(!$site) return response_error([],"该站点不存在，刷新页面重试！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            $site_data = $post_data;
            unset($site_data['operate']);
            unset($site_data['operate_id']);
            $bool = $site->fill($site_data)->save();
            if($bool)
            {
                // 封面图片
                if(!empty($post_data["cover"]))
                {
                    // 删除原封面图片
                    $site_cover_pic = $site->cover_pic;
                    if(!empty($site_cover_pic) && file_exists(storage_path("resource/" . $site_cover_pic)))
                    {
                        unlink(storage_path("resource/" . $site_cover_pic));
                    }

                    $result = upload_storage($post_data["cover"]);
                    if($result["result"])
                    {
                        $site->cover_pic = $result["local"];
                        $site->save();
                    }
                    else throw new Exception("upload-cover-fail");
                }

                $keyword_data['sitename'] = $site_data['sitename'];
                $keyword_data['website'] = $site_data['website'];
                $keywords_count = SEOKeyword::where('siteid',$id)->count();
                $bool_1 = SEOKeyword::where('siteid',$id)->update($keyword_data);
                if($keywords_count != $bool_1)  return response_error([],"update--keyword-fail");

            }
            else throw new Exception("insert--site--fail");

            DB::commit();
            return response_success(['id'=>$site->id]);
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




    // 【关键词】返回-列表-视图
    public function show_business_keyword_list()
    {
        $data = [];

        $query = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1]);

        // 优化关键词总数
        $keyword_count = $query->count('*');
        $data['keyword_count'] = $keyword_count;

        // 检测关键词总数
        $query_1 = $query->whereDate('detectiondate',date("Y-m-d"));
        $keyword_detect_count = $query_1->count("*");
        $data['keyword_detect_count'] = $keyword_detect_count;

        // 已达标关键词总数
        $query_2 = $query->whereDate('standarddate',date("Y-m-d"))->where('standardstatus','已达标');
        $keyword_standard_count = $query_2->count("*");
        $data['keyword_standard_count'] = $keyword_standard_count;

        // 已达标关键词消费
        $keyword_standard_fund_sum = $query_2->sum('latestconsumption');
        $data['keyword_standard_fund_sum'] = $keyword_standard_fund_sum;


        if($keyword_count > 0)
        {
            $data['keyword_standard_rate'] = round($data['keyword_standard_count']/$keyword_count*100)."％";
        }
        else $data['keyword_standard_rate'] = "--";


        $query_detect = SEOKeywordDetectRecord::whereDate('createtime',date("Y-m-d"))->where('rank','>',0)->where('rank','<=',10);
        $keyword_standard_fund_sum_1 = $query_detect->count('*');
        $data['keyword_standard_sum_by_detect'] = $keyword_standard_fund_sum_1;


        $query_expense = ExpenseRecord::whereDate('createtime',date("Y-m-d"));
        $keyword_standard_fund_sum_2 = $query_expense->count('*');
        $data['keyword_standard_sum_by_expense'] = $keyword_standard_fund_sum_2;

        return view('mt.admin.entrance.business.keyword-list')
            ->with([
                'data'=>$data,
                'sidebar_business_keyword_active'=>'active',
                'sidebar_business_keyword_list_active'=>'active'
            ]);
    }
    // 【关键词】返回-列表-数据
    public function get_business_keyword_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = SEOKeyword::select('*')->with('creator');

        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
            }
        }
        if(!empty($post_data['keywordstatus']))
        {
            if($post_data['keywordstatus'] == "默认")
            {
                $query->where('status',1)->whereIn('keywordstatus',['优化中','待审核']);
            }
            else if($post_data['keywordstatus'] == "全部")
            {
            }
            else if($post_data['keywordstatus'] == "已删除")
            {
                $query->where('status','!=',1);
            }
            else
            {
                $query->where(['status'=>1,'keywordstatus'=>$post_data['keywordstatus']]);
            }
        }
        else
        {
            $query->where(['status'=>1,'keywordstatus'=>['优化中','待审核']]);
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

    // 【今日关键词】返回-列表-视图
    public function show_business_keyword_today_list()
    {
        $data = [];

        $query = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1]);

        // 优化关键词总数
        $keyword_count = $query->count('*');
        $data['keyword_count'] = $keyword_count;

        // 检测关键词总数
        $query_1 = $query->whereDate('detectiondate',date("Y-m-d"));
        $keyword_detect_count = $query_1->count("*");
        $data['keyword_detect_count'] = $keyword_detect_count;

        // 已达标关键词总数
        $query_2 = $query->whereDate('standarddate',date("Y-m-d"))->where('standardstatus','已达标');
        $keyword_standard_count = $query_2->count("*");
        $data['keyword_standard_count'] = $keyword_standard_count;

        // 已达标关键词消费
        $keyword_standard_fund_sum = $query_2->sum('latestconsumption');
        $data['keyword_standard_fund_sum'] = $keyword_standard_fund_sum;


        if($keyword_count > 0)
        {
            $data['keyword_standard_rate'] = round($data['keyword_standard_count']/$keyword_count*100)."％";
        }
        else $data['keyword_standard_rate'] = "--";


//        $query_detect = SEOKeywordDetectRecord::whereDate('createtime',date("Y-m-d"))->where('rank','>',0)->where('rank','<=',10);
//        $keyword_standard_count_by_detect = $query_detect->count('*');
//        $data['keyword_standard_count_by_detect'] = $keyword_standard_count_by_detect;
//
//
//        $query_expense = ExpenseRecord::whereDate('createtime',date("Y-m-d"));
//        $keyword_standard_count_by_expense = $query_expense->count('*');
//        $data['keyword_standard_count_by_expense'] = $keyword_standard_count_by_expense;
//
//        $keyword_standard_fund_sum_by_expense = $query_expense->sum('price');
//        $data['keyword_standard_fund_sum_by_expense'] = $keyword_standard_fund_sum_by_expense;

//        dd($data);

        return view('mt.admin.entrance.business.keyword-today-list')
            ->with([
                'data'=>$data,
                'sidebar_business_keyword_active'=>'active',
                'sidebar_business_keyword_today_active'=>'active'
            ]);
    }
    // 【今日关键词】返回-列表-数据
    public function get_business_keyword_today_list_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = SEOKeyword::select('*')->with('creator')
            ->where(['keywordstatus'=>'优化中','status'=>1]);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
            }
        }
        if(!empty($post_data['keywordstatus']))
        {
            if($post_data['keywordstatus'] == "已删除")
            {
                $query->where('status','!=',1);
            }
            else
            {
                $query->where(['status'=>1,'keywordstatus'=>$post_data['keywordstatus']]);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

    // 【今日新增上词关键词】返回-列表-视图
    public function show_business_keyword_today_newly_list()
    {
        $data = [];

        $query = SEOKeyword::where(['keywordstatus'=>'优化中','status'=>1]);

        // 优化关键词总数
        $keyword_count = $query->count('*');
        $data['keyword_count'] = $keyword_count;

        // 检测关键词总数
        $query_1 = $query->whereDate('detectiondate',date("Y-m-d"));
        $keyword_detect_count = $query_1->count("*");
        $data['keyword_detect_count'] = $keyword_detect_count;

        // 已达标关键词总数
        $query_2 = $query->whereDate('standarddate',date("Y-m-d"))->where('standardstatus','已达标');
        $keyword_standard_count = $query_2->count("*");
        $data['keyword_standard_count'] = $keyword_standard_count;

        // 已达标关键词消费
        $keyword_standard_fund_sum = $query_2->sum('latestconsumption');
        $data['keyword_standard_fund_sum'] = $keyword_standard_fund_sum;


//        $query_detect = SEOKeywordDetectRecord::whereDate('createtime',date("Y-m-d"))->where('rank','>',0)->where('rank','<=',10);
//        $keyword_standard_count_by_detect = $query_detect->count('*');
//        $data['keyword_standard_count_by_detect'] = $keyword_standard_count_by_detect;
//
//
//        $query_expense = ExpenseRecord::whereDate('createtime',date("Y-m-d"));
//        $keyword_standard_count_by_expense = $query_expense->count('*');
//        $data['keyword_standard_count_by_expense'] = $keyword_standard_count_by_expense;
//
//        $keyword_standard_fund_sum_by_expense = $query_expense->sum('price');
//        $data['keyword_standard_fund_sum_by_expense'] = $keyword_standard_fund_sum_by_expense;

//        dd($data);

        return view('mt.admin.entrance.business.keyword-today-newly-list')
            ->with([
                'data'=>$data,
                'sidebar_business_keyword_active'=>'active',
                'sidebar_business_keyword_today_newly_active'=>'active'
            ]);
    }
    // 【今日新增上词关键词】返回-列表-数据
    public function get_business_keyword_today_newly_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $query = SEOKeyword::select('*')
            ->with([
                'creator',
                'detects'=>function($query) {
                    $query->whereDate('detect_time','>',date("Y-m-d",strtotime("-8 day")))->orderby('id','desc');
                }
            ])
            ->where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'已达标'])
            ->whereHas('detects',function($query) {
                $query
                    ->whereDate('detect_time','>',date("Y-m-d",strtotime("-2 day")))
                    ->where(function($query) {
                        $query->where('rank','<=',0)->orWhere('rank','>',10);
                    });
            });

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
            }
        }
        if(!empty($post_data['keywordstatus']))
        {
            if($post_data['keywordstatus'] == "已删除")
            {
                $query->where('status','!=',1);
            }
            else
            {
                $query->where(['status'=>1,'keywordstatus'=>$post_data['keywordstatus']]);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

    // 【异常关键词】返回-列表-视图
    public function show_business_keyword_anomaly_list()
    {
        $data = [];

        return view('mt.admin.entrance.business.keyword-anomaly-list')
            ->with([
                'data'=>$data,
                'sidebar_business_keyword_active'=>'active',
                'sidebar_business_keyword_anomaly_active'=>'active'
            ]);
    }
    // 【异常关键词】返回-列表-数据
    public function get_business_keyword_anomaly_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $query = SEOKeyword::select('*')
            ->with([
                'creator',
                'detects'=>function($query) {
                    $query->whereDate('detect_time','>',date("Y-m-d",strtotime("-8 day")))->orderby('id','desc');
                }
            ])
            ->where(['keywordstatus'=>'优化中','status'=>1,'standardstatus'=>'未达标'])
            ->whereHas('detects',function($query) {
                $query->whereDate('detect_time','>',date("Y-m-d",strtotime("-8 day")))->where('rank','>',0)->where('rank','<=',10);
            });

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
            }
        }
        if(!empty($post_data['keywordstatus']))
        {
            if($post_data['keywordstatus'] == "已删除")
            {
                $query->where('status','!=',1);
            }
            else
            {
                $query->where(['status'=>1,'keywordstatus'=>$post_data['keywordstatus']]);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

    // 【待审核关键词】返回-列表-数据
    public function get_business_keyword_todo_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();
        $query = SEOKeyword::select('*')->with('creator')
            ->where(['status'=>1,'keywordstatus'=>'待审核']);

        if(!empty($post_data['keyword'])) $query->where('keyword', 'like', "%{$post_data['keyword']}%");
        if(!empty($post_data['website'])) $query->where('website', 'like', "%{$post_data['website']}%");
        if(!empty($post_data['searchengine'])) $query->where('searchengine', $post_data['searchengine']);
        if(!empty($post_data['latest_ranking']))
        {
            if($post_data['latest_ranking'] = 1)
            {
                $query->where('latestranking', '>', 0)->where('latestranking', '<=', 10);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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




    // 【关键词】审核
    public function operate_business_keyword_review($post_data)
    {
        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $current_time = date('Y-m-d H:i:s');
        $keyword_status = $post_data["keywordstatus"];
        $keyword_price = $post_data["review-price"];
        if(!in_array($keyword_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");

        $keyword = SEOKeyword::find($id);
        if($keyword)
        {
            $keyword_status_original = $keyword->keywordstatus;
            $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();
            if($keyword_owner)
            {
                if(($keyword_status_original == '待审核') && ($keyword_status == '优化中'))
                {
                    if($keyword_owner->fund_available < ($keyword_price * 30))
                    {
                        return response_error([],'用户可用余额不足！');
                    }
                }
            }
            else return response_error([],'用户不存在，刷新页面试试！');
        }
        else return response_error([],'关键词不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $keyword_data["reviewuserid"] = $me->id;
            $keyword_data["reviewusername"] = $me->username;
            $keyword_data["reviewdate"] = $current_time;
            $keyword_data["keywordstatus"] = $keyword_status;
            $keyword_data["price"] = $keyword_price;

            $bool = $keyword->fill($keyword_data)->save();
            if($bool)
            {
            }
            else throw new Exception("update--keyword--fail");

            if(($keyword_status_original == '待审核') && ($keyword_status == '优化中'))
            {

                $keyword_owner->fund_available = $keyword_owner->fund_available - ($keyword_price * 30);
                $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + ($keyword_price * 30);
                $keyword_owner->fund_frozen_init = $keyword_owner->fund_frozen_init + ($keyword_price * 30);
                $keyword_owner->save();

                $cart = SEOCart::find($keyword->cartid);
                $cart->price = $keyword_price;
                $cart->save();

                $freeze = new FundFreezeRecord;
                $freeze_date["owner_id"] = $keyword->createuserid;
                $freeze_date["siteid"] = $keyword->siteid;
                $freeze_date["keywordid"] = $keyword->id;
                $freeze_date["freezefunds"] = $keyword_price * 30;
                $freeze_date["createuserid"] = $me->id;
                $freeze_date["createusername"] = $me->username;
                $freeze_date["reguser"] = $me->username;
                $freeze_date["regtime"] = time();
                $bool_1 = $freeze->fill($freeze_date)->save();
                if($bool_1)
                {
                }
                else throw new Exception("insert--freeze--fail");
            }

            DB::commit();

            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 【关键词】批量审核
    public function operate_business_keyword_review_bulk($post_data)
    {
        $messages = [
            'bulk_keyword_id.required' => '请选择站点！',
            'bulk_keyword_status.required' => '请选择状态！',
        ];
        $v = Validator::make($post_data, [
            'bulk_keyword_id' => 'required',
            'bulk_keyword_status' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $keyword_status = $post_data["bulk_keyword_status"];
        if(!in_array($keyword_status,['待审核','优化中','合作停','被拒绝'])) return response_error([],"审核参数有误！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $current_time = date('Y-m-d H:i:s');

            $keyword_ids = $post_data["bulk_keyword_id"];
            foreach($keyword_ids as $key => $keyword_id)
            {
                if(intval($keyword_id) !== 0 && !$keyword_id) return response_error([],"id有误，刷新页面试试！");

                $keyword = SEOKeyword::find($keyword_id);
                if($keyword)
                {
                    $keyword_status_original = $keyword->keywordstatus;
                    $keyword_price = $keyword->price;
                    $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();
                    if($keyword_owner)
                    {
                        if(($keyword_status_original == '待审核') && ($keyword_status == '优化中'))
                        {
                            if($keyword_owner->fund_available < ($keyword_price * 30))
                            {
                                return response_error([],'用户可用余额不足！');
                            }
                        }
                    }
                    else return response_error([],'用户不存在，刷新页面试试！');
                }
                else return response_error([],'关键词不存在，刷新页面试试！');


                $keyword_data["reviewuserid"] = $me->id;
                $keyword_data["reviewusername"] = $me->username;
                $keyword_data["reviewdate"] = $current_time;
                $keyword_data["keywordstatus"] = $keyword_status;

                $bool = $keyword->fill($keyword_data)->save();
                if(!$bool) throw new Exception("update--keyword--fail");

                $cart = SEOCart::find($keyword->cartid);
                $cart->price = $keyword_price;
                $cart->save();

                if(($keyword_status_original == '待审核') && ($keyword_status == '优化中'))
                {
                    $keyword_owner->fund_available = $keyword_owner->fund_available - ($keyword_price * 30);
                    $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + ($keyword_price * 30);
                    $keyword_owner->fund_frozen_init = $keyword_owner->fund_frozen_init + ($keyword_price * 30);
                    $keyword_owner->save();

                    $freeze = new FundFreezeRecord;
                    $freeze_date["owner_id"] = $keyword->createuserid;
                    $freeze_date["siteid"] = $keyword->siteid;
                    $freeze_date["keywordid"] = $keyword->id;
                    $freeze_date["freezefunds"] = $keyword_price * 30;
                    $freeze_date["createuserid"] = $me->id;
                    $freeze_date["createusername"] = $me->username;
                    $freeze_date["reguser"] = $me->username;
                    $freeze_date["regtime"] = time();
                    $bool_1 = $freeze->fill($freeze_date)->save();
                    if(!$bool_1) throw new Exception("insert--freeze--fail");
                }
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }

    // 【待选关键词】删除
    public function operate_business_keyword_todo_delete($post_data)
    {
        $mine = Auth::guard('admin')->user();
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $item = SEOKeyword::where(["id"=>$id,"keywordstatus"=>"待审核"])->first();
        if(!$item) return response_error([],'关键词不存在，刷新页面试试！');

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $content = $item->content;
            $cover_pic = $item->cover_pic;

            // 删除【Cart】
//            $deletedRows_1 = SEOCart::find($item->id)->delete();

            // 删除【该条目】
//            $item->pivot_menus()->detach(); // 删除相关目录
            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--keyword--fail");

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
    // 【待选关键词】批量删除
    public function operate_business_keyword_todo_delete_bulk($post_data)
    {
        $messages = [
            'bulk_keyword_id.required' => '请选择关键词！',
        ];
        $v = Validator::make($post_data, [
            'bulk_keyword_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }
//        dd($post_data);

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");


        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $keyword_ids = $post_data["bulk_keyword_id"];
            foreach($keyword_ids as $key => $keyword_id)
            {
                if(intval($keyword_id) !== 0 && !$keyword_id) return response_error([],"ID有误，刷新页面试试！");

                $item = SEOKeyword::where(["id"=>$keyword_id,"keywordstatus"=>"待审核"])->first();
                if(!$item) return response_error([],'关键词不存在，刷新页面试试！');

                $content = $item->content;
                $cover_pic = $item->cover_pic;

                // 删除【Cart】
//                $deletedRows_1 = SEOCart::find($item->id)->delete();

                // 删除【该条目】
//                $item->pivot_menus()->detach(); // 删除相关目录
                $bool = $item->delete();
                if(!$bool) throw new Exception("delete--keyword--fail");
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




    // 【关键词】获取-详情
    public function operate_business_keyword_get($post_data)
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
        if($operate != 'keyword-get') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $item = SEOKeyword::find($id);
        if(!$item) return response_error([],"该内容不存在，刷新页面试试！");
        return response_success($item,"");

    }
    // 【关键词】删除
    public function operate_business_keyword_delete($post_data)
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
        if($operate != 'delete-keyword') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $keyword = SEOKeyword::find($id);
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["status"] = 0;
            $bool = $keyword->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--keyword--fail");

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
    // 【关键词】批量删除
    public function operate_business_keyword_delete_bulk($post_data)
    {
        $messages = [
            'bulk_keyword_id.required' => '请选择关键词！',
        ];
        $v = Validator::make($post_data, [
            'bulk_keyword_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $keyword_ids = $post_data["bulk_keyword_id"];
            foreach($keyword_ids as $key => $keyword_id)
            {
                if(intval($keyword_id) !== 0 && !$keyword_id) return response_error([],"参数ID有误！");

                $keyword = SEOKeyword::find($keyword_id);
                if($keyword)
                {
                    $update["status"] = 0;
                    $bool = $keyword->fill($update)->save();
                    if($bool)
                    {
                    }
                    else throw new Exception("update--keyword--fail");
                }
                else return response_error([],'关键词不存在，刷新页面试试！');
            }

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试';
            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }
    }
    // 【关键词】合作停
    public function operate_business_keyword_stop($post_data)
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
        if($operate != 'stop-keyword') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $keyword = SEOKeyword::find($id);
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["keywordstatus"] = "合作停";
            $bool = $keyword->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--keyword--fail");

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
    // 【关键词】再合作
    public function operate_business_keyword_start($post_data)
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
        if($operate != 'start-keyword') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $keyword = SEOKeyword::find($id);
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $update["keywordstatus"] = "优化中";
            $bool = $keyword->fill($update)->save();
            if($bool)
            {
            }
            else throw new Exception("update--keyword--fail");

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




    // 【关键词排名】返回-列表-视图
    public function show_business_keyword_detect_record($post_data)
    {
        $id = $post_data["id"];
        $keyword_data = SEOKeyword::select('*')->with('creator')->where('id',$id)->first();
        return view('mt.admin.entrance.business.keyword-detect-record')
            ->with(['data'=>$keyword_data]);
    }
    // 【关键词排名】返回-列表-数据
    public function get_business_keyword_detect_record_datatable($post_data)
    {
        $mine = Auth::guard("admin")->user();

        $id  = $post_data["id"];
        $query = SEOKeywordDetectRecord::select('*')->where('keywordid',$id);

        if(!empty($post_data['rank']))
        {
            if($post_data['rank'] = 1)
            {
                $query->where('rank', '>', 0)->where('rank', '<=', 10);
            }
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

        if(isset($post_data['order']))
        {
            $columns = $post_data['columns'];
            $order = $post_data['order'][0];
            $order_column = $order['column'];
            $order_dir = $order['dir'];

            $field = $columns[$order_column]["data"];
            $query->orderBy($field, $order_dir);
        }
        else $query->orderBy("createtime", "desc");

        if($limit == -1) $list = $query->get();
        else $list = $query->skip($skip)->take($limit)->get();

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 【关键词排名】添加
    public function operate_business_keyword_detect_create_rank($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'keyword_id.required' => '参数有误',
            'detect_date.required' => '请输入指定日期',
            'detect_rank.required' => '请输入指定排名',
            'detect_rank.numeric' => '指定排名必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'keyword_id' => 'required',
            'detect_date' => 'required',
            'detect_rank' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $me = Auth::guard("admin")->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限");

        $operate = $post_data["operate"];
        if($operate != 'detect-create-rank') return response_error([],"参数有误！");

        $keyword_id = $post_data["keyword_id"];
        if(intval($keyword_id) !== 0 && !$keyword_id) return response_error([],"该关键词不存在，刷新页面试试！");

        $detect_rank = $post_data['detect_rank'];
        if($detect_rank <= 0 or $detect_rank >= 101) return response_error([],"指定排名必须在1-100之间！");

        $detect_date = $post_data['detect_date'];
        if(strtotime($detect_date) > time('Y-m-d')) return response_error([],"指定日期不能大于今天！");

        $keyword = SEOKeyword::where("id",$keyword_id)->lockForUpdate()->first();
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试！");
        $price = $keyword->price;


        $DetectRecord = SEOKeywordDetectRecord::where(['keywordid'=>$keyword->id])->whereDate('detect_time',$detect_date)->first();
        if($DetectRecord) return response_error([],"该日期已存在，不能重复添加！");


        $time = date('Y-m-d H:i:s');
        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $DetectRecord = New SEOKeywordDetectRecord;
            $DetectRecord_data['owner_id'] = $keyword->createuserid;
            $DetectRecord_data['ownuserid'] = $keyword->createuserid;
            $DetectRecord_data['createuserid'] = $me->id;
            $DetectRecord_data['createusername'] = $me->username;
            $DetectRecord_data['createtime'] = $time;
            $DetectRecord_data['keywordid'] = $keyword->id;
            $DetectRecord_data['keyword'] = $keyword->keyword;
            $DetectRecord_data['website'] = $keyword->website;
            $DetectRecord_data['searchengine'] = $keyword->searchengine;
            $DetectRecord_data['detect_time'] = $detect_date;
            $DetectRecord_data['rank'] = $detect_rank;
            $DetectRecord_data['rank_original'] = $detect_rank;
            $DetectRecord_data['rank_real'] = $detect_rank;

            $bool = $DetectRecord->fill($DetectRecord_data)->save();
            if($bool)
            {
                $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                $detect_standard_count = $query_detect->count('*');
                $detect_standard_price_sum = $detect_standard_count * $keyword->price;

                if(strtotime($detect_date) >= strtotime($keyword->detectiondate))
                {
                    $keyword->detectiondate = $detect_date;
                    $keyword->latestranking = $detect_rank;

                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $keyword->standardstatus = "已达标";
                        $keyword->standarddate = $detect_date;
                        $keyword->latestconsumption = (int)$price;
                    }
                    else
                    {
                        $keyword->standardstatus = "未达标";
                        $keyword->latestconsumption = 0;
                    }
                }

                if(strtotime($detect_date) <= strtotime($keyword->firststandarddate))
                {
                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $keyword->firststandarddate = $detect_date;
                    }
                }

                $keyword->standarddays = $detect_standard_count;
                $keyword->totalconsumption = $detect_standard_price_sum;

                $keyword->standard_days_1 = $detect_standard_count;
                $keyword->standard_days_1 = $detect_standard_count;
                $keyword->consumption_total = $detect_standard_price_sum;

                $bool_1 = $keyword->save();
                if($bool_1)
                {
                    if($detect_rank > 0 and $detect_rank <= 10)
                    {
                        $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$detect_date)->first();
                        if(!$ExpenseRecord)
                        {
                            $ExpenseRecord = new ExpenseRecord;
                            $ExpenseRecord_data['detect_id'] = $DetectRecord->id;
                            $ExpenseRecord_data['owner_id'] = $keyword->createuserid;
                            $ExpenseRecord_data['ownuserid'] = $keyword->createuserid;
                            $ExpenseRecord_data['standarddate'] = $detect_date;
                            $ExpenseRecord_data['createtime'] = $time;
                            $ExpenseRecord_data['siteid'] = $keyword->siteid;
                            $ExpenseRecord_data['keywordid'] = $keyword->id;
                            $ExpenseRecord_data['keyword'] = $keyword->keyword;
                            $ExpenseRecord_data['price'] = (int)$keyword->price;
                            $bool_2 = $ExpenseRecord->fill($ExpenseRecord_data)->save();
                            if($bool_2)
                            {
                                $DetectRecord->expense_id = $ExpenseRecord->id;
                                $DetectRecord->save();

                                $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();
                                $keyword_owner->fund_expense = $keyword_owner->fund_expense + $price;
                                $keyword_owner->fund_expense_1 = $keyword_owner->fund_expense_1 + $price;
                                $keyword_owner->fund_expense_2 = $keyword_owner->fund_expense_2 + $price;
                                $keyword_owner->fund_balance = $keyword_owner->fund_balance - $price;
                                if($keyword_owner->fund_frozen >= $price)
                                {
                                    $keyword_owner->fund_frozen = $keyword_owner->fund_frozen - $price;
                                }
                                else
                                {
                                    $keyword_owner->fund_available = $keyword_owner->fund_available - ($price - $keyword_owner->fund_frozen);
                                    $keyword_owner->fund_frozen = 0;
                                }
                                $keyword_owner->save();
                            }
                            else throw new Exception("update--expense-record--fail");
                        }
                    }
                }
                else throw new Exception("update--detect-record--fail");
            }
            else throw new Exception("insert--detect-record--fail");

            DB::commit();
            return response_success(['id'=>$keyword->id]);
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
    // 【关键词排名】修改
    public function operate_business_keyword_detect_set_rank($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'detect_id.required' => '参数有误',
            'detect_date.required' => '请输入指定日期',
            'detect_rank.required' => '请输入指定排名',
            'detect_rank.numeric' => '指定排名必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'detect_id' => 'required',
            'detect_date' => 'required',
            'detect_rank' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        if($operate != 'detect-set-rank') return response_error([],"参数有误！");

        $me = Auth::guard("admin")->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限");

        $detect_id = $post_data["detect_id"];
        if(intval($detect_id) !== 0 && !$detect_id) return response_error([],"该检测不存在，刷新页面试试！");

        $detect_rank = $post_data['detect_rank'];
        if($detect_rank <= 0 or $detect_rank >= 101) return response_error([],"指定排名必须在【1-100】之间！");

        $DetectRecord = SEOKeywordDetectRecord::find($detect_id);
        if(!$DetectRecord) return response_error([],"该检测不存在，刷新页面重试！");
        $DetectRecordRank = $DetectRecord->rank;

        $detect_date = $post_data['detect_date'];
        if($detect_date != date("Y-m-d",strtotime($DetectRecord->detect_time))) return response_error([],"参数有误！");

        // [old=1-10][new=10+]
//        if(($DetectRecordRank > 0 and $DetectRecordRank <= 10) and $detect_rank > 10) return response_error([],"已上词，指定排名不能大于【10】！");

        $keyword = SEOKeyword::where("id",$DetectRecord->keywordid)->lockForUpdate()->first();
        if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试！");
        $price = $keyword->price;

        $time = date('Y-m-d H:i:s');
        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $DetectRecord->createuserid = $me->id;
            $DetectRecord->createusername = $me->username;
            $DetectRecord->rank = $detect_rank;
            $bool = $DetectRecord->save();

            // [old=1-10][new=1-10]
            if(($DetectRecordRank > 0 and $DetectRecordRank <= 10) and ($detect_rank > 0 and $detect_rank <= 10))
            {
                if($detect_date == date("Y-m-d",strtotime($keyword->detectiondate)))
                {
                    $keyword->latestranking = $detect_rank;
                    $bool_1 = $keyword->save();
                    if(!$bool_1) throw new Exception("update--keyword--fail");
                }
            }

            // [old=10+][new=10+]
            if(($DetectRecordRank <= 0 or $DetectRecordRank > 10) and ($detect_rank > 10))
            {
                $bool = $DetectRecord->save();
                if($detect_date == date("Y-m-d",strtotime($keyword->detectiondate)))
                {
                    $keyword->latestranking = $detect_rank;
                    $bool_1 = $keyword->save();
                    if(!$bool_1) throw new Exception("update--keyword--fail");
                }
            }

            // [old=1-10][new=10+]
            if(($DetectRecordRank > 0 and $DetectRecordRank <= 10) and $detect_rank > 10)
            {
//                return response_error([],"已上词，指定排名不能大于【10】！");
                if($bool)
                {
                    if($detect_date == date("Y-m-d",strtotime($keyword->detectiondate)))
                    {
                        $keyword->latestranking = $detect_rank;
                        $keyword->standardstatus = "未达标";
                        $keyword->latestconsumption = 0;

                        $detect_last = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10)
                            ->orderby('detect_time','asc')->first();
                        if($detect_last)
                        {
                            $keyword->standarddate = $detect_last->detect_time;
                        }
                        else
                        {
                            $keyword->standarddate = "";
                        }
                    }

                    if($detect_date == date("Y-m-d",strtotime($keyword->firststandarddate)))
                    {
                        $detect_first = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10)
                            ->orderby('detect_time','asc')->first();
                        if($detect_first)
                        {
                            $keyword->firststandarddate = $detect_first->detect_time;
                        }
                        else
                        {
                            $keyword->firststandarddate = "";
                        }
                    }

                    $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                    $detect_standard_count = $query_detect->count('*');
                    $detect_standard_price_sum = $detect_standard_count * $keyword->price;

                    $keyword->standarddays = $detect_standard_count;
                    $keyword->totalconsumption = $detect_standard_price_sum;

                    $keyword->standard_days_1 = $detect_standard_count;
                    $keyword->standard_days_2 = $detect_standard_count;
                    $keyword->consumption_total = $detect_standard_price_sum;

                    $bool_1 = $keyword->save();
                    if($bool_1)
                    {
                        $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$detect_date)->first();
                        if($DetectRecord->expense_id == $ExpenseRecord->id)
                        {
                            $bool_2 = $ExpenseRecord->delete();
                            if($bool_2)
                            {
                                $DetectRecord->expense_id = 0;
                                $bool_x = $DetectRecord->save();

                                $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();

                                if($keyword_owner->fund_frozen > 0)
                                {
                                    $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + $price;
                                }
                                else
                                {
                                    // 只扣费【冻结金额】
                                    if($keyword_owner->fund_expense == $keyword_owner->fund_frozen_init)
                                    {
                                        $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + $price;
                                    }

                                    // 只扣费【可用余额】
                                    if($keyword_owner->fund_expense >= ($keyword_owner->fund_frozen_init + $price))
                                    {
                                        $keyword_owner->fund_available = $keyword_owner->fund_available + $price;
                                    }

                                    // 扣费【冻结金额 + 可用余额】
                                    if($keyword_owner->fund_expense < ($keyword_owner->fund_frozen_init + $price))
                                    {
                                        $available_cost = $keyword_owner->fund_expense - $keyword_owner->fund_frozen_init;
                                        if($available_cost > 0)
                                        {
                                            $frozen_cost = $price - $available_cost;
                                            $keyword_owner->fund_available = $keyword_owner->fund_available + $available_cost;
                                            $keyword_owner->fund_frozen = $frozen_cost;
                                        }
                                    }
                                }

                                $keyword_owner->fund_expense = $keyword_owner->fund_expense - $price;
                                $keyword_owner->fund_expense_1 = $keyword_owner->fund_expense_1 - $price;
                                $keyword_owner->fund_expense_2 = $keyword_owner->fund_expense_2 - $price;
                                $keyword_owner->fund_balance = $keyword_owner->fund_balance + $price;

                                $keyword_owner->save();
                            }
                        }
                    }
                }
            }

            // [old=10+][new=1-10]
            if(($DetectRecordRank <= 0 or $DetectRecordRank > 10) and ($detect_rank > 0 and $detect_rank <= 10))
            {
                if($bool)
                {
                    if($detect_date == date("Y-m-d",strtotime($keyword->detectiondate)))
                    {
                        $keyword->latestranking = $detect_rank;
                        $keyword->standardstatus = "已达标";
                        $keyword->standarddate = $detect_date;
                        $keyword->latestconsumption = (int)$price;
                    }

                    if(!$keyword->firststandarddate or $detect_date <= date("Y-m-d",strtotime($keyword->firststandarddate)))
                    {
                        $keyword->firststandarddate = $detect_date;
                    }

                    $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                    $detect_standard_count = $query_detect->count('*');
                    $detect_standard_price_sum = $detect_standard_count * $keyword->price;

                    $keyword->standarddays = $detect_standard_count;
                    $keyword->totalconsumption = $detect_standard_price_sum;

                    $keyword->standard_days_1 = $detect_standard_count;
                    $keyword->standard_days_2 = $detect_standard_count;
                    $keyword->consumption_total = $detect_standard_price_sum;

                    $bool_1 = $keyword->save();
                    if($bool_1)
                    {
                        $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$detect_date)->first();
                        if(!$ExpenseRecord)
                        {
                            $ExpenseRecord = new ExpenseRecord;
                            $ExpenseRecord_data['detect_id'] = $DetectRecord->id;
                            $ExpenseRecord_data['owner_id'] = $keyword->createuserid;
                            $ExpenseRecord_data['ownuserid'] = $keyword->createuserid;
                            $ExpenseRecord_data['standarddate'] = $detect_date;
                            $ExpenseRecord_data['createtime'] = $time;
                            $ExpenseRecord_data['siteid'] = $keyword->siteid;
                            $ExpenseRecord_data['keywordid'] = $keyword->id;
                            $ExpenseRecord_data['keyword'] = $keyword->keyword;
                            $ExpenseRecord_data['price'] = (int)$keyword->price;
                            $bool_2 = $ExpenseRecord->fill($ExpenseRecord_data)->save();
                            if($bool_2)
                            {
                                $DetectRecord->expense_id = $ExpenseRecord->id;
                                $DetectRecord->save();

                                $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();

                                $keyword_owner->fund_expense = $keyword_owner->fund_expense + $price;
                                $keyword_owner->fund_expense_1 = $keyword_owner->fund_expense_1 + $price;
                                $keyword_owner->fund_expense_2 = $keyword_owner->fund_expense_2 + $price;
                                $keyword_owner->fund_balance = $keyword_owner->fund_balance - $price;
                                if($keyword_owner->fund_frozen >= $price)
                                {
                                    $keyword_owner->fund_frozen = $keyword_owner->fund_frozen - $price;
                                }
                                else
                                {
                                    $keyword_owner->fund_available = $keyword_owner->fund_available - ($price - $keyword_owner->fund_frozen);
                                    $keyword_owner->fund_frozen = 0;
                                }

                                $keyword_owner->save();
                            }
                            else throw new Exception("update--expense-record--fail");
                        }
                        else
                        {
                            $ExpenseRecord->detect_id = $DetectRecord->id;
                            $ExpenseRecord->save();
                        }
                    }
                    else throw new Exception("update--keyword--fail");
                }
                else throw new Exception("insert--detect-record--fail");
            }

            DB::commit();
            return response_success(['id'=>$keyword->id]);
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
    // 【关键词排名】批量修改
    public function operate_business_keyword_detect_set_rank_bulk($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'bulk_detect_id.required' => '参数有误',
            'bulk_detect_rank.required' => '请输入指定排名',
            'bulk_detect_rank.numeric' => '指定排名必须为数字',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'bulk_detect_id' => 'required',
            'bulk_detect_rank' => 'required|numeric',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $operate = $post_data["operate"];
        if($operate != 'detect-set-rank-bulk') return response_error([],"参数有误！");

        $me = Auth::guard("admin")->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限");

        $detect_rank = $post_data['bulk_detect_rank'];
        if($detect_rank <= 0 or $detect_rank >= 101) return response_error([],"指定排名必须在【1-100】之间！");

        $time = date('Y-m-d H:i:s');
        // 启动数据库事务
        DB::beginTransaction();
        try
        {

            $detect_ids = $post_data["bulk_detect_id"];
            foreach($detect_ids as $key => $detect_id)
            {
                if(intval($detect_id) !== 0 && !$detect_id) return response_error([],"该检测不存在，刷新页面试试！");

                $DetectRecord = SEOKeywordDetectRecord::find($detect_id);
                if(!$DetectRecord) return response_error([],"该检测不存在，刷新页面重试！");
                $DetectRecordRank = $DetectRecord->rank;
                $DetectRecordTime = date("Y-m-d",strtotime($DetectRecord->detect_time));

                // [old=1-10][new=10+]
//                if(($DetectRecordRank > 0 and $DetectRecordRank <= 10) and $detect_rank > 10) return response_error([],"已上词，指定排名不能大于【10】！");

                $keyword = SEOKeyword::where("id",$DetectRecord->keywordid)->lockForUpdate()->first();
                if(!$keyword) return response_error([],"该关键词不存在，刷新页面重试！");
                $price = $keyword->price;


                $DetectRecord->createuserid = $me->id;
                $DetectRecord->createusername = $me->username;
                $DetectRecord->rank = $detect_rank;
                $bool = $DetectRecord->save();

                // [old=1-10][new=1-10]
                if(($DetectRecordRank > 0 and $DetectRecordRank <= 10) and ($detect_rank > 0 and $detect_rank <= 10))
                {
                    if($DetectRecordTime == date("Y-m-d",strtotime($keyword->detectiondate)))
                    {
                        $keyword->latestranking = $detect_rank;
                        $bool_1 = $keyword->save();
                        if(!$bool_1) throw new Exception("update--keyword--fail");
                    }
                }

                // [old=10+][new=10+]
                if(($DetectRecordRank <= 0 or $DetectRecordRank > 10) and ($detect_rank > 10))
                {
                    $bool = $DetectRecord->save();
                    if($DetectRecordTime == date("Y-m-d",strtotime($keyword->detectiondate)))
                    {
                        $keyword->latestranking = $detect_rank;
                        $bool_1 = $keyword->save();
                        if(!$bool_1) throw new Exception("update--keyword--fail");
                    }
                }

                // [old=1-10][new=10+]
                if(($DetectRecordRank > 0 and $DetectRecordRank <= 10) and $detect_rank > 10)
                {
//                return response_error([],"已上词，指定排名不能大于【10】！");
                    if($bool)
                    {
                        if($DetectRecordTime == date("Y-m-d",strtotime($keyword->detectiondate)))
                        {
                            $keyword->latestranking = $detect_rank;
                            $keyword->standardstatus = "未达标";
                            $keyword->latestconsumption = 0;

                            $detect_last = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10)
                                ->orderby('detect_time','asc')->first();
                            if($detect_last)
                            {
                                $keyword->standarddate = $detect_last->detect_time;
                            }
                            else
                            {
                                $keyword->standarddate = "";
                            }
                        }

                        if($DetectRecordTime == date("Y-m-d",strtotime($keyword->firststandarddate)))
                        {
                            $detect_first = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10)
                                ->orderby('detect_time','asc')->first();
                            if($detect_first)
                            {
                                $keyword->firststandarddate = $detect_first->detect_time;
                            }
                            else
                            {
                                $keyword->firststandarddate = "";
                            }
                        }

                        $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                        $detect_standard_count = $query_detect->count('*');
                        $detect_standard_price_sum = $detect_standard_count * $keyword->price;

                        $keyword->standarddays = $detect_standard_count;
                        $keyword->totalconsumption = $detect_standard_price_sum;

                        $keyword->standard_days_1 = $detect_standard_count;
                        $keyword->standard_days_2 = $detect_standard_count;
                        $keyword->consumption_total = $detect_standard_price_sum;

                        $bool_1 = $keyword->save();
                        if($bool_1)
                        {
                            $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$DetectRecordTime)->first();
                            if($DetectRecord->expense_id == $ExpenseRecord->id)
                            {
                                $bool_2 = $ExpenseRecord->delete();
                                if($bool_2)
                                {
                                    $DetectRecord->expense_id = 0;
                                    $bool_x = $DetectRecord->save();

                                    $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();

                                    if($keyword_owner->fund_frozen > 0)
                                    {
                                        $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + $price;
                                    }
                                    else
                                    {
                                        // 只扣费【冻结金额】
                                        if($keyword_owner->fund_expense == $keyword_owner->fund_frozen_init)
                                        {
                                            $keyword_owner->fund_frozen = $keyword_owner->fund_frozen + $price;
                                        }

                                        // 只扣费【可用余额】
                                        if($keyword_owner->fund_expense >= ($keyword_owner->fund_frozen_init + $price))
                                        {
                                            $keyword_owner->fund_available = $keyword_owner->fund_available + $price;
                                        }

                                        // 扣费【冻结金额 + 可用余额】
                                        if($keyword_owner->fund_expense < ($keyword_owner->fund_frozen_init + $price))
                                        {
                                            $available_cost = $keyword_owner->fund_expense - $keyword_owner->fund_frozen_init;
                                            if($available_cost > 0)
                                            {
                                                $frozen_cost = $price - $available_cost;
                                                $keyword_owner->fund_available = $keyword_owner->fund_available + $available_cost;
                                                $keyword_owner->fund_frozen = $frozen_cost;
                                            }
                                        }
                                    }

                                    $keyword_owner->fund_expense = $keyword_owner->fund_expense - $price;
                                    $keyword_owner->fund_expense_1 = $keyword_owner->fund_expense_1 - $price;
                                    $keyword_owner->fund_expense_2 = $keyword_owner->fund_expense_2 - $price;
                                    $keyword_owner->fund_balance = $keyword_owner->fund_balance + $price;

                                    $keyword_owner->save();
                                }
                            }
                        }
                    }
                }

                // [old=10+][new=1-10]
                if(($DetectRecordRank <= 0 or $DetectRecordRank > 10) and ($detect_rank > 0 and $detect_rank <= 10))
                {
                    if($bool)
                    {
                        if($DetectRecordTime == date("Y-m-d",strtotime($keyword->detectiondate)))
                        {
                            $keyword->latestranking = $detect_rank;
                            $keyword->standardstatus = "已达标";
                            $keyword->standarddate = $DetectRecordTime;
                            $keyword->latestconsumption = (int)$price;
                        }

                        if(!$keyword->firststandarddate or $DetectRecordTime <= date("Y-m-d",strtotime($keyword->firststandarddate)))
                        {
                            $keyword->firststandarddate = $DetectRecordTime;
                        }

                        $query_detect = SEOKeywordDetectRecord::where('keywordid',$keyword->id)->where('rank','>',0)->where('rank','<=',10);
                        $detect_standard_count = $query_detect->count('*');
                        $detect_standard_price_sum = $detect_standard_count * $keyword->price;

                        $keyword->standarddays = $detect_standard_count;
                        $keyword->totalconsumption = $detect_standard_price_sum;

                        $keyword->standard_days_1 = $detect_standard_count;
                        $keyword->standard_days_2 = $detect_standard_count;
                        $keyword->consumption_total = $detect_standard_price_sum;

                        $bool_1 = $keyword->save();
                        if($bool_1)
                        {
                            $ExpenseRecord = ExpenseRecord::where(['keywordid'=>$keyword->id])->whereDate('standarddate',$DetectRecordTime)->first();
                            if(!$ExpenseRecord)
                            {
                                $ExpenseRecord = new ExpenseRecord;
                                $ExpenseRecord_data['detect_id'] = $DetectRecord->id;
                                $ExpenseRecord_data['owner_id'] = $keyword->createuserid;
                                $ExpenseRecord_data['ownuserid'] = $keyword->createuserid;
                                $ExpenseRecord_data['standarddate'] = $DetectRecordTime;
                                $ExpenseRecord_data['createtime'] = $time;
                                $ExpenseRecord_data['siteid'] = $keyword->siteid;
                                $ExpenseRecord_data['keywordid'] = $keyword->id;
                                $ExpenseRecord_data['keyword'] = $keyword->keyword;
                                $ExpenseRecord_data['price'] = (int)$keyword->price;
                                $bool_2 = $ExpenseRecord->fill($ExpenseRecord_data)->save();
                                if($bool_2)
                                {
                                    $DetectRecord->expense_id = $ExpenseRecord->id;
                                    $DetectRecord->save();

                                    $keyword_owner = User::where("id",$keyword->createuserid)->lockForUpdate()->first();

                                    $keyword_owner->fund_expense = $keyword_owner->fund_expense + $price;
                                    $keyword_owner->fund_expense_1 = $keyword_owner->fund_expense_1 + $price;
                                    $keyword_owner->fund_expense_2 = $keyword_owner->fund_expense_2 + $price;
                                    $keyword_owner->fund_balance = $keyword_owner->fund_balance - $price;
                                    if($keyword_owner->fund_frozen >= $price)
                                    {
                                        $keyword_owner->fund_frozen = $keyword_owner->fund_frozen - $price;
                                    }
                                    else
                                    {
                                        $keyword_owner->fund_available = $keyword_owner->fund_available - ($price - $keyword_owner->fund_frozen);
                                        $keyword_owner->fund_frozen = 0;
                                    }

                                    $keyword_owner->save();
                                }
                                else throw new Exception("update--expense-record--fail");
                            }
                            else
                            {
                                $ExpenseRecord->detect_id = $DetectRecord->id;
                                $ExpenseRecord->save();
                            }
                        }
                        else throw new Exception("update--keyword--fail");
                    }
                    else throw new Exception("insert--detect-record--fail");
                }
            }
//            dd($post_data);

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




    // 【关键词】返回-查询-视图
    public function view_business_keyword_search()
    {
        $me = Auth::guard('admin')->user();
        $view_blade = 'mt.admin.entrance.business.keyword-search';
        return view($view_blade)->with([
            'operate'=>'search',
            'operate_id'=>0,
            'sidebar_business_active'=>'active',
            'sidebar_business_keyword_search_active'=>'active'
        ]);
    }
    // 【关键词】返回-查询-结果
    public function operate_business_keyword_search($post_data)
    {
        $messages = [
            'keywords.required' => '关键词不能为空',
        ];
        $v = Validator::make($post_data, [
            'keywords' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $mine = Auth::guard('admin')->user();
        $mine_id = $mine->id;
        if($mine->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $CommonRepository = new CommonRepository();


        $keywords = $post_data['keywords'];

        // 将回车换行替换成逗号
        $keywords = str_replace(array("\r\n", "\r", "\n"), ",", $keywords);

//        //将回车换行替换成逗号
//        $kws = str_replace(",","\r\n", $keywords);
//        $kws = str_replace(",","\r", $keywords);
//        $kws = str_replace(",","\n", $keywords);

        $keyword_array = explode(',' , $keywords );
        // 去重空值
        $keyword_array = array_filter( $keyword_array );
        // 去重操作
        $keyword_array = array_values(array_unique( $keyword_array ));

        $KeywordLengthPriceIndexOptions = config('seo.KeywordLengthPriceIndexOptions');

        $search_engine_keys = array_keys($KeywordLengthPriceIndexOptions);

        //组成字符
        $keywords = implode(',' , $keyword_array);

        //
        foreach ( $keyword_array as $key => $vo ){

            // 去掉关键词前后的空额
            //$vo = strtolower(trim($vo));
            $vo = trim($vo);
            $replace = array(" ","　","\n","\r","\t");
            $vo = str_replace($replace, "", $vo);
            $temp['keyword'] = $vo;
            foreach ( $search_engine_keys as $vo2 )
            {
                $temp[$vo2] = 0;
            }
            $arr[] = $temp;
        }

        $list = $CommonRepository -> combKeywordSearchResults( $arr );
        $view_blade = 'mt.admin.entrance.business.keyword-search-result';
        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list])->__toString();
//        $html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$list]);
//        $html = response($html)->getContent();


        $recommend_list = $CommonRepository->get_keyword_recommend($post_data);
        $recommend_html = view($view_blade)->with(['keywords'=>$keywords,'items'=>$recommend_list])->__toString();

        return response_success([
            'list'=>json_encode($list),
            'html'=>$html,
            'recommend_list'=>json_encode($recommend_list),
            'recommend_html'=>$recommend_html
        ]);

    }
    // 【关键词】导出-查询-结果
    public function operate_business_keyword_search_export($post_data)
    {
        $me = Auth::guard('admin')->user();
        $list_decode = json_decode($post_data['list'],true);
        $recommend_list_decode = json_decode($post_data['recommend_list'],true);

        $cellData = array_merge($list_decode,$recommend_list_decode);
        array_unshift($cellData,['关键词','百度PC(元/天)','百度移动(元/天)','搜狗(元/天)','360(元/天)','神马(元/天)','难度指数','难度指数','优化周期']);

//        dd($cellData);

        $title = '【关键词价格查询】 - '.date('YmdHis');
        Excel::create($title,function($excel) use ($cellData){
            $excel->sheet('all', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');

        return false;
    }







    // 【站点工单】返回-添加-视图
    public function view_business_site_work_order_create($post_data)
    {
        $me = Auth::guard('admin')->user();
        if(!in_array($me->usergroup,['Manage'])) return response("你没有权限操作！", 404);

        $site_id = $post_data["site-id"];
        $site_data = SEOSite::select('*')->with('creator')->find($site_id);

        $view_blade = 'mt.admin.entrance.business.site-work-order-edit';
        return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0, 'site_data'=>$site_data]);
    }
    // 【站点工单】返回-编辑-视图
    public function view_business_site_work_order_edit($post_data)
    {
        $me = Auth::guard('admin')->user();
        if(!in_array($me->usergroup,['Manage'])) return response("你没有权限操作！", 404);

        $id = $post_data["id"];
        $mine = Item::with(['user'])->find($id);
        if(!$mine) return response_error([],"该工单不存在，刷新页面试试！");

        $site_data = SEOSite::select('*')->with('creator')->find($mine->site_id);

        $view_blade = 'mt.admin.entrance.business.site-work-order-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>$id]);
        }
        else
        {
            $mine = Item::with(['user'])->find($id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with(['operate'=>'edit', 'operate_id'=>$id, 'site_data'=>$site_data, 'data'=>$mine]);
            }
            else return response("该工单不存在！", 404);
        }
    }

    // 【站点工单】保存数据
    public function operate_business_site_work_order_save($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'site_id.required' => '参数有误',
            'title.required' => '请输入标题',
        ];
        $v = Validator::make($post_data, [
            'operate' => 'required',
            'site_id' => 'required',
            'title' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }


        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        $site_id = $post_data["site_id"];
        $site = SEOSite::select('*')->with('creator')->find($site_id);
        if(!$site) return response_error([],"站点不存在！");
        $post_data["user_id"] = $site->createuserid;

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $mine = new Item;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate == 'edit') // 编辑
        {
            $mine = Item::find($operate_id);
            if(!$mine) return response_error([],"该工单不存在，刷新页面重试！");
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
            $bool = $mine->fill($mine_data)->save();
            if($bool)
            {
                // 封面图片
                if(!empty($post_data["attachment"]))
                {
                    // 删除原封面图片
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


    // 【站点工单】返回-列表-视图
    public function show_business_site_work_order_list($post_data)
    {
        $site_id = $post_data["site-id"];
        $site_data = SEOSite::select('*')->with('creator')->find($site_id);
        return view('mt.admin.entrance.business.site-work-order-list')
            ->with(['data'=>$site_data]);
    }
    // 【站点工单】返回-列表-数据
    public function get_business_site_work_order_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();

        $site_id  = $post_data["site-id"];
        $query = Item::select('*')->with(['user','site'])->where('site_id',$site_id)->where('category',1);

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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


    // 【工单】返回-列表-视图
    public function show_business_work_order_list()
    {
        return view('mt.admin.entrance.business.work-order-list')
            ->with(['sidebar_work_order_list_active'=>'active menu-open']);
    }
    // 【工单】返回-列表-数据
    public function get_business_work_order_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();

        $query = Item::select('*')->with(['user','site'])->where('category',1);

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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


    // 【工单】获取详情
    public function operate_business_work_order_get($post_data)
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
        if($operate != 'work-order-get') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");

        $item = Item::find($id);
        return response_success($item,"");

    }
    // 【工单】推送
    public function operate_business_work_order_push($post_data)
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
        if($operate != 'work-order-push') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $item = Item::find($id);
        if(!$item) return response_error([],"该工单不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $item->active = 1;
            $bool = $item->save();
            if(!$bool) throw new Exception("update--item--fail");

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
    // 【工单】删除
    public function operate_business_work_order_delete($post_data)
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
        if($operate != 'work-order-delete') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $item = Item::find($id);
        if(!$item) return response_error([],"该工单不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--item--fail");

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
     * 财务系统
     */
    // 【财务概览】返回-列表-视图
    public function show_finance_overview()
    {
//        $this_month = date('Y-m');
//        $this_month_year = date('Y');
//        $this_month_month = date('m');
//        $last_month = date('Y-m',strtotime('last month'));
//        $last_month_year = date('Y',strtotime('last month'));
//        $last_month_month = date('m',strtotime('last month'));
////        dd($this_month_year."--".$this_month_month."--".$last_month_year."--".$last_month_month);
//
//        $query1 = ExpenseRecord::select('id','price','createtime')
//            ->whereYear('createtime',$this_month_year)->whereMonth('createtime',$this_month_month);
//        $count1 = $query1->count("*");
//        $sum1 = $query1->sum("price");
//        $data1 = $query1->groupBy(DB::raw("STR_TO_DATE(createtime,'%Y-%m-%d')"))
//            ->select(DB::raw("
//                    STR_TO_DATE(createtime,'%Y-%m-%d') as date,
//                    DATE_FORMAT(createtime,'%e') as day,
//                    sum(price) as sum,
//                    count(*) as count
//                "))->get();
//
//        $query2 = ExpenseRecord::select('id','price','createtime')
//            ->whereYear('createtime',$last_month_year)->whereMonth('createtime',$last_month_month);
//        $count2 = $query2->count("*");
//        $sum2 = $query2->sum("price");
//        $data2 = $query2->groupBy(DB::raw("STR_TO_DATE(createtime,'%Y-%m-%d')"))
//            ->select(DB::raw("
//                    STR_TO_DATE(createtime,'%Y-%m-%d') as date,
//                    DATE_FORMAT(createtime,'%e') as day,
//                    sum(price) as sum,
//                    count(*) as count
//                "))->get();
//
//        $data[0]['month'] = $this_month;
//        $data[0]['data'] = $data1->keyBy('day');
//        $data[1]['month'] = $last_month;
//        $data[1]['data'] = $data2->keyBy('day');
////        dd($data[0]['data']);

        $data = [];

        return view('mt.admin.entrance.finance.overview')
            ->with([
                'data'=>$data,
                'sidebar_finance_active'=>'active',
                'sidebar_finance_overview_active'=>'active'
            ]);
    }
    // 【财务概览】返回-列表-数据
    public function get_finance_overview_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();

        $query = ExpenseRecord::select('id','price','standarddate','createtime');
        $data = $query->groupBy(DB::raw("STR_TO_DATE(standarddate,'%Y-%m')"))
            ->select(
                DB::raw("
                    STR_TO_DATE(standarddate,'%Y-%m-%d') as date,
                    DATE_FORMAT(standarddate,'%Y-%m') as month,
                    DATE_FORMAT(standarddate,'%d') as day,
                    sum(price) as sum,
                    count(*) as count
                "))
            ->orderby("month","desc")
            ->get();

        $list = $data->keyBy('month')->sortByDesc('month');
        $total = $list->count();
        $list = collect(array_values($list->toArray()));


        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : -1;

//        if(isset($post_data['order']))
//        {
//            $columns = $post_data['columns'];
//            $order = $post_data['order'][0];
//            $order_column = $order['column'];
//            $order_dir = $order['dir'];
//
//            $field = $columns[$order_column]["data"];
//            $query->orderBy($field, $order_dir);
//        }
//        else $query->orderBy("id", "desc");
//
//        if($limit == -1) $list = $query->get();
//        else $list = $query->skip($skip)->take($limit)->get();

//        foreach ($list as $k => $v)
//        {
//            $list[$k]->encode_id = encode($v->id);
//        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 【月份财务】返回-列表-视图
    public function show_finance_overview_month($post_data)
    {
        $month = $post_data['month'];
        $month_arr = explode("-",$month);
        $_year = $month_arr[0];
        $_month = $month_arr[1];

        $data = [];
        return view('mt.admin.entrance.finance.overview-month')
            ->with([
                'data'=>$data,
                'sidebar_finance_active'=>'active'
            ]);
    }
    // 【月份财务】返回-列表-数据
    public function get_finance_overview_month_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();

        $month = $post_data['month'];
        $month_arr = explode("-",$month);
        $_year = $month_arr[0];
        $_month = $month_arr[1];

        $query = ExpenseRecord::select('id','price','standarddate')->whereYear('standarddate',$_year)->whereMonth('standarddate',$_month);
        $list = $query->groupBy(DB::raw("STR_TO_DATE(standarddate,'%Y-%m-%d')"))
            ->select(
                DB::raw("
                    STR_TO_DATE(standarddate,'%Y-%m-%d') as date,
                    DATE_FORMAT(standarddate,'%Y-%m') as month,
                    DATE_FORMAT(standarddate,'%d') as day,
                    DATE_FORMAT(standarddate,'%e') as day_0,
                    sum(price) as sum,
                    count(*) as count
                "))
            ->orderby("day","desc")
            ->get();


//        $list = $data->groupBy(function ($item, $key) {
//            return date("Y-m-d",strtotime($item['createtime']));
//        });

//        $list = $data->keyBy('month')->sortByDesc('month');
        $total = $list->count();
        $list = collect(array_values($list->toArray()));


        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : -1;

//        if(isset($post_data['order']))
//        {
//            $columns = $post_data['columns'];
//            $order = $post_data['order'][0];
//            $order_column = $order['column'];
//            $order_dir = $order['dir'];
//
//            $field = $columns[$order_column]["data"];
//            $query->orderBy($field, $order_dir);
//        }
//        else $query->orderBy("id", "desc");
//
//        if($limit == -1) $list = $query->get();
//        else $list = $query->skip($skip)->take($limit)->get();

//        foreach ($list as $k => $v)
//        {
//            $list[$k]->encode_id = encode($v->id);
//        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 【充值记录】返回-列表-数据
    public function get_finance_recharge_record_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = FundRechargeRecord::select('*')
//        $query = FundRechargeRecord::select('id','userid','puserid','createuserid','amount','createtime')
            ->with('user','parent','creator');

        if(!empty($post_data['creator'])) $query->where('createusername', 'like', "%{$post_data['creator']}%");
//        {
//            $query->whereHas('fund', function ($query1) { $query1->where('totalfunds', '>=', 1000); } )
//        }

        if(!empty($post_data['receiver']))
        {
            $receiver = $post_data['receiver'];
            $query->whereHas('user', function ($query1) use($receiver) { $query1->where('username', 'like', $receiver); } );
        }



        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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


    // 【消费记录】返回-列表-数据
    public function get_finance_expense_record_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = ExpenseRecord::select('*')
//        $query = ExpenseRecord::select('id','siteid','keywordid','ownuserid','price','createtime')
            ->with('user','site','keyword');

        if(!empty($post_data['standarddate']))
        {
            $query->whereDate('standarddate', $post_data['standarddate']);
        }

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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
    // 【每日消费记录】返回-列表-数据
    public function get_finance_expense_record_daily_datatable($post_data)
    {
        $admin_id = Auth::guard("admin")->user()->id;
        $query = ExpenseRecord::select('*')
//        $query = ExpenseRecord::select('id','siteid','keywordid','ownuserid','price','createtime')
            ->with('user','site','keyword');

        if(!empty($post_data['createtime']))
        {
            $query->whereDate('createtime', $post_data['createtime']);
        }
        else
        {
            $query->whereDate('createtime', date("Y-m-d") );
        }


        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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
//        $list["fund_total"] = $fund_total;
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 【资金冻结记录】返回-列表-视图
    public function show_finance_freeze_record()
    {
        $freeze_data = [];
        return view('mt.admin.entrance.finance.freeze-record')
            ->with([
                'freeze_data'=>$freeze_data,
                'sidebar_finance_active'=>'active',
                'sidebar_finance_freeze_active'=>'active'
            ]);
    }
    // 【资金冻结记录】返回-列表-数据
    public function get_finance_freeze_record_datatable($post_data)
    {
        $admin = Auth::guard("admin")->user();
        $query = FundFreezeRecord::select('*')
            ->with('creator','site','keyword');

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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




    // 删除
    public function delete($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $activity = Activity::find($id);
        if($activity->admin_id != $admin->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $bool = $activity->delete();
            if($bool)
            {
                $item = Item::find($activity->item_id);
                if($item)
                {
                    $bool1 = $item->delete();
                    if(!$bool1) throw new Exception("delete-item--fail");
                }
            }
            else throw new Exception("delete-activity--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'删除失败，请重试');
        }

    }
    // 启用
    public function enable($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $activity = Activity::find($id);
        if($activity->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $activity->fill($update)->save();
            if($bool)
            {
                $item = Item::find($activity->item_id);
                if($item)
                {
                    $bool1 = $item->fill($update)->save();
                    if(!$bool1) throw new Exception("update-item--fail");
                }
            }
            else throw new Exception("update-activity--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'启用失败，请重试');
        }
    }
    // 禁用
    public function disable($post_data)
    {
        $admin = Auth::guard('admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $activity = Activity::find($id);
        if($activity->admin_id != $admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $activity->fill($update)->save();
            if($bool)
            {
                $item = Item::find($activity->item_id);
                if($item)
                {
                    $bool1 = $item->fill($update)->save();
                    if(!$bool1) throw new Exception("update-item--fail");
                }
            }
            else throw new Exception("update-activity--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'禁用失败，请重试');
        }
    }




    // 下载
    public function operate_download_keyword_today()
    {
        $cellData = SEOKeyword::select('keyword','searchengine','price','detectiondate','latestranking')
            ->whereDate('detectiondate',date("Y-m-d"))->orderby('id','desc')
            ->get()
            ->toArray();
        array_unshift($cellData,['关键词','搜索引擎','价格','检测时间','排名']);

        $title = '【今日关键词】 - '.date('YmdHis');
        Excel::create($title,function($excel) use ($cellData){
            $excel->sheet('all', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    // 下载
    public function operate_download_keyword_detect($post_data)
    {
//        echo 0;
        $keyword_id = $post_data["id"];
        $keyword = SEOKeyword::find($keyword_id);
        if(!$keyword) return response_fail([],'关键词不存在，请重试！');

        $cellData = SEOKeywordDetectRecord::select('detect_time','rank')
            ->where('keywordid',$keyword_id)->orderby('detect_time','desc')
            ->get()
            ->toArray();
        array_unshift($cellData,['检测时间','排名']);

        $title = "【关键词】{$keyword->keyword}-{$keyword->searchengine}-{$keyword->price}元 - ".date('YmdHis');
        $engine = $keyword->searchengine;
        Excel::create($title,function($excel) use ($cellData,$keyword,$engine){
            $excel->sheet($engine, function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
        return response_success([]);
    }




    /*
     * 公告&通知
     */
    // 【公告】返回-添加-视图
    public function show_notification_all_list($post_data)
    {
        $me = Auth::guard('org')->user();
        $me_id = $me->id;

        $count = K_Notification::where(['is_read'=>0,'notification_category'=>11])->count();
        if($count)
        {
            $notification_list = K_Notification::with([
                'source',
                'item'=>function($query) {
                    $query->with([
                        'owner',
                        'forward_item'=>function($query) { $query->with('user'); }
                    ]);
                },
                'communication'=>function($query) { $query->with(['user']); },
                'reply'=>function($query) {
                    $query->with([
                        'user',
                        'reply'=>function($query) { $query->with('user'); }
                    ]);
                }
            ])
                ->where(['notification_type'=>11,'is_read'=>0])
//                ->where(['user_id'=>$me_id])
                ->orderBy('id','desc')
                ->get();

            $update_num = K_Notification::where(['notification_category'=>11,'is_read'=>0])->update(['is_read'=>1]);
            view()->share('notification_style', 'new');
        }
        else
        {
            $notification_list = K_Notification::with([
                'source',
                'item'=>function($query) {
                    $query->with([
                        'owner',
                        'forward_item'=>function($query) { $query->with('user'); }
                    ]);
                },
                'communication'=>function($query) { $query->with(['user']); },
                'reply'=>function($query) {
                    $query->with([
                        'user',
                        'reply'=>function($query) { $query->with('user'); }
                    ]);
                }
            ])
                ->where(['notification_category'=>11])
//                ->where(['user_id'=>$me_id])
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
        return view(env('TEMPLATE_DEFAULT').'org.entrance.notification.notification-all-list')
            ->with([
                'notification_list'=>$notification_list,
                'sidebar_notification_all_list_active'=>'active'
            ]);
    }




    /*
     * 公告&通知
     */
    // 【公告】返回-添加-视图
    public function view_notice_notice_create()
    {
        $admin = Auth::guard('admin')->user();
        $view_blade = 'mt.admin.entrance.notice.notice-edit';
        return view($view_blade)->with(['operate'=>'create', 'operate_id'=>0]);
    }
    // 【公告】返回-编辑-视图
    public function view_notice_notice_edit($post_data)
    {
        $me = Auth::guard('admin')->user();
        if(!in_array($me->usergroup,['Manage'])) return response("你没有权限操作！", 404);

        $id = $post_data["id"];
        $mine = Item::with(['user'])->find($id);
        if(!$mine) return response_error([],"该公告不存在，刷新页面试试！");

        $view_blade = 'mt.admin.entrance.notice.notice-edit';

        if($id == 0)
        {
            return view($view_blade)->with(['operate'=>'create', 'operate_id'=>$id]);
        }
        else
        {
            $mine = Item::with(['user'])->find($id);
            if($mine)
            {
                $mine->custom = json_decode($mine->custom);
                $mine->custom2 = json_decode($mine->custom2);
                $mine->custom3 = json_decode($mine->custom3);

                return view($view_blade)->with(['operate'=>'edit', 'operate_id'=>$id, 'data'=>$mine]);
            }
            else return response("该公告不存在！", 404);
        }
    }
    // 【公告】保存数据
    public function operate_notice_notice_save($post_data)
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


        $me = Auth::guard('admin')->user();
        if($me->usergroup != "Manage") return response_error([],"你没有操作权限！");


        $operate = $post_data["operate"];
        $operate_id = $post_data["operate_id"];

        if($operate == 'create') // 添加 ( $id==0，添加一个新用户 )
        {
            $mine = new Item;
            $post_data["category"] = 9;
            $post_data["sort"] = 1;
            $post_data["creator_id"] = $me->id;
        }
        else if($operate == 'edit') // 编辑
        {
            $mine = Item::find($operate_id);
            if(!$mine) return response_error([],"该公告不存在，刷新页面重试！");
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
                }
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


    // 【公告】返回-列表-视图
    public function show_notice_notice_list()
    {
        return view('mt.admin.entrance.notice.notice-list')
            ->with(['sidebar_notice_notice_list_active'=>'active']);
    }
    // 【公告】返回-列表-数据
    public function get_notice_notice_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();

        $query = Item::select('*')->with(['creator'])->where('category',9);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['creator']))
        {
            $creator = $post_data['creator'];
            $query->whereHas('creator',function ($query1) use ($creator)  { $query1->where('username', 'like', "%{$creator}%"); });
        }
        if(!empty($post_data['sort'])) $query->where('sort',$post_data['sort']);
        if(!empty($post_data['type'])) $query->where('type',$post_data['type']);

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }

    // 【我发布的公告】返回-列表-视图
    public function show_notice_my_notice_list()
    {
        return view('mt.admin.entrance.notice.my-notice-list')
            ->with(['sidebar_notice_my_notice_list_active'=>'active']);
    }
    // 【我发布的公告】返回-列表-数据
    public function get_notice_my_notice_list_datatable($post_data)
    {
        $me = Auth::guard("admin")->user();

        $query = Item::select('*')->with(['creator'])->where('category',9)->where('creator_id',$me->id);

        if(!empty($post_data['title'])) $query->where('title', 'like', "%{$post_data['title']}%");
        if(!empty($post_data['creator']))
        {
            $creator = $post_data['creator'];
            $query->whereHas('creator',function ($query1) use ($creator)  { $query1->where('username', 'like', "%{$creator}%"); });
        }
        if(!empty($post_data['sort'])) $query->where('sort',$post_data['sort']);
        if(!empty($post_data['type'])) $query->where('type',$post_data['type']);

        $total = $query->count();

        $draw  = isset($post_data['draw'])  ? $post_data['draw']  : 1;
        $skip  = isset($post_data['start'])  ? $post_data['start']  : 0;
        $limit = isset($post_data['length']) ? $post_data['length'] : 20;

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

        foreach ($list as $k => $v)
        {
            $list[$k]->encode_id = encode($v->id);
        }
//        dd($list->toArray());
        return datatable_response($list, $draw, $total);
    }


    // 【公告】获取-详情
    public function operate_notice_notice_get($post_data)
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
        if($operate != 'notice-get') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if(!in_array($me->usergroup,['Manage'])) return response_error([],"你没有操作权限！");

        $work_order = Item::find($id);
        return response_success($work_order,"");

    }
    // 【公告】发布
    public function operate_notice_notice_push($post_data)
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
        if($operate != 'notice-push') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $item = Item::find($id);
        if(!$item) return response_error([],"该公告不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $item->active = 1;
            $bool = $item->save();
            if(!$bool) throw new Exception("update--item--fail");

            DB::commit();
            return response_success([],"操作成功！");
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
    // 【公告】删除
    public function operate_notice_notice_delete($post_data)
    {
        $messages = [
            'operate.required' => '参数有误',
            'id.required' => '请输入ID',
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
        if($operate != 'notice-delete') return response_error([],"参数有误！");
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"参数ID有误！");

        $me = Auth::guard('admin')->user();
        if($me->usertype != "admin") return response_error([],"你没有操作权限");

        $item = Item::find($id);
        if(!$item) return response_error([],"该公告不存在，刷新页面重试");

        // 启动数据库事务
        DB::beginTransaction();
        try
        {
            $bool = $item->delete();
            if(!$bool) throw new Exception("delete--item--fail");

            DB::commit();
            return response_success([],"操作成功！");
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




}