{{--<!-- START: module -->--}}
<div class="main-side-block main-side-container text-center">
    <ul>
        <div class="side-info">
            <a href="{{ url('/org/'.$item->org->id) }}">
                <div class="box-body main-side-hover">
                    <img src="{{ url(env('DOMAIN_CDN').'/'.$item->org->logo) }}" alt="">
                </div>
            </a>
        </div>

        <li class="{{ $item_mine_active or '' }}">
            <a href="{{ url('/org/'.$item->org->id) }}" title="{{ $item->org->nickname or '' }}">
                <div class="box-body main-side-hover row-ellipsis">
                    {{ $item->org->name or '' }}
                </div>
            </a>
        </li>

        <li class="{{ $item_relation_follower_active or '' }} _none">
            <a href="{{ url('/org/'.$item->org->id.'/follow') }}">
                <div class="box-body main-side-hover">
                    <i class="fa fa-calendar-plus-o _none"></i> 关注 {{ $item->org->follower_num or 0 }}
                </div>
            </a>
        </li>

        <li class="{{ $item_relation_fans_active or '' }} _none">
            <a href="{{ url('/org/'.$item->org->id.'/fans') }}">
                <div class="box-body main-side-hover">
                    <i class="fa fa-calendar-plus-o _none"></i> 粉丝 {{ $item->org->fans_num or 0 }}
                </div>
            </a>
        </li>

        <br>

        <li class="{{ $item_root_active or '' }}">
            <a href="{{ url('/org/'.$item->org->id) }}">
                <div class="box-body main-side-hover">
                    主页
                </div>
            </a>
        </li>
        <li class="{{ $item_activity_active or '' }}">
            <a href="{{ url('/org/'.$item->org->id.'/item-list?category=activity') }}">
                <div class="box-body main-side-hover">
                    活动
                </div>
            </a>
        </li>
        <li class="{{ $item_article_active or '' }}">
            <a href="{{ url('/org/'.$item->org->id.'/item-list?category=article') }}">
                <div class="box-body main-side-hover">
                    文章
                </div>
            </a>
        </li>

        <br>

        <li class="{{ $item_sponsor_active or '' }}">
            <a href="{{ url('/org/'.$item->org->id.'/item-list?category=sponsor') }}">
                <div class="box-body main-side-hover">
                    赞助商
                </div>
            </a>
        </li>
    </ul>
</div>
{{--<!-- END: module -->--}}