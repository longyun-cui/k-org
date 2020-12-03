{{--<!-- Main Header -->--}}
<header class="main-header">

    <!-- Logo -->
    <a href="{{url('/')}}" class="logo" style="display:none;background-color:#222d32;">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>K-ORG</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>朝鲜族组织活动网</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation" style="margin-left:0;background-color:#1a2226;">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle visible-xs" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu" style="height:50px;float:left;">
            <a href="{{url('/')}}">
                <span class="logo-big">
                    <img src="/favicon_transparent.png" class="img-icon" alt="Image"> <b>朝鲜族组织活动网</b>
                </span>
            </a>
        </div>

        <div class="navbar-custom-menu visible-sm-" style="display:none;float:left;">
            <ul class="nav navbar-nav">

                <li><a href="{{url('/')}}"> 平台首页</a></li>

                <li><a href="{{url('/?type=activity')}}"> 活动</a></li>

                <li><a href="{{url('/organization-list')}}"> 组织</a></li>

            </ul>
        </div>



        <div class="header-logo" >
            <span class="logo-lg"><b>@yield('header_title')</b></span>
        </div>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">

            <div class="navbar-custom-menu" style="height:50px;float:left;">
                @if(!Auth::check())
                <a href="{{url('/')}}">
                    <span class="logo-big">
                        <img src="{{ url(env('DOMAIN_CDN').'/'.Auth::user()->portrait_img) }}" class="img-icon" alt="Image">
                        <b>{{ Auth::user()->username }}</b>
                    </span>
                </a>
                @else
                    <a href="{{ url('https://open.weixin.qq.com/connect/qrconnect?appid=wxc08005678d8d8736&redirect_uri=http%3A%2F%2Fk-org.cn%2Fweixin%2Flogin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect') }}">
                        <i class="fa fa-sign-in" style="width:20px;"></i>
                        <span>登录</span>
                    </a>
                @endif
            </div>

            <ul class="nav navbar-nav hidden-xs hidden-sm">

                @if(!Auth::check())
                    <li class="visible-sm-">
                        <a href="{{ url('https://open.weixin.qq.com/connect/qrconnect?appid=wxc08005678d8d8736&redirect_uri=http%3A%2F%2Fk-org.cn%2Fweixin%2Flogin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect') }}">
                            <i class="fa fa-sign-in" style="width:20px;"></i>
                            <span>登录</span>
                        </a>
                    </li>
                    {{--<li class="visible-sm" style="display:none;">--}}
                    {{--<a href="{{url('/register')}}"><i class="fa fa-circle-o"></i> <span> 注册</span></a>--}}
                    {{--</li>--}}
                @else
                    <li class="visible-sm- visible-xs- _none">
                        <a href="{{url('/')}}">
                            {{--<i class="fa fa-user text-default" style="width:20px;"></i>--}}
                            <img src="{{ url(env('DOMAIN_CDN').'/'.Auth::user()->portrait_img) }}" class="img-icon" alt="Image">
                            <span>{{ Auth::user()->username }}</span>
                        </a>
                    </li>
                    <li class="visible-sm- visible-xs-">
                        <a href="{{url('/logout')}}">
                            <i class="fa fa-sign-out text-default" style="width:16px;"></i>
                            <span>退出</span>
                        </a>
                    </li>
                @endif

                <li class="dropdown notifications-menu _none-">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-list" style="width:20px;"></i>
                        {{--<span class="label label-warning">10</span>--}}
                    </a>
                    <ul class="dropdown-menu">
                        {{--<li class="header">You have 10 notifications</li>--}}
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                @if(!Auth::check())
                                    <li>
                                        <a href="{{ url('/login') }}">
                                            <i class="fa fa-sign-in"></i>
                                            <span>登录</span>
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ url('/home') }}">
                                            <i class="fa fa-home text-default" style="width:16px;"></i>
                                            <span>{{ Auth::user()->username }}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/logout') }}">
                                            <i class="fa fa-sign-out text-default" style="width:16px;"></i>
                                            <span>退出</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        {{--<li class="footer"><a href="#">View all</a></li>--}}
                    </ul>
                </li>


                <!-- Control Sidebar Toggle Button -->
                <li style="display:none;">
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>