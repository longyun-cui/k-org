<?php
namespace App\Http\Controllers\OrgAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Org\OrgAdministrator;

use App\Repositories\OrgAdmin\AuthRepository;

use Response, Auth, Validator, DB, Exception;


class AuthController extends Controller
{
    //
    private $service;
    private $repo;
    public function __construct()
    {
        $this->repo = new AuthRepository;
    }

    // 登陆
    public function org_admin_login()
    {
        if(request()->isMethod('get')) return view('org-admin.auth.login');
        else if(request()->isMethod('post'))
        {
            $where['email'] = request()->get('email');
            $where['password'] = request()->get('password');
            $email = request()->get('email');
            $password = request()->get('password');
            $admin = OrgAdministrator::whereEmail($email)->first();
            if($admin)
            {
                if($admin->active == 1)
                {
                    if(password_check($password,$admin->password))
                    {
                        Auth::guard('org_admin')->login($admin,true);
                        return response_success();
                    }
                    else return response_error([],'账户or密码不正确 ');
                }
                else return response_error([],'账户尚未激活，请先去邮箱激活。');
            }
            else return response_error([],'账户不存在');
        }
    }

    // 退出
    public function org_admin_logout()
    {
        Auth::guard('org_admin')->logout();
        return redirect('/org-admin/login');
    }

    // 注册
    public function org_admin_register()
    {
        if(request()->isMethod('get')) return view('home.auth.register');
        else if(request()->isMethod('post'))
        {
            return $this->repo->register(request()->all());
        }
    }

    // 激活用户
    public function org_admin_activation()
    {
        $this->repo->activation(request()->all());
    }





}
