@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')
    @if(request('type') == 'root')
        {{ $data->username or '' }}的主页
    @elseif(request('type') == 'article')
        {{ $data->username or '' }}的文章
    @elseif(request('type') == 'activity')
        {{ $data->username or '' }}的活动
    @else
        {{ $data->username or '' }}的主页
    @endif
@endsection
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title'){{ $data->username or '朝鲜族组织平台' }}@endsection
@section('wx_share_desc')欢迎加入我们@endsection
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

        @include(env('TEMPLATE_K_WWW').'component.right-side.right-user', ['data'=>$data])
        {{--@include(env('TEMPLATE_K_WWW').'component.right-user-menu', ['data'=>$data])--}}

    </div>


    {{--左侧-内容--}}
    <div class="main-body-section main-body-left-section section-wrapper">

        {{--<div class="box-body visible-xs visible-sm" style="margin-bottom:4px;background:#fff;">--}}
            {{--<i class="fa fa-user text-orange"></i>&nbsp; <b>{{ $data->name or '' }}</b>--}}
        {{--</div>--}}

        {{--<div class="box-body visible-xs visible-sm" style="margin-bottom:16px;background:#fff;">--}}
            {{--<div class="margin">访问：{{ $data->visit_num or 0 }}</div>--}}
            {{--<div class="margin">文章：{{ $data->article_count or 0 }}</div>--}}
            {{--<div class="margin">活动：{{ $data->activity_count or 0 }}</div>--}}
        {{--</div>--}}


        @if(!in_array(request('type'),['org','introduction-']))
            {{--<div class="item-row margin-bottom-4px pull-right visible-xs">--}}
                {{--<strong>Ta的内容</strong>--}}
            {{--</div>--}}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="{{ $menu_active_for_item_all or '' }}">
                        <a href="{{ url('/user/'.$data->id) }}" data-toggle="tab-">全部内容</a>
                        {{--<a href="{{ url('/user/'.$data->id) }}" data-toggle="tab-">全部内容 {{ $data->item_count or 0 }}</a>--}}
                    </li>
                    <li class="{{ $menu_active_for_item_article or '' }}">
                        <a href={{ url('/user/'.$data->id.'?type=article') }} data-toggle="tab-">文章</a>
                        {{--<a href={{ url('/user/'.$data->id.'?type=article') }} data-toggle="tab-">文章 {{ $data->article_count or 0 }}</a>--}}
                    </li>
                    <li class="{{ $menu_active_for_item_activity or '' }}">
                        <a href={{ url('/user/'.$data->id.'?type=activity') }} data-toggle="tab-">活动</a>
                        {{--<a href={{ url('/user/'.$data->id.'?type=activity') }} data-toggle="tab-">活动 {{ $data->activity_count or 0 }}</a>--}}
                    </li>
                    <li class="{{ $menu_active_for_introduction or '' }}">
                        <a href={{ url('/user/'.$data->id.'?type=introduction') }} data-toggle="introduction">图文介绍</a>
                    </li>
                </ul>
                <div class="tab-content" style="width:100%; padding:10px 0;float:left;">
                    <div class="active tab-pane" id="all">

                        @if(request('type') != 'introduction')
                        @if(!empty($item_list) && count($item_list))
                            @include(env('TEMPLATE_K_COMMON_FRONT').'component.item-list',['item_list'=>$item_list])
                            {!! $item_list->links() !!}
                        @endif
                        @endif
                    </div>

                    {{--<div class="tab-pane" id="timeline">--}}
                    {{--</div>--}}

                    <div class="active tab-pane" id="introduction">
                        @if(request('type') == 'introduction')
                            <div class="item-piece item-option item-wrapper">
                                <div class="box-body item-row item-content-row">

                                    {{--<div class="item-row margin-bottom-8px" style="text-align:center;">--}}
                                        {{--<h4>{{ $data->introduction->title or '图文介绍' }}</h4>--}}
                                    {{--</div>--}}

                                    {{--@if(!empty($data->introduction->description))--}}
                                        {{--<div class="item-row margin-bottom-8px">--}}
                                            {{--<div class="text-row text-description-row text-muted">--}}
                                                {{--{{ $data->introduction->description or '暂无描述' }}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--@endif--}}

                                    @if(!empty($data->ext->description))
                                        <div class="item-row item-description-row with-background margin-bottom-16px">
                                            <div class="text-row text-description-row text-muted">
                                                {{ $data->ext->description or '暂无描述' }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="item-row">
                                        {{--@if(!empty($data->introduction->content))--}}
                                            {{--{!! $data->introduction->content or '' !!}--}}
                                        {{--@else--}}
                                            {{--<small>暂无介绍</small>--}}
                                        {{--@endif--}}
                                        @if(!empty($data->ext->content))
                                            {!! $data->ext->content or '' !!}
                                        @else
                                            <small>暂无介绍</small>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @endif



        @if($data->user_type == 88 && request('type') == 'org')
            @include(env('TEMPLATE_K_WWW').'component.user-list',['user_list'=>$data->pivot_org_list])
        @endif

    </div>


    {{--右侧-广告等--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right">


        {{--[IF]我是组织--}}
        @if($data->user_type == 11)

            {{--贴片广告--}}
            @if(!empty($data->ad))
                <div class="item-row margin-top-8px pull-right _none">
                    <strong>广告</strong>
                </div>
                @include(env('TEMPLATE_K_WWW').'component.right-side.right-ad-paste', ['item'=>$data->ad])
            @endif

            @if(count($data->pivot_sponsor_list))
                <div class="item-row margin-top-8px pull-right _none">
                    <strong>我的赞助商</strong>
                </div>
                @include(env('TEMPLATE_K_WWW').'component.right-side.right-sponsor', ['sponsor_list'=>$data->pivot_sponsor_list])
            @endif

        @endif


        {{--[IF]我是赞助商--}}
        @if($data->user_type == 88)

            {{--我赞助的组织机构--}}
            @if(count($data->pivot_org_list))
                <div class="item-row margin-top-8px pull-right _none">
                    {{--<strong>赞助的组织</strong>--}}
                </div>
                @include(env('TEMPLATE_K_WWW').'component.right-side.right-org', ['org_list'=>$data->pivot_org_list])
            @endif

            {{--我的广告--}}
            @if(count($data->ad_list))
                <div class="item-row margin-top-8px pull-right _none">
                    <strong>广告</strong>
                </div>
                @include(env('TEMPLATE_K_WWW').'component.right-side.right-ad-list', ['ad_list'=>$data->ad_list,'ad_tag'=>'广告'])
            @endif

        @endif

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




@section('js')
<script>
    $(function() {
        $('article').readmore({
            speed: 150,
            moreLink: '<a href="#">更多</a>',
            lessLink: '<a href="#">收起</a>'
        });
    });
</script>
@endsection