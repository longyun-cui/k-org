<?php
namespace App\Repositories\Frontend;

use App\User;
use App\Models\Org\OrgOrganization;
use App\Models\Org\OrgAdministrator;
use App\Models\RootItem;
use App\Models\Content;
use App\Models\Communication;
use App\Models\Notification;
use App\Models\Pivot_User_Item;
use App\Models\Pivot_User_Relation;

use App\Models\K\K_User;
use App\Models\K\K_Item;
use App\Models\K\K_Communication;
use App\Models\K\K_Pivot_User_Relation;
use App\Models\K\K_Pivot_User_Item;
use App\Models\K\K_Notification;

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


    // 【平台首页】
    public function view_root($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $notification_count = Notification::where(['user_id'=>$me_id,'is_read'=>0])->count();
            view()->share('notification_count',$notification_count);
        }
        else $me_id = 0;


        if(Auth::check())
        {
            $item_query = K_Item::with([
                    'owner',
                    'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
                ]);
        }
        else
        {
            $item_query = K_Item::with(['owner']);
        }


        $type = !empty($post_data['type']) ? $post_data['type'] : 'root';
        if($type == 'root') $item_query->whereIn('item_type',[1,11]);
        else if($type == 'article') $item_query->whereIn('item_type',[1]);
        else if($type == 'activity') $item_query->whereIn('item_type',[11]);


        $items = $item_query->orderByDesc('id')->paginate(20);
        $return['items'] = $items;

        foreach ($items as $item)
        {
            $item->custom_decode = json_decode($item->custom);
            $item->content_show = strip_tags($item->content);
            $item->img_tags = get_html_img($item->content);
        }


        $sidebar_active = '';
        if($type == 'root') $sidebar_active = 'sidebar_menu_root_active';
        else if($type == 'article') $sidebar_active = 'sidebar_menu_article_active';
        else if($type == 'activity') $sidebar_active = 'sidebar_menu_activity_active';


        $return[$sidebar_active] = 'active';
        $return['getType'] = 'items';

        $path = request()->path();
        if($path == "root-1") return view(env('TEMPLATE_DEFAULT').'frontend.entrance.root-1')->with($return);
//        else return view('frontend.entrance.root')->with($return);
        else return view(env('TEMPLATE_DEFAULT').'frontend.entrance.root')->with($return);
    }


    // 【内容列表】
    public function view_item_list($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
        }
        else $me_id = 0;

        $items = RootItem::with(['org'])->where('is_shared','>=',99);

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

        return view(env('TEMPLATE_DEFAULT').'frontend.entrance.item-list')->with($return);
    }

    // 【内容详情】
    public function view_item($post_data,$id=0)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
        }
        else $me_id = 0;

        $item = K_Item::with([
            'owner'
            ])
            ->find($id);
        if($item)
        {
            $item->timestamps = false;
            $item->increment('visit_num');

            $user = K_User::with([
                    'ad',
                    'pivot_relation'=>function($query) { $query->where(['relation_active'=>1,'relation_type'=>88])->orderby('updated_at','desc'); },
                ])->find($item->owner_id);
            $user->timestamps = false;
            $user->increment('visit_num');

            $item->custom_decode = json_decode($item->custom);
        }
        else return view(env('TEMPLATE_DEFAULT').'frontend.errors.404');

        return view(env('TEMPLATE_DEFAULT').'frontend.entrance.item')
            ->with([
                'getType'=>'item',
                'item'=>$item,
                'user'=>$user
            ]);
    }




    // 【用户】【主页】
    public function view_user($post_data,$id=0)
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
            'pivot_sponsor_list'=>function($query) { $query->where(['relation_active'=>1,'relation_type'=>88])->orderby('updated_at','desc'); },
            'pivot_org_list'=>function($query) { $query->where(['relation_active'=>1,'relation_type'=>88])->orderby('updated_at','desc'); },
        ])->withCount([
            'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
            'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
        ])->find($user_id);
