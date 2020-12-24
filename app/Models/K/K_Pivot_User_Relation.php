<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;

class K_Pivot_User_Relation extends Model
{
    //
    protected $table = "pivot_user_relation";
    protected $fillable = [
        'active', 'category', 'type', 'relation_active', 'relation_category', 'relation_type', 'relation',
        'mine_user_id', 'relation_user_id'
    ];
    protected $dateFormat = 'U';


    // 用户
    function belong_user()
    {
        return $this->belongsTo('App\Models\K\K_User','belong_user_id','id');
    }

    // 用户
    function mine_user()
    {
        return $this->belongsTo('App\Models\K\K_User','mine_user_id','id');
    }

    // 用户
    function relation_user()
    {
        return $this->belongsTo('App\Models\K\K_User','relation_user_id','id');
    }

    // 关联人
    function relations()
    {
        return $this->hasMany('App\Models\K\K_User','relation_user_id','id');
    }


}
