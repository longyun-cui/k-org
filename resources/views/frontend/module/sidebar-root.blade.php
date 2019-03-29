{{--<!-- START: module -->--}}
<div class="main-side-block main-side-container text-center">
    <ul>
        <div class="side-info">
            <a href="{{ url('/') }}">
                <div class="box-body main-side-hover">
                    <img src="{{ url('/favicon.png') }}" alt="朝鲜族">
                </div>
            </a>
        </div>

        <li class="{{ $root_active or '' }}">
            <a href="{{ url('/') }}">
                <div class="box-body main-side-hover row-ellipsis">
                    平台首页
                </div>
            </a>
        </li>

        <br>

        <li class="{{ $root_all_active or '' }}">
            <a href="{{ url('/item-list/') }}">
                <div class="box-body main-side-hover">
                    全部内容
                </div>
            </a>
        </li>
        <li class="{{ $root_activity_active or '' }}">
            <a href="{{ url('/item-list?category=activity') }}">
                <div class="box-body main-side-hover">
                    活动
                </div>
            </a>
        </li>
        <li class="{{ $root_article_active or '' }}">
            <a href="{{ url('/item-list?category=article') }}">
                <div class="box-body main-side-hover">
                    文章
                </div>
            </a>
        </li>

        <br>

        <li class="{{ $root_sponsor_active or '' }}">
            <a href="{{ url('/item-list?category=sponsor') }}">
                <div class="box-body main-side-hover">
                    赞助商
                </div>
            </a>
        </li>
    </ul>
</div>
{{--<!-- END: module -->--}}