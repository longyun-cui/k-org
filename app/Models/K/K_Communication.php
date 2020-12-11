<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;

class K_Communication extends Model
{
    //
    protected $table = "communication";
    protected $fillable = [
        'active', 'communication_category', 'communication_type', 'category', 'type', 'sort',
        'owner_id', 'user_id', 'belong_id', 'source_id',
        'item_id', 'communication_id', 'reply_id', 'dialog_id', 'order',
        'title', 'description', 'ps', 'content',
        'is_anonymous', 'is_shared',
        'support',
        'favor_num', 'comment_num'
    ];
    protected $dateFormat = 'U';


    // 管理员
    function user()
    {
        return $this->belongsTo('App\Models\K\K_User','user_id','id');
    }

    // 课程
    function item()
    {
        return $this->belongsTo('App\Models\K\K_Item','item_id','id');
    }

    //
    function chapter()
    {
        return $this->belongsTo('App\Models\Content','content_id','id');
    }

    // 父节点
    function reply()
    {
        return $this->belongsTo('App\Models\K\K_Communication','reply_id','id');
    }

    // 子节点
    function children()
    {
        return $this->hasMany('App\Models\K\K_Communication','reply_id','id');
    }

    // 对话
    function dialogs()
    {
        return $this->hasMany('App\Models\K\K_Communication','dialog_id','id');
    }

    // 点赞
    function favors()
    {
        return $this->hasMany('App\Models\K\K_Communication','reply_id','id');
    }

    /**
     * 获得此人的所有标签。
     */
    public function tags()
    {
        return $this->morphToMany('App\Models\Tag', 'taggable');
    }




}
