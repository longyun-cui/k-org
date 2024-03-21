<div class="box-body bg-white margin-bottom-4px right-menu">

    <a href="{{ url('/') }}">
        <div class="box-body {{ $menu_active_for_root or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i>
            <span>平台首页</span>
        </div>
    </a>

    <a href="{{ url('/?type=activity') }}" class="_none">
        <div class="box-body {{ $menu_active_for_activity or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i>
            <span>只看活动</span>
        </div>
    </a>

    <a href="{{ url('/organization-list') }}" class="_none">
        <div class="box-body {{ $menu_active_for_organization_list or '' }}">
            <i class="fa fa-list text-orange" style="width:24px;"></i>
            <span>只看组织</span>
        </div>
    </a>

</div>