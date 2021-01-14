<?php
namespace App\Repositories\Org;

use App\User;
use App\Administrator;

use App\Models\K\K_User;
use App\Models\K\K_Item;
use App\Models\K\K_Verification;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception;
use QrCode;

class AuthRepository {

    private $model;
    public function __construct()
    {
    }

    // 注册用户
    public function register($post_data)
    {
        $messages = [
//            'captcha.required' => '请输入验证码',
//            'captcha.captcha' => '验证码有误',
            'username.required' => '请填写组织名称！',
            'mobile.required' => '请输入手机！',
            'mobile.unique' => '手机已存在，请更换手机！',
            'password.required' => '请输入密码！',
            'password_confirm.required' => '请确认密码！',
        ];
        $v = Validator::make($post_data, [
//            'captcha' => 'required|captcha',
            'username' => 'required',
            'mobile' => 'required|unique:user',
            'password' => 'required',
            'password_confirm' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error(['error_type'=>$messages->keys()[0]],$messages->first());
        }


        $username = $post_data['username'];
        $mobile = $post_data['mobile'];
        if(!isMobile($mobile)) return response_error(['error_type'=>'mobile'],'非法手机号！');
        $password = $post_data['password'];
        $password_confirm = $post_data['password_confirm'];
        if($password == $password_confirm)
        {
            DB::beginTransaction();
            try
            {
                // 注册超级管理员
                $user = new K_User;
                $user_create['user_category'] = 1;
                $user_create['user_type'] = 11;
                $user_create['username'] = $username;
                $user_create['mobile'] = $mobile;
                $user_create['password'] = password_encode($password);
                $user_create['portrait_img'] = 'unique/portrait/user2.jpg';
                $bool = $user->fill($user_create)->save();
                if($bool)
                {
                }
                else throw new Exception("insert--user--failed");

                DB::commit();
                return response_success([],'注册成功！');
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '注册失败，请重试！';
                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([],$msg);
            }
        }
        else return response_error(['error_type'=>'password_confirm'],'确认密码不一致！');
    }

    // 激活邮箱
    public function activation($post_data)
    {
        $user_id = decode($post_data['user']);
        $where['user_id'] = $user_id;
        $where['type'] = $post_data['type'];
        $where['code'] = $post_data['code'];
        $verification = Verification::where($where)->first();
        if($verification)
        {
            if($verification->active == 0)
            {
                $user = User::where('id',$user_id)->first();
                if($user)
                {
                    $user->active = 1;
                    $bool1 = $user->save();
                    if($bool1)
                    {
                        $verification->active = 1;
                        $bool2 = $verification->save();
                        header("Refresh:4;url=/home");
                        if($bool2) echo('验证成功，5秒后跳转后台页面！');
                        else echo('验证成功2，5秒后跳转后台页面！');
                    }
                    else dd('验证失败');
                }
            }
            else
            {
                header("Refresh:3;url=/home");
                echo('已经验证过了，3秒后跳转后台页面！');
            }
        }
        else dd('参数有误');
    }


}