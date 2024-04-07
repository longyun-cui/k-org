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
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动,{{ $data->username or '朝鲜族社群平台' }},{{ $data->description or '' }}@endsection


@section('wx_share_title'){{ $data->username or '朝鲜族社群平台' }}@endsection
@section('wx_share_desc')@if($data->user_type == 11){{ $data->description or '欢迎加入我们' }}@else{{ $data->description or '' }}@endif@endsection
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
    <div class="main-body-section main-body-right-section section-wrapper pull-right _none">

{{--        @include(env('TEMPLATE_K_COMMON').'component.component-user', ['data'=>$data])--}}
        {{--@include(env('TEMPLATE_K_COMMON').'component.menu-for-user', ['data'=>$data])--}}

    </div>


    <div class="main-body-section main-body-center-section section-wrapper page-root">
        <div class="container-box pull-left margin-bottom-4px">

            @include(env('TEMPLATE_K_COMMON').'component.component-card', ['data'=>$data])

            {{--<div class="box-body visible-xs visible-sm" style="margin-bottom:4px;background:#fff;">--}}
            {{--<i class="fa fa-user text-orange"></i>&nbsp; <b>{{ $data->name or '' }}</b>--}}
            {{--</div>--}}

{{--            <div class="box-body visible-xs visible-sm" style="margin-bottom:16px;background:#fff;">--}}
{{--                <div class="margin">访问：{{ $data->visit_num or 0 }}</div>--}}
{{--                <div class="margin">文章：{{ $data->article_count or 0 }}</div>--}}
{{--                <div class="margin">活动：{{ $data->activity_count or 0 }}</div>--}}
{{--            </div>--}}


            @if(!in_array(request('type'),['org','introduction']))
                {{--<div class="item-row margin-bottom-4px pull-right visible-xs">--}}
                {{--<strong>Ta的内容</strong>--}}
                {{--</div>--}}
                {{--@include(env('TEMPLATE_ROOT_FRONT').'component.item-list',['item_list'=>$item_list])--}}
                {{--{!! $item_list->links() !!}--}}
            @endif


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
                                <small>暂无简介3</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{--@if($data->user_type == 88 && request('type') == 'org')--}}
            {{--@include(env('TEMPLATE_ROOT_FRONT').'component.user-list',['user_list'=>$data->pivot_org_list])--}}
            {{--@endif--}}

        </div>
    </div>


    {{--左侧-内容--}}
    <div class="main-body-section main-body-center-section section-wrapper">

        @if(request('type') != 'introduction')
            @if(!empty($item_list) && count($item_list))
                @include(env('TEMPLATE_K_COMMON').'component.item-list',['item_list'=>$item_list])
                {!! $item_list->links() !!}
            @endif
        @endif


        @if($data->user_type == 88 && request('type') == 'org')
            @include(env('TEMPLATE_K_COMMON').'component.user-list',['user_list'=>$data->pivot_org_list])
        @endif

    </div>


    {{--右侧-广告等--}}
    <div class="main-body-section main-body-left-section section-wrapper pull-left margin-top-4px _none-">


        {{--[IF]我是组织--}}
        @if($data->user_type == 11)

            {{--贴片广告--}}
            @if(!empty($data->ad))
                <div class="item-row margin-top-8px pull-right _none">
                    <strong>广告</strong>
                </div>
                @include(env('TEMPLATE_K_COMMON').'component.component-ad-paste', ['item'=>$data->ad])
            @endif

            @if(count($data->pivot_sponsor_list))
                <div class="item-row margin-top-8px pull-right _none">
                    <strong>我的赞助商</strong>
                </div>
                @include(env('TEMPLATE_K_COMMON').'component.component-sponsor', ['sponsor_list'=>$data->pivot_sponsor_list])
            @endif

        @endif


        {{--[IF]我是赞助商--}}
        @if($data->user_type == 88)

            {{--我赞助的组织机构--}}
            @if(count($data->pivot_org_list))
                <div class="item-row margin-top-8px pull-right _none">
                    {{--<strong>赞助的组织</strong>--}}
                </div>
                @include(env('TEMPLATE_K_COMMON').'component.menu-for-org', ['org_list'=>$data->pivot_org_list])
            @endif

            {{--我的广告--}}
            @if(count($data->ad_list))
                <div class="item-row margin-top-8px pull-right _none">
                    <strong>广告</strong>
                </div>
                @include(env('TEMPLATE_K_COMMON').'component.component-ad-list', ['ad_list'=>$data->ad_list,'ad_tag'=>'广告'])
            @endif

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