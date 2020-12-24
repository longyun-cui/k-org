<div class="box-body bg-white margin-bottom-4px right-menu hidden-xs hidden-sm">

    <a href="{{ url('/user/'.$data->id) }}">
        <div class="box-body padding-8px {{ $sidebar_menu_root_active or '' }}">
            <i class="fa fa-home text-orange"></i>
            <span>Ta的主页</span>
        </div>
    </a>

    {{--<a href="{{ url('/user/'.$data->id.'/introduction') }}">--}}
        {{--<div class="box-body padding-8px {{ $sidebar_menu_introduction_active or '' }}">--}}
            {{--<i class="fa fa-dashboard text-orange"></i>--}}
            {{--<span>图文介绍</span>--}}
        {{--</div>--}}
    {{--</a>--}}

    <a href="{{ url('/user/'.$data->id.'?type=introduction') }}">
        <div class="box-body padding-8px {{ $sidebar_menu_introduction_active or '' }}">
            <i class="fa fa-dashboard text-orange"></i>
            <span>图文介绍</span>
        </div>
    </a>

    @if($data->user_type == 11)
    <a href="{{ url('/user/'.$data->id.'?type=article') }}">
        <div class="box-body padding-8px {{ $sidebar_menu_article_active or '' }}">
            <i class="fa fa-file text-orange"></i>
            <span>文章</span>
            <span class="margin-left-8px pull-right-">{{ $data->article_count or 0 }}</span>
        </div>
    </a>

    <a href="{{ url('/user/'.$data->id.'?type=activity') }}">
        <div class="box-body padding-8px {{ $sidebar_menu_activity_active or '' }}">
            <i class="fa fa-calendar text-orange"></i>
            <span>活动</span>
            <span class="margin-left-8px pull-right-">{{ $data->activity_count or 0 }}</span>
        </div>
    </a>
    @endif

    @if($data->user_type == 88)
    <a href="{{ url('/user/'.$data->id.'?type=org') }}">
        <div class="box-body padding-8px {{ $sidebar_menu_org_active or '' }}">
            <i class="fa fa-list text-orange"></i>
            <span>赞助组织</span>
            <span class="margin-left-8px pull-right-">{{ $data->pivot_org_count or 0 }}</span>
        </div>
    </a>
    @endif

</div>