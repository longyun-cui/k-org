<ul class="sidebar-menu">

    <li class="header">目录</li>

    <li class="treeview {{ $sidebar_menu_root_active or '' }}">
        <a href="{{url('/')}}">
            <i class="fa fa-list text-orange"></i>
            <span>平台首页</span>
        </a>
    </li>

    <li class="treeview {{ $sidebar_menu_activity_active or '' }}">
        <a href="{{url('/?type=activity')}}">
            <i class="fa fa-list text-orange"></i>
            <span>活动</span>
        </a>
    </li>

    <li class="treeview {{ $sidebar_menu_organization_active or '' }}">
        <a href="{{url('/organization-list')}}">
            <i class="fa fa-list text-orange"></i>
            <span>组织</span>
        </a>
    </li>

    <li class="header">Home</li>

    @if(!Auth::check())

        <li class="treeview">
            <a href="{{ url('/login-link') }}">
                <i class="fa fa-circle-o"></i>
                <span>登录</span>
            </a>
        </li>
        <li class="treeview _none">
            <a href="{{ url('/register') }}">
                <i class="fa fa-circle-o"></i>
                <span>注册</span>
            </a>
        </li>
    @else
        <li class="treeview">
            <a href="{{ url('/home') }}">
                <i class="fa fa-home text-orange"></i>
                <span>{{ Auth::user()->username }}</span>
            </a>
        </li>
        <li class="treeview">
            <a href="{{ url('/logout') }}">
                <i class="fa fa-sign-out text-default"></i>
                <span>退出</span>
            </a>
        </li>
    @endif

</ul>