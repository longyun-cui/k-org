<div class="box-body bg-white margin-bottom-8px right-menu">

    <a href="{{url('/')}}">
        <div class="box-body {{ $sidebar_menu_root_active or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i><span>首页</span>
        </div>
    </a>

    <a href="{{url('/?type=activity')}}">
        <div class="box-body {{ $sidebar_menu_activity_active or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i><span>活动</span>
        </div>
    </a>

    <a href="{{url('/organization-list')}}">
        <div class="box-body {{ $sidebar_menu_organization_list_active or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i><span>组织</span>
        </div>
    </a>

</div>