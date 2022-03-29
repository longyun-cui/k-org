<?php
namespace App\Repositories\WWW;

use App\User;

use App\Models\K\K_User;
use App\Models\K\K_Item;
use App\Models\K\K_Communication;
use App\Models\K\K_Pivot_User_Relation;
use App\Models\K\K_Pivot_User_Item;
use App\Models\K\K_Notification;
use App\Models\K\K_Record;

use App\Repositories\Common\CommonRepository;

use Response, Auth, Validator, DB, Exception, Blade;
use QrCode;

class IndexRepository {

    private $model;
    public function __construct()
    {
        Blade::setEchoFormat('%s');
        Blade::setEchoFormat('e(%s)');
        Blade::setEchoFormat('nl2br(e(%s))');
    }


    // 【K】【平台首页】
    public function view_root($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;
        }
        else $me_id = 0;


        if(Auth::check())
        {
            $item_query = K_Item::with([
                    'owner',
                    'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
                ]);

            $user_list = K_User::with([
                    'ad',
                    'fans_list'=>function($query) use($me_id) { $query->where('mine_user_id',$me_id); },
                ])->withCount([
                    'fans_list as fans_count' => function($query) { $query->where([]); },
                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
                ])
                ->where('user_type',11)
                ->where('user_status',1)
                ->where('active',1)
                ->orderByDesc('id')
                ->get();
//                ->paginate(20);
        }
        else
        {
            $item_query = K_Item::with(['owner']);

            $user_list = K_User::with([
                    'ad',
                ])->withCount([
                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
                ])
                ->where('user_type',11)
                ->where('user_status',1)
                ->where('active',1)
                ->orderByDesc('id')
                ->get();
//                ->paginate(20);
        }

        $item_query->where(['active'=>1,'status'=>1,'item_active'=>1,'item_status'=>1,'is_published'=>1]);

        $type = !empty($post_data['type']) ? $post_data['type'] : 'root';
        if($type == 'root')
        {
            $item_query->whereIn('item_type',[1,11]);
            $record["page_module"] = 1; // page_module=1 default index
        }
        else if($type == 'article')
        {
            $item_query->whereIn('item_type',[1]);
            $record["page_module"] = 9; // page_module=9 article
        }
        else if($type == 'activity')
        {
            $item_query->whereIn('item_type',[11]);
            $record["page_module"] = 11; // page_module=11 activity
        }
        else
        {
            $record["page_module"] = 1; // page_module=0 default index
        }


        $item_list = $item_query->orderByDesc('published_at')->paginate(20);
        dd(1);

        foreach ($item_list as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);

            $item->custom = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);

            if(@getimagesize(env('DOMAIN_CDN').'/'.$item->cover_pic))
            {
                $item->cover_picture = env('DOMAIN_CDN').'/'.$item->cover_pic;
            }
            else
            {
                if(!empty($item->img_tags[0])) $item->cover_picture = $item->img_tags[2][0];
            }
//            dd($item->cover_picture);
        }

        $return['item_list'] = $item_list;

        $return['user_list'] = $user_list;



        // 插入记录表
        $record["record_category"] = 1; // record_category=1 browse/share
        $record["record_type"] = 1; // record_type=1 browse
        $record["page_type"] = 1; // page_type=1 default platform
        $record["page_num"] = $item_list->toArray()["current_page"];
        $record["from"] = request('from',NULL);
        $this->record($record);


        $page["type"] = 1;
        $page["module"] = 1;
        $page["num"] = 0;
        $page["item_id"] = 0;
        $page["user_id"] = 0;

        $sidebar_active = '';

        if($type == 'root')
        {
            $head_title = "首页 - 朝鲜族组织平台";
            $sidebar_active = 'sidebar_menu_root_active';
            $page["module"] = 1;
        }
        else if($type == 'article')
        {
            $head_title = "文章 - 朝鲜族组织平台";
            $sidebar_active = 'sidebar_menu_article_active';
            $page["module"] = 9;
        }
        else if($type == 'activity')
        {
            $head_title = "活动 - 朝鲜族组织平台";
            $sidebar_active = 'sidebar_menu_activity_active';
            $page["module"] = 11;
        }
        else
        {
            $head_title = "首页 - 朝鲜族组织平台";
            $sidebar_active = 'sidebar_menu_root_active';
            $page["module"] = 1;
        }


        $return[$sidebar_active] = 'active';
        $return['head_title'] = $head_title;
        $return['getType'] = 'items';
        $return['page_type'] = 'root';
        $return['page'] = $page;
        dd(0);

        $path = request()->path();
        if($path == "root-1") return view(env('TEMPLATE_K_WWW').'entrance.root-1')->with($return);
//        else return view('entrance.root')->with($return);
        else return view(env('TEMPLATE_K_WWW').'entrance.root')->with($return);
    }

    // 【K】【平台介绍】
    public function view_introduction()
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;
        }
        else $me_id = 0;

        $introduction = K_Item::find(1);




        // 插入记录表
        $record["record_category"] = 1; // record_category=1 browse/share
        $record["record_type"] = 1; // record_type=1 browse
        $record["page_type"] = 1; // page_type=1 default platform
        $record["page_module"] = 2; // page_module=2 introduction
        $record["page_num"] = 1;
        $record["from"] = request('from',NULL);
        $this->record($record);

        $page["type"] = 1;
        $page["module"] = 2;
        $page["num"] = 0;
        $page["item_id"] = 0;
        $page["user_id"] = 0;

        $return['data'] = $introduction;
        $return['page'] = $page;

        $path = request()->path();
        if($path == "root-1") return view(env('TEMPLATE_K_WWW').'entrance.root-1')->with($return);
//        else return view('entrance.root')->with($return);
        else return view(env('c').'entrance.root-introduction')->with($return);
    }


    // 【K】【平台首页】
    public function view_tag($post_data,$q='')
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;
        }
        else $me_id = 0;

        if(Auth::check())
        {
            $user_query = K_User::select('*')
                ->with([
                    'ad',
                    'fans_list'=>function($query) use($me_id) { $query->where('mine_user_id',$me_id); },
                ])
                ->withCount([
                    'fans_list as fans_count' => function($query) { $query->where([]); },
                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
                ])
                ->where('user_type',11)
                ->where('user_status',1)
                ->where('active',1);

            if($q) $user_query->where('tag','like',"%$q%");

            $user_list = $user_query->orderByDesc('id')->paginate(20);

            $item_query = K_Item::select('*')
                ->with([
                    'owner',
                    'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
                ]);
        }
        else
        {
            $user_query = K_User::select('*')
                ->with([
                    'ad',
                ])
                ->withCount([
                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
                ])
                ->where('user_type',11)
                ->where('user_status',1)
                ->where('active',1);

            if($q) $user_query->where('tag','like',"%$q%");

            $user_list = $user_query->orderByDesc('id')->paginate(20);

            $item_query = K_Item::select('*')->with(['owner']);
        }

        $return['user_list'] = $user_list;

        $user_ids = $user_list->pluck('id')->toArray();

//        if(!count($user_ids))
//        {
//            $user_ids = [0];
//        }

        $item_query->where(['item_status'=>1,'active'=>1])->whereIn('owner_id',$user_ids);

        $type = !empty($post_data['type']) ? $post_data['type'] : 'root';
        if($type == 'root')
        {
            $item_query->whereIn('item_type',[1,11]);
            $record["page_module"] = 1; // page_module=1 default index
        }
        else if($type == 'article')
        {
            $item_query->whereIn('item_type',[1]);
            $record["page_module"] = 9; // page_module=9 article
        }
        else if($type == 'activity')
        {
            $item_query->whereIn('item_type',[11]);
            $record["page_module"] = 11; // page_module=11 activity
        }
        else
        {
            $record["page_module"] = 1; // page_module=0 default index
        }


        $item_list = $item_query->orderByDesc('published_at')->paginate(20);
        $return['item_list'] = $item_list;


        foreach ($item_list as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }


        // 插入记录表
        $record["record_category"] = 1; // record_category 1.browse/2.share/3.search
        $record["record_type"] = 3; // record_type=3 search
        $record["page_type"] = 1; // page_type=1 default platform
        $record["page_num"] = $item_list->toArray()["current_page"];
        $record["title"] = $q;
        $record["from"] = request('from',NULL);
        $this->record($record);

        $sidebar_active = '';
//        if($type == 'root') $sidebar_active = 'sidebar_menu_root_active';
//        else if($type == 'article') $sidebar_active = 'sidebar_menu_article_active';
//        else if($type == 'activity') $sidebar_active = 'sidebar_menu_activity_active';


        $page["type"] = 3;
        $page["module"] = 1;
        $page["item_id"] = 0;
        $page["user_id"] = 0;

        $return['q'] = $q;
        $return[$sidebar_active] = 'active';
        $return['getType'] = 'items';
        $return['page_type'] = 'tag';
        $return['head_title'] = "#{$q} - 朝鲜族组织平台";
        $return['page'] = $page;

        $path = request()->path();
        if($path == "root-1") return view(env('TEMPLATE_K_WWW').'entrance.root-1')->with($return);
