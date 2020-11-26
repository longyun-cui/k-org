<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" type="image/ico" href="{{ url('favicon.ico') }}">
    <link rel="shortcut icon" type="image/png" href="{{ url('favicon.png') }}">
    <link rel="icon" sizes="16x16 32x32 64x64" href="{{ url('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="196x196" href="{{ url('favicon.png') }}">

    <title>@yield('head_title')</title>
    <meta name="title" content="@yield('meta_title')" />
    <meta name="author" content="@yield('meta_author')" />
    <meta name="description" content="@yield('meta_description')" />
    <meta name="keywords" content="@yield('meta_keywords')" />
    <meta name="robots" content="all" />
    <meta name="_token" content="{{ csrf_token() }}"/>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}
    <!-- Font Awesome -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">--}}
    {{--<link href="https://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/font-awesome-4.5.0.min.css') }}">
    <!-- Ionicons -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">--}}
    {{--<link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/ionicons-2.0.1.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    {{--<link href="https://cdn.bootcss.com/admin-lte/2.3.11/css/AdminLTE.min.css" rel="stylesheet">--}}
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/skins/skin-blue.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    {{--<!--[if lt IE 9]>--}}
    {{--<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>--}}
    {{--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>--}}
    {{--<![endif]-->--}}
    {{--<link href="https://cdn.bootcss.com/bootstrap-modal/2.2.6/css/bootstrap-modal.min.css" rel="stylesheet">--}}

    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap-fileinput/4.4.3/css/fileinput.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/bootstrap-fileinput-4.4.8.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/lib/css/fileinput-only.css') }}">

    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables/dataTables.bootstrap.css') }}">

    {{--<link href="https://cdn.bootcss.com/iCheck/1.0.2/skins/all.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="/AdminLTE/plugins/iCheck/all.css">

    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">--}}
    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/bootstrap-datetimepicker-4.17.47.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/lib/css/bootstrap-datepicker-1.9.0.min.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/layer/3.0.3/skin/moon/style.min.css">--}}
    {{--<link rel="stylesheet" href="{{ asset('/lib/css/layer-style-3.0.3.min.css') }}">--}}
    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/layer/3.1.1/theme/moon/style.min.css">--}}
    {{--<link rel="stylesheet" href="{{ asset('/lib/css/layer-style-3.1.1.min.css') }}">--}}


    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/bootstrap-switch-3.3.4.min.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/Swiper/4.2.2/css/swiper.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/swiper-4.2.2.min.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/fancybox/3.3.5/jquery.fancybox.css">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/jquery.fancybox-3.3.5.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/lightcase/2.5.0/css/lightcase.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/lightcase-2.5.0.min.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/timelinejs/3.6.6/css/timeline.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/lib/css/timeline-3.6.6.min.css') }}">


    <link type="text/css" rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/frontend/index.css') }}">


    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/common.css') }}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/frontend.css') }}" media="all" />

    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/frontend/index.css') }}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/frontend/item.css') }}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/frontend/menu.css') }}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/backend/index.css') }}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/animate/wicked.css') }}" media="all" />
    <link type="text/css" rel="stylesheet" href="{{ asset('common/css/animate/hover.css') }}" media="all" />

    @yield('style')

    <style>
        .header-logo {
            -webkit-transition: width .3s ease-in-out;
            -o-transition: width .3s ease-in-out;
            transition: width .3s ease-in-out;
            display: block;
            float: left;
            height: 50px;
            font-size: 20px;
            line-height: 50px;
            text-align: center;
            width: calc(100% - 584px);
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            padding: 0 15px;
            font-weight: 300;
            color:#fff;
            overflow: hidden;
        }
    </style>

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
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
                    <span class="logo-big"><a href="{{url('/')}}"><img src="/favicon_transparent.png" class="img-icon" alt="Image"> <b>朝鲜族组织活动网</b></a></span>
            </div>

            <div class="navbar-custom-menu visible-sm" style="display:none;float:left;">
                <ul class="nav navbar-nav">

                    <li><a href="{{url('/')}}"> 首页</a></li>

                    <li><a href="{{url('/debates')}}"> 辩题</a></li>

                    <li><a href="{{url('/anonymous')}}"> 匿名话题</a></li>

                </ul>
            </div>



            <div class="header-logo" >
                <span class="logo-lg"><b>@yield('header_title')</b></span>
            </div>

            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    @if(!Auth::check())
                        <li class="visible-sm" style="display:none;">
                            <a href="{{url('/login')}}"><i class="fa fa-circle-o"></i> <span> 登录</span></a>
                        </li>
                        <li class="visible-sm" style="display:none;">
                            <a href="{{url('/register')}}"><i class="fa fa-circle-o"></i> <span> 注册</span></a>
                        </li>
                    @else
                        <li class="visible-sm visible-xs" style="display:none;">
                            <a href="{{url('/home')}}"><i class="fa fa-home text-default"></i> <span> {{Auth::user()->name}}</span></a>
                        </li>
                    @endif

                    <li class="dropdown notifications-menu" style="display:none;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-home"></i>
                            {{--<span class="label label-warning">10</span>--}}
                        </a>
                        <ul class="dropdown-menu">
                            {{--<li class="header">You have 10 notifications</li>--}}
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    @if(!Auth::check())
                                        <li><a href="{{url('/login')}}"><i class="fa fa-circle-o"></i> <span> 登录</span></a></li>
                                        <li><a href="{{url('/register')}}"><i class="fa fa-circle-o"></i> <span> 注册</span></a></li>
                                    @else
                                        <li><a href="{{url('/home')}}"><i class="fa fa-home text-default"></i> <span> {{Auth::user()->name}}</span></a></li>
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



    {{--<!-- Left side column. contains the logo and sidebar -->--}}
    <aside class="main-sidebar visible-xs">

        {{--<!-- sidebar: style can be found in sidebar.less -->--}}
        <section class="sidebar">

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">

                <li class="header">目录</li>

                <li class="treeview {{ $menu_all or '' }}">
                    <a href="{{url('/')}}"><i class="fa fa-list text-orange"></i> <span>平台首页</span></a>
                </li>

                <li class="treeview {{ $menu_debates or '' }}">
                    <a href="{{url('/debates')}}"><i class="fa fa-list text-orange"></i> <span>辩题</span></a>
                </li>

                <li class="treeview {{ $menu_anonymous or '' }}">
                    <a href="{{url('/anonymous')}}"><i class="fa fa-list text-orange"></i> <span>匿名话题</span></a>
                </li>

                <li class="header">Home</li>

                @if(!Auth::check())

                    <li class="treeview">
                        <a href="{{url('/login')}}"><i class="fa fa-circle-o"></i> <span>登录</span></a>
                    </li>
                    <li class="treeview">
                        <a href="{{url('/register')}}"><i class="fa fa-circle-o"></i> <span>注册</span></a>
                    </li>
                @else
                    <li class="treeview">
                        <a href="{{url('/home')}}"><i class="fa fa-home text-default"></i> <span>返回我的后台</span></a>
                    </li>
                @endif

            </ul>
            <!-- /.sidebar-menu -->

        </section>
        {{--<!-- /.sidebar -->--}}
    </aside>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left:0;background:url(/bg.gif) repeat;">
        <!-- Content Header (Page header) -->
        <section class="content-header" style="display:none;">
            <h1>
                @yield('header')
                <small>@yield('description')</small>
            </h1>
            <ol class="breadcrumb">
                @yield('breadcrumb')
            </ol>
        </section>

        <!-- Main content -->
        <section class="content" id="content-container">
            @yield('content') {{--Your Page Content Here--}}
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    {{--<!-- Main Footer -->--}}
    <footer class="main-footer" style="margin-left:0;">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Anything you want
        </div>
        <!-- Default to the left -->
        {{--<strong>Copyright &copy; 上海如哉网络科技有限公司 2017-2020 <a href="#">Company</a>.</strong> All rights reserved. 沪ICP备17052782号-4--}}
        <strong>版权所有&copy;上海如哉网络科技有限公司(2017-2020)</strong>
        <a target="_blank" href="http://www.miitbeian.gov.cn">沪ICP备17052782号-4</a>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="pull-right-container">
                                    <span class="label label-danger pull-right">70%</span>
                                </span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

