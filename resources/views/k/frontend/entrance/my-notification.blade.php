@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')

@section('head_title','消息通知 - 朝鲜族组织活动网')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族组织活动网@endsection
@section('wx_share_desc')朝鲜族组织活动分享平台@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection


@section('sidebar')

    @include(env('TEMPLATE_DEFAULT').'frontend.component.sidebar-root')

@endsection


@section('header','')
@section('description','')
@section('content')
<div style="display:none;">
    <input type="hidden" id="" value="{{ $encode or '' }}" readonly>
</div>

<div class="container">

    <div class="col-sm-12 col-md-9 container-body-left">

        {{--@foreach($datas as $num => $item)--}}
            {{--@include('frontend.component.topic')--}}
        {{--@endforeach--}}
        @include(env('TEMPLATE_DEFAULT').'frontend.component.notification-list',['notification_list'=>$notification_list])

    </div>

    <div class="col-sm-12 col-md-3 hidden-xs hidden-sm container-body-right">

        @include(env('TEMPLATE_DEFAULT').'frontend.component.right-root')
        {{--@include(env('TEMPLATE_DEFAULT').'frontend.component.right-me')--}}

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
            moreLink: '<a href="#">展开更多</a>',
            lessLink: '<a href="#">收起</a>'
        });
    });
</script>
@endsection