@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')

@section('head_title') {{ $data->username or '' }}的主页 @endsection
@section('header','')
@section('description','')

@section('header_title')  @endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user', ['data'=>$data])

            <div class="box-body bg-white right-menu hidden-xs hidden-sm">

                <a href="{{ url('/user/'.$data->id) }}">
                    <div class="padding-4px box-body {{ $sidebar_menu_root_active or '' }}">
                        <i class="fa fa-list text-orange"></i> <span>&nbsp; 主页</span>
                    </div>
                </a>

                <a href="{{ url('/user/'.$data->id.'?type=article') }}">
                    <div class="padding-4px box-body {{ $sidebar_menu_article_active or '' }}">
                        <i class="fa fa-list text-orange"></i>
                        &nbsp;
                        <span>文章</span>
                        <span class="margin-left-8px pull-right-">{{ $data->article_count or 0 }}</span>
                    </div>
                </a>

                <a href="{{ url('/user/'.$data->id.'?type=activity') }}">
                    <div class="padding-4px box-body {{ $sidebar_menu_activity_active or '' }}">
                        <i class="fa fa-list text-orange"></i>
                        &nbsp;
                        <span>活动</span>
                        <span class="margin-left-8px pull-right-">{{ $data->activity_count or 0 }}</span>
                    </div>
                </a>

            </div>

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

            @include(env('TEMPLATE_DEFAULT').'frontend.component.items')

            {{ $items->links() }}

        </div>


        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-paste-ad', ['item'=>$data->ad])

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-sponsor', ['sponsor_list'=>$data->pivot_relation])

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