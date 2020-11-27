<?php
namespace App\Http\Controllers\Sponsor;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\K\K_User;
use App\Models\K\K_Item;

use Response, Auth, Validator, DB, Exception;


class AuthController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
//        $this->repo = new AuthRepository;
    }

    // 登陆
    public function login()
    {
        if(request()->isMethod('get'))
        {
            return view(env('TEMPLATE_ADMIN').'sponsor.auth.login');
        }
        else if(request()->isMethod('post'))
        {
//            $where['password'] = request()->get('password');
//            $where['email'] = request()->get('email');
//            $where['mobile'] = request()->get('mobile');
//            $admin = Administrator::where($where)->first();

            // 邮箱验证
//            $email = request()->get('email');
//            $admin = Administrator::whereEmail($email)->first();

            // 手机验证
            $mobile = request()->get('mobile');
            $me = K_User::whereMobile($mobile)->first();

            if($me)
            {
//                if($admin->active == 1)
//                {
                    $password = request()->get('password');
                    if(password_check($password,$me->password))
                    {
                        if($me->user_type == 88)
                        {
                            Auth::guard('sponsor')->login($me,true);
                            return response_success();
                        }
                        else return response_error([],'账户类型错误！');
                    }
                    else return response_error([],'账户or密码不正确！');
//                }
//                else return response_error([],'账户尚未激活，请先激活账户！');
            }
            else return response_error([],'账户不存在！');
        }
    }

    // 退出
    public function logout()
    {
        Auth::guard('sponsor')->logout();
        return redirect('/sponsor/login');
    }






}
