@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')名片列表-朝鲜族平台@endsection

@section('meta_title')朝鲜族社群组织活动平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动@endsection


@section('wx_share_title')朝鲜族平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div class="main-body-section main-body-left-section section-wrapper page-item">

        {{--@include(env('TEMPLATE_K_WWW').'component.left-tag')--}}

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
{{--                <li class="{{ $menu_active_for_root or '' }}"><a href="/" data-toggle="tab-">首页</a></li>--}}
{{--                <li class="{{ $menu_active_for_activity or '' }}"><a href="/?type=activity" data-toggle="tab-">只看活动</a></li>--}}
{{--                <li class="{{ $menu_active_for_my_focus or '' }}"><a href="/?type=my-focus" data-toggle="tab-">我的关注</a></li>--}}
{{--                <li class="{{ $menu_active_for_organization_list or '' }}"><a href="/organization-list" data-toggle="tab-">组织机构</a></li>--}}
            </ul>
            <div class="tab-content" style="width:100%; padding:10px 0;float:left;">
                <div class="active tab-pane" id="all">
                    @include(env('TEMPLATE_K_COMMON').'component.user-list',['user_list'=>$user_list])
                    {!! $user_list->links() !!}
                </div>
                {{--<div class="tab-pane" id="timeline">--}}
                {{--</div>--}}

                {{--<div class="tab-pane" id="settings">--}}
                {{--</div>--}}
            </div>
        </div>

    </div>

    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm">

{{--        @if($auth_check)--}}
{{--            @include(env('TEMPLATE_K_COMMON').'component.menu-for-me')--}}
{{--        @else--}}
{{--            @include(env('TEMPLATE_K_COMMON').'component.menu-for-root')--}}
{{--        @endif--}}

    </div>

</div>
@endsection




@section('style')
<style>
</style>
@endsection




@section('script')
<script>
</script>
@endsection