//        dd($user->toArray());

        if(!$user) return view(env('TEMPLATE_DEFAULT').'frontend.errors.404');

        $user->timestamps = false;
        $user->increment('visit_num');

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
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
                $relation = Pivot_User_Relation::where(['mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
                view()->share(['relation'=>$relation]);
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


        $sidebar_active = '';
        if($type == 'root') $sidebar_active = 'sidebar_menu_root_active';
        else if($type == 'article') $sidebar_active = 'sidebar_menu_article_active';
        else if($type == 'activity') $sidebar_active = 'sidebar_menu_activity_active';

        view()->share('user_root_active','active');
        return view(env('TEMPLATE_DEFAULT').'frontend.entrance.user')
            ->with([
                'data'=>$user,
                'items'=>$items,
                $sidebar_active => 'active'
            ]);
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
            $items = RootItem::with([
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
            $items = RootItem::with([
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
        return view('frontend.entrance.user-original')->with(['data'=>$user,'items'=>$items,'user_original_active'=>'active']);
    }

    // 【Ta关注的人】
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

        return view('frontend.entrance.user-follow')->with(['data'=>$Ta,'users'=>$pivot_users,'user_relation_follow_active'=>'active']);
    }
    // 【关注Ta的人】
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

        return view('frontend.entrance.user-fans')->with(['data'=>$Ta,'users'=>$pivot_users,'user_relation_fans_active'=>'active']);
    }




    // 【组织列表】
    public function view_organization_list($post_data)
    {

        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $user_list = K_User::with([
                    'ad',
                    'fans_list'=>function($query) use($me_id) { $query->where('mine_user_id',$me_id); },
                ])->withCount([
                    'fans_list as fans_count' => function($query) { $query->where([]); },
                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
                ])
                ->where('user_type',11)
                ->paginate(20);
        }
        else
        {

            $user_list = K_User::with([
                    'ad',
                ])->withCount([
                    'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
                    'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
                ])
                ->where('user_type',11)
                ->paginate(20);
        }


//dd($user_list->toArray());


        return view(env('TEMPLATE_DEFAULT').'frontend.entrance.organization-list')
            ->with([
                'user_list'=>$user_list,
                'sidebar_menu_organization_list_active' => 'active'
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

        $article_items = RootItem::with(['org'])
            ->where(['org_id'=>$id,'category'=>'1'])->where('is_shared','>=',99)
            ->orderby('id','desc')->limit(8)->get();
        $return['article_items'] = $article_items;

        $activity_items = RootItem::with(['org'])
            ->where(['org_id'=>$id,'category'=>'11'])->where('is_shared','>=',99)
            ->orderby('id','desc')->limit(8)->get();
        $return['activity_items'] = $activity_items;

        $sponsor_items = RootItem::with(['org'])
            ->where(['org_id'=>$id,'category'=>'88'])->where('is_shared','>=',99)
            ->orderby('id','desc')->limit(8)->get();
        $return['sponsor_items'] = $sponsor_items;

        return view('frontend.entrance.org')->with($return);
    }

    // 【机构介绍页】
    public function view_user_introduction($post_data,$id=0)
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
        $return['org_introduce_active'] = "active";

        return view('frontend.entrance.org-introduce')->with($return);
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

        $items = RootItem::with(['org'])->where('org_id',$id)->where('is_shared','>=',99);

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

        return view('frontend.entrance.org-item-list')->with($return);
    }



    // 【添加关注】
    public function user_relation_add($post_data)
    {
        $messages = [
            'user_id.required' => '参数有误',
            'user_id.numeric' => '参数有误',
            'user_id.exists' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'user_id' => 'required|numeric|exists:root_users,id'
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
            $user = User::find($user_id);

            DB::beginTransaction();
            try
            {
                $me_relation = Pivot_User_Relation::where(['mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
                if($me_relation)
                {
                    if($me_relation->relation_type == 71) $me_relation->relation_type = 21;
                    else $me_relation->relation_type = 41;
                    $me_relation->save();
                }
                else
                {
                    $me_relation = new Pivot_User_Relation;
                    $me_relation->mine_user_id = $me_id;
                    $me_relation->relation_user_id = $user_id;
                    $me_relation->relation_type = 41;
                    $me_relation->save();
                }
                $me->timestamps = false;
                $me->increment('follow_num');

                $it_relation = Pivot_User_Relation::where(['mine_user_id'=>$user_id,'relation_user_id'=>$me_id])->first();
                if($it_relation)
                {
                    if($it_relation->relation_type == 41) $it_relation->relation_type = 21;
                    else $it_relation->relation_type = 71;
                    $it_relation->save();
                }
                else
                {
                    $it_relation = new Pivot_User_Relation;
                    $it_relation->mine_user_id = $user_id;
                    $it_relation->relation_user_id = $me_id;
                    $it_relation->relation_type = 71;
                    $it_relation->save();
                }
                $user->timestamps = false;
                $user->increment('fans_num');

                DB::commit();
                return response_success(['relation_type'=>$me_relation->relation_type]);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '添加关注失败，请重试！';
//                $msg = $e->getMessage();
//                exit($e->getMessage());
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");
    }
    // 【取消关注】
    public function user_relation_remove($post_data)
    {
        $messages = [
            'user_id.required' => '参数有误',
            'user_id.numeric' => '参数有误',
            'user_id.exists' => '参数有误',
        ];
        $v = Validator::make($post_data, [
            'user_id' => 'required|numeric|exists:root_users,id'
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
            $user = User::find($user_id);

            DB::beginTransaction();
            try
            {
                $me_relation = Pivot_User_Relation::where(['mine_user_id'=>$me_id,'relation_user_id'=>$user_id])->first();
                if($me_relation)
                {
                    if($me_relation->relation_type == 21) $me_relation->relation_type = 71;
                    else if($me_relation->relation_type == 41) $me_relation->relation_type = 91;
                    else $me_relation->relation_type = 91;
                    $me_relation->save();
                }
                $me->timestamps = false;
                $me->decrement('follow_num');

                $it_relation = Pivot_User_Relation::where(['mine_user_id'=>$user_id,'relation_user_id'=>$me_id])->first();
                if($it_relation)
                {
                    if($it_relation->relation_type == 21) $it_relation->relation_type = 41;
                    else if($it_relation->relation_type == 71) $it_relation->relation_type = 92;
                    else $it_relation->relation_type = 92;
                    $it_relation->save();
                }
                $user->timestamps = false;
                $user->decrement('fans_num');

                DB::commit();
                return response_success(['relation_type'=>$me_relation->relation_type]);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '取消关注失败，请重试！';
//                $msg = $e->getMessage();
//                exit($e->getMessage());、
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");
    }



    // 【我的】【关注】
    public function view_my_follow($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $user_list = K_Pivot_User_Relation::with([
                    'relation_user'=>function($query) {
                        $query->withCount([
                            'fans_list as fans_count' => function($query) { $query->where(['relation_type'=>41]); },
                            'items as article_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>1]); },
                            'items as activity_count' => function($query) { $query->where(['item_category'=>1,'item_type'=>11]); },
                        ]);
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

        return view(env('TEMPLATE_DEFAULT').'frontend.entrance.my-follow')
            ->with([
                'user_list'=>$user_list,
                'sidebar_menu_my_follow_active'=>'active'
            ]);
    }
    // 【我的】【粉丝】
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

        return view('frontend.entrance.relation-fans')->with(['users'=>$users,'root_relation_fans_active'=>'active']);
    }

    // 【我的】【收藏】
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

        return view(env('TEMPLATE_DEFAULT').'frontend.entrance.my-favor')
            ->with([
                'item_list'=>$item_list,
                'sidebar_menu_my_favor_active'=>'active'
            ]);
    }





    // 【我的原创】
    public function view_home_mine_original($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;

            $items = RootItem::select("*")->with([
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

        return view('frontend.entrance.root-original')->with(['items'=>$items,'root_mine_active'=>'active']);
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

        return view('frontend.entrance.root-todolist')->with(['items'=>$items,'root_todolist_active'=>'active']);
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

        return view('frontend.entrance.root-schedule')->with(['items'=>$items,'root_schedule_active'=>'active']);
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

        return view('frontend.entrance.root-collection')->with(['items'=>$items,'root_collection_active'=>'active']);
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

        return view('frontend.entrance.root-favor')->with(['items'=>$items,'root_favor_active'=>'active']);
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

        $items = RootItem::with([
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

        return view('frontend.entrance.root-discovery')->with(['items'=>$items,'root_discovery_active'=>'active']);
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
//        $items = RootItem::with([
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

        return view('frontend.entrance.root-follow')->with(['items'=>$items,'root_follow_active'=>'active']);
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
//        $items = RootItem::with([
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

        return view('frontend.entrance.root-circle')->with(['items'=>$items,'root_circle_active'=>'active']);
    }




    // 内容模板
    public function view_item_html($id)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
            $item = RootItem::with([
                'user',
                'contents'=>function($query) { $query->where(['active'=>1,'p_id'=>0])->orderBy('id','asc'); },
                'pivot_item_relation'=>function($query) use($me_id) { $query->where('user_id',$me_id); }
            ])->find($id);
        }
        else
        {
            $item = RootItem::with([
                'user',
                'contents'=>function($query) { $query->where(['active'=>1,'p_id'=>0])->orderBy('id','asc'); }
            ])->find($id);
        }
        $items[0] = $item;
        return view('frontend.'.env('TEMPLATE').'.component.item-list-1')->with(['items'=>$items])->__toString();
    }








    // 【消息提醒】
    public function view_home_notification($post_data)
    {
        if(Auth::check())
        {
            $me = Auth::user();
            $me_id = $me->id;
        }
        else $me_id = 0;

        $count = Notification::where(['is_read'=>0,'type'=>11,'user_id'=>$me_id])->count();
        if($count)
        {
            $notifications = Notification::with([
                'source',
                'item'=>function($query) {
                    $query->with([
                        'user',
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
            ])->where(['type'=>11,'is_read'=>0,'user_id'=>$me_id])->orderBy('id','desc')->get();

            $update_num = Notification::where(['type'=>11,'is_read'=>0,'user_id'=>$me_id])->update(['is_read'=>1]);
            view()->share('notification_type', 'new');
        }
        else
        {
            $notifications = Notification::with([
                'source',
                'item'=>function($query) {
                    $query->with([
                        'user',
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
            ])->where(['type'=>11,'user_id'=>$me_id])->orderBy('id','desc')->paginate(10);
            view()->share('notification_type', 'paginate');
        }


//        dd($notifications->toArray());

//        foreach ($items as $item)
//        {
//            $item->custom_decode = json_decode($item->custom);
//            $item->content_show = strip_tags($item->content);
//            $item->img_tags = get_html_img($item->content);
//        }
//
        return view('frontend.entrance.root-notification')->with(['notifications'=>$notifications,'root_notification_active'=>'active']);
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
        $view_blade = 'frontend.entrance.root-edit';
        return view($view_blade)->with(['operate'=>'create', 'encode_id'=>encode(0), 'root_edit_active'=>'active']);
    }
    // 返回【编辑】视图
    public function view_home_mine_item_edit()
    {
        $id = request("id",0);
        if(!$id && intval($id) !== 0) return view('home.404');

        if($id == 0)
        {
            return view('frontend.entrance.root-create')->with(['operate'=>'create', 'encode_id'=>$id]);
        }
        else
        {
            $data = RootItem::find($id);
            if($data)
            {
                unset($data->id);
                return view('frontend.entrance.root-edit')->with(['operate'=>'edit', 'encode_id'=>$id, 'data'=>$data]);
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
                $mine = new RootItem;
                $post_data["user_id"] = $user->id;
            }
            elseif('edit') // 编辑
            {
                $mine = RootItem::find($id);
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

        $item = RootItem::with([
            'contents'=>function($query) { $query->orderBy('rank','asc'); }
        ])->find($id);
        if($item)
        {
            $item->encode_id = encode($item->id);

            $item->contents_recursion = $this->get_recursion($item->contents,0);

            return view('frontend.entrance.root-edit-for-menutype')->with(['data'=>$item]);
        }
        else return view('home.404')->with(['error'=>'该内容不存在']);

    }
    // 返回【时间线类型】视图
    public function view_home_mine_item_edit_timeline($post_data)
    {
        $id = $post_data['id'];
        if(!$id) return view('home.404')->with(['error'=>'参数有误']);
        // abort(404);

        $item = RootItem::with([
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

            return view('frontend.entrance.root-edit-for-timeline')->with(['data'=>$item]);
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
        $item = RootItem::find($item_decode);
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
                        $content = new RootItem;
                        $post_data["user_id"] = $user->id;
                    }
                    elseif('edit') // 编辑
                    {
                        if($content_decode == $post_data["p_id"]) return response_error([],"不能选择自己为父节点");

                        $content = RootItem::find($content_decode);
                        if(!$content) return response_error([],"该内容不存在，刷新页面重试");
                        if($content->user_id != $user->id) return response_error([],"你没有操作权限");
//                        if($content->type == 1) unset($post_data["type"]);

                        if($post_data["p_id"] != 0)
                        {
                            $is_child = true;
                            $p_id = $post_data["p_id"];
                            while($is_child)
                            {
                                $p = RootItem::find($p_id);
                                if(!$p) return response_error([],"参数有误，刷新页面重试");
                                if($p->p_id == 0) $is_child = false;
                                if($p->p_id == $content_decode)
                                {
                                    $content_children = RootItem::where('p_id',$content_decode)->get();
                                    $children_count = count($content_children);
                                    if($children_count)
                                    {
                                        $num = RootItem::where('p_id',$content_decode)->update(['p_id'=>$content->p_id]);
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
                        $parent = RootItem::find($post_data["p_id"]);
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
        $item = RootItem::find($item_decode);
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
                        $content = new RootItem;
                        $post_data["user_id"] = $user->id;
                    }
                    elseif('edit') // 编辑
                    {
                        $content = RootItem::find($content_decode);
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

        $mine = RootItem::find($id);
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




    // 【添加】
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
                            // 点赞
                            $item->timestamps = false;
                            $item->increment('favor_num');
                            $communication_insert['communication_category'] = 1;
                            $communication_insert['communication_type'] = 11;
                        }
                        else if($type == 11)
                        {
                            // 点赞
                            $item->timestamps = false;
                            $item->increment('favor_num');
                            $communication_insert['communication_category'] = 1;
                            $communication_insert['communication_type'] = 11;
                        }
                        else if($type == 21)
                        {
                            // 添加收藏
                            $item->timestamps = false;
                            $item->increment('collection_num');
                            $communication_insert['communication_category'] = 1;
                            $communication_insert['communication_type'] = 21;
                        }
                        else if($type == 31)
                        {
                            // 添加待办
                            $item->timestamps = false;
                            $item->increment('working_num');
                            $communication_insert['communication_category'] = 1;
                            $communication_insert['communication_type'] = 31;
                        }
                        else if($type == 32)
                        {
                            // 添加日程
                            $item->timestamps = false;
                            $item->increment('agenda_num');
                            $communication_insert['communication_category'] = 1;
                            $communication_insert['communication_type'] = 32;
                        }

                        $communication_insert['source_id'] = $me->id;
                        $communication_insert['user_id'] = $item->user_id;
                        $communication_insert['item_id'] = $item_id;

                        $communication = new K_Communication;
                        $bool = $communication->fill($communication_insert)->save();
                        if(!$bool) throw new Exception("insert--communication--fail");


                        // 通知机制 Communication
                        if($type == 11)
                        {
                            // 点赞
                            if($item->user_id != $me->id)
                            {
                                $notification_insert['notification_category'] = 11;
                                $notification_insert['notification_type'] = 11;
                                $notification_insert['user_id'] = $item->user_id;
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
    // 【移除】
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
                            $communication_insert['relation_category'] = 1;
                            $communication_insert['relation_type'] = 12;
                        }
                        else if($type == 11)
                        {
                            // 移除点赞
                            $item->timestamps = false;
                            $item->decrement('favor_num');
                            $communication_insert['relation_category'] = 1;
                            $communication_insert['relation_type'] = 12;
                        }
                        else if($type == 21)
                        {
                            // 移除收藏
                            $item->timestamps = false;
                            $item->decrement('collection_num');
                            $communication_insert['relation_category'] = 1;
                            $communication_insert['relation_type'] = 22;
                        }
                        else if($type == 31)
                        {
                            // 移除待办
                            $item->timestamps = false;
                            $item->decrement('working_num');
                            $communication_insert['relation_category'] = 1;
                            $communication_insert['relation_type'] = 32;
                        }
                        else if($type == 32)
                        {
                            // 移除日程
                            $item->timestamps = false;
                            $item->decrement('agenda_num');
                            $communication_insert['type'] = 32;
                            $communication_insert['sort'] = 9;
                        }

                        $communication_insert['user_id'] = $item->user_id;
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
            $item = RootItem::find($item_id);
            if($item)
            {
                $me = Auth::user();
                $me_id = $me->id;

                DB::beginTransaction();
                try
                {
                    $mine = new RootItem;
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
//                        $communication = new Communication;
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







    // 添加评论
    public function item_comment_save($post_data)
    {
        $messages = [
            'type.required' => '参数有误',
            'item_id.required' => '参数有误',
            'content.required' => '内容不能为空',
        ];
        $v = Validator::make($post_data, [
            'type' => 'required',
            'item_id' => 'required',
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
            if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试");

            $communication_insert['type'] = 1;
            $communication_insert['user_id'] = $me_id;
            $communication_insert['item_id'] = $item_id;
            $communication_insert['content'] = $post_data['content'];
            $communication_insert['support'] = !empty($post_data['support']) ? $post_data['support'] : 0;

            DB::beginTransaction();
            try
            {
                $item = RootItem::find($item_id);
                if(!$item) return response_error([],"该内容不存在，刷新一下试试");

                $item->timestamps = false;
                $item->increment('comment_num');

                $communication = new Communication;
                $bool = $communication->fill($communication_insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

                // 通知对方
                if($item->user_id != $me_id)
                {
                    $notification_insert['type'] = 11;
                    $notification_insert['sort'] = 1;
                    $notification_insert['user_id'] = $item->user_id;
                    $notification_insert['source_id'] = $me_id;
                    $notification_insert['item_id'] = $item_id;
                    $notification_insert['communication_id'] = $communication->id;

                    $notification = new Notification;
                    $bool = $notification->fill($notification_insert)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                $html["html"] = view('frontend.'.env('TEMPLATE').'.component.comment')->with("comment",$communication)->__toString();

                DB::commit();
                return response_success($html);
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '添加失败，请重试！';
//                $msg = $e->getMessage();
//                exit($e->getMessage());、
                return response_fail([], $msg);
            }
        }
        else return response_error([],"请先登录！");

    }
    // 获取评论
    public function item_comment_get($post_data)
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

        $type = $post_data['type'];

        $item_id = $post_data['item_id'];
        if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试");

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); },
//                'dialogs'=>function($query) use ($user_id) { $query->with([
//                    'user',
//                    'reply'=>function($query1) { $query1->with(['user']); },
//                    'favors'=>function($query) use ($user_id)  { $query->where(['type'=>11,'user_id'=>$user_id]); }
//                ])->orderBy('id','desc'); },
                'favors'=>function($query) use ($user_id) { $query->where(['type'=>11,'user_id'=>$user_id]); }
            ])->withCount('dialogs')
            ->where(['type'=>$type,'reply_id'=>0,'item_id'=>$item_id]);
        }
        else
        {
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); }//,
//                'dialogs'=>function($query) { $query->with([
//                    'user',
//                    'reply'=>function($query1) { $query1->with(['user']); }
//                ])->orderBy('id','desc'); },
            ])->withCount('dialogs')
            ->where(['type'=>$type,'reply_id'=>0,'item_id'=>$item_id]);
        }

        if(!empty($post_data['min_id']) && $post_data['min_id'] != 0) $comments->where('id', '<', $post_data['min_id']);

        if(!empty($post_data['support']))
        {
            if(in_array($post_data['support'], [0,1,2]))
            {
                if($post_data['support'] != 0) $comments->where('support', $post_data['support']);
            }
            else
            {
                return response_error([],"参数有误");
            }
        }

        $comments = $comments->orderBy('id','desc')->paginate(10);

        foreach ($comments as $comment)
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

        if(!$comments->isEmpty())
        {
            $return["html"] = view('frontend.'.env('TEMPLATE').'.component.comments')->with("communications",$comments)->__toString();
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
    // 用户评论
    public function item_comment_get_html($post_data)
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

        $item_encode = $post_data['item_id'];
        $item_decode = decode($item_encode);
        if(!$item_decode) return response_error([],"参数有误，刷新一下试试");

        $communications = Communication::with(['user'])
            ->where(['item_id'=>$item_decode])->orderBy('id','desc')->get();

        $html["html"] = view('frontend.component.comments')->with("communications",$communications)->__toString();
        return response_success($html);

    }


    // 添加回复
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
            if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试");

            $comment_encode = $post_data['comment_id'];
            $comment_decode = decode($comment_encode);
            if(!$comment_decode) return response_error([],"参数有误，刷新一下试试！");

            $communication_insert['type'] = 1;
            $communication_insert['user_id'] = $me_id;
            $communication_insert['item_id'] = $item_id;
            $communication_insert['reply_id'] = $comment_decode;
            $communication_insert['content'] = $post_data['content'];

            DB::beginTransaction();
            try
            {
                $item = RootItem::find($item_id);
                if(!$item) return response_error([],"该内容不存在，刷新一下试试");

                $item->timestamps = false;
                $item->increment('comment_num');

                $comment = Communication::find($comment_decode);
                if(!$comment) return response_error([],"该评论不存在，刷新一下试试！");
                $comment->timestamps = false;
                $comment->increment('comment_num');

                if($comment->dialog_id)
                {
                    $communication_insert['dialog_id'] = $comment->dialog_id;
                    $dialog = Communication::find($communication_insert['dialog_id']);
                    $dialog->timestamps = false;
                    $dialog->increment('comment_num');
                }
                else
                {
                    $communication_insert['dialog_id'] = $comment_decode;
                }

                $communication = new Communication;
                $bool = $communication->fill($communication_insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

                // 通知对方
                if($comment->user_id != $me_id)
                {
                    $notification_insert_1['type'] = 11;
                    $notification_insert_1['sort'] = 2;
                    $notification_insert_1['user_id'] = $comment->user_id;
                    $notification_insert_1['source_id'] = $me_id;
                    $notification_insert_1['item_id'] = $item_id;
                    $notification_insert_1['communication_id'] = $communication->id;
                    $notification_insert_1['reply_id'] = $comment->id;

                    $notification_1 = new Notification;
                    $bool = $notification_1->fill($notification_insert_1)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                if(($item->user_id != $me_id) && ($item->user_id != $comment->user_id))
                {
                    $notification_insert_2['type'] = 11;
                    $notification_insert_2['sort'] = 3;
                    $notification_insert_2['user_id'] = $item->user_id;
                    $notification_insert_2['source_id'] = $me_id;
                    $notification_insert_2['item_id'] = $item_id;
                    $notification_insert_2['communication_id'] = $communication->id;
                    $notification_insert_2['reply_id'] = $comment->id;

                    $notification_2 = new Notification;
                    $bool = $notification_2->fill($notification_insert_2)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                $html["html"] = view('frontend.'.env('TEMPLATE').'.component.reply')->with("reply",$communication)->__toString();

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
    // 获取回复
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

        $comment_encode = $post_data['comment_id'];
        $comment_decode = decode($comment_encode);
        if(!$comment_decode) return response_error([],"参数有误，刷新一下试试");

        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->id;
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); },
                'favors'=>function($query) use ($user_id) { $query->where(['type'=>11,'user_id'=>$user_id]); }
            ])->where(['type'=>$type,'item_id'=>$item_id,'dialog_id'=>$comment_decode])
                ->where('reply_id','<>',0);
        }
        else
        {
            $comments = Communication::with([
                'user',
                'reply'=>function($query) { $query->with(['user']); },
            ])->where(['type'=>$type,'item_id'=>$item_id,'dialog_id'=>$comment_decode])
                ->where('reply_id','<>',0);
        }

        if(!empty($post_data['min_id']) && $post_data['min_id'] != 0) $comments->where('id', '<', $post_data['min_id']);

        $comments = $comments->orderBy('id','desc')->paginate(10);

        if(!$comments->isEmpty())
        {
            $return["html"] = view('frontend.'.env('TEMPLATE').'.component.replies')->with("communications",$comments)->__toString();
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


    // 评论点赞
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
            if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试");

            $comment_encode = $post_data['comment_id'];
            $comment_decode = decode($comment_encode);
            if(!$comment_decode) return response_error([],"参数有误，刷新一下试试！");

            $communication_insert['type'] = 11;
            $communication_insert['user_id'] = $me_id;
            $communication_insert['item_id'] = $item_id;
            $communication_insert['reply_id'] = $comment_decode;

            DB::beginTransaction();
            try
            {
                $item = RootItem::find($item_id);
                if(!$item) return response_error([],"该内容不存在，刷新一下试试");

                $comment = Communication::find($comment_decode);
                if(!$comment) return response_error([],"该评论不存在，刷新一下试试！");

                $comment->timestamps = false;
                $comment->increment('favor_num');

                $communication = new Communication;
                $bool = $communication->fill($communication_insert)->save();
                if(!$bool) throw new Exception("insert--communication--fail");

//                通知对方
                if($comment->user_id != $me_id)
                {
                    $notification_insert_1['type'] = 11;
                    $notification_insert_1['sort'] = 12;
                    $notification_insert_1['user_id'] = $comment->user_id;
                    $notification_insert_1['source_id'] = $me_id;
                    $notification_insert_1['item_id'] = $item_id;
                    $notification_insert_1['communication_id'] = $communication->id;
                    $notification_insert_1['reply_id'] = $comment_decode;

                    $notification_1 = new Notification;
                    $bool = $notification_1->fill($notification_insert_1)->save();
                    if(!$bool) throw new Exception("insert--notification--fail");
                }

                if(($item->user_id != $me_id) && ($item->user_id != $comment->user_id))
                {
                    $notification_insert_2['type'] = 11;
                    $notification_insert_2['sort'] = 13;
                    $notification_insert_2['user_id'] = $item->user_id;
                    $notification_insert_2['source_id'] = $me_id;
                    $notification_insert_2['item_id'] = $item_id;
                    $notification_insert_2['communication_id'] = $communication->id;
                    $notification_insert_2['reply_id'] = $comment->id;

                    $notification_2 = new Notification;
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
    // 评论取消赞
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
            if(!is_numeric($item_id)) return response_error([],"参数有误，刷新一下试试");

            $comment_encode = $post_data['comment_id'];
            $comment_decode = decode($comment_encode);
            if(!$comment_decode) return response_error([],"参数有误，刷新一下试试！");

            DB::beginTransaction();
            try
            {
                $comment = Communication::find($comment_decode);
                if(!$comment && $comment->user_id != $me_id) return response_error([],"参数有误，刷新一下试试");
                $comment->decrement('favor_num');

                $favors = Communication::where([
                    'type'=>11,
                    'user_id'=>$me_id,
                    'item_id'=>$item_id,
                    'reply_id'=>$comment_decode
                ]);
                $count = count($favors->get());
                if($count)
                {
                    $num = $favors->delete();
                    if($num != $count) throw new Exception("delete--commnucation--fail");
                }

                DB::commit();
                return response_success();
            }
            catch (Exception $e)
            {
                DB::rollback();
                $msg = '操作失败，请重试！';
//                    $msg = $e->getMessage();
//                    exit($e->getMessage());
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







}