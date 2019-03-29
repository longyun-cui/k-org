<?php
namespace App\Models\Org;
use Illuminate\Database\Eloquent\Model;

class OrgOrganization extends Model
{
    //
    protected $table = "cxz_organization";
    protected $fillable = [
        'sort', 'type', 'name', 'website_name', 'short', 'slogan', 'description', 'logo',
        'address', 'telephone', 'email', 'qq', 'wechat', 'wechat_id', 'wechat_qrcode', 'weibo', 'weibo_name', 'weibo_address'
    ];
    protected $dateFormat = 'U';

    function administrators()
    {
        return $this->hasMany('App\Models\Org\OrgAdministrator','org_id','id');
    }

    function ext()
    {
        return $this->hasOne('App\Models\Org\OrgOrganizationExt','org_id','id');
    }

    function modules()
    {
        return $this->hasMany('App\Models\Org\OrgModule','org_id','id');
    }

    function menus()
    {
        return $this->hasMany('App\Models\Org\OrgMenu','org_id','id');
    }

    function items()
    {
        return $this->hasMany('App\Models\Org\OrgItem','org_id','id');
    }

    function root_items()
    {
        return $this->hasMany('App\Models\RootItem','org_id','id');
    }



}
