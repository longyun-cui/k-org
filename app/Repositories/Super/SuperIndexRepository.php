<?php
namespace App\Repositories\Super;

use App\Models\K\K_User;
use App\Models\K\K_Item;
use App\Models\K\K_Record;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;

class SuperIndexRepository {

    private $env;
    private $auth_check;
    private $me;
    private $me_admin;
    private $modelUser;
    private $modelItem;
    private $view_blade_403;
    private $view_blade_404;

    public function __construct()
    {
        $this->modelUser = new K_User;
        $this->modelItem = new K_Item;

        $this->view_blade_403 = env('TEMPLATE_K_SUPER_ADMIN').'entrance.errors.403';
        $this->view_blade_404 = env('TEMPLATE_K_SUPER_ADMIN').'entrance.errors.404';

        Blade::setEchoFormat('%s');
        Blade::setEchoFormat('e(%s)');
        Blade::setEchoFormat('nl2br(e(%s))');

        if(isMobileEquipment()) $is_mobile_equipment = 1;
        else $is_mobile_equipment = 0;
        view()->share('is_mobile_equipment',$is_mobile_equipment);
    }


    // 登录情况
    public function get_me()
    {
        if(Auth::guard("super")->check())
        {
            $this->auth_check = 1;
            $this->me = Auth::guard("super")->user();
            $me = $this->me;
            view()->share('me',$me);
        }
        else $this->auth_check = 0;

        view()->share('auth_check',$this->auth_check);

        if(isMobileEquipment()) $is_mobile_equipment = 1;
        else $is_mobile_equipment = 0;
        view()->share('is_mobile_equipment',$is_mobile_equipment);
    }




    // 返回（后台）主页视图
    public function view_index()
    {
        $this->get_me();
        $me = $this->me;

        $view_data['index_data'] = [];
        $view_data['consumption_data'] = [];
        $view_data['insufficient_clients'] = [];

        $view_blade = env('TEMPLATE_K_SUPER_ADMIN').'entrance.index';

        return view($view_blade)->with($view_data);
    }




}