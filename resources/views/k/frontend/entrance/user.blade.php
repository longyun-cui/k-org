@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')

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


@section('wx_share_title'){{ $data->username or '朝鲜族组织活动网' }}@endsection
@section('wx_share_desc')欢迎来到我的主页@endsection
@section('wx_share_imgUrl'){{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}@endsection




@section('sidebar')

    @include(env('TEMPLATE_DEFAULT').'frontend.component.sidebar-user')

@endsection




@section('header','')
@section('description','')
@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right margin-bottom-16px">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user', ['data'=>$data])

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user-menu', ['data'=>$data])

        </div>


        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left margin-bottom-16px">

            {{--<div class="box-body visible-xs visible-sm" style="margin-bottom:4px;background:#fff;">--}}
                {{--<i class="fa fa-user text-orange"></i>&nbsp; <b>{{ $data->name or '' }}</b>--}}
            {{--</div>--}}

            {{--<div class="box-body visible-xs visible-sm" style="margin-bottom:16px;background:#fff;">--}}
                {{--<div class="margin">访问：{{ $data->visit_num or 0 }}</div>--}}
                {{--<div class="margin">文章：{{ $data->article_count or 0 }}</div>--}}
                {{--<div class="margin">活动：{{ $data->activity_count or 0 }}</div>--}}
            {{--</div>--}}


            @if(!in_array(request('type'),['org','introduction']))
                {{--<div class="item-row margin-bottom-4px pull-right visible-xs">--}}
                    {{--<strong>Ta的内容</strong>--}}
                {{--</div>--}}
                @include(env('TEMPLATE_DEFAULT').'frontend.component.item-list')
                {{ $items->links() }}
            @endif


            @if(request('type') == 'introduction')
            <div class="item-piece item-option topic-option">
                <div class="box-body item-row item-content-row">
                    <div class="item-row">
                        <h4>图文简介</h4>
                    </div>
                    <div class="item-row">
                        @if(!empty($data->introduction->content))
                            {!! $data->introduction->content or '' !!}
                        @else
                            <small>暂无简介</small>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if($data->user_type == 88 && request('type') == 'org')
                @include(env('TEMPLATE_DEFAULT').'frontend.component.user-list',['user_list'=>$data->pivot_org_list])
            @endif

        </div>


        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right" style="clear:right;">

            @if(!empty($data->ad))
                <div class="item-row margin-top-4px margin-bottom-2px pull-right">
                    <strong>贴片广告</strong>
                </div>
            @endif
            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-ad-paste', ['item'=>$data->ad])


            @if(count($data->pivot_org_list))
                <div class="item-row margin-top-4px margin-bottom-2px pull-right">
                    <strong>我赞助的组织</strong>
                </div>
            @endif
            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-org', ['org_list'=>$data->pivot_org_list])


            @if($data->user_type == 88)
                @if(count($data->ad_list))
                    <div class="item-row margin-top-4px margin-bottom-2px pull-right">
                        <strong>我的广告</strong>
                    </div>
                @endif
                @include(env('TEMPLATE_DEFAULT').'frontend.component.right-ad-list', ['ad_list'=>$data->ad_list,'ad_tag'=>'广告'])
            @endif


            @if(count($data->pivot_sponsor_list))
                <div class="item-row margin-top-4px margin-bottom-2px pull-right">
                    <strong>我的赞助商</strong>
                </div>
            @endif
            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-sponsor', ['sponsor_list'=>$data->pivot_sponsor_list])

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
