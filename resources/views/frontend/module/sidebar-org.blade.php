{{--<!-- START: module -->--}}
<div class="main-side-block main-side-container text-center">
    <ul>
        <div class="side-info">
            <a href="{{ url('/org/'.$data->id) }}">
                <div class="box-body main-side-hover">
                    <img src="{{ url(env('DOMAIN_CDN').'/'.$data->logo) }}" alt="">
                </div>
            </a>
        </div>

        <li class="{{ $org_mine_active or '' }}">
            <a href="{{ url('/org/'.$data->id) }}">
                <div class="box-body main-side-hover row-ellipsis" title="{{ $data->name or '' }}">
                    {{ $data->name or '' }}
                </div>
            </a>
        </li>

        <li class="{{ $org_introduce_active or '' }}">
            <a href="{{ url('/org/'.$data->id.'/introduce') }}">
                <div class="box-body main-side-hover">
                    介绍
                </div>
            </a>
        </li>

        <li class="{{ $org_relation_follow_active or '' }} _none">
            <a href="{{ url('/org/'.$data->id.'/follow') }}">
                <div class="box-body main-side-hover">
                    <i class="fa fa-calendar-plus-o _none"></i> 关注 {{ $data->follow_num or 0 }}
                </div>
            </a>
        </li>

        <li class="{{ $org_relation_fans_active or '' }} _none">
            <a href="{{ url('/org/'.$data->id.'/fans') }}">
                <div class="box-body main-side-hover">
                    <i class="fa fa-calendar-plus-o _none"></i> 粉丝 {{ $data->fans_num or 0 }}
                </div>
            </a>
        </li>

        <br>

        <li class="{{ $org_root_active or '' }}">
            <a href="{{ url('/org/'.$data->id) }}">
                <div class="box-body main-side-hover">
                    主页
                </div>
            </a>
        </li>
        <li class="{{ $org_activity_active or '' }}">
            <a href="{{ url('/org/'.$data->id.'/item-list?category=activity') }}">
                <div class="box-body main-side-hover">
                    活动
                </div>
            </a>
        </li>
        <li class="{{ $org_article_active or '' }}">
            <a href="{{ url('/org/'.$data->id.'/item-list?category=article') }}">
                <div class="box-body main-side-hover">
                    文章
                </div>
            </a>
        </li>

        <br>

        <li class="{{ $org_sponsor_active or '' }}">
            <a href="{{ url('/org/'.$data->id.'/item-list?category=sponsor') }}">
                <div class="box-body main-side-hover">
                    赞助商
                </div>
            </a>
        </li>
    </ul>
</div>
{{--<!-- END: module -->--}}