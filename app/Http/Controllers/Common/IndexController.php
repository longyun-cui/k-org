<?php

namespace App\Http\Controllers\Common;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\K\K_User;
use App\Models\K\K_Item;

use App\Repositories\Common\IndexRepository;

use QrCode, Image;


class IndexController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new IndexRepository;
    }



    public function operate_download_qr_code()
    {
        $type = request('type','none');
        $id = request('id',0);

        $view = env('TEMPLATE_ADMIN').'frontend.errors.404';

        $qr_code_path = "resource/unique/qr_code/";
        if($type == "none")
        {
            return response()->view($view);
        }
        else if($type == "user")
        {
            $qr_code = $qr_code_path.'qr_code_user_'.$id.'.png';
            $user = K_User::find($id);
            if(!$user) return view($view);
        }
        else if($type == "item")
        {
            $qr_code = $qr_code_path.'qr_code_item_'.$id.'.png';
            $item = K_Item::find($id);
            if(!$item) return view($view);
        }
        else
        {
            return response()->view($view);
        }

        if(file_exists(storage_path($qr_code)))
        {
            return response()->download(storage_path($qr_code), 'qr_code.png');
        }
        else
        {
            $create_result = $this->create_qr_code($type, $id);
            if($create_result)
            {
                return response()->download(storage_path($qr_code), 'qr_code.png');
            }
            else
            {
                return response()->view($view);
            }
        }
    }




    // 生成二维码
    public function create_qr_code($type, $id)
    {
        // 保存二维码
        if($type == 'none')
        {
            return 0;
        }
        else if($type == 'user')
        {
            $url = 'http://www.k-org.cn/user/'.$id;  // 目标URL
            $filename = 'qr_code_user_'.$id.'.png';  // 目标文件
        }
        else if($type == 'item')
        {
            $url = 'http://www.k-org.cn/item/'.$id;  // 目标URL
            $filename = 'qr_code_item_'.$id.'.png';  // 目标文件
        }
        else
        {
            return 0;
        }

        // 保存位置
        $qr_code_path = "resource/unique/qr_code/";
        if(!file_exists(storage_path($qr_code_path)))
            mkdir(storage_path($qr_code_path), 0777, true);

        // qr_code 图片文件
        $qr_code = $qr_code_path.$filename;

        QrCode::errorCorrection('H')->format('png')->size(640)->margin(0)->encoding('UTF-8')->generate($url,storage_path($qr_code));
        return 1;
    }



}
