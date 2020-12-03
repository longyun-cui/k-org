<?php
namespace App\Repositories\Common;

use QrCode, Image;

/**
 * Description of UploadRepository
 */
class IndexRepository {

    public function operate_download_qr_code($post_data)
    {
        $type = !empty($post_data['type']) ? $post_data['type'] : 'none';
        $id = !empty($post_data['id']) ? $post_data['id'] : '0';

        $qr_code_path = "resource/unique/qr_code/";

        if($type == "none")
        {
            response()->view(env('TEMPLATE_ADMIN').'frontend.errors.404');
            return false;
        }
        else if($type == "user")
        {
            $qr_code = $qr_code_path.'qr_code_user_'.$id.'.png';
        }
        else if($type == "item")
        {
            $qr_code = $qr_code_path.'qr_code_item_'.$id.'.png';
        }
        else
        {
            response()->view(env('TEMPLATE_ADMIN').'frontend.errors.404');
            return false;
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
                return response()->view(env('TEMPLATE_ADMIN').'frontend.errors.404');
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
