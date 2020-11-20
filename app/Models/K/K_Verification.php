<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;

class K_Verification extends Model
{
    //
    protected $table = "verification";
    protected $fillable = [
        'category', 'type', 'sort',
        'owner_id', 'user_id', 'email', 'mobile', 'code'
    ];
    protected $dateFormat = 'U';

    function user()
    {
        return $this->belongsTo('App\Models\K\K_User','user_id','id');
    }


}
