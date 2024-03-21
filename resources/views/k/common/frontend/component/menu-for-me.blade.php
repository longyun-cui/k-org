<div class="box-body bg-white margin-bottom-4px right-home">

    @if(!Auth::check())
        <a href="{{ url('/') }}">
            <div class="box-body">
                <i class="fa fa-sign-in text-blue" style="width:24px;"></i><span>登录</span>
            </div>
        </a>
        {{--<a href="{{url('/register')}}">--}}
        {{--<div class="box-body {{ $menu_anonymous or '' }}">--}}
        {{--<i class="fa fa-circle-o text-blue"></i> <span>&nbsp; 注册</span>--}}
        {{--</div>--}}
        {{--</a>--}}
    @else
        {{--<a href="{{url('/home')}}">--}}
        <div class="box-body _none">
            <i class="fa fa-home text-blue" style="width:24px;"></i><span>{{ Auth::user()->username }}</span>
        </div>
        {{--</a>--}}
        <a href="{{ url('/') }}">
            <div class="box-body {{ $menu_active_for_root or '' }}">
                <i class="fa fa-list text-orange" style="width:24px;"></i>
                <span>平台首页</span>
            </div>
        </a>
        <a href="{{ url('/mine/my-card-index') }}">
            <div class="box-body {{ $menu_active_for_my_card or '' }}">
                <i class="fa fa-user text-red" style="width:24px;"></i><span>我的名片</span>
            </div>
        </a>
        <a href="{{ url('/mine/my-follow') }}">
            <div class="box-body {{ $menu_active_for_my_follow or '' }}">
                <i class="fa fa-user text-red" style="width:24px;"></i><span>我的关注</span>
            </div>
        </a>
        <a href="{{ url('/mine/my-fans') }}">
            <div class="box-body {{ $menu_active_for_my_fans or '' }}">
                <i class="fa fa-user text-red" style="width:24px;"></i><span>我的粉丝</span>
            </div>
        </a>
        <a href="{{ url('/mine/my-favor') }}">
            <div class="box-body {{ $menu_active_for_my_favor or '' }}">
                <i class="fa fa-heart text-red" style="width:24px;"></i><span>我的点赞</span>
            </div>
        </a>
        <a href="{{ url('/mine/my-collection') }}">
            <div class="box-body {{ $menu_active_for_my_collection or '' }}">
                <i class="fa fa-star text-red" style="width:24px;"></i><span>我的收藏</span>
            </div>
        </a>
        <a href="{{ url('/mine/my-notification') }}">
            <div class="box-body {{ $menu_active_for_my_notification or '' }}">
                <i class="fa fa-bell text-red" style="width:24px;"></i><span>消息通知</span>
            </div>
        </a>
    @endif

</div>