{{--<!-- jQuery 2.2.3 -->--}}
<script src="/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
{{--<!-- Bootstrap 3.3.6 -->--}}
<script src="/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
{{--<!-- AdminLTE App -->--}}
<script src="/AdminLTE/dist/js/app.min.js"></script>

{{--<script src="https://cdn.bootcss.com/iCheck/1.0.2/icheck.min.js"></script>--}}
{{--<script src="{{ asset('/lib/js/icheck-1.0.2.min.js') }}"></script>--}}
<script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>

<script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.js"></script>--}}
<script src="{{ asset('/lib/js/jquery-ui-1.12.1.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>--}}
<script src="{{ asset('/lib/js/jquery.cookie-1.4.1.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js"></script>--}}
{{--<script src="{{ asset('/lib/js/bootstrap-modal-2.2.6.min.js') }}"></script>--}}

{{--<script src="https://cdn.bootcss.com/layer/3.0.3/layer.min.js"></script>--}}
<script src="{{ asset('/lib/js/layer-3.0.3.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/bootstrap-fileinput/4.4.3/js/fileinput.min.js"></script>--}}
<script src="{{ asset('/lib/js/fileinput-4.4.8.min.js') }}"></script>
<script src="{{ asset('/lib/js/fileinput-only.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>--}}
<script src="{{ asset('/lib/js/jquery.form-4.2.2.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/moment.js/2.19.0/moment.min.js"></script>--}}
<script src="{{ asset('/lib/js/moment-2.19.0.min.js') }}"></script>
<script src="{{ asset('/lib/js/moment-2.19.0-locale-zh-cn.js') }}"></script>
<script src="{{ asset('/lib/js/moment-2.19.0-locale-ko.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>--}}
<script src="{{ asset('/lib/js/bootstrap-switch-3.3.4.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/Swiper/4.2.2/js/swiper.min.js"></script>--}}
<script src="{{ asset('/lib/js/swiper-4.2.2.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/jquery.sticky/1.0.4/jquery.sticky.min.js"></script>--}}
<script src="{{ asset('/lib/js/jquery.sticky-1.0.4.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/fancybox/3.3.5/jquery.fancybox.js"></script>--}}
<script src="{{ asset('/lib/js/jquery.fancybox-3.3.5.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/lightcase/2.5.0/js/lightcase.min.js"></script>--}}
<script src="{{ asset('/lib/js/lightcase-2.5.0.min.js') }}"></script>

