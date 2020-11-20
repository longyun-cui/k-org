<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;

class K_Item extends Model
{
    //
    protected $table = "item";
    protected $fillable = [
        'active', 'status', 'item_category', 'item_type', 'category', 'type', 'sort',
        'owner_id', 'creator_id', 'org_id', 'admin_id', 'user_id', 'p_id', 'parent_id',
        'item_id', 'menu_id',
        'name', 'title', 'subtitle', 'description', 'content', 'custom', 'custom2', 'custom3',
        'link_url', 'cover_pic', 'attachment_name', 'attachment_src',
        'time_point', 'time_type', 'start_time', 'end_time',
        'visit_num', 'share_num', 'favor_num', 'comment_num'
    ];
    protected $dateFormat = 'U';

    protected $dates = ['created_at','updated_at'];
//    public function getDates()
//    {
////        return array(); // 原形返回；
//        return array('created_at','updated_at');
//    }




    function owner()
    {
        return $this->belongsTo('App\Models\K\K_User','owner_id','id');
    }

    function creator()
    {
        return $this->belongsTo('App\Models\K\K_User','creator_id','id');
    }

    function user()
    {
        return $this->belongsTo('App\Models\K\K_User','user_id','id');
    }


    // 一对多 关联的目录
    function menu()
    {
        return $this->belongsTo('App\Models\K\K_Menu','menu_id','id');
    }

    // 多对多 关联的目录
    function menus()
    {
        return $this->belongsToMany('App\Models\K\K_Menu','pivot_menu_item','item_id','menu_id');
    }


    /**
     * 获得此文章的所有评论。
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'itemable');
    }

    /**
     * 获得此文章的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }
}
