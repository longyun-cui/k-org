{{--<!-- Left side column. contains the logo and sidebar -->--}}
<aside class="main-sidebar">

    {{--<!-- sidebar: style can be found in sidebar.less -->--}}
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(Auth::guard('sponsor')->user()->portrait_img)
                    <img src="{{ url(env('DOMAIN_CDN').'/'.Auth::guard('sponsor')->user()->portrait_img) }}" class="img-circle" alt="User Image">
                @else
                    <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                @endif
            </div>
            <div class="pull-left info">
                <p>{{ Auth::guard('sponsor')->user()->username }}</p>
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

            {{--用户管理--}}
            <li class="header">我的介绍</li>

            <li class="treeview {{ $sidebar_user_member_list_active or '' }}">
                <a href="{{ url('/sponsor/introduction') }}">
                    <i class="fa fa-user"></i><span>图文介绍</span>
                </a>
            </li>




            {{--用户管理--}}
            <li class="header">我的赞助</li>

            <li class="treeview {{ $sidebar_user_member_list_active or '' }}">
                <a href="{{ url('/sponsor/user/my-org-list') }}">
                    <i class="fa fa-user"></i><span>赞助组织</span>
                </a>
            </li>




            {{--业务管理--}}
            <li class="header">内容管理</li>

            <li class="treeview {{ $sidebar_item_all_list_active or '' }} ">
                <a href="{{ url('/sponsor/item/item-all-list') }}">
                    <i class="fa fa-file-text"></i><span>全部内容</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_item_article_list_active or '' }}">
                <a href="{{ url('/sponsor/item/item-list?type=article') }}">
                    <i class="fa fa-file-text"></i><span>文章列表</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_item_activity_list_active or '' }}">
                <a href="{{ url('/sponsor/item/item-activity-list') }}">
                    <i class="fa fa-file-text"></i><span>活动列表</span>
                </a>
            </li>




            {{--工单管理--}}
            <li class="header">广告</li>

            <li class="treeview {{ $sidebar_item_advertising_list_active or '' }}">
                <a href="{{ url('/sponsor/item/item-advertising-list') }}">
                    <i class="fa fa-cny text-red"></i> <span>广告列表</span>
                </a>
            </li>




            {{--消息管理--}}
            <li class="header">消息管理</li>

            <li class="treeview {{ $sidebar_message_list_active or '' }}">
                <a href="{{ url('/sponsor/message/message-list') }}">
                    <i class="fa fa-envelope text-green"></i> <span>消息列表</span>
                </a>
            </li>




            {{--流量统计--}}
            <li class="header _none">流量统计</li>

            <li class="treeview _none">
                <a href="{{ url('/sponsor/statistics/website') }}"><i class="fa fa-bar-chart text-green"></i> <span>流量统计</span></a>
            </li>




            <li class="header">平台</li>

            <li class="treeview">
                <a href="{{ url('/user/'.Auth::guard('sponsor')->user()->id) }}" target="_blank">
                    <i class="fa fa-cube text-default"></i> <span>我的主页</span>
                </a>
            </li>


        </ul>
        <!-- /.sidebar-menu -->
    </section>
    {{--<!-- /.sidebar -->--}}
</aside>