{{--<script src="https://cdn.bootcss.com/Readmore.js/2.2.0/readmore.min.js"></script>--}}
<script src="{{ asset('/lib/js/readmore-2.2.0.min.js') }}"></script>


{{--<script src="https://cdn.bootcdn.net/ajax/libs/timelinejs/3.6.6/js/timeline-min.min.js"></script>--}}
{{--<script src="https://cdn.bootcdn.net/ajax/libs/timelinejs/3.6.6/js/timeline.min.js"></script>--}}
<script src="{{ asset('/lib/js/timeline-min-3.6.6.min.js') }}"></script>
<script src="{{ asset('/lib/js/timeline-3.6.6.min.js') }}"></script>


{{--<script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>--}}
{{--<script src="https://cdn.bootcdn.net/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>--}}
{{--<script src="https://cdn.bootcdn.net/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>--}}
<script src="{{ asset('/lib/js/bootstrap-datetimepicker-4.17.47.min.js') }}"></script>
{{--<script src="{{ asset('/lib/js/bootstrap-datepicker-1.9.0.zh-CN.min.js') }}"></script>--}}
<script src="{{ asset('/lib/js/bootstrap-datepicker-1.9.0.min.js') }}"></script>


<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
<script>

    var wechat_config = {!! $wechat_config or '' !!};
    //    console.log(wechat_config);

    $(function(){

//        var link = window.location.href;
        var link = location.href.split('#')[0];
//        console.log(link);

        if(typeof wx != "undefined") wxFn();

        function wxFn() {

            wx.config({
                debug: false,
                appId: wechat_config.app_id, // 必填，公众号的唯一标识
                timestamp: wechat_config.timestamp, // 必填，生成签名的时间戳
                nonceStr: wechat_config.nonce_str, // 必填，生成签名的随机串
                signature: wechat_config.signature, // 必填，签名，见附录1
                jsApiList: [
                    'checkJsApi',
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareQZone',
                    'onMenuShareWeibo'
                ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            }) ;

            wx.ready(function(){
                wx.updateAppMessageShareData({
                    title: "@yield('wx_share_title')",
                    desc: "@yield('wx_share_desc')",
                    link: link,
                    dataUrl: '',
                    imgUrl: $.trim("@yield('wx_share_imgUrl')"),
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        {{--$.get(--}}
                        {{--"/share",--}}
                        {{--{--}}
                        {{--'_token': $('meta[name="_token"]').attr('content'),--}}
                        {{--'website': "{{$org->website_name or '0'}}",--}}
                        {{--'sort': 1,--}}
                        {{--'module': 0,--}}
                        {{--'share': 1--}}
                        {{--},--}}
                        {{--function(data) {--}}
                        {{--if(!data.success) layer.msg(data.msg);--}}
                        {{--}, --}}
                        {{--'json');--}}
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.updateTimelineShareData({
                    title: "@yield('wx_share_title')",
                    desc: "@yield('wx_share_desc')",
                    link: link,
                    imgUrl: $.trim("@yield('wx_share_imgUrl')"),
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        {{--$.get(--}}
                        {{--"/share",--}}
                        {{--{--}}
                        {{--'_token': $('meta[name="_token"]').attr('content'),--}}
                        {{--'website': "{{$org->website_name or '0'}}",--}}
                        {{--'sort': 1,--}}
                        {{--'module': 0,--}}
                        {{--'share': 2--}}
                        {{--},--}}
                        {{--function(data) {--}}
                        {{--if(!data.success) layer.msg(data.msg);--}}
                        {{--}, --}}
                        {{--'json');--}}
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
                wx.onMenuShareWeibo({
                    title: "@yield('wx_share_title')",
                    desc: "@yield('wx_share_desc')",
                    link: link,
                    imgUrl: $.trim("@yield('wx_share_imgUrl')"),
                    success: function () {
                        // 用户确认分享后执行的回调函数
                        {{--$.get(--}}
                        {{--"/share",--}}
                        {{--{--}}
                        {{--'_token': $('meta[name="_token"]').attr('content'),--}}
                        {{--'website': "{{$org->website_name or '0'}}",--}}
                        {{--'sort': 1,--}}
                        {{--'module': 0,--}}
                        {{--'share': 5--}}
                        {{--},--}}
                        {{--function(data) {--}}
                        {{--if(!data.success) layer.msg(data.msg);--}}
                        {{--}, --}}
                        {{--'json');--}}
                    },
                    cancel: function () {
                        // 用户取消分享后执行的回调函数
                    }
                });
            })   ;
        }
    });
</script>


<script src="{{asset('js/frontend/index.js')}}"></script>

@yield('js')
@yield('custom-js')
@yield('custom-script')



</body>
</html>
