<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" type="image/ico" href="{{ env('FAVICON_K_WWW') }}">
    <link rel="shortcut icon" type="image/png" href="{{ env('FAVICON_K_WWW') }}">
    <link rel="icon" sizes="16x16 32x32 64x64" href="{{ env('FAVICON_K_WWW') }}">
    <link rel="icon" type="image/png" sizes="196x196" href="{{ env('FAVICON_K_WWW') }}">

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
    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/bootstrap/3.3.7/css/bootstrap.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/AdminLTE/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">--}}
    {{--<link href="https://cdn.bootcdn.net/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/font-awesome-4.5.0.min.css') }}">
    <!-- Ionicons -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">--}}
    {{--<link href="https://cdn.bootcdn.net/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/ionicons-2.0.1.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/dist/css/AdminLTE.min.css') }}">
    {{--<link href="https://cdn.bootcdn.net/admin-lte/2.3.11/css/AdminLTE.min.css" rel="stylesheet">--}}
<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE/dist/css/skins/_all-skins.css') }}">
    {{--<link rel="stylesheet" href="{{ asset('/AdminLTE/dist/css/skins/skin-blue.min.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('/AdminLTE/dist/css/skins/skin-black.min.css') }}">--}}

    <link rel="stylesheet" href="{{ asset('/AdminLTE/plugins/iCheck/all.css') }}">
    {{--<link href="https://cdn.bootcdn.net/iCheck/1.0.2/skins/all.css" rel="stylesheet">--}}
    {{--<link rel="stylesheet" href="{{ asset('/resource/component/css/iCheck-1.0.2-skins-all.css') }}">--}}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    {{--<!--[if lt IE 9]>--}}
    {{--<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>--}}
    {{--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>--}}
    {{--<![endif]-->--}}
    {{--<link href="https://cdn.bootcdn.net/bootstrap-modal/2.2.6/css/bootstrap-modal.min.css" rel="stylesheet">--}}

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/layer/3.0.3/skin/moon/style.min.css">--}}
    {{--<link rel="stylesheet" href="{{ asset('/resource/component/css/layer-style-3.0.3.min.css') }}">--}}
    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/layer/3.1.1/theme/moon/style.min.css">--}}
    {{--<link rel="stylesheet" href="{{ asset('/resource/component/css/layer-style-3.1.1.min.css') }}">--}}

    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables/dataTables.bootstrap.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/bootstrap-fileinput/4.4.3/css/fileinput.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/bootstrap-fileinput-4.4.8.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/resource/component/css/fileinput-only.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">--}}
    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/bootstrap-datetimepicker-4.17.47.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/resource/component/css/bootstrap-datepicker-1.9.0.min.css') }}">


    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/bootstrap-switch-3.3.4.min.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/Swiper/4.2.2/css/swiper.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/swiper-4.2.2.min.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/fancybox/3.3.5/jquery.fancybox.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/jquery.fancybox-3.3.5.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/lightcase/2.5.0/css/lightcase.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/lightcase-2.5.0.min.css') }}">

    {{--<link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/timelinejs/3.6.6/css/timeline.min.css">--}}
    <link rel="stylesheet" href="{{ asset('/resource/component/css/timeline-3.6.6.min.css') }}">


{{--    <link rel="stylesheet" href="{{ asset('/resource/common/css/animate/wicked.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('/resource/common/css/animate/hover.css') }}">--}}

    <link rel="stylesheet" href="{{ asset('/resource/common/css/AdminLTE/index.css') }}">
    <link rel="stylesheet" href="{{ asset('/resource/common/css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('/resource/common/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('/resource/common/css/item.css') }}">

    <link rel="stylesheet" href="{{ asset('/resource/common/css/frontend/index.css') }}">
    <link rel="stylesheet" href="{{ asset('/resource/others/css/frontend/index.css') }}">


    {{--layout-style--}}
    @include(env('TEMPLATE_K_WWW').'layout.layout-style')

    @yield('css')
    @yield('style')
    @yield('custom-css')
    @yield('custom-style')

    {{--<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>--}}

    <style>

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
<body class="hold-transition skin-black sidebar-mini page-frontend">
<div class="wrapper">

    {{--main-header--}}
    @include(env('TEMPLATE_K_WWW').'layout.main-header')

    {{--main-sidebar--}}
    @include(env('TEMPLATE_K_WWW').'layout.main-sidebar')

    {{--main-content--}}
    @include(env('TEMPLATE_K_WWW').'layout.main-content')

    {{--main-footer--}}
    @include(env('TEMPLATE_K_WWW').'layout.main-footer')
    @include(env('TEMPLATE_K_WWW').'layout.main-footer-nav')

    {{--control-sidebar--}}
    @include(env('TEMPLATE_K_WWW').'layout.control-sidebar')

</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

{{--<!-- jQuery 2.2.3 -->--}}
{{--<script src="/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>--}}
<script src="{{ asset('/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>

{{--<!-- Bootstrap 3.3.6 -->--}}
{{--<script src="/AdminLTE/bootstrap/js/bootstrap.min.js"></script>--}}
<script src="{{ asset('/AdminLTE/bootstrap/js/bootstrap.min.js') }}"></script>

{{--<!-- AdminLTE App -->--}}
{{--<script src="/AdminLTE/dist/js/app.min.js"></script>--}}
<script src="{{ asset('/AdminLTE/dist/js/app.min.js') }}"></script>

{{--<script src="/AdminLTE/plugins/iCheck/icheck.min.js"></script>--}}
{{--<script src="https://cdn.bootcdn.net/iCheck/1.0.2/icheck.min.js"></script>--}}
{{--<script src="{{ asset('/resource/component/js/icheck-1.0.2.min.js') }}"></script>--}}
<script src="{{ asset('/AdminLTE/plugins/iCheck/icheck.min.js') }}"></script>

<script src="{{ asset('/AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/jqueryui/1.12.1/jquery-ui.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/jquery-ui-1.12.1.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/jquery.cookie-1.4.1.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js"></script>--}}
{{--<script src="{{ asset('/resource/component/js/bootstrap-modal-2.2.6.min.js') }}"></script>--}}

{{--<script src="https://cdn.bootcdn.net/ajax/libs/feather-icons/4.29.1/feather.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/feather-icons-4.29.1.min.js') }}"></script>


{{--<script src="https://cdn.bootcdn.net/layer/3.0.3/layer.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/layer-3.0.3.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/bootstrap-fileinput/4.4.3/js/fileinput.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/fileinput-4.4.8.min.js') }}"></script>
<script src="{{ asset('/resource/component/js/fileinput-only.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/jquery.form/4.2.2/jquery.form.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/jquery.form-4.2.2.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/moment.js/2.19.0/moment.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/moment-2.19.0.min.js') }}"></script>
<script src="{{ asset('/resource/component/js/moment-2.19.0-locale-zh-cn.js') }}"></script>
<script src="{{ asset('/resource/component/js/moment-2.19.0-locale-ko.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/bootstrap-switch-3.3.4.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/Swiper/4.2.2/js/swiper.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/swiper-4.2.2.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/jquery.sticky/1.0.4/jquery.sticky.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/jquery.sticky-1.0.4.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/fancybox/3.3.5/jquery.fancybox.js"></script>--}}
<script src="{{ asset('/resource/component/js/jquery.fancybox-3.3.5.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/lightcase/2.5.0/js/lightcase.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/lightcase-2.5.0.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/Readmore.js/2.2.0/readmore.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/readmore-2.2.0.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/ajax/libs/timelinejs/3.6.6/js/timeline-min.min.js"></script>--}}
{{--<script src="https://cdn.bootcdn.net/ajax/libs/timelinejs/3.6.6/js/timeline.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/timeline-min-3.6.6.min.js') }}"></script>
<script src="{{ asset('/resource/component/js/timeline-3.6.6.min.js') }}"></script>

{{--<script src="https://cdn.bootcdn.net/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>--}}
{{--<script src="https://cdn.bootcdn.net/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>--}}
{{--<script src="https://cdn.bootcdn.net/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>--}}
<script src="{{ asset('/resource/component/js/bootstrap-datetimepicker-4.17.47.min.js') }}"></script>
{{--<script src="{{ asset('/resource/component/js/bootstrap-datepicker-1.9.0.zh-CN.min.js') }}"></script>--}}
<script src="{{ asset('/resource/component/js/bootstrap-datepicker-1.9.0.min.js') }}"></script>


<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
{{--<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>--}}
{{--<script src="http://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>--}}

@include(env('TEMPLATE_K_WWW').'layout.wx-script')


<script src="{{ asset('/resource/common/js/common.js') }}"></script>
<script src="{{ asset('/resource/custom/www/frontend/js/index.js') }}"></script>
{{--<script src="{{  asset('js/frontend/index.js') }}"></script>--}}


<script src="{{ asset('/resource/common/js/area_data.js') }}"></script>
<script src="{{ asset('/resource/common/js/area_select.js') }}"></script>

@include(env('TEMPLATE_K_WWW').'layout.layout-script')


@yield('js')
@yield('script')
@yield('custom-js')
@yield('custom-script')



</body>
</html>