<div class="box-body bg-white margin-bottom-4px right-menu">

    <a href="{{ url('/org') }}">
        <div class="box-body {{ $menu_active_for_root or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i>
            <span>内容管理</span>
        </div>
    </a>

    <a href="{{ url('/org/mine/my-advertising-list') }}">
        <div class="box-body {{ $menu_active_for_my_advertising or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i>
            <span>我的广告</span>
        </div>
    </a>

    <a href="{{ url('/org/mine/user/my-fans-list') }}">
        <div class="box-body {{ $menu_active_for_my_fans_list or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i>
            <span>我的粉丝</span>
        </div>
    </a>

    <a href="{{ url('/org/mine/user/my-sponsor-list') }}">
        <div class="box-body {{ $menu_active_for_my_sponsor_list or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i>
            <span>赞助商</span>
        </div>
    </a>

</div>