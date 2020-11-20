<?php
namespace App\Models\K;
use Illuminate\Database\Eloquent\Model;

class K_Notification extends Model
{
    //
    protected $table = "notification";
    protected $fillable = [
        'category', 'type', 'sort', 'is_read',
        'owner_id', 'user_id', 'source_id', 'item_id', 'communication_id', 'reply_id', 'content', 'ps'
    ];
    protected $dateFormat = 'U';

    function user()
    {
        return $this->belongsTo('App\Models\K\K_User','user_id','id');
    }

    function source()
    {
        return $this->belongsTo('App\Models\K\K_User','source_id','id');
    }

    function item()
    {
        return $this->belongsTo('App\Models\RootItem','item_id','id');
    }

    function communication()
    {
        return $this->belongsTo('App\Models\Communication','communication_id','id');
    }

    function reply()
    {
        return $this->belongsTo('App\Models\Communication','reply_id','id');
    }


}
