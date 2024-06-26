@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')
    {{ $head_title or '朝鲜族社群平台 - 发现身边的朝鲜族社群组织活动' }}
@endsection

@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')朝鲜族社群组织活动平台,发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社区,朝鲜族社群,朝鲜族组织,朝鲜族活动,朝鲜族社群平台,朝鲜族组织平台,朝鲜族活动平台,朝鲜族生活社区@endsection


@section('wx_share_title')朝鲜族社群平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www-wx-share.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-center-section section-wrapper page-item">


{{--        <div class="nav-tabs-custom">--}}
{{--            <ul class="nav nav-tabs _none">--}}
{{--                <li class="{{ $menu_active_for_root or '' }}"><a href="/" data-toggle="tab-">首页</a></li>--}}
{{--                <li class="{{ $menu_active_for_activity or '' }}"><a href="/?type=activity" data-toggle="tab-">只看活动</a></li>--}}
{{--                <li class="{{ $menu_active_for_my_focus or '' }}"><a href="/?type=my-focus" data-toggle="tab-">我的关注</a></li>--}}
{{--                <li class="{{ $menu_active_for_organization_list or '' }}"><a href="/organization-list" data-toggle="tab-">组织机构</a></li>--}}
{{--            </ul>--}}
{{--            <div class="tab-content" style="width:100%; padding:10px 0;float:left;">--}}
{{--                <div class="active tab-pane" id="all">--}}
{{--                    @if(!empty($item_list) && count($item_list))--}}
{{--                        @include(env('TEMPLATE_K_COMMON').'component.item-list',['item_list'=>$item_list])--}}
{{--                    @endif--}}
{{--                </div>--}}
                {{--<div class="tab-pane" id="timeline">--}}
                {{--</div>--}}

                {{--<div class="tab-pane" id="settings">--}}
                {{--</div>--}}
{{--            </div>--}}
{{--        </div>--}}


        {{--推荐用户--}}
        @if($user_recommend_is == 'recommend')

            <div class="container-box pull-left margin-bottom-4px">
                @include(env('TEMPLATE_K_COMMON').'component.user-list',['user_list'=>$user_query_of_recommend])
            </div>

        @endif


        {{--推荐内容--}}
        @if($item_recommend_is == 'recommend')

            <div class="container-box pull-left margin-bottom-4px">
                @include(env('TEMPLATE_K_COMMON').'component.item-list',['item_list'=>$item_list_of_recommend])
            </div>

        @endif



        <div class="container-box pull-left margin-bottom-4px">
            @include(env('TEMPLATE_K_COMMON').'component.item-list',['item_list'=>$item_list])
        </div>

        {!! $item_list->links() !!}



        <div class="container-box pull-left margin-bottom-4px">
            @include(env('TEMPLATE_K_COMMON').'component.user-list',['user_list'=>$user_list])
        </div>



    </div>


    {{--右侧--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm _none">
        <div class="fixed-to-top">


            @include(env('TEMPLATE_K_WWW').'component.tag-list')

{{--            @if($auth_check)--}}
{{--                @include(env('TEMPLATE_K_COMMON').'component.menu-for-me')--}}
{{--            @else--}}
{{--                @include(env('TEMPLATE_K_COMMON').'component.menu-for-root')--}}
{{--            @endif--}}


        </div>
    </div>

</div>
@endsection




@section('style')
<style>
    .box-footer a {color:#777;cursor:pointer;}
    .box-footer a:hover {color:orange;cursor:pointer;}
    .comment-choice-container {border-top:2px solid #ddd;}
    .comment-choice-container .form-group { margin-bottom:0;}
</style>
@endsection




@section('script')
<script>
    $(function() {
    });
</script>
@endsection