//        else return view('entrance.root')->with($return);
        else return view(env('TEMPLATE_K_WWW').'entrance.tag')->with($return);
    }




    // 【K】【分享记录】
    public function record_share($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;
        }
        else $me_id = 0;

        $record_module = isset($post_data["record_module"]) ? $post_data["record_module"] : 0;
        $page_type = isset($post_data["page_type"]) ? $post_data["page_type"] : 0;
        $page_module = isset($post_data["page_module"]) ? $post_data["page_module"] : 0;
        $page_num = isset($post_data["page_num"]) ? $post_data["page_num"] : 0;
        $item_id = isset($post_data["item_id"]) ? $post_data["item_id"] : 0;
        $user_id = isset($post_data["user_id"]) ? $post_data["user_id"] : 0;

        // 插入记录表
        $record["record_category"] = 1; // record_category=1 browse/share
        $record["record_type"] = 2; // record_type=2 share
        $record["record_module"] = $record_module; // record_module 1.微信好友|QQ好友 2.朋友圈|QQ空间
        $record["page_type"] = $page_type; // page_type=1 default platform
        $record["page_module"] = $page_module; // page_module=2 introduction
        $record["page_num"] = $page_num;
        $record["item_id"] = $item_id;
        $record["object_id"] = $user_id;
        $record["from"] = request('from',NULL);
        $this->record($record);

        if($page_type == 1)
        {

        }
        else if($page_type == 2)
        {
            $user = K_User::find($user_id);
            $user->timestamps = false;
            $user->increment('share_num');
        }
        else if($page_type == 3)
        {
            $item = K_Item::find($item_id);
            $item->timestamps = false;
            $item->increment('share_num');

            $user = K_User::find($item->owner_id);
            $user->timestamps = false;
            $user->increment('share_num');
        }

        return response_success([]);
    }




    /*
     * 用户基本信息
     */
    // 【基本信息】返回--视图
    public function view_my_info_index()
    {
        $me = Auth::user();
        return view(env('TEMPLATE_ADMIN').'entrance.my-info-index')->with(['info'=>$me]);
    }

    // 【基本信息】返回-编辑-视图
    public function view_my_info_edit()
    {
        $me = Auth::user();
        return view(env('TEMPLATE_ADMIN').'entrance.my-info-edit')->with(['info'=>$me]);
    }
    // 【基本信息】保存-数据
    public function operate_my_info_save($post_data)
    {
        $me = Auth::user();

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




    /*
     * 用户基本信息
     */
    // 【K】【内容列表】
    public function view_item_list($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
        }
        else $me_id = 0;

        $items = K_Item::with(['org'])->where('is_shared','>=',99);

        $category = isset($post_data["category"]) ? $post_data["category"] : '';
        if($category == 'article')
        {
            $items = $items->where('category','1');
            $return['root_article_active'] = 'active';
        }
        else if($category == 'activity')
        {
            $items = $items->where('category','11');
            $return['root_activity_active'] = 'active';
        }
        else if($category == 'sponsor')
        {
            $items = $items->where('category','88');
            $return['root_sponsor_active'] = 'active';
        }
        else $return['root_all_active'] = 'active';

        $items = $items->orderby('id','desc')->paginate(20);
        $return['items'] = $items;

        return view(env('TEMPLATE_K_WWW').'entrance.item-list')->with($return);
    }

    // 【K】【内容详情】
    public function view_item($post_data,$id=0)
    {

        $item = K_Item::with(['owner'])->find($id);
        if($item)
        {
            if($item->item_category != 1)
            {
                $error["text"] = '该内容拒绝访问！';
                return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
            }

            if($item->item_status != 1)
            {
                $error["text"] = '该内容被禁啦！';
                return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
            }

            $item->timestamps = false;
            $item->increment('visit_num');

            if($item->owner)
            {
                if($item->owner->user_category != 1)
                {
                    $error["text"] = '该内容用户有误！';
                    return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
                }
                if($item->owner->user_status != 1)
                {
                    $error["text"] = '该内容用户被禁啦！';
                    return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
                }
            }
            else
            {
                $error["text"] = '作者有误！';
                return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
            }

            $user = K_User::with([
                    'ad',
                    'pivot_sponsor_list'=>function($query) { $query->where(['relation_active'=>1,'relation_category'=>88])->orderby('updated_at','desc'); },
                ])
                ->withCount([
                    'items as article_count' => function($query) { $query->where(['item_status'=>1,'item_category'=>1,'item_type'=>1]); },
                    'items as activity_count' => function($query) { $query->where(['item_status'=>1,'item_category'=>1,'item_type'=>11]); },
                ])->find($item->owner_id);
            $user->timestamps = false;
            $user->increment('visit_num');

            $item->custom_decode = json_decode($item->custom);
        }
        else
        {
            $error["text"] = '内容不存在或者被删除了！';
            return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
        }


        $is_follow = 0;

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;

            $relation_with_me = K_Pivot_User_Relation::where(['mine_user_id'=>$me_id,'relation_user_id'=>$item->owner_id])->first();
            if($relation_with_me &&  in_array($relation_with_me->relation_type,[21,41]))
            {
                $is_follow = 1;
            }
        }
        else $me_id = 0;


        // 插入记录表
        $record["record_category"] = 1; // record_category=1 browse/share
        $record["record_type"] = 1; // record_type=1 browse
        $record["page_type"] = 3; // page_type=3 item
        $record["page_module"] = 1; // page_type=1 default index
        $record["object_id"] = $item->owner_id;
        $record["item_id"] = $id;
        $record["from"] = request('from',NULL);
        $this->record($record);


        $page["type"] = 3;
        $page["module"] = 1;
        $page["num"] = 1;
        $page["item_id"] = $id;
        $page["user_id"] = 0;

        return view(env('TEMPLATE_K_WWW').'entrance.item')
            ->with([
                'getType'=>'item',
                'item'=>$item,
                'user'=>$user,
                'is_follow'=>$is_follow,
                'page'=>$page,
            ]);
    }




    // 【K】【用户】【主页】
    public function view_user($post_data,$id=0)
    {
        $user_id = $id;

        $type = !empty($post_data['type']) ? $post_data['type'] : 'root';

        $user = K_User::with([
                'ext','introduction',
//                'items'=>function($query) { $query->with('owner')->where(['item_status'=>1,'active'=>1])->orderBy('published_at','desc'); },
//                'ad',
//                'ad_list'=>function($query) { $query->where(['item_category'=>1,'item_type'=>88])->orderby('updated_at','desc'); },
//                'pivot_sponsor_list'=>function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1,'user.user_status'=>1])->orderby('updated_at','desc'); },
//                'pivot_org_list'=>function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1])->orderby('updated_at','desc'); },
            ])
            ->withCount([
                'pivot_sponsor_list as pivot_sponsor_count' => function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1,'user.user_status'=>1]); },
                'pivot_org_list as pivot_org_count' => function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1]); },
                'items as item_count' => function($query) { $query->where(['is_published'=>1,'item_status'=>1,'item_category'=>1])->whereIn('item_type',[1,11]); },
                'items as article_count' => function($query) { $query->where(['is_published'=>1,'item_status'=>1,'item_category'=>1,'item_type'=>1]); },
                'items as activity_count' => function($query) { $query->where(['is_published'=>1,'item_status'=>1,'item_category'=>1,'item_type'=>11]); },
            ])
            ->find($user_id);


        if($user)
        {
            if($user->user_category != 1)
            {
                $error["text"] = '该用户拒绝访问！';
                return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
            }
            if($user->user_status != 1)
            {
                $error["text"] = '该用户被禁啦！';
                return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
            }

            if($user->user_type == 11)
            {
                $user->load([
                    'ad',
                    'pivot_sponsor_list'=>function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1,'user.user_status'=>1])->orderby('updated_at','desc'); }
                ]);
            }
            else if($user->user_type == 88)
            {
                $user->load([
                    'ad_list'=>function($query) { $query->where(['item_category'=>1,'item_type'=>88])->orderby('updated_at','desc'); },
                    'pivot_org_list'=>function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1])->orderby('updated_at','desc'); }
                ]);
            }
        }
        else
        {
            $error["text"] = '该用户不存在！';
            return view(env('TEMPLATE_K_WWW').'frontend.errors.404')->with('error',$error);
        }


        $user->timestamps = false;
        $user->increment('visit_num');

        $is_follow = 0;

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;

            $item_query = K_Item::with([
                    'owner',
    //                'forward_item'=>function($query) { $query->with('user'); },
                    'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
                ])
                ->where('item_status',1)
                ->where('active',1)
                ->where('owner_id',$user_id);

            if($type == 'root')
            {
                $item_query->whereIn('item_type',[1,11]);
                $record["page_module"] = 1; // page_module=0 default index
            }
            else if($type == 'introduction')
            {
                $record["page_module"] = 2; // page_module=2 introduction
            }
            else if($type == 'article')
            {
                $item_query->whereIn('item_type',[1]);
                $record["page_module"] = 9; // page_module=0 article
            }
            else if($type == 'activity')
            {
                $item_query->whereIn('item_type',[11]);
                $record["page_module"] = 11; // page_module=0 activity
            }
            else
            {
                $record["page_module"] = 1; // page_module=0 default index
            }

            $item_list = $item_query->orderBy('published_at','desc')->paginate(20);

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
            $item_query = K_Item::with(['owner'])
                ->where('item_status',1)
                ->where('active',1)
                ->where('owner_id',$user_id);

            if($type == 'root')
            {
                $item_query->whereIn('item_type',[1,11]);
                $record["page_module"] = 1; // page_module=0 default index
            }
            else if($type == 'introduction')
            {
                $record["page_module"] = 2; // page_module=2 introduction
            }
            else if($type == 'article')
            {
                $item_query->whereIn('item_type',[1]);
                $record["page_module"] = 9; // page_module=0 article
            }
            else if($type == 'activity')
            {
                $item_query->whereIn('item_type',[11]);
                $record["page_module"] = 11; // page_module=0 activity
            }
            else
            {
                $record["page_module"] = 1; // page_module=0 default index
            }

            $item_list = $item_query->orderBy('published_at','desc')->paginate(20);
        }

        foreach ($item_list as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }
