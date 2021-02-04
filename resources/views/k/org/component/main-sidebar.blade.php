{{--<!-- Left side column. contains the logo and sidebar -->--}}
<aside class="main-sidebar">

    {{--<!-- sidebar: style can be found in sidebar.less -->--}}
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(Auth::guard('org')->user()->portrait_img)
                    <img src="{{ url(env('DOMAIN_CDN').'/'.Auth::guard('org')->user()->portrait_img) }}" class="img-circle" alt="User Image">
                @else
                    <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                @endif
            </div>
            <div class="pull-left info">
                <p>{{ Auth::guard('org')->user()->username }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form _none">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu tree"data-widget="tree">

            {{--基本资料--}}
            <li class="header">基本资料</li>

            <li class="treeview {{ $sidebar_me_info_active or '' }}">
                <a href="{{ url('/org/info/index') }}">
                    <i class="fa fa-info text-info"></i><span>基本信息</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_me_introduction_active or '' }}">
                <a href="{{ url('/org/introduction') }}">
                    <i class="fa fa-file-photo-o text-info"></i><span>图文介绍</span>
                </a>
            </li>

            {{--用户管理--}}
            <li class="header">用户管理</li>

            <li class="treeview {{ $sidebar_user_member_list_active or '' }}">
                <a href="{{ url('/org/user/my-member-list') }}">
                    <i class="fa fa-user text-orange"></i><span>成员列表</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_user_fans_list_active or '' }}">
                <a href="{{ url('/org/user/my-fans-list') }}">
                    <i class="fa fa-user text-orange"></i><span>粉丝列表</span>
                </a>
            </li>




            {{--内容管理--}}
            <li class="header">内容管理</li>

            <li class="treeview {{ $sidebar_item_all_list_active or '' }} ">
                <a href="{{ url('/org/item/item-all-list') }}">
                    <i class="fa fa-list text-green"></i><span>全部内容</span>
                </a>
            </li>
            {{--<li class="treeview {{ $sidebar_item_article_list_active or '' }}">--}}
                {{--<a href="{{ url('/org/item/item-list?type=article') }}">--}}
                    {{--<i class="fa fa-file-text text-green"></i><span>文章列表</span>--}}
                {{--</a>--}}
            {{--</li>--}}
            <li class="treeview {{ $sidebar_item_article_list_active or '' }}">
                <a href="{{ url('/org/item/item-article-list') }}">
                    <i class="fa fa-file-text text-green"></i><span>文章列表</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_item_activity_list_active or '' }}">
                <a href="{{ url('/org/item/item-activity-list') }}">
                    <i class="fa fa-calendar text-green"></i><span>活动列表</span>
                </a>
            </li>




            {{--赞助商&广告--}}
            <li class="header">赞助商&广告</li>

            <li class="treeview {{ $sidebar_user_sponsor_list_active or '' }}">
                <a href="{{ url('/org/user/my-sponsor-list') }}">
                    <i class="fa fa-cny text-red"></i> <span>我的赞助商</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_item_advertising_list_active or '' }}">
                <a href="{{ url('/org/item/item-advertising-list') }}">
                    <i class="fa fa-cny text-red"></i> <span>广告列表</span>
                </a>
            </li>




            {{--流量统计--}}
            <li class="header">流量统计</li>

            <li class="treeview {{ $sidebar_statistic_active or '' }}">
                <a href="{{ url('/org/statistic') }}">
                    <i class="fa fa-bar-chart text-green"></i> <span>流量统计</span>
                </a>
            </li>




            {{--消息管理--}}
            <li class="header _none">消息管理</li>

            <li class="treeview {{ $sidebar_notification_all_list_active or '' }} _none">
                <a href="{{ url('/org/notification/notification-all-list') }}">
                    <i class="fa fa-envelope text-green"></i> <span>消息列表</span>
                </a>
            </li>




            {{--平台--}}
            <li class="header">平台</li>

            <li class="treeview">
                <a class="org-login-user" data-id="{{ Auth::guard('org')->user()->id }}" data-type="root">
                    <i class="fa fa-sign-in text-default"></i> <span>登录我的主页</span>
                </a>
            </li>


        </ul>
        <!-- /.sidebar-menu -->
    </section>
    {{--<!-- /.sidebar -->--}}
</aside>