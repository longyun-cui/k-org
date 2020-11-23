<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;

class K_Pivot_User_Item extends Model
{
    //
    protected $table = "pivot_user_item";
    protected $fillable = [
        'sort', 'type', 'user_id', 'item_id'
    ];
    protected $dateFormat = 'U';


    // 用户
    function user()
    {
        return $this->belongsTo('App\Models\K\K_User','user_id','id');
    }

    // ITEM
    function item()
    {
        return $this->belongsTo('App\Models\K\K_Item','item_id','id');
    }


}
