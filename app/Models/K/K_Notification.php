<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class K_Notification extends Model
{
    use SoftDeletes;
    //
    protected $table = "notification";
    protected $fillable = [
        'active', 'status', 'category', 'type', 'form', 'sort', 'notification_category', 'notification_type',
        'is_read',
        'owner_id', 'creator_id', 'user_id', 'belong_id', 'source_id', 'object_id', 'parent_id',
        'item_id', 'communication_id', 'reply_id',
        'title', 'description', 'ps', 'content'
    ];
    protected $dateFormat = 'U';


    // 拥有者
    function owner()
    {
        return $this->belongsTo('App\Models\K\K_User','owner_id','id');
    }
    // 创作者
    function creator()
    {
        return $this->belongsTo('App\Models\K\K_User','creator_id','id');
    }
    // 用户
    function user_er()
    {
        return $this->belongsTo('App\Models\K\K_User','user_id','id');
    }
    // 归属
    function belong_er()
    {
        return $this->belongsTo('App\Models\K\K_User','belong_id','id');
    }
    // 来源
    function source_er()
    {
        return $this->belongsTo('App\Models\K\K_User','source_id','id');
    }
    // 对象
    function object_er()
    {
        return $this->belongsTo('App\Models\K\K_User','object_id','id');
    }


    // 内容
    function item()
    {
        return $this->belongsTo('App\Models\K\K_Item','item_id','id');
    }

    function communication()
    {
        return $this->belongsTo('App\Models\K\K_Communication','communication_id','id');
    }

    function reply()
    {
        return $this->belongsTo('App\Models\K\K_Communication','reply_id','id');
    }


}
