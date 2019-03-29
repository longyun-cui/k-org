{{--<!-- Left side column. contains the logo and sidebar -->--}}
<aside class="main-sidebar">

    {{--<!-- sidebar: style can be found in sidebar.less -->--}}
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(!empty(Auth::guard('org_admin')->user()->portrait_img))
                    <img src="{{ url(env('DOMAIN_CDN').'/'.Auth::guard('org_admin')->user()->portrait_img) }}" class="img-circle" alt="User Image" />
                @else
                    <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                @endif
            </div>
            <div class="pull-left info">
                <p>{{ Auth::guard('org_admin')->user()->nickname }}</p>
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
        <ul class="sidebar-menu">

            <li class="header">组织管理</li>

            <li class="treeview {{ $menu_org_info_active or '' }}">
                <a href="{{ url('/org-admin/org/info') }}"><i class="fa fa-sun-o text-aqua"></i> <span>基本信息</span></a>
            </li>

            <li class="treeview {{ $menu_org_info_edit_active or '' }}">
                <a href="{{ url('/org-admin/org/info/edit') }}"><i class="fa fa-circle-o text-aqua"></i> <span>编辑信息</span></a>
            </li>



            <li class="header">内容管理</li>

            <li class="treeview {{ $menu_all_list_active or '' }}">
                <a href="{{ url('/org-admin/item/list') }}"><i class="fa fa-list text-red"></i> <span>全部内容</span></a>
            </li>

            <li class="treeview {{ $menu_article_list_active or '' }}">
                <a href="{{ url('/org-admin/item/list?category=article') }}"><i class="fa fa-circle-o text-red"></i> <span>文章类型</span></a>
            </li>

            <li class="treeview {{ $menu_activity_list_active or '' }}">
                <a href="{{ url('/org-admin/item/list?category=activity') }}"><i class="fa fa-circle-o text-red"></i> <span>活动类型</span></a>
            </li>

            <li class="header">赞助广告管理</li>

            <li class="treeview {{ $menu_sponsor_list_active or '' }}">
                <a href="{{ url('/org-admin/item/list?category=sponsor') }}"><i class="fa fa-list text-red"></i> <span>广告列表</span></a>
            </li>



            <li class="header _none"><i class="fa fa-envelope"></i></li>

            <li class="treeview {{ $menu_notification_comment or '' }} _none">
                <a href="{{ url('/org-admin/notification/comment') }}">
                    <i class="fa fa-bell"></i>消息
                    <span class="pull-right-container">
                        <small class="label pull-right bg-red">{{ $notification_count or '' }}</small>
                        <small class="label pull-right bg-blue _none">17</small>
                    </span>
                </a>
            </li>

            <li class="treeview {{ $menu_notification_favor or '' }} _none">
                <a href="{{ url('/org-admin/notification/favor') }}"><i class="fa fa-dot-circle-o"></i>点赞</a>
            </li>



            <li class="header">首页</li>

            <li class="treeview">
                <a href="{{ url('/') }}" target="_blank"><i class="fa fa-cube text-default"></i> <span>平台主页</span></a>
            </li>


        </ul>
        <!-- /.sidebar-menu -->
    </section>
    {{--<!-- /.sidebar -->--}}
</aside>