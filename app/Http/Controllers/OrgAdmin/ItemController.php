<?php

namespace App\Http\Controllers\OrgAdmin;

use function foo\func;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\Common\CommonRepository;
use App\Repositories\OrgAdmin\ItemRepository;


class ItemController extends Controller
{
    //
    private $repo;
    public function __construct()
    {
        $this->repo = new ItemRepository;
    }


    public function root()
    {
        return $this->repo->root();
    }

    // 列表
    public function viewList()
    {
        if(request()->isMethod('get'))
        {

            $category = request('category','all');
            if($category == "all") view()->share('menu_all_list_active', 'active');
            else if($category == "article") view()->share('menu_article_list_active', 'active');
            else if($category == "activity") view()->share('menu_activity_list_active', 'active');
            else view()->share('menu_all_list_active', 'active');

            return view('org-admin.entrance.item-list');
        }
        else if(request()->isMethod('post')) return $this->repo->get_list_datatable(request()->all());
    }

    // 创建
    public function createAction()
    {
        return $this->repo->view_create();
    }

    // 编辑
    public function editAction()
    {
        if(request()->isMethod('get')) return $this->repo->view_edit();
        else if (request()->isMethod('post')) return $this->repo->save(request()->all());
    }

    // 【删除】
    public function deleteAction()
    {
        return $this->repo->delete(request()->all());
    }

    // 【删除】
    public function shareAction()
    {
        return $this->repo->share(request()->all());
    }

    // 【启用】
    public function enableAction()
    {
        return $this->repo->enable(request()->all());
    }

    // 【禁用】
    public function disableAction()
    {
        return $this->repo->disable(request()->all());
    }




    // 【select2】
    public function select2_menus()
    {
        return $this->repo->select2_menus(request()->all());
    }




}
