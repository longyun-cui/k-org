<footer class="aui-footer row-before">

    <a class="aui-footer-list {{ $menu_active_by_home or '' }}" href="{{ url('/') }}">
        <div class="">
            <i class="fa fa-home"></i>
            <p class="mt-1">首页</p>
        </div>
    </a>

    <a class="aui-footer-list {{ $menu_active_by_organization or '' }}" href="{{ url('/organization-list') }}">
        <div class="">
            <i class="fa fa-sitemap"></i>
            <p class="mt-1">社群</p>
        </div>
    </a>

    <a class="aui-footer-list {{ $menu_active_by_activity or '' }} _none" href="{{ url('/?type=activity') }}">
        <div class="">
            <i class="fa fa-clock-o"></i>
            <p class="mt-1">活动</p>
        </div>
    </a>

    <a class="aui-footer-list {{ $menu_active_by_discovery or '' }} _none" href="{{ url('/discovery') }}">
        <div class="">
            <i class="fa fa-safari"></i>
            <p class="mt-1">发现</p>
        </div>
    </a>

    <div class="aui-footer-list position-relative publishAction justify-content-center _none">
        <div class="publishBtn" onclick="">
            <i class="fa fa-plus "></i>
        </div>
    </div>

    <a class="aui-footer-list {{ $menu_active_for_my_follow or '' }}" href="{{ url('/mine/my-follow') }}">
        <div class="">
            <i class="fa fa-cc"></i>
            <p class="mt-1">名片夹</p>
        </div>
    </a>

    <div class="aui-footer-list dropUp">
        <a class="dropUp-toggle" data-toggle="dropUp">
            <i class="fa fa-user"></i>
            <p class="mt-1">我的</p>
        </a>

        <ul class="aui-footer-menu-list dropUp-menu">
            <li class="_none">
                <a href="{{ url('/mine/my-follow') }}">
                    <i class="fa fa-cc text-red" style="width:16px;margin-right:4px;text-align:right;"></i>
                    <span>名片夹</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('/mine/my-fans') }}">
                    <i class="fa fa-user text-red" style="width:16px;margin-right:4px;text-align:right;"></i>
                    <span>我的粉丝</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('/mine/my-favor') }}">
                    <i class="fa fa-heart text-red" style="width:16px;margin-right:4px;text-align:right;"></i>
                    <span>我的点赞</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('/mine/my-collection') }}">
                    <i class="fa fa-star text-red" style="width:16px;margin-right:4px;text-align:right;"></i>
                    <span>我的收藏</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('/mine/my-organization') }}">
                    <i class="fa fa-sitemap text-red" style="width:16px;margin-right:4px;text-align:right;"></i>
                    <span>我的社群组织</span>
                </a>
            </li>
            @if($auth_check)
            <li class="">
                <a href="{{ url('/user/'.$me->id) }}">
                    <i class="fa fa-info text-red" style="width:16px;margin-right:4px;text-align:right;"></i>
                    <span>我的主页</span>
                </a>
            </li>
            @endif
        </ul>

    </div>

</footer>


<style>
    .aui-footer {
        width: 100vw;
        height: 56px;
        border-top:1px solid #888;
        background: #FFF;
        display: flex;
        justify-content: space-around;
        white-space: nowrap;
        position: fixed !important;
        bottom: 0;
        left: 0;
        z-index: 997;
    }
    .aui-footer-list {
        padding: 3px 20px;
        max-width: 25%;
        text-align: center;
        flex: 1;
        box-sizing: border-box;
        display: inline-block;
    }
    .aui-footer-list i {
        width: 100%;
        height: 30px;
        line-height: 30px;
        display: inline-block;
        font-size: 20px;
        background: #AAAAAA;
        -webkit-background-clip: text;
        color: transparent;
    }
    .aui-footer-list p {
        width: 100%;
        height: 20px;
        line-height: 12px;
        font-size: 12px;
        background: #AAAAAA;
        -webkit-background-clip: text;
        color: transparent;
    }
    .aui-footer-list.active i, .aui-footer-list.active p {
        color: #a68ad4;
    }
    .aui-footer-list.active i, .aui-footer-list.active p {
        background: #197DE0;
        -webkit-background-clip: text;
        color: transparent;
    }
    .publishBtn {
        background: #a68ad4;
        position: absolute;
        width: 50px;
        height: 50px;
        line-height: 50px;
        border-radius: 50%;
        color: #fff;
        top: -5px;
        left: 18px;
        border: 4px solid #fff;
        box-shadow: 0 0 2px #eee;
    }
    .position-relative {
        position: relative !important;
    }
    .justify-content-center {
        -ms-flex-pack: center !important;
        justify-content: center !important;
    }
    .aui-footer-list .publishBtn i {
        color: #fff !important;
    }

    .aui-footer-menu-list {
        display: none;
        width: auto;
        height: auto;
        min-width: 160px;
        /*border:1px solid #888;*/
        box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.5);
        background: #fff;
        /*display: flex;*/
        justify-content: space-around;
        white-space: nowrap;
        position: fixed !important;
        bottom: 56px;
        right: 2px;
        z-index: 997;
        margin-bottom:0;
        border-radius: 4px;
    }
    .aui-footer-menu-list li {
        list-style: none;
        width:100%;
        float:left;
        clear:both;
    }
    .aui-footer-menu-list>li>a {
        display: block;
        padding: 8px 16px;
        border-bottom: 1px solid #f4f4f4;
        color: #444444;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .aui-footer-menu-list i {
        font-size: 13px;
    }
    .aui-footer-list.open .aui-footer-menu-list {
        display: block;
    }
</style>