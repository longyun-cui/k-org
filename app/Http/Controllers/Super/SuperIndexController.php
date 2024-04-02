<?php
namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\K\K_User;
use App\Models\K\K_Item;

use App\Repositories\Super\SuperIndexRepository;

use Response, Auth, Validator, DB, Exception, Cache, Blade, Carbon;
use QrCode, Excel;

class SuperIndexController extends Controller
{
    //
    private $repo;

    public function __construct()
    {
        $this->repo = new SuperIndexRepository;
    }


    // 返回【主页】视图
    public function view_index()
    {
        return view(env('TEMPLATE_K_SUPER').'welcome');
    }




}
