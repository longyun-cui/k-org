@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')
    @if(request('type') == 'root')
        {{ $data->username or '' }}
    @elseif(request('type') == 'article')
        {{ $data->username or '' }}的文章
    @elseif(request('type') == 'activity')
        {{ $data->username or '' }}的活动
    @else
        {{ $data->username or '' }}
    @endif
@endsection

@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动,朝鲜族社群平台,朝鲜族组织平台,朝鲜族活动平台,{{ $data->username or '' }},{{ $data->description or '' }}@endsection


@section('wx_share_title'){{ $data->username or '朝鲜族社群平台' }}@endsection
@section('wx_share_desc'){{ $data->description or '' }}@endsection
@section('wx_share_imgUrl'){{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}@endsection




@section('sidebar-toggle','_none')
@section('sidebar')
    {{--@include(env('TEMPLATE_K_WWW').'frontend.component.sidebar-user')--}}
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--右侧-用户名片--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right">

        {{--@include(env('TEMPLATE_K_COMMON').'component.component-user', ['data'=>$data])--}}
        {{--@include(env('TEMPLATE_K_COMMON').'component.menu-for-user', ['data'=>$data])--}}

    </div>


    <div class="main-body-section main-body-center-section section-wrapper page-root">
        <div class="container-box pull-left margin-bottom-4px">

            @include(env('TEMPLATE_K_COMMON').'component.component-card', ['data'=>$data])



{{--            @if(!in_array(request('type'),['org','introduction']))--}}
                {{--<div class="item-row margin-bottom-4px pull-right visible-xs">--}}
                {{--<strong>Ta的内容</strong>--}}
                {{--</div>--}}
                {{--@include(env('TEMPLATE_ROOT_FRONT').'component.item-list',['item_list'=>$item_list])--}}
                {{--{!! $item_list->links() !!}--}}
{{--            @endif--}}


            @if(request('type') == 'introduction')
                <div class="item-piece item-option">
                    <div class="box-body item-row item-content-row">
                        <div class="item-row">
                            <h4>我的简介</h4>
                        </div>
                        <div class="item-row item-description-row with-background">
                            @if(!empty($data->introduction->content))
                                {!! $data->introduction->content or '' !!}
                            @else
                                <small>暂无简介</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>


    {{--右侧-广告等--}}
    <div class="main-body-section main-body-center-section section-wrapper margin-top-4px">



        {{--贴片广告--}}
        @if(!empty($data->ad))
            <div class="item-row margin-top-4px pull-right _none-">
{{--                <strong>广告</strong>--}}
            </div>
            <div class="container-box pull-left margin-top-8px">
                @include(env('TEMPLATE_K_COMMON').'component.component-ad-paste', ['item'=>$data->ad])
            </div>
        @endif


        {{--我的赞助商--}}
        @if(count($data->pivot_sponsor_list))
            <div class="item-row margin-top-8px pull-right _none">
                <strong>Ta的赞助商</strong>
                {{--<strong>{{ $data->username or 'Ta' }}的赞助商</strong>--}}
            </div>
{{--            @include(env('TEMPLATE_K_COMMON').'component.component-sponsor', ['sponsor_list'=>$data->pivot_sponsor_list])--}}
            <div class="container-box pull-left margin-top-8px">
                @include(env('TEMPLATE_K_COMMON').'component.user-list', ['user_list'=>$data->pivot_sponsor_list,'type'=>'sponsor'])
            </div>
        @endif


        {{--我赞助的组织机构--}}
        @if(count($data->pivot_sponsored_list))
            <div class="item-row margin-top-4px pull-right _none">
                <strong>Ta赞助的人</strong>
            </div>
            <div class="container-box pull-left margin-top-8px">
{{--            @include(env('TEMPLATE_K_COMMON').'component.menu-for-org', ['org_list'=>$data->pivot_sponsored_list])--}}
                @include(env('TEMPLATE_K_COMMON').'component.user-list', ['user_list'=>$data->pivot_sponsored_list,'type'=>'sponsored'])
            </div>
        @endif


        {{--我的广告--}}
        @if(count($data->ad_list))
            <div class="item-row margin-top-4px pull-right _none">
                <strong>Ta的广告</strong>
            </div>
            <div class="container-box pull-left margin-top-8px">
                @include(env('TEMPLATE_K_COMMON').'component.component-ad-list', ['ad_list'=>$data->ad_list,'ad_tag'=>'广告'])
            </div>
        @endif


    </div>


    {{--内容--}}
    <div class="main-body-section main-body-center-section section-wrapper margin-top-4px">

        @if(!empty($item_list) && count($item_list))
            <div class="item-row margin-top-8px pull-right _none">
                <strong>Ta的分享</strong>
            </div>
            <div class="container-box pull-left margin-top-8px">
                @include(env('TEMPLATE_K_COMMON').'component.item-list',['item_list'=>$item_list])
                {!! $item_list->links() !!}
            </div>
        @endif

    </div>

</div>
@endsection




@section('custom-style')
<style>
</style>
@endsection




@section('custom-script')
<script>
    $(function() {
    });
</script>
@endsection