<div class="box-body bg-white margin-bottom-4px right-home">

    @if(!Auth::check())
        <a href="{{ url('https://open.weixin.qq.com/connect/qrconnect?appid=wxc08005678d8d8736&redirect_uri=http%3A%2F%2Fk-org.cn%2Fweixin%2Flogin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect') }}">
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
        <div class="box-body">
            <i class="fa fa-home text-blue" style="width:24px;"></i><span>{{ Auth::user()->username }}</span>
        </div>
        {{--</a>--}}
        <a href="{{url('/my-follow')}}">
            <div class="box-body {{ $sidebar_menu_my_follow_active or '' }}">
                <i class="fa fa-heart text-blue" style="width:24px;"></i><span>我的关注</span>
            </div>
        </a>
        <a href="{{url('/my-favor')}}">
            <div class="box-body {{ $sidebar_menu_my_favor_active or '' }}">
                <i class="fa fa-heart text-blue" style="width:24px;"></i><span>我的收藏</span>
            </div>
        </a>
    @endif

</div>