//        dd($item->toArray());


        if($type == 'root')
        {
            $record["page_module"] = 1;  // page_module=0 default index
        }
        else if($type == 'introduction')
        {
            $record["page_module"] = 2;  // page_module=2 introduction
        }
        else if($type == 'article')
        {
            $record["page_module"] = 9;  // page_module=0 article
        }
        else if($type == 'activity')
        {
            $record["page_module"] = 11;  // page_module=0 activity
        }
        else
        {
            $record["page_module"] = 1;  // page_module=0 default index
        }

        // 插入记录表
        $record["record_category"] = 1; // record_category=1 browse/share
        $record["record_type"] = 1; // record_type=1 browse
        $record["page_type"] = 2; // page_type=2 user
        $record["page_num"] = $item_list->toArray()["current_page"];
        $record["object_id"] = $user_id;
        $record["from"] = request('from',NULL);
        $this->record($record);


        $page["type"] = 2;
        $page["module"] = 1;
        $page["num"] = $item_list->toArray()["current_page"];
        $page["item_id"] = 0;
        $page["user_id"] = $id;


        if($type == 'root')
        {
            $return['menu_active_for_item_all'] = 'active';
            $page["module"] = 1;
        }
        else if($type == 'introduction')
        {
            $return['menu_active_for_introduction'] = 'active';
            $page["module"] = 2;
        }
        else if($type == 'article')
        {
            $return['menu_active_for_item_article'] = 'active';
            $page["module"] = 9;
        }
        else if($type == 'activity')
        {
            $return['menu_active_for_item_activity'] = 'active';
            $page["module"] = 11;
        }
        else if($type == 'org')
        {
            $return['menu_active_for_org'] = 'active';
            $page["module"] = 1;
        }

        $return['data'] = $user;
        $return['item_list'] = $item_list;
        $return['is_follow'] = $is_follow;
        $return['page'] = $page;

        view()->share('user_root_active','active');
        $view_blade = env('TEMPLATE_K_WWW').'entrance.user';
        return view($view_blade)->with($return);
    }

    // 【用户】【原创】
    public function view_user_original($post_data,$id=0)
    {
//        $user_encode = $id;
//        $user_decode = decode($user_encode);
//        if(!$user_decode) return view('frontend.404');

        $user_id = $id;

        $user = User::with([
            'items'=>function($query) { $query->orderBy('id','desc'); }
        ])->withCount('items')->find($user_id);

        if(!$user) return view('frontend.errors.404');

        $user->timestamps = false;
        $user->increment('visit_num');

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $items = K_Item::with([
                'user',
                'forward_item'=>function($query) { $query->with('user'); },
                'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
            ])->where('user_id',$user_id)
                ->where('category','<>',99)
                ->where('is_shared','>=',99)
                ->orderBy('id','desc')->get();

            if($user_id != $me_id)
            {
                $relation = Pivot_User_Relation::where(['mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
                view()->share(['relation'=>$relation]);
            }
        }
        else
        {
            $items = K_Item::with([
                'user',
                'forward_item'=>function($query) { $query->with('user'); }
            ])->where('user_id',$user_id)
                ->where('category','<>',99)
                ->where('is_shared','>=',99)
                ->orderBy('id','desc')->get();
        }

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }
//        dd($lines->toArray());

        view()->share('user_original_active','active');
        return view('entrance.user-original')->with(['data'=>$user,'items'=>$items,'user_original_active'=>'active']);
    }

    // 【K】【Ta关注的人】
    public function view_user_follow($post_data,$id=0)
    {
        $Ta = User::withCount('items')->find($id);
        if(!$Ta) return view('frontend.errors.404');

        $pivot_users = Pivot_User_Relation::with(['relation_user'])->where(['mine_user_id'=>$id])->whereIn('relation_type',[21,41])
            ->orderBy('id','desc')->get();

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            if($id != $me_id)
            {
                $relation = Pivot_User_Relation::where(['mine_user_id'=>$me_id,'relation_user_id'=>$id])->first();
                view()->share(['relation'=>$relation]);
            }

            $me_users = Pivot_User_Relation::where(['mine_user_id'=>$me_id])->get();

            foreach ($pivot_users as $num => $user)
            {
                $relationship = $me_users->where('relation_user_id', $user->relation_user_id);
                if(count($relationship) > 0)
                {
                    $user->relation_with_me = $relationship->first()->relation_type;
//                    if($user->relation_user_id == $me_id) unset($pivot_users[$num]);
                }
                else $user->relation_with_me = 0;
            }
        }
        else
        {
            foreach ($pivot_users as $user)
            {
                $user->relation_with_me = 0;
            }
        }

        return view('entrance.user-follow')->with(['data'=>$Ta,'users'=>$pivot_users,'user_relation_follow_active'=>'active']);
    }
    // 【K】【关注Ta的人】
    public function view_user_fans($post_data,$id=0)
    {
        $Ta = User::withCount('items')->find($id);
        if(!$Ta) return view('frontend.errors.404');

        $pivot_users = Pivot_User_Relation::with(['relation_user'])->where(['mine_user_id'=>$id])->whereIn('relation_type',[21,71])
            ->orderBy('id','desc')->get();

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            if($id != $me_id)
            {
                $relation = Pivot_User_Relation::where(['mine_user_id'=>$me_id,'relation_user_id'=>$id])->first();
                view()->share(['relation'=>$relation]);
            }

            $me_users = Pivot_User_Relation::where(['mine_user_id'=>$me_id])->get();

            foreach ($pivot_users as $num => $user)
            {
                $relationship = $me_users->where('relation_user_id', $user->relation_user_id);
                if(count($relationship) > 0)
                {
                    $user->relation_with_me = $relationship->first()->relation_type;
//                    if($user->relation_user_id == $me_id) unset($pivot_users[$num]);
                }
                else $user->relation_with_me = 0;
            }
        }
        else
        {
            foreach ($pivot_users as $user)
            {
                $user->relation_with_me = 0;
            }
        }

        return view('entrance.user-fans')->with(['data'=>$Ta,'users'=>$pivot_users,'user_relation_fans_active'=>'active']);
    }

    // 【K】【机构介绍页】
    public function view_user_introduction($post_data,$id=0)
    {
//        $user_encode = $id;
//        $user_decode = decode($user_encode);
//        if(!$user_decode) return view('frontend.404');

        $user_id = $id;

        $type = !empty($post_data['type']) ? $post_data['type'] : 'root';

        $user = K_User::with([
            'introduction',
            'items'=>function($query) { $query->with('owner')->orderBy('updated_at','desc'); },
            'ad',
            'ad_list'=>function($query) { $query->where(['item_category'=>1,'item_type'=>88])->orderby('updated_at','desc'); },
            'pivot_sponsor_list'=>function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1])->orderby('updated_at','desc'); },
            'pivot_org_list'=>function($query) { $query->where(['relation_active'=>1,'relation_category'=>88,'relation_type'=>1])->orderby('updated_at','desc'); },
        ])->withCount([
            'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
            'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
        ])->find($user_id);
//        dd($user->toArray());

        if(!$user) return view(env('TEMPLATE_K_WWW').'frontend.errors.404');

        $user->timestamps = false;
        $user->increment('visit_num');

        $is_follow = 0;

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;

            $item_query = K_Item::with([
                'owner',
                //                'forward_item'=>function($query) { $query->with('user'); },
                'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
            ])
                ->where('owner_id',$user_id);

            if($type == 'root') $item_query->whereIn('item_type',[1,11]);
            else if($type == 'article') $item_query->whereIn('item_type',[1]);
            else if($type == 'activity') $item_query->whereIn('item_type',[11]);

            $items = $item_query->orderBy('updated_at','desc')->paginate(20);

            if($user_id != $me_id)
            {
                $relation = Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
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
            $item_query = K_Item::with(['owner'])
                ->where('owner_id',$user_id);

            if($type == 'root') $item_query->whereIn('item_type',[1,11]);
            else if($type == 'article') $item_query->whereIn('item_type',[1]);
            else if($type == 'activity') $item_query->whereIn('item_type',[11]);

            $items = $item_query->orderBy('updated_at','desc')->paginate(20);
        }

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }
//        dd($item->toArray());




        // 插入记录表
        $record["record_category"] = 1; // record_category=1 browse/share
        $record["record_type"] = 1; // record_type=1 browse
        $record["page_type"] = 2; // page_type=2 user
        $record["page_num"] = 1;
        $record["object_id"] = $user_id;
        $record["from"] = request('from',NULL);
        $this->record($record);


        $sidebar_active = 'sidebar_menu_introduction_active';

        view()->share('user_root_active','active');
        return view(env('TEMPLATE_K_WWW').'entrance.user-introduction')
            ->with([
                'data'=>$user,
                'items'=>$items,
                'is_follow'=>$is_follow,
                $sidebar_active => 'active'
            ]);
    }




    // 【K】【组织列表】
    public function view_organization_list($post_data)
    {

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $record["creator_id"] = $me_id;

            $user_list = K_User::select('*')
                ->with([
                    'ad',
                    'fans_list'=>function($query) use($me_id) { $query->where('mine_user_id',$me_id); },
                ])
//                ->withCount([
//                    'fans_list as fans_count' => function($query) { $query->where([]); },
//                    'items as item_count' => function($query) { $query->where(['item_category'=>1]); },
//                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
//                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
//                ])
                ->where('user_type',11)
                ->where('user_status',1)
                ->where('active',1)
                ->paginate(50);
        }
        else
        {

            $user_list = K_User::select('*')
                ->with([
                    'ad',
                ])
//                ->withCount([
//                    'items as item_count' => function($query) { $query->where(['item_category'=>1]); },
//                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
//                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
//                ])
                ->where('user_type',11)
                ->where('user_status',1)
                ->where('active',1)
                ->paginate(50);
        }

//        foreach($user_list as $u)
//        {
//            if(count($u->fans_list->whereIn('relation_type', [21,41])) > 0) echo 1;
//        }
//        dd($user_list->toArray());


        // 插入记录表
        $record["record_category"] = 1; // record_category=1 browse/share
        $record["record_type"] = 1; // record_type=1 browse
        $record["page_type"] = 1; // page_type=1 platform
        $record["page_module"] = 33; // page_module=33 organization
        $record["page_num"] = $user_list->toArray()["current_page"];
        $record["from"] = request('from',NULL);
        $this->record($record);

        $page["type"] = 1;
        $page["module"] = 33;
        $page["num"] = 0;
        $page["item_id"] = 0;
        $page["user_id"] = 0;

        return view(env('TEMPLATE_K_WWW').'entrance.organization-list')
            ->with([
                'user_list'=>$user_list,
                'sidebar_menu_organization_list_active' => 'active',
                'page' => $page
            ]);
    }



    // 【机构首页】
    public function view_org($post_data,$id=0)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
        }
        else $me_id = 0;

        $org = OrgOrganization::with([])->find($id);
        if($org)
        {
            $org->timestamps = false;
            $org->increment('visit_num');

            $org->custom_decode = json_decode($org->custom);
        }
        else return view('frontend.errors.404');

        $return['data'] = $org;
        $return['org_root_active'] = "active";

        $article_items = K_Item::with(['org'])
            ->where(['org_id'=>$id,'category'=>'1'])->where('is_shared','>=',99)
            ->orderby('id','desc')->limit(8)->get();
        $return['article_items'] = $article_items;

        $activity_items = K_Item::with(['org'])
            ->where(['org_id'=>$id,'category'=>'11'])->where('is_shared','>=',99)
            ->orderby('id','desc')->limit(8)->get();
        $return['activity_items'] = $activity_items;

        $sponsor_items = K_Item::with(['org'])
            ->where(['org_id'=>$id,'category'=>'88'])->where('is_shared','>=',99)
            ->orderby('id','desc')->limit(8)->get();
        $return['sponsor_items'] = $sponsor_items;

        return view('entrance.org')->with($return);
    }

    // 【机构内容列表】
    public function view_org_item_list($post_data,$id=0)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
        }
        else $me_id = 0;

        $org = OrgOrganization::with([])->find($id);
        if($org)
        {
            $org->timestamps = false;
            $org->increment('visit_num');

            $org->custom_decode = json_decode($org->custom);
            $return['data'] = $org;
        }
        else return view('frontend.errors.404');

        $items = K_Item::with(['org'])->where('org_id',$id)->where('is_shared','>=',99);

        $category = isset($post_data["category"]) ? $post_data["category"] : '';
        if($category == 'article')
        {
            $items = $items->where('category','1');
            $return['org_article_active'] = 'active';
        }
        else if($category == 'activity')
        {
            $items = $items->where('category','11');
            $return['org_activity_active'] = 'active';
        }
        else if($category == 'sponsor')
        {
            $items = $items->where('category','88');
            $return['org_sponsor_active'] = 'active';
        }

        $items = $items->orderby('id','desc')->paginate(10);
        $return['items'] = $items;

        return view('entrance.org-item-list')->with($return);
    }



    // 【K】【添加关注】
    public function user_relation_add($post_data)
    {
        $messages = [
            'user_id.required' => '参数有误',
            'user_id.numeric' => '参数有误',
            'user_id.exists' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'user_id' => 'required|numeric|exists:user,id'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $user_id = $post_data['user_id'];
            $user = K_User::find($user_id);

            DB::beginTransaction();
            try
            {
                $me_relation = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
                if($me_relation)
                {
                    if($me_relation->relation_type == 71) $me_relation->relation_type = 21;
                    else $me_relation->relation_type = 41;
                    $me_relation->save();
                }
                else
                {
                    $me_relation = new K_Pivot_User_Relation;
                    $me_relation->relation_category = 1;
                    $me_relation->relation_type = 41;
                    $me_relation->mine_user_id = $me_id;
                    $me_relation->relation_user_id = $user_id;
                    $me_relation->save();
                }
                $me->timestamps = false;
                $me->increment('follow_num');

                $it_relation = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$user_id,'relation_user_id'=>$me_id])->first();
                if($it_relation)
                {
                    if($it_relation->relation_type == 41) $it_relation->relation_type = 21;
                    else $it_relation->relation_type = 71;
                    $it_relation->save();
                }
                else
                {
                    $it_relation = new K_Pivot_User_Relation;
                    $it_relation->relation_category = 1;
                    $it_relation->relation_type = 71;
                    $it_relation->mine_user_id = $user_id;
                    $it_relation->relation_user_id = $me_id;
                    $it_relation->save();
                }
                $user->timestamps = false;
                $user->increment('fans_num');

                $notification_insert['notification_category'] = 9;
                $notification_insert['notification_type'] = 1;
                $notification_insert['owner_id'] = $user_id;
                $notification_insert['user_id'] = $user_id;
                $notification_insert['belong_id'] = $user_id;
                $notification_insert['source_id'] = $me_id;

                $notification = new K_Notification;
                $bool = $notification->fill($notification_insert)->save();
                if(!$bool) throw new Exception("insert--notification--fail");

                DB::commit();
                return response_success(['relation_type'=>$me_relation->relation_type]);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '操作失败，请重试！';
                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");
    }
    // 【K】【取消关注】
    public function user_relation_remove($post_data)
    {
        $messages = [
            'user_id.required' => '参数有误',
            'user_id.numeric' => '参数有误',
            'user_id.exists' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'user_id' => 'required|numeric|exists:user,id'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $user_id = $post_data['user_id'];
            $user = K_User::find($user_id);

            DB::beginTransaction();
            try
            {
                $me_relation = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
                if($me_relation)
                {
                    if($me_relation->relation_type == 21)
                    {
                        $me_relation->relation_type = 71;
                        $me_relation->save();
                    }
                    else if($me_relation->relation_type == 41)
                    {
//                        $me_relation->relation_type = 91;
//                        $me_relation->save();

                        $bool = $me_relation->delete();
                        if(!$bool) throw new Exception("delete--pivot_relation--fail");
                    }
                    else
                    {
//                        $me_relation->relation_type = 91;
//                        $me_relation->save();

                        $bool = $me_relation->delete();
                        if(!$bool) throw new Exception("delete--pivot_relation--fail");
                    }
                }
                $me->timestamps = false;
                $me->decrement('follow_num');

                $it_relation = K_Pivot_User_Relation::where(['relation_category'=>1,'mine_user_id'=>$user_id,'relation_user_id'=>$me_id])->first();
                if($it_relation)
                {
                    if($it_relation->relation_type == 21)
                    {
                        $it_relation->relation_type = 41;
                        $it_relation->save();
                    }
                    else if($it_relation->relation_type == 71)
                    {
//                        $it_relation->relation_type = 92;
//                        $it_relation->save();

                        $bool = $it_relation->delete();
                        if(!$bool) throw new Exception("delete--pivot_relation--fail");
                    }
                    else
                    {
//                        $it_relation->relation_type = 92;
//                        $it_relation->save();

                        $bool = $it_relation->delete();
                        if(!$bool) throw new Exception("delete--pivot_relation--fail");
                    }
                }
                $user->timestamps = false;
                $user->decrement('fans_num');

                DB::commit();
                return response_success(['relation_type'=>$me_relation->relation_type]);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '操作失败，请重试！';
                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");
    }



    // 【K】【我的】【关注】
    public function view_my_follow($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $user_list = K_Pivot_User_Relation::with([
                    'relation_user'=>function($query) {
//                        $query->withCount([
//                            'fans_list as fans_count' => function($query) { $query->where(['relation_type'=>41]); },
//                            'items as item_count' => function($query) { $query->where(['item_category'=>1]); },
//                            'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
//                            'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
//                        ]);
                    },
                ])
                ->where(['mine_user_id'=>$me_id])
                ->whereIn('relation_type',[21,41])
                ->orderby('id','desc')
                ->paginate(20);

            foreach ($user_list as $user)
            {
                $user->relation_with_me = $user->relation_type;
            }

        }
        else return response_error([],"请先登录！");

        return view(env('TEMPLATE_K_WWW').'entrance.my-follow')
            ->with([
                'user_list'=>$user_list,
                'sidebar_menu_my_follow_active'=>'active'
            ]);
    }
    // 【K】【我的】【粉丝】
    public function view_my_fans($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $users = Pivot_User_Relation::with(['relation_user'])->where(['mine_user_id'=>$me_id])->whereIn('relation_type',[21,71])->get();
            foreach ($users as $user)
            {
                $user->relation_with_me = $user->relation_type;
            }
        }
        else return response_error([],"请先登录！");

        return view('entrance.relation-fans')->with(['users'=>$users,'root_relation_fans_active'=>'active']);
    }

    // 【K】【我的】【收藏】
    public function view_my_favor($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            // Method 1
//            $query = K_User::with([
//                'pivot_item'=>function($query) use($me_id) { $query->with([
//                    'user',
//                    'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
//                ])->wherePivot('type',1)->orderby('pivot_user_item.id','desc'); }
//            ])->find($me_id);
//            $items = $query->pivot_item;

            $item_list = K_Pivot_User_Item::with([
                'item'=>function($query) use($me_id) {
                    $query->with([
                        'owner',
                        'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
                    ]);
                }
            ])
                ->where('user_id',$me_id)
                ->orderby('id','desc')
                ->paginate(20);
        }
        else return response_error([],"请先登录！");
//        dd($item_list->toArray());

        foreach ($item_list as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view(env('TEMPLATE_K_WWW').'entrance.my-favor')
            ->with([
                'item_list'=>$item_list,
                'sidebar_menu_my_favor_active'=>'active'
            ]);
    }




    // 【K】【我的消息】
    public function view_my_notification($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
        }
        else $me_id = 0;

        $count = K_Notification::where(['owner_id'=>$me_id,'is_read'=>0])->whereIn('notification_category',[9,11])->count();
        if($count)
        {
            $notification_list = K_Notification::with([
                    'source',
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
                    'source',
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
        return view(env('TEMPLATE_K_WWW').'entrance.my-notification')
            ->with([
                'notification_list'=>$notification_list,
                'sidebar_menu_my_notification_active'=>'active'
            ]);
    }



    // 【我的原创】
    public function view_home_mine_original($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $items = K_Item::select("*")->with([
                'user',
                'forward_item'=>function($query) { $query->with('user'); },
                'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
            ])->where(['user_id'=>$me_id])->where('category','<>',99)->orderBy("updated_at", "desc")->paginate(20);
//            ])->where(['user_id'=>$me_id,'item_id'=>0])->where('category','<>',99)->orderBy("updated_at", "desc")->paginate(20);
        }
        else $items = [];

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-original')->with(['items'=>$items,'root_mine_active'=>'active']);
    }




    // 【待办事】
    public function view_home_mine_todolist($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;

            // Method 1
            $query = User::with([
                'pivot_item'=>function($query) use($user_id) { $query->with([
                    'user',
                    'forward_item'=>function($query) { $query->with('user'); },
                    'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
                ])->wherePivot('type',31)->orderby('pivot_user_item.id','desc'); }
            ])->find($user_id);
            $items = $query->pivot_item;

//            // Method 2
//            $query = Pivot_User_Item::with([
//                    'item'=>function($query) { $query->with(['user']); }
//                ])->where(['type'=>11,'user_id'=>$user_id])->orderby('id','desc')->get();
//            dd($query->toArray());
        }
        else $items = [];

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-todolist')->with(['items'=>$items,'root_todolist_active'=>'active']);
    }

    // 【日程】
    public function view_home_mine_schedule($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;

            // Method 1
//            $query = User::with([
//                'pivot_item'=>function($query) use($user_id) { $query->with([
//                    'user',
//                    'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
//                ])->wherePivot('type',12)->orderby('pivot_user_item.id','desc'); }
//            ])->find($user_id);
//            $items = $query->pivot_item;

            $items = [];
        }
        else $items = [];

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-schedule')->with(['items'=>$items,'root_schedule_active'=>'active']);
    }

    // 【收藏内容】
    public function view_home_mine_collection($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;

            // Method 1
            $query = User::with([
                'pivot_item'=>function($query) use($user_id) { $query->with([
                    'user',
                    'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
                ])->wherePivot('type',21)->orderby('pivot_user_item.id','desc'); }
            ])->find($user_id);
            $items = $query->pivot_item;
        }
        else $items = [];

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-collection')->with(['items'=>$items,'root_collection_active'=>'active']);
    }

    // 【点赞内容】
    public function view_home_mine_favor($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;

            // Method 1
            $query = User::with([
                'pivot_item'=>function($query) use($user_id) { $query->with([
                    'user',
                    'forward_item'=>function($query) { $query->with('user'); },
                    'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
                ])->wherePivot('type',11)->orderby('pivot_user_item.id','desc'); }
            ])->find($user_id);
            $items = $query->pivot_item;
        }
        else $items = [];

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-favor')->with(['items'=>$items,'root_favor_active'=>'active']);
    }




    // 【发现】
    public function view_home_mine_discovery($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
        }
        else $user_id = 0;

        $items = K_Item::with([
            'user',
            'forward_item'=>function($query) { $query->with('user'); },
            'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
        ])->where('is_shared','>=',99)->orderBy('id','desc')->get();

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-discovery')->with(['items'=>$items,'root_discovery_active'=>'active']);
    }

    // 【关注】
    public function view_home_mine_follow($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
        }
        else $user_id = 0;
//
//        $items = K_Item::with([
//            'user',
//            'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
//        ])->where('is_shared','>=',99)->orderBy('id','desc')->get();

        $user = User::with([
            'relation_items'=>function($query) use($user_id) {$query->with([
                'user',
                'forward_item'=>function($query) { $query->with('user'); },
                'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
            ])->where('pivot_user_relation.relation_type','<=', 50)->where('root_items.is_shared','>=', 41); }
        ])->find($user_id);

        $items = $user->relation_items;
        $items = $items->sortByDesc('id');
//        dd($items->toArray());

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-follow')->with(['items'=>$items,'root_follow_active'=>'active']);
    }

    // 【好友圈】
    public function view_home_mine_circle($post_data)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
        }
        else $user_id = 0;
//
//        $items = K_Item::with([
//            'user',
//            'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
//        ])->where('is_shared','>=',99)->orderBy('id','desc')->get();

        $user = User::with([
            'relation_items'=>function($query) use($user_id) { $query->with([
                'user',
                'forward_item'=>function($query) { $query->with('user'); },
                'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
            ])->where('pivot_user_relation.relation_type',21)->where('root_items.is_shared','>=', 41); }
        ])->find($user_id);

        $items = $user->relation_items;
        $items = $items->sortByDesc('id');
//        dd($items->toArray());

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }

        return view('entrance.root-circle')->with(['items'=>$items,'root_circle_active'=>'active']);
    }




    // 内容模板
    public function view_item_html($id)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $item = K_Item::with([
                'user',
                'contents'=>function($query) { $query->where(['active'=>1,'p_id'=>0])->orderBy('id','asc'); },
                'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
            ])->find($id);
        }
        else
        {
            $item = K_Item::with([
                'user',
                'contents'=>function($query) { $query->where(['active'=>1,'p_id'=>0])->orderBy('id','asc'); }
            ])->find($id);
        }
        $items[0] = $item;
        return view('frontend.'.env('TEMPLATE').'.component.item-list-1')->with(['items'=>$items])->__toString();
    }













    // 【获取日程】
    public function ajax_get_schedule($post_data)
    {
        if(Auth::check())
        {
            $messages = [
                'year.required' => '参数有误',
                'month.required' => '参数有误'
            ];
            $v = Validator::make($post_data, [
                'year' => 'required',
                'month' => 'required'
            ], $messages);
            if ($v->fails())
            {
                $errors = $v->errors();
                return response_error([],$errors->first());
            }

            $user = Auth::user();
            $user_id = $user->id;

            $year = $post_data['year'];
            $month = $post_data['month'];
            $monthStr = $year."-".$month;
            $start = strtotime($monthStr); // 指定月份月初时间戳
            $end = mktime(23, 59, 59, date('m', strtotime($monthStr))+1, 00); // 指定月份月末时间戳

            // Method 1
            $query = User::with([
                'pivot_item'=>function($query) use($user_id,$start,$end) { $query->with([
                    'user',
                    'pivot_item_relation'=>function($query) use($user_id) { $query->where('user_id',$user_id); }
                ])->wherePivot('type',32)->where(function ($query) use($start,$end) {
                    $query
                        ->where(function ($query) use($start,$end) {$query->where('start_time', '>=', $start)->where('start_time', '<=', $end);})
                        ->orWhere(function ($query) use($start,$end) {$query->where('end_time', '>=', $start)->where('end_time', '<=', $end);})
                        ->orWhere(function ($query) use($start,$end) {$query->where('start_time', '<=', $start)->where('end_time', '>=', $end);});
                })->orderby('pivot_user_item.id','desc'); }
            ])->find($user_id);

//            $query->where(function ($query) use($start_time,$end_time) {
//                $query
//                    ->where(function ($query) use($start_time,$end_time) {
//                        $query->where('start_time', '>=', $start_time)->where('start_time', '<=', $end_time);})
//                    ->orWhere(function ($query) use($start_time,$end_time) {
//                        $query->where('end_time', '>=', $start_time)->where('end_time', '<=', $end_time);})
//                    ->orWhere(function ($query) use($start_time,$end_time) {
//                        $query->where('start_time', '<=', $start_time)->where('end_time', '>=', $end_time);});
//            });

            $items = $query->pivot_item;
            foreach ($items as $item)
            {
                $item->calendar_days = $this->handleScheduleDays($item->start_time, $item->end_time);
            }

            $html =  view('frontend.'.env('TEMPLATE').'.component.item-list-1')->with(['items'=>$items])->__toString();
            return response_success(['html'=>$html]);

        }
        else return response_error([],'请先登录！');
    }




    // 返回【添加】视图
    public function view_home_mine_item_create()
    {
        $category = request("category",'');
        $view_blade = 'entrance.root-edit';
        return view($view_blade)->with(['operate'=>'create', 'encode_id'=>encode(0), 'root_edit_active'=>'active']);
    }
    // 返回【编辑】视图
    public function view_home_mine_item_edit()
    {
        $id = request("id",0);
        if(!$id && intval($id) !== 0) return view('home.404');

        if($id == 0)
        {
            return view('entrance.root-create')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = K_Item::find($id);
            if($data)
            {
                unset($data->id);
                return view('entrance.root-edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data]);
            }
            else return response("该内容不存在！", 404);
        }
    }
    // 【存储】
    public function home_mine_item_save($post_data)
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

        $user = Auth::user();

        $id = $post_data["id"];
        $operate = $post_data["operate"];
        if(intval($id) !== 0 && !$id) return response_error();

        DB::beginTransaction();
        try
        {
            if($operate == 'create') // $id==0，添加一个新的课程
            {
                $mine = new K_Item;
                $post_data["user_id"] = $user->id;
            }
            elseif('edit') // 编辑
            {
                $mine = K_Item::find($id);
                if(!$mine) return response_error([],"该内容不存在，刷新页面重试");
                if($mine->user_id != $user->id) return response_error([],"你没有操作权限");
            }
            else throw new Exception("operate--error");

            if(!empty($post_data['custom']))
            {
                $post_data['custom'] = json_encode($post_data['custom']);
            }

            if($operate == 'create' && $post_data['category'] == 1 && $post_data['time_type'] == 1)
            {
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
                unset($post_data['start_time']);
                unset($post_data['end_time']);
            }

            $bool = $mine->fill($post_data)->save();
            if($bool)
            {
                $encode_id = encode($mine->id);

                $is_working = isset($post_data["is_working"]) ? $post_data["is_working"] : 0;
                if($is_working == 1)
                {
                    $time = time();
                    $user->pivot_item()->attach($mine->id,['type'=>11,'created_at'=>$time,'updated_at'=>$time]);
                }

                if($operate == 'create' && $post_data['category'] == 1 && $post_data['time_type'] == 1)
                {
                    $time = time();
                    $user->pivot_item()->attach($mine->id,['type'=>12,'created_at'=>$time,'updated_at'=>$time]);
                }

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
            else throw new Exception("insert--people--fail");


            DB::commit();
            return response_success(['id'=>$mine->id]);
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


    // 返回【目录类型】视图
    public function view_home_mine_item_edit_menutype($post_data)
    {
        $id = $post_data['id'];
        if(!$id) return view('home.404')->with(['error'=>'参数有误']);
        // abort(404);

        $item = K_Item::with([
            'contents'=>function($query) { $query->orderBy('rank','asc'); }
        ])->find($id);
        if($item)
        {
            $item->encode_id = encode($item->id);

            $item->contents_recursion = $this->get_recursion($item->contents,0);

            return view('entrance.root-edit-for-menutype')->with(['data'=>$item]);
        }
        else return view('home.404')->with(['error'=>'该内容不存在']);

    }
    // 返回【时间线类型】视图
    public function view_home_mine_item_edit_timeline($post_data)
    {
        $id = $post_data['id'];
        if(!$id) return view('home.404')->with(['error'=>'参数有误']);
        // abort(404);

        $item = K_Item::with([
            'contents'=>function($query) {
                $query->orderByRaw(DB::raw('cast(replace(trim(time_point)," ","") as SIGNED) asc'));
                $query->orderByRaw(DB::raw('cast(replace(trim(time_point)," ","") as DECIMAL) asc'));
                $query->orderByRaw(DB::raw('replace(trim(time_point)," ","") asc'));
                $query->orderBy('time_point','asc');
            }
        ])->find($id);
        if($item)
        {
            $item->encode_id = encode($item->id);
//            unset($item->id);

            return view('entrance.root-edit-for-timeline')->with(['data'=>$item]);
        }
        else return view('home.404')->with(['error'=>'该内容不存在']);

    }


    // 【目录类型】【存储】
    public function home_mine_item_menutype_save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'title.required' => '请输入标题',
            'p_id.required' => '请选择目录',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'title' => 'required',
            'p_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $user = Auth::user();

//        $post_data["category"] = 11;
        $item_encode = $post_data["item_id"];
        $item_decode = decode($item_encode);
        if(!$item_decode) return response_error();
        $item = K_Item::find($item_decode);
        if($item)
        {
            if($item->user_id == $user->id)
            {

                $content_encode = $post_data["id"];
                $content_decode = decode($content_encode);
                if(intval($content_decode) !== 0 && !$content_decode) return response_error();

                DB::beginTransaction();
                try
                {
                    $post_data["item_id"] = $item_decode;
                    $operate = $post_data["operate"];
                    if($operate == 'create') // $id==0，添加一个新的内容
                    {
                        $content = new K_Item;
                        $post_data["user_id"] = $user->id;
                    }
                    elseif('edit') // 编辑
                    {
                        if($content_decode == $post_data["p_id"]) return response_error([],"不能选择自己为父节点");

                        $content = K_Item::find($content_decode);
                        if(!$content) return response_error([],"该内容不存在，刷新页面重试");
                        if($content->user_id != $user->id) return response_error([],"你没有操作权限");
//                        if($content->type == 1) unset($post_data["type"]);

                        if($post_data["p_id"] != 0)
                        {
                            $is_child = true;
                            $p_id = $post_data["p_id"];
                            while($is_child)
                            {
                                $p = K_Item::find($p_id);
                                if(!$p) return response_error([],"参数有误，刷新页面重试");
                                if($p->p_id == 0) $is_child = false;
                                if($p->p_id == $content_decode)
                                {
                                    $content_children = K_Item::where('p_id',$content_decode)->get();
                                    $children_count = count($content_children);
                                    if($children_count)
                                    {
                                        $num = K_Item::where('p_id',$content_decode)->update(['p_id'=>$content->p_id]);
                                        if($num != $children_count)  throw new Exception("update--children--fail");
                                    }
                                }
                                $p_id = $p->p_id;
                            }
                        }

                        if($content_encode == $item_encode)
                        {
                            unset($post_data['item_id']);
                            unset($post_data['rank']);
                        }

                    }
                    else throw new Exception("operate--error");


                    if($post_data["p_id"] != 0)
                    {
                        $parent = K_Item::find($post_data["p_id"]);
                        if(!$parent) return response_error([],"父节点不存在，刷新页面重试");
                    }

                    $bool = $content->fill($post_data)->save();
                    if($bool)
                    {
                        $encode_id = encode($content->id);
                    }
                    else throw new Exception("insert--content--fail");


                    DB::commit();
                    return response_success(['id'=>$encode_id]);
                }
                catch (Exception $e)
                {
                    DB::rollback();
                    $msg = '操作失败，请重试！';
                    $msg = $e->getMessage();
//                    exit($e->getMessage());
                    return response_fail([], $msg);
                }

            }
            else response_error([],"该内容不是您的，您不能操作！");

        }
        else return response_error([],"该内容不存在");
    }
    // 【时间点】【存储】
    public function home_mine_item_timeline_save($post_data)
    {
        $messages = [
            'id.required' => '参数有误',
            'title.required' => '请输入标题',
            'time_point.required' => '请输入时间点',
        ];
        $v = Validator::make($post_data, [
            'id' => 'required',
            'title' => 'required',
            'time_point' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $messages = $v->errors();
            return response_error([],$messages->first());
        }

        $user = Auth::user();

//        $post_data["category"] = 18;
        $item_encode = $post_data["item_id"];
        $item_decode = decode($item_encode);
        if(!$item_decode) return response_error();
        $item = K_Item::find($item_decode);
        if($item)
        {
            if($item->user_id == $user->id)
            {

                $content_encode = $post_data["id"];
                $content_decode = decode($content_encode);
                if(intval($content_decode) !== 0 && !$content_decode) return response_error();

                DB::beginTransaction();
                try
                {
                    $post_data["item_id"] = $item_decode;
                    $operate = $post_data["operate"];
                    if($operate == 'create') // $id==0，添加一个新的内容
                    {
                        $content = new K_Item;
                        $post_data["user_id"] = $user->id;
                    }
                    elseif('edit') // 编辑
                    {
                        $content = K_Item::find($content_decode);
                        if(!$content) return response_error([],"该内容不存在，刷新页面重试");
                        if($content->user_id != $user->id) return response_error([],"你没有操作权限");
//                        if($content->type == 1) unset($post_data["type"]);

                        if($content_encode == $item_encode)
                        {
                            unset($post_data['item_id']);
                            unset($post_data['time_point']);
                        }
                    }
                    else throw new Exception("operate--error");

                    $bool = $content->fill($post_data)->save();
                    if($bool)
                    {
                        $encode_id = encode($content->id);
                    }
                    else throw new Exception("insert--content--fail");


                    DB::commit();
                    return response_success(['id'=>$encode_id]);
                }
                catch (Exception $e)
                {
                    DB::rollback();
                    $msg = '操作失败，请重试！';
                    $msg = $e->getMessage();
//                    exit($e->getMessage());
                    return response_fail([], $msg);
                }

            }
            else response_error([],"该内容不是您的，您不能操作！");

        }
        else return response_error([],"该内容不存在");
    }




    // 【删除】
    public function item_delete($post_data)
    {
        $me = Auth::user();
        $id = $post_data["id"];
        if(intval($id) !== 0 && !$id) return response_error([],"该内容不存在，刷新页面试试");

        $mine = K_Item::find($id);
        if($mine->user_id != $me->id) return response_error([],"你没有操作权限");

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




    // 【K】【ITEM】【添加】【点赞&收藏 | +待办事 | +日程】
    public function item_add_this($post_data,$type=0)
    {
        if(Auth::check())
        {
            $messages = [
                'type.required' => '参数有误',
                'item_id.required' => '参数有误'
            ];
            $v = Validator::make($post_data, [
                'type' => 'required',
                'item_id' => 'required'
            ], $messages);
            if ($v->fails())
            {
                $errors = $v->errors();
                return response_error([],$errors->first());
            }

            $item_id = $post_data['item_id'];
            $item = K_Item::find($item_id);
            if($item)
            {
                $me = Auth::user();
                $pivot = K_Pivot_User_Item::where(['type'=>1,'relation_type'=>$type,'user_id'=>$me->id,'item_id'=>$item_id])->first();
                if(!$pivot)
                {
                    DB::beginTransaction();
                    try
                    {
                        $time = time();
                        $me->pivot_item()->attach($item_id,['type'=>1,'relation_type'=>$type,'created_at'=>$time,'updated_at'=>$time]);
//

                        // 记录机制 Communication
                        if($type == 1)
                        {
                            // 点赞&收藏
                            $item->timestamps = false;
                            $item->increment('favor_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 11;
                        }
                        else if($type == 11)
                        {
                            // 点赞
                            $item->timestamps = false;
                            $item->increment('favor_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 11;
                        }
                        else if($type == 21)
                        {
                            // 添加收藏
                            $item->timestamps = false;
                            $item->increment('collection_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 21;
                        }
                        else if($type == 31)
                        {
                            // 添加待办
                            $item->timestamps = false;
                            $item->increment('working_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 31;
                        }
                        else if($type == 32)
                        {
                            // 添加日程
                            $item->timestamps = false;
                            $item->increment('agenda_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 32;
                        }

                        $communication_insert['owner_id'] = $me->id;
                        $communication_insert['user_id'] = $me->id;
                        $communication_insert['belong_id'] = $item->owner_id;
                        $communication_insert['source_id'] = $me->id;
                        $communication_insert['item_id'] = $item_id;

                        $communication = new K_Communication;
                        $bool = $communication->fill($communication_insert)->save();
                        if(!$bool) throw new Exception("insert--communication--fail");


                        // 通知机制 Notification
                        if($type == 1)
                        {
                            // 点赞
                            if($item->owner_id != $me->id)
                            {
                                $notification_insert['notification_category'] = 11;
                                $notification_insert['notification_type'] = 11;
                                $notification_insert['owner_id'] = $item->owner_id;
                                $notification_insert['user_id'] = $item->owner_id;
                                $notification_insert['belong_id'] = $item->owner_id;
                                $notification_insert['source_id'] = $me->id;
                                $notification_insert['item_id'] = $item_id;

                                $notification_once = K_Notification::where($notification_insert)->first();
                                if(!$notification_once)
                                {
                                    $notification = new K_Notification;
                                    $bool = $notification->fill($notification_insert)->save();
                                    if(!$bool) throw new Exception("insert--notification--fail");
                                }
                            }
                        }

//                        $html['html'] = $this->view_item_html($item_id);

                        DB::commit();
                        return response_success([]);
                    }
                    catch (Exception $e)
                    {
                        DB::rollback();
                        $msg = '操作失败，请重试！';
                        $msg = $e->getMessage();
//                        exit($e->getMessage());
                        return response_fail([],$msg);
                    }
                }
                else
                {
                    if($type == 1) $msg = '已经点赞';
                    else if($type == 11) $msg = '已经点赞';
                    else if($type == 21) $msg = '已经收藏过了';
                    else if($type == 31) $msg = '已经在待办事列表';
                    else if($type == 32) $msg = '已经在日程列表';
                    else $msg = '';
                    return response_fail(['reason'=>'exist'],$msg);
                }
            }
            else return response_fail([],'内容不存在！');

        }
        else return response_error([],'请先登录！');
    }
    // 【K】【ITEM】【移除】【点赞&收藏 | +待办事 | +日程】
    public function item_remove_this($post_data,$type=0)
    {
        if(Auth::check())
        {
            $messages = [
                'type.required' => '参数有误',
                'item_id.required' => '参数有误'
            ];
            $v = Validator::make($post_data, [
                'type' => 'required',
                'item_id' => 'required'
            ], $messages);
            if ($v->fails())
            {
                $errors = $v->errors();
                return response_error([],$errors->first());
            }

            $item_id = $post_data['item_id'];
            $item = K_Item::find($item_id);
            if($item)
            {
                $me = Auth::user();
                $pivots = K_Pivot_User_Item::where(['type'=>1,'relation_type'=>$type,'user_id'=>$me->id,'item_id'=>$item_id])->get();
                if(count($pivots) > 0)
                {
                    DB::beginTransaction();
                    try
                    {
                        $num = K_Pivot_User_Item::where(['type'=>1,'relation_type'=>$type,'user_id'=>$me->id,'item_id'=>$item_id])->delete();
                        if($num != count($pivots)) throw new Exception("delete--pivots--fail");

                        // 记录机制 Communication
                        if($type == 1)
                        {
                            // 移除点赞
                            $item->timestamps = false;
                            $item->decrement('favor_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 12;
                        }
                        else if($type == 11)
                        {
                            // 移除点赞
                            $item->timestamps = false;
                            $item->decrement('favor_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 12;
                        }
                        else if($type == 21)
                        {
                            // 移除收藏
                            $item->timestamps = false;
                            $item->decrement('collection_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 22;
                        }
                        else if($type == 31)
                        {
                            // 移除待办
                            $item->timestamps = false;
                            $item->decrement('working_num');
                            $communication_insert['communication_category'] = 11;
                            $communication_insert['communication_type'] = 32;
                        }
                        else if($type == 32)
                        {
                            // 移除日程
                            $item->timestamps = false;
                            $item->decrement('agenda_num');
                            $communication_insert['type'] = 32;
                            $communication_insert['sort'] = 9;
                        }

                        $communication_insert['owner_id'] = $me->id;
                        $communication_insert['user_id'] = $me->id;
                        $communication_insert['belong_id'] = $item->owner_id;
                        $communication_insert['source_id'] = $me->id;
                        $communication_insert['item_id'] = $item_id;

                        $communication = new K_Communication;
                        $bool = $communication->fill($communication_insert)->save();
                        if(!$bool) throw new Exception("insert--communication--fail");

//
//                        $html['html'] = $this->view_item_html($item_id);

                        DB::commit();
                        return response_success([]);
                    }
                    catch (Exception $e)
                    {
                        DB::rollback();
                        $msg = '操作失败，请重试！';
//                        $msg = $e->getMessage();
//                        exit($e->getMessage());
                        return response_fail([],$msg);
                    }
                }
                else
                {
                    if($type == 1) $msg = '取消点赞';
                    else if($type == 11) $msg = '取消点赞';
                    else if($type == 21) $msg = '移除收藏成功';
                    else if($type == 31) $msg = '移除待办事成功';
                    else if($type == 32) $msg = '移除日程成功';
                    else $msg = '';
                    return response_fail(['reason'=>'exist'],$msg);
                }
            }
            else return response_fail([],'内容不存在！');
        }
        else return response_error([],'请先登录！');

    }
    // 【转发】
    public function item_forward($post_data)
    {
        if(Auth::check())
        {
            $messages = [
                'type.required' => '参数有误',
                'item_id.required' => '参数有误'
            ];
            $v = Validator::make($post_data, [
                'type' => 'required',
                'item_id' => 'required'
            ], $messages);
            if ($v->fails())
            {
                $errors = $v->errors();
                return response_error([],$errors->first());
            }

            $item_id = $post_data['item_id'];
            $item = K_Item::find($item_id);
            if($item)
            {
                $me = Auth::user();
                $me_id = $me->id;

                DB::beginTransaction();
                try
                {
                    $mine = new K_Item;
                    $post_data['user_id'] = $me_id;
                    $post_data['category'] = 99;
                    $post_data['is_shared'] = 100;
                    $bool = $mine->fill($post_data)->save();
                    if($bool)
                    {
                        $item->timestamps = false;
                        $item->increment('share_num');
                    }
                    else throw new Exception("insert--item--fail");

//                        $insert['type'] = 4;
//                        $insert['user_id'] = $user->id;
//                        $insert['item_id'] = $item_id;
//
//                        $communication = new K_Communication;
//                        $bool = $communication->fill($insert)->save();
//                        if(!$bool) throw new Exception("insert--communication--fail");
//
//                        $html['html'] = $this->view_item_html($item_id);

                    DB::commit();
                    return response_success([]);
                }
                catch (Exception $e)
                {
                    DB::rollback();
                    $msg = '操作失败，请重试！';
//                        $msg = $e->getMessage();
//                        exit($e->getMessage());
                    return response_fail([],$msg);
                }
            }
            else return response_fail([],'内容不存在！');
        }
        else return response_error([],'请先登录！');

    }







    // 【K】添加评论
    public function item_comment_save($post_data)
    {
        $messages = [
            'type.required' => '[type] 参数有误',
            'item_id.required' => '[item_id] 参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required',
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $item_id = $post_data['item_id'];
            if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试！");

            $item = K_Item::find($item_id);
            if(!$item) return response_error([],"该内容不存在，刷新一下试试！");
            $item->timestamps = false;
            $item->increment('comment_num');

            $communication_insert['communication_category'] = 11;
            $communication_insert['communication_type'] = 1;
            $communication_insert['owner_id'] = $me_id;
            $communication_insert['user_id'] = $me_id;
            $communication_insert['belong_id'] = $item->owner_id;
            $communication_insert['source_id'] = $me_id;
            $communication_insert['item_id'] = $item_id;
            $communication_insert['content'] = $post_data['content'];
            $communication_insert['support'] = !empty($post_data['support']) ? $post_data['support'] : 0;

            DB::beginTransaction();
            try
            {
                $communication = new K_Communication;
                $bool = $communication->fill($communication_insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

                // 通知对方
                if($item->owner_id != $me_id)
                {
                    $notification_insert['notification_category'] = 11;
                    $notification_insert['notification_type'] = 1;
                    $notification_insert['owner_id'] = $item->owner_id;
                    $notification_insert['user_id'] = $item->owner_id;
                    $notification_insert['belong_id'] = $item->owner_id;
                    $notification_insert['source_id'] = $me_id;
                    $notification_insert['item_id'] = $item_id;
                    $notification_insert['communication_id'] = $communication->id;

                    $notification = new K_Notification;
                    $bool = $notification->fill($notification_insert)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                $view = env('TEMPLATE_K_WWW').'frontend.component.comment';
                $html["html"] = view($view)->with("comment",$communication)->__toString();

                DB::commit();
                return response_success($html);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '添加失败，请重试！';
                $msg = $e->getMessage();
//                exit($e->getMessage());、
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }
    // 【K】获取评论
    public function item_comment_get($post_data)
    {
        $messages = [
            'type.required' => '[type] 参数有误！',
            'item_id.required' => '[item_id] 参数有误！'
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        $type = $post_data['type'];

        $item_id = $post_data['item_id'];
        if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试！");

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $comment_list = K_Communication::with([
                    'user',
                    'reply'=>function($query) { $query->with(['user']); },
//                    'dialogs'=>function($query) use ($user_id) { $query->with([
//                        'user',
//                        'reply'=>function($query1) { $query1->with(['user']); },
//                        'favors'=>function($query) use ($user_id)  { $query->where(['type'=>11,'user_id'=>$user_id]); }
//                    ])->orderBy('id','desc'); },
                    'favors'=>function($query) use ($user_id) { $query->where(['communication_type'=>11,'user_id'=>$user_id]); }
                ])
//                ->withCount('dialogs')
//                ->where('reply_id',0)
                ->where(['communication_category'=>11,'item_id'=>$item_id]);

            if($type == 0) $comment_list->whereIn('communication_type',[1,2,3,4,5]);
            else $comment_list->where('communication_type',$type);
        }
        else
        {
            $comment_list = K_Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); }//,
//                    'dialogs'=>function($query) { $query->with([
//                        'user',
//                        'reply'=>function($query1) { $query1->with(['user']); }
//                    ])->orderBy('id','desc'); },
                ])
//                ->withCount('dialogs')
//                ->where('reply_id',0)
                ->where(['communication_category'=>11,'item_id'=>$item_id]);

            if($type == 0) $comment_list->whereIn('communication_type',[1,2,3,4,5]);
            else $comment_list->where('communication_type',$type);
        }

        if(!empty($post_data['min_id']) && $post_data['min_id'] != 0) $comment_list->where('id', '<', $post_data['min_id']);

        if(!empty($post_data['support']))
        {
            if(in_array($post_data['support'], [0,1,2]))
            {
                if($post_data['support'] != 0) $comment_list->where('support', $post_data['support']);
            }
            else
            {
                return response_error([],"参数有误");
            }
        }

        $comment_list = $comment_list->orderBy('id','desc')->paginate(10);

        foreach ($comment_list as $comment)
        {
            if($comment->dialogs_count)
            {
                $comment->dialog_max_id = 0;
                $comment->dialog_min_id = 0;
                $comment->dialog_more = 'more';
                $comment->dialog_more_text = '还有 <span class="text-blue">'.$comment->dialogs_count.'</span> 回复';
            }
            else
            {
                $comment->dialog_max_id = 0;
                $comment->dialog_min_id = 0;
                $comment->dialog_more = 'none';
                $comment->dialog_more_text = '没有了';
            }

//            if(count($comment->dialogs))
//            {
//                $comment->dialogs = $comment->dialogs->take(1);
//
//                $comment->dialog_max_id = $comment->dialogs->first()->id;
//                $comment->dialog_min_id = $comment->dialogs->last()->id;
//                if($comment->dialogs->count() >= 1)
//                {
//                    $comment->dialog_more = 'more';
//                    $comment->dialog_more_text = '更多';
//                }
//                else
//                {
//                    $comment->dialog_more = 'none';
//                    $comment->dialog_more_text = '没有了';
//                }
//            }
//            else
//            {
//                $comment->dialog_max_id = 0;
//                $comment->dialog_min_id = 0;
//                $comment->dialog_more = 'none';
//                $comment->dialog_more_text = '没有了';
//            }
        }

        if(!$comment_list->isEmpty())
        {

            $view = env('TEMPLATE_K_WWW').'frontend.component.comment-list';
            $return["html"] = view($view)->with("comment_list",$comment_list)->__toString();
            $return["max_id"] = $comment_list->first()->id;
            $return["min_id"] = $comment_list->last()->id;
            $return["more"] = ($comment_list->count() >= 10) ? 'more' : 'none';
        }
        else
        {
            $return["html"] = '';
            $return["max_id"] = 0;
            $return["min_id"] = 0;
            $return["more"] = 'none';
        }

        return response_success($return);

    }
    // 【K】用户评论
    public function item_comment_get_html($post_data)
    {
        $messages = [
            'type.required' => '[type] 参数有误！',
            'item_id.required' => '[item_id] 参数有误！'
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        $item_encode = $post_data['item_id'];
        $item_decode = decode($item_encode);
        if(!$item_decode) return response_error([],"参数有误，刷新一下试试！");

        $communications = K_Communication::with(['user'])
            ->where(['item_id'=>$item_decode])->orderBy('id','desc')->get();

        $view = env('TEMPLATE_K_WWW').'frontend.component.comment-list';
        $html["html"] = view($view)->with("communications",$communications)->__toString();
        return response_success($html);

    }


    // 【K】添加回复
    public function item_reply_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'item_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
            'content.required' => '回复不能为空',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required',
            'comment_id' => 'required',
            'content' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $item_id = $post_data['item_id'];
            if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试！");
            $item = K_Item::find($item_id);
            if(!$item) return response_error([],"该内容不存在，刷新一下试试！");
            $item->timestamps = false;
            $item->increment('comment_num');

            $comment_id= $post_data['comment_id'];
            if(!is_numeric($comment_id)) return response_error([],"参数有误，刷新一下试试！");
            $comment = K_Communication::find($comment_id);
            if(!$comment) return response_error([],"该评论不存在，刷新一下试试！");
            $comment->timestamps = false;
            $comment->increment('comment_num');

            $communication_insert['communication_category'] = 11;
            $communication_insert['communication_type'] = 2;
            $communication_insert['owner_id'] = $me_id;
            $communication_insert['user_id'] = $me_id;
            $communication_insert['source_id'] = $me_id;
            $communication_insert['belong_id'] = $item->owner_id;
            $communication_insert['object_id'] = $comment->owner_id;
            $communication_insert['item_id'] = $item_id;
            $communication_insert['reply_id'] = $comment_id;
            $communication_insert['content'] = $post_data['content'];

            DB::beginTransaction();
            try
            {
                if($comment->dialog_id)
                {
                    $communication_insert['dialog_id'] = $comment->dialog_id;
                    $dialog = K_Communication::find($communication_insert['dialog_id']);
                    $dialog->timestamps = false;
                    $dialog->increment('comment_num');
                }
                else
                {
                    $communication_insert['dialog_id'] = $comment_id;
                }

                $communication = new K_Communication;
                $bool = $communication->fill($communication_insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

                // 通知对方
                if($comment->owner_id != $me_id)
                {
                    $notification_insert_1['notification_category'] = 11;
                    $notification_insert_1['notification_type'] = 2;
                    $notification_insert_1['owner_id'] = $comment->owner_id;
                    $notification_insert_1['user_id'] = $comment->owner_id;
                    $notification_insert_1['belong_id'] = $me_id;
                    $notification_insert_1['source_id'] = $me_id;
                    $notification_insert_1['item_id'] = $item_id;
                    $notification_insert_1['communication_id'] = $communication->id;
                    $notification_insert_1['reply_id'] = $comment->id;

                    $notification_1 = new K_Notification;
                    $bool = $notification_1->fill($notification_insert_1)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                // 通知作者
                if(($item->user_id != $me_id) && ($item->user_id != $comment->user_id))
                {
                    $notification_insert_2['notification_category'] = 11;
                    $notification_insert_2['notification_type'] = 3;
                    $notification_insert_2['owner_id'] = $item->owner_id;
                    $notification_insert_2['user_id'] = $item->owner_id;
                    $notification_insert_1['belong_id'] = $me_id;
                    $notification_insert_2['source_id'] = $me_id;
                    $notification_insert_2['item_id'] = $item_id;
                    $notification_insert_2['communication_id'] = $communication->id;
                    $notification_insert_2['reply_id'] = $comment->id;

                    $notification_2 = new K_Notification;
                    $bool = $notification_2->fill($notification_insert_2)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                $html["html"] = view(env('TEMPLATE_K_WWW').'frontend.component.reply')->with("reply",$communication)->__toString();

                DB::commit();
                return response_success($html);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '添加失败，请重试！';
//                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }
    // 【K】获取回复
    public function item_reply_get($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'item_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required',
            'comment_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        $type = $post_data['type'];

        $item_id = $post_data['item_id'];
        if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试");

        $comment_id = $post_data['comment_id'];
        if(!is_numeric($comment_id)) return response_error([],"参数有误，刷新一下试试");

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $comments = K_Communication::with([
                    'user',
                    'reply'=>function($query) { $query->with(['user']); },
                    'favors'=>function($query) use ($user_id) { $query->where(['communication_type'=>11,'user_id'=>$user_id]); }
                ])
                ->where(['communication_type'=>$type,'item_id'=>$item_id,'dialog_id'=>$comment_id])
                ->where('reply_id','<>',0);
        }
        else
        {
            $comments = K_Communication::with([
                    'user',
                    'reply'=>function($query) { $query->with(['user']); },
                ])
                ->where(['communication_type'=>$type,'item_id'=>$item_id,'dialog_id'=>$comment_id])
                ->where('reply_id','<>',0);
        }

        if(!empty($post_data['min_id']) && $post_data['min_id'] != 0) $comments->where('id', '<', $post_data['min_id']);

        $comments = $comments->orderBy('id','desc')->paginate(10);

        if(!$comments->isEmpty())
        {
            $return["html"] = view(env('TEMPLATE_K_WWW').'frontend.component.reply-list')
                ->with("communication_list",$comments)->__toString();
            $return["max_id"] = $comments->first()->id;
            $return["min_id"] = $comments->last()->id;
            $return["more"] = ($comments->count() >= 10) ? 'more' : 'none';
        }
        else
        {
            $return["html"] = '';
            $return["max_id"] = 0;
            $return["min_id"] = 0;
            $return["more"] = 'none';
        }

        return response_success($return);

    }


    // 【K】评论点赞
    public function item_comment_favor_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'item_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required',
            'comment_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $item_id = $post_data['item_id'];
            if(!is_numeric($item_id)) return response_error([],"[item_id] 参数有误，刷新一下试试！");

            $comment_id = $post_data['comment_id'];
            if(!is_numeric($comment_id)) return response_error([],"[comment_id] 参数有误，刷新一下试试！");

            $communication_insert['communication_type'] = 11;
            $communication_insert['user_id'] = $me_id;
            $communication_insert['item_id'] = $item_id;
            $communication_insert['reply_id'] = $comment_id;

            DB::beginTransaction();
            try
            {
                $item = k_Item::find($item_id);
                if(!$item) return response_error([],"该内容不存在，刷新一下试试！");

                $comment = K_Communication::find($comment_id);
                if(!$comment) return response_error([],"该评论不存在，刷新一下试试！");

                $comment->timestamps = false;
                $comment->increment('favor_num');

                $communication = new K_Communication;
                $bool = $communication->fill($communication_insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

//                通知对方
                if($comment->owner_id != $me_id)
                {
                    $notification_insert_1['notification_category'] = 11;
                    $notification_insert_1['notification_type'] = 4;
                    $notification_insert_1['owner_id'] = $comment->owner_id;
                    $notification_insert_1['user_id'] = $comment->owner_id;
                    $notification_insert_1['belong_id'] = $comment->owner_id;
                    $notification_insert_1['source_id'] = $me_id;
                    $notification_insert_1['item_id'] = $item_id;
                    $notification_insert_1['communication_id'] = $communication->id;
                    $notification_insert_1['reply_id'] = $comment_id;

                    $notification_1 = new K_Notification;
                    $bool = $notification_1->fill($notification_insert_1)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                if(($item->owner_id != $me_id) && ($item->owner_id != $comment->owner_id))
                {
                    $notification_insert_2['notification_category'] = 11;
                    $notification_insert_2['notification_type'] = 5;
                    $notification_insert_2['owner_id'] = $item->owner_id;
                    $notification_insert_2['user_id'] = $item->owner_id;
                    $notification_insert_2['belong_id'] = $item->owner_id;
                    $notification_insert_2['source_id'] = $me_id;
                    $notification_insert_2['item_id'] = $item_id;
                    $notification_insert_2['communication_id'] = $communication->id;
                    $notification_insert_2['reply_id'] = $comment->id;

                    $notification_2 = new K_Notification;
                    $bool = $notification_2->fill($notification_insert_2)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                DB::commit();
                return response_success();
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '添加失败，请重试！';
//                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }
    // 【K】评论取消赞
    public function item_comment_favor_cancel($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'item_id.required' => '参数有误',
            'comment_id.required' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required',
            'comment_id' => 'required'
        ], $messages);
        if ($v->fails())
        {
            $errors = $v->errors();
            return response_error([],$errors->first());
        }

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $item_id = $post_data['item_id'];
            if(!is_numeric($item_id)) return response_error([],"[item_id] 参数有误，刷新一下试试！");

            $comment_id = $post_data['comment_id'];
            if(!is_numeric($comment_id)) return response_error([],"[comment_id] 参数有误，刷新一下试试！");

            DB::beginTransaction();
            try
            {
                $comment = K_Communication::find($comment_id);
                if(!$comment && $comment->user_id != $me_id) return response_error([],"参数有误，刷新一下试试！");
                $comment->decrement('favor_num');

                $favors = K_Communication::where([
                    'communication_type'=>11,
                    'user_id'=>$me_id,
                    'item_id'=>$item_id,
                    'reply_id'=>$comment_id
                ]);
                $count = count($favors->get());
                if($count)
                {
                    $num = $favors->delete();
                    if($num != $count) throw new Exception("delete--communication--fail");
                }

                DB::commit();
                return response_success();
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '操作失败，请重试！';
                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([], $msg);
            }

        }
        else return response_error([],"请先登录！");

    }





    // 顺序排列
    function get_recursion($result, $parent_id=0, $level=0)
    {
        /*记录排序后的类别数组*/
        static $list = array();

        foreach ($result as $k => $v)
        {
            if($v->p_id == $parent_id)
            {
                $v->level = $level;

                foreach($list as $key=>$val)
                {
                    if($val->id == $parent_id) $list[$key]->has_child = 1;
                }

                /*将该类别的数据放入list中*/
                $list[] = $v;

                $this->get_recursion($result, $v->id, $level+1);
            }
        }

        return $list;
    }




    public function handleScheduleDays($start_time,$end_time)
    {
        $data_days = "";
        if(($start_time != 0) && ($end_time != 0))
        {
            $day_start = strtotime(date("Y-n-j",$start_time));
            for($i=$day_start;$i<=$end_time;$i=$i+(3600*24))
            {
                $data_days .= "calendar-day-".date("Y-m-j",$i)." ";
            }
        }
        else if(($start_time == 0) || ($end_time == 0))
        {
            if($end_time == 0) $data_days .= "calendar-day-".date("Y-m-j", $start_time)." ";
            if($start_time == 0) $data_days .= "calendar-day-".date("Y-m-j", $end_time)." ";
        }
        return $data_days;
    }

    public function handleScheduleWeeks($start_time,$end_time)
    {
        $data_year_weeks = "";
        $day_start = strtotime(date("Y-n-j",$start_time));
        $year_week_start = $day_start - ((date("N",$start_time)-1)*3600*24);
        for($i=$year_week_start;$i<=$end_time;$i=$i+(3600*24*7))
        {
            $data_year_weeks .= "calendar-week-".date("Y.W",$i)." ";
        }
        return $data_year_weeks;
    }



    // 记录访问
    public function record($post_data)
    {
        $record = new K_Record();

        $browseInfo = getBrowserInfo();
        $type = $browseInfo['type'];
        if($type == "Mobile") $post_data["open_device_type"] = 1;
        else if($type == "PC") $post_data["open_device_type"] = 2;

        $post_data["referer"] = $browseInfo['referer'];
        $post_data["open_system"] = $browseInfo['system'];
        $post_data["open_browser"] = $browseInfo['browser'];
        $post_data["open_app"] = $browseInfo['app'];

        $post_data["ip"] = Get_IP();
        $bool = $record->fill($post_data)->save();
        if($bool) return true;
        else return false;
    }







}