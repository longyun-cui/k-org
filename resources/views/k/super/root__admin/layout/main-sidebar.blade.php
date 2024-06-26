{{--<!-- Left side column. contains the logo and sidebar -->--}}
<aside class="main-sidebar">

    {{--<!-- sidebar: style can be found in sidebar.less -->--}}
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel _none">
            <div class="pull-left image">
                <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $me->username or 'username' }}</p>
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
            <li class="header">机构管理</li>

            <li class="treeview {{ $menu_active_by_user_list or '' }}">
                <a href="{{ url('/admin/user/user-list') }}">
                    <i class="fa fa-user"></i><span>全部用户</span>
                </a>
            </li>
            <li class="treeview {{ $menu_active_by_user_list_for_individual or '' }} _none">
                <a href="{{ url('/admin/user/user-list-for-individual') }}">
                    <i class="fa fa-user"></i><span>个人用户</span>
                </a>
            </li>
            <li class="treeview {{ $menu_active_by_user_list_for_org or '' }} _none">
                <a href="{{ url('/admin/user/user-list-for-org') }}">
                    <i class="fa fa-user"></i><span>社群组织</span>
                </a>
            </li>




            {{--内容管理--}}
            <li class="header">内容管理</li>

            <li class="treeview {{ $menu_active_by_item_list_for_all or '' }} ">
                <a href="{{ url('/admin/item/item-list') }}">
                    <i class="fa fa-file-text text-green"></i><span>全部内容</span>
                </a>
            </li>
            <li class="treeview {{ $menu_active_by_item_list_for_article or '' }} _none">
                <a href="{{ url('/admin/item/item-list-for-article') }}">
                    <i class="fa fa-file-text text-green"></i><span>文章</span>
                </a>
            </li>
            <li class="treeview {{ $menu_active_by_item_list_for_activity or '' }} _none">
                <a href="{{ url('/admin/item/item-list-for-activity') }}">
                    <i class="fa fa-file-text text-green"></i><span>活动</span>
                </a>
            </li>
            <li class="treeview {{ $menu_active_by_item_list_for_advertising or '' }} _none">
                <a href="{{ url('/admin/item/item-list-for-advertising') }}">
                    <i class="fa fa-file-text text-green"></i><span>广告</span>
                </a>
            </li>
            <li class="treeview {{ $menu_active_by_item_list_for_mine or '' }} _none">
                <a href="{{ url('/admin/item/item-list-for-mine') }}">
                    <i class="fa fa-file-text text-green"></i><span>我的</span>
                </a>
            </li>




            {{--流量统计--}}
            <li class="header">消息</li>

            <li class="treeview {{ $menu_active_by_item_list_for_notification or '' }}">
                <a href="{{ url('/admin/notification/notification-list-for-all') }}">
                    <i class="fa fa-envelope text-orange"></i> <span>消息</span>
                </a>
            </li>




            {{--流量统计--}}
            <li class="header">流量统计</li>

            <li class="treeview {{ $sidebar_statistic_active or '' }}">
                <a href="{{ url('/admin/statistic') }}">
                    <i class="fa fa-line-chart text-purple"></i> <span>统计</span>
                </a>
            </li>
            <li class="treeview {{ $sidebar_statistic_list_active or '' }}">
                <a href="{{ url('/admin/statistic/statistic-list') }}">
                    <i class="fa fa-bar-chart text-purple"></i> <span>列表</span>
                </a>
            </li>




            {{--留言管理--}}
            <li class="header _none">消息管理</li>

            <li class="treeview {{ $sidebar_notice_notice_list_active or '' }} _none">
                <a href="{{ url('/admin/notice/notice-list') }}">
                    <i class="fa fa-envelope"></i> <span>消息列表</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_notice_my_notice_list_active or '' }} _none">
                <a href="{{ url('/admin/notice/my-notice-list') }}">
                    <i class="fa fa-envelope"></i> <span>我发布的</span>
                </a>
            </li>

            <li class="treeview {{ $sidebar_notice_active or '' }} _none">
                <a href="#">
                    <i class="fa fa-envelope"></i>
                    <span>公告&通知管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ $sidebar_notice_all_active or '' }}">
                        <a href="{{ url('/admin/notice/notice-all') }}">
                            <i class="fa fa-circle-o"></i> <span>全体通知</span>
                        </a>
                    </li>
                    <li class="{{ $sidebar_notice_agent_active or '' }}">
                        <a href="{{ url('/admin/notice/notice-agent') }}"><i class="fa fa-circle-o"></i> <span>代理商公告</span></a>
                    </li>
                    <li class="{{ $sidebar_notice_client_active or '' }}">
                        <a href="{{ url('/admin/notice/notice-client') }}"><i class="fa fa-circle-o"></i> <span>客户公告</span></a>
                    </li>
                </ul>
            </li>




            {{--目录管理--}}
            <li class="header _none">自定义内容管理</li>

            <li class="treeview _none">
                <a href="{{ url('/admin/menu/list') }}">
                    <i class="fa fa-folder-open-o text-blue"></i> <span>目录列表</span>
                </a>
            </li>

            <li class="treeview _none">
                <a href="{{ url('/admin/item/list') }}">
                    <i class="fa fa-file-o text-blue"></i> <span>内容列表</span>
                </a>
            </li>


            <li class="treeview _none">
                <a href="{{ url('/admin/menu/sort') }}">
                    <i class="fa fa-sort text-red"></i> <span>目录排序</span>
                </a>
            </li>


            <li class="treeview _none">
                <a href=""><i class="fa fa-th text-aqua"></i> <span>特殊内容</span>
                    <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ url('/admin/product/list') }}">
                            <i class="fa fa-file-text text-red"></i> <span>产品列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/article/list') }}">
                            <i class="fa fa-file-text text-red"></i> <span>文章列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/activity/list') }}">
                            <i class="fa fa-calendar-check-o text-red"></i> <span>活动/会议列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/survey/list') }}">
                            <i class="fa fa-question-circle text-red"></i> <span>调研问卷列表</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/admin/slide/list') }}">
                            <i class="fa fa-th-large text-red"></i> <span>幻灯片列表</span>
                        </a>
                    </li>
                </ul>
            </li>



            {{--平台--}}
            <li class="header">平台</li>

            <li class="treeview">
                <a href="{{ url(env('DOMAIN_WWW')) }}" target="_blank">
                    <i class="fa fa-cube text-default"></i> <span>平台首页</span>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ url(env('DOMAIN_ORG')) }}" target="_blank">
                    <i class="fa fa-cube text-default"></i> <span>ORG首页</span>
                </a>
            </li>
            <li class="treeview">
                <a href="{{ url('/admin/user/user-login?id=10000') }}" target="_blank">
                    <i class="fa fa-sign-in text-default"></i> <span>登录组织</span>
                </a>
            </li>

            <li class="treeview _none">
                <a href=""><i class="fa fa-th text-aqua"></i> <span>平台</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="{{ url(env('DOMAIN_WWW')) }}" target="_blank">
                            <i class="fa fa-cube text-default"></i> <span>平台首页</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="{{ url('/org') }}" target="_blank">
                            <i class="fa fa-cube text-default"></i> <span>组织后台</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="{{ url('/sponsor') }}" target="_blank">
                            <i class="fa fa-cube text-default"></i> <span>赞助商后台</span>
                        </a>
                    </li>
                </ul>
            </li>



        </ul>
        <!-- /.sidebar-menu -->
    </section>
    {{--<!-- /.sidebar -->--}}
</aside>