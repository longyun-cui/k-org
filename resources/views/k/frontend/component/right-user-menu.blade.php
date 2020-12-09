<div class="box-body bg-white margin-bottom-4px right-menu hidden-xs hidden-sm">

    <a href="{{ url('/user/'.$data->id) }}">
        <div class="padding-4px box-body {{ $sidebar_menu_root_active or '' }}">
            <i class="fa fa-list text-orange"></i>
            <span>主页</span>
        </div>
    </a>

    <a href="{{ url('/user/'.$data->id.'?type=article') }}">
        <div class="padding-4px box-body {{ $sidebar_menu_article_active or '' }}">
            <i class="fa fa-list text-orange"></i>
            <span>文章</span>
            <span class="margin-left-8px pull-right-">{{ $data->article_count or 0 }}</span>
        </div>
    </a>

    <a href="{{ url('/user/'.$data->id.'?type=activity') }}">
        <div class="padding-4px box-body {{ $sidebar_menu_activity_active or '' }}">
            <i class="fa fa-list text-orange"></i>
            <span>活动</span>
            <span class="margin-left-8px pull-right-">{{ $data->activity_count or 0 }}</span>
        </div>
    </a>

</div>