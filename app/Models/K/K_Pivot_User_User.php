<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;

class K_Pivot_User_User extends Model
{
    //
    protected $table = "pivot_user_user";
    protected $fillable = [
        'sort', 'type', 'user_1_id', 'user_2_id', 'content_id'
    ];
    protected $dateFormat = 'U';


    // 用户
    function user_1()
    {
        return $this->belongsTo('App\Models\K\K_User','user_1_id','id');
    }

    // ITEM
    function user_2()
    {
        return $this->belongsTo('App\Models\K\K_User','user_2_id','id');
    }


}
