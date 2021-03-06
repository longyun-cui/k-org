<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "user";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'email', 'wx_unionid', 'nickname', 'true_name', 'description', 'portrait_img', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'wx_unionid', 'remember_token',
    ];

    protected $dateFormat = 'U';


    // 内容
    function items()
    {
        return $this->hasMany('App\Models\RootItem','user_id','id');
    }

    // 收藏
    function pivot_collection()
    {
        return $this->belongsToMany('App\Models\RootItem','pivot_user_collection','user_id','item_id');
    }

    // 与我相关的内容
    function pivot_item()
    {
        return $this->belongsToMany('App\Models\RootItem','pivot_user_item','user_id','item_id')
            ->withPivot('type')->withTimestamps();
    }

    public function relation_items()
    {
        // doc eg.
//        return $this->hasManyThrough('App\Post', 'App\User');
//        return $this->hasManyThrough(
//            'App\Post',
//            'App\User',
//            'country_id', // 用户表外键...
//            'user_id', // 文章表外键...
//            'id', // 国家表本地键...
//            'id' // 用户表本地键...
//        );

        return $this->hasManyThrough(
            'App\Models\RootItem',
            'App\Models\Pivot_User_Relation',
            'mine_user_id', // (中间表)外键...
            'user_id', // (目标表)外键...
            'id', // (主表)本地键...
            'relation_user_id' // (中间表)本地键...
        );
    }


}
