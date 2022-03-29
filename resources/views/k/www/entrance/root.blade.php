@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')
    {{ $head_title or '朝鲜族组织平台 - 发现身边的朝鲜族社群组织' }}
@endsection
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族组织平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_WWW').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-left-section section-wrapper page-item">

        @include(env('TEMPLATE_K_WWW').'component.left-tag')


        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ $sidebar_menu_root_active or '' }}"><a href="/" data-toggle="tab-">首页</a></li>
                <li class="{{ $sidebar_menu_activity_active or '' }}"><a href="/?type=activity" data-toggle="tab-">活动</a></li>
                <li class="{{ $sidebar_menu_organization_list_active or '' }}"><a href="/organization-list" data-toggle="tab-">组织机构</a></li>
            </ul>
            <div class="tab-content" style="width:100%; padding:10px 0;float:left;">
                <div class="active tab-pane" id="all">
                    @if(!empty($item_list) && count($item_list))
                        @include(env('TEMPLATE_K_WWW').'component.item-list',['item_list'=>$item_list])
                    @endif
                </div>
                {{--<div class="tab-pane" id="timeline">--}}
                {{--</div>--}}

                {{--<div class="tab-pane" id="settings">--}}
                {{--</div>--}}
            </div>
        </div>

        @if($page_type == 'tag')

            <div class="container-box pull-left margin-bottom-16px">
                @if(request('type') != 'activity')
                    @include(env('TEMPLATE_K_WWW').'component.user-list',['user_list'=>$user_list])
                @endif
            </div>

            <div class="container-box pull-left margin-bottom-16px">
                @include(env('TEMPLATE_K_COMMON_FRONT').'component.item-list',['item_list'=>$item_list])
            </div>

        @else

            <div class="container-box pull-left margin-bottom-16px">
                @include(env('TEMPLATE_K_COMMON_FRONT').'component.item-list',['item_list'=>$item_list])
            </div>

            <div class="container-box pull-left margin-bottom-16px">
                @if(request('type') != 'activity')
                    @include(env('TEMPLATE_K_WWW').'component.user-list',['user_list'=>$user_list])
                @endif
            </div>

        @endif

        {!! $item_list->links() !!}

    </div>


    {{--右侧--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm">

        @include(env('TEMPLATE_K_WWW').'component.right-side.right-root')
        {{--@include(env('TEMPLATE_K_WWW').'component.right-side.right-me')--}}

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
//        $('article').readmore({
//            speed: 150,
//            moreLink: '<a href="#">展开更多</a>',
//            lessLink: '<a href="#">收起</a>'
//        });
    });
</script>
@endsection