<?php
namespace App\Repositories\OrgAdmin;

use App\Models\RootItem;
use App\Models\RootMenu;

use App\Repositories\Common\CommonRepository;

use Request, Response, Auth, Validator, DB, Exception;
use QrCode;

class ItemRepository {

    private $model;
    public function __construct()
    {
//        $this->model = new RootItem;
    }


    public function root()
    {
        return view('org-admin.entrance.root');
    }


    // 返回【列表】数据
    public function get_list_datatable($post_data)
    {
        $org_admin = Auth::guard('org_admin')->user();
        $query = RootItem::select("*")->with(['admin'])->where('org_id', $org_admin->id)->where('item_id', 0);

        $category = isset($post_data['category']) ? $post_data['category'] : '';
        if($category == "article") $query->where('category', 1);
        else if($category == "activity") $query->where('category', 11);
        else if($category == "sponsor") $query->where('category', 88);

        if(!empty($post_data['name'])) $query->where('name', 'like', "%{$post_data['name']}%");

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
        return datatable_response($list, $draw, $total);
    }


    // 返回【添加】视图
    public function view_create()
    {
        $category = request("category",'');

        if($category == 'item') $view_blade = 'org-admin.entrance.item-edit';
        elseif($category == 'article') $view_blade = 'org-admin.entrance.item-edit-article';
        elseif($category == 'activity') $view_blade = 'org-admin.entrance.item-edit-activity';
        elseif($category == 'sponsor') $view_blade = 'org-admin.entrance.item-edit-sponsor';
        else $view_blade = 'org-admin.entrance.item-edit';

        if($category == 'item') $menus = RootMenu::get();
        elseif($category == 'article') $menus = RootMenu::where(['category'=>1])->get();
        elseif($category == 'activity') $menus = RootMenu::where(['category'=>11])->get();
        elseif($category == 'sponsor') $menus = RootMenu::where(['category'=>88])->get();
        else $menus = [];

        return view($view_blade)->with(['operate'=>'create', 'encode_id'=>encode(0), 'menus'=>$menus]);
    }

    // 返回【编辑】视图
    public function view_edit()
    {
        $id = request("id",0);
        $decode_id = decode($id);
        if(!$decode_id && intval($id) !== 0) return view('home.404');

        if($decode_id == 0)
        {
            return view('org-admin.entrance.item-edit')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = RootItem::find($decode_id);
            if($data)
            {
                unset($data->id);
                return view('org-admin.entrance.item-edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data]);
            }
            else return response("该内容不存在！", 404);
        }
    }

    // 【保存】
    public function save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'title.required' => '请输入标题',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'title' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $org_admin = Auth::guard('org_admin')->user();

        $id = decode($post_data["id"]);
        $operate = $post_data["operate"];
        if(intval($id) !== 0 && !$id) return response_error();

        DB::beginTransaction();
        try
        {
            if($operate == 'create') // $id==0，添加一个新的内容
            {
                $mine = new RootItem;
                $post_data["admin_id"] = $org_admin->id;
                $post_data["org_id"] = $org_admin->org_id;
            }
            elseif('edit') // 编辑
            {
                $mine = RootItem::find($id);
                if(!$mine) return response_error([],"该内容不存在，刷新页面重试");
                if($mine->admin_id != $org_admin->id) return response_error([],"你没有操作权限");
            }
            else throw new Exception("operate--error");

            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            // 时间
            if($operate == 'create' && $post_data['category'] == 11)
            {
                $post_data['time_type'] = 1;
                if(!empty($post_data['start_time'])) {
                    $post_data['start_time'] = strtotime($post_data['start_time']);
                }
                else $post_data['start_time'] = 0;

                if(!empty($post_data['end_time'])) {
                    $post_data['end_time'] = strtotime($post_data['end_time']);
                }
                else $post_data['end_time'] = 0;
            }
            else {
                $post_data['time_type'] = 0;
                unset($post_data['start_time']);
                unset($post_data['end_time']);
            }

            $bool = $mine->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($mine->id);

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
            return response_success(['id'=>$encode_id]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
//            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([], $msg);
        }
    }

    // 【删除】
    public function delete($post_data)
    {
        $org_admin = Auth::guard('org_admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该内容不存在，刷新页面试试");

        $mine = RootItem::find($id);
        if($mine->admin_id != $org_admin->id) return response_error([],"你没有操作权限");

        DB::beginTransaction();
        try
        {
            $content = $mine->content;
            $cover_pic = $mine->cover_pic;

            $bool = $mine->delete();
            if(!$bool) throw new Exception("delete--item--fail");

            DB::commit();

            // 删除UEditor图片
            $img_tags = get_html_img($content);
            foreach ($img_tags[2] as $img)
            {
                if (!empty($img) && file_exists(public_path($img)))
                {
                    unlink(public_path($img));
                }
            }

            // 删除封面图片
            if(!empty($cover_pic) && file_exists(storage_path("resource/" . $cover_pic)))
            {
                unlink(storage_path("resource/" . $cover_pic));
            }

            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            $msg = '操作失败，请重试！';
//            $msg = $e->getMessage();
//            exit($e->getMessage());
            return response_fail([],$msg);
        }

    }

    // 【分享】
    public function share($post_data)
    {
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该内容不存在，刷新页面试试");

        if(!is_numeric($post_data["is_shared"])) return response_error([],"参数有误，刷新页面试试");

        $org_admin = Auth::guard('org_admin')->user();

        $mine = RootItem::find($id);
        if($mine->admin_id != $org_admin->id) return response_error([],"你没有操作权限");
        $update["is_shared"] = $post_data["is_shared"];
        DB::beginTransaction();
        try
        {
            $mine->timestamps = false;
            $bool = $mine->fill($update)->save();
            if(!$bool) throw new Exception("update--item--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'操作失败，请重试');
        }
    }

    // 【启用】
    public function enable($post_data)
    {
        $org_admin = Auth::guard('org_admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该内容不存在，刷新页面试试");

        $mine = RootItem::find($id);
        if($mine->admin_id != $org_admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 1;
        DB::beginTransaction();
        try
        {
            $bool = $mine->fill($update)->save();
            if(!$bool) throw new Exception("update--item--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'启用失败，请重试');
        }
    }

    // 【禁用】
    public function disable($post_data)
    {
        $org_admin = Auth::guard('org_admin')->user();
        $id = decode($post_data["id"]);
        if(intval($id) !== 0 && !$id) return response_error([],"该内容不存在，刷新页面试试");

        $mine = RootItem::find($id);
        if($mine->admin_id != $org_admin->id) return response_error([],"你没有操作权限");
        $update["active"] = 9;
        DB::beginTransaction();
        try
        {
            $bool = $mine->fill($update)->save();
            if(!$bool) throw new Exception("update--item--fail");

            DB::commit();
            return response_success([]);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response_fail([],'禁用失败，请重试');
        }
    }




    //
    public function select2_menus($post_data)
    {
        $item_encode = $post_data['course_id'];
        $item_decode = decode($item_encode);
        if(!$item_decode) return view('home.404')->with(['error'=>'参数有误']);

        if(empty($post_data['keyword']))
        {
            $list =RootItem::select(['id','title as text'])->where('item_id', $item_decode)->get()->toArray();
        }
        else
        {
            $keyword = "%{$post_data['keyword']}%";
            $list =RootItem::select(['id','title as text'])->where('item_id', $item_decode)->where('name','like',"%$keyword%")->get()->toArray();
        }
        return $list;
    }




}