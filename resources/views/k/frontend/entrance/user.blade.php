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


@section('header','')
@section('description','')


@section('wx_share_title'){{ $data->username or '朝鲜族组织活动网' }}@endsection
@section('wx_share_desc')欢迎来到我的主页@endsection
@section('wx_share_imgUrl'){{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}@endsection




@section('sidebar')

    @include(env('TEMPLATE_DEFAULT').'frontend.component.sidebar-user')

@endsection




@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user', ['data'=>$data])

            @if($data->user_type == 11)
                @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user-menu', ['data'=>$data])
            @endif

        </div>


        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left">

            {{--<div class="box-body visible-xs visible-sm" style="margin-bottom:4px;background:#fff;">--}}
                {{--<i class="fa fa-user text-orange"></i>&nbsp; <b>{{ $data->name or '' }}</b>--}}
            {{--</div>--}}

            {{--<div class="box-body visible-xs visible-sm" style="margin-bottom:16px;background:#fff;">--}}
                {{--<div class="margin">访问：{{ $data->visit_num or 0 }}</div>--}}
                {{--<div class="margin">文章：{{ $data->article_count or 0 }}</div>--}}
                {{--<div class="margin">活动：{{ $data->activity_count or 0 }}</div>--}}
            {{--</div>--}}

            @include(env('TEMPLATE_DEFAULT').'frontend.component.item-list')

            {{ $items->links() }}

            @if($data->user_type == 88)
                @if(!empty($data->introduction->content))
                <div class="item-piece item-option topic-option">
                    <div class="box-body item-row item-content-row">
                        <div class="colo-md-12"> {!! $data->introduction->content or '' !!} </div>
                    </div>
                </div>
                @endif
            @endif

            @include(env('TEMPLATE_DEFAULT').'frontend.component.user-list',['user_list'=>$data->pivot_org_list])
        </div>


        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right" style="clear:right;">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-ad-paste', ['item'=>$data->ad])

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-org', ['org_list'=>$data->pivot_org_list])

            @if($data->user_type == 88)
                @include(env('TEMPLATE_DEFAULT').'frontend.component.right-ad-list', ['ad_list'=>$data->ad_list,'ad_tag'=>'广告'])
            @endif

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-sponsor', ['sponsor_list'=>$data->pivot_sponsor_list])

        </div>

    </div>

@endsection


@section('style')
<style>
    .box-footer a {color:#777;cursor:pointer;}
    .box-footer a:hover {color:orange;cursor:pointer;}
    .comment-container {border-top:2px solid #ddd;}
    .comment-choice-container {border-top:2px solid #ddd;}
    .comment-choice-container .form-group { margin-bottom:0;}
    .comment-entity-container {border-top:2px solid #ddd;}
    .comment-piece {border-bottom:1px solid #eee;}
    .comment-piece:first-child {}
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
