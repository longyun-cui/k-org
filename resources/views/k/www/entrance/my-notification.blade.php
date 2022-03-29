@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title','消息通知 - 朝鲜族组织平台')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族组织平台@endsection
@section('wx_share_desc')朝鲜族社群组织活动分享平台@endsection
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

        {{--@foreach($datas as $num => $item)--}}
            {{--@include('frontend.component.topic')--}}
        {{--@endforeach--}}
        @include(env('TEMPLATE_K_WWW').'component.notification-list',['notification_list'=>$notification_list])
        {{--{!! $notification_list->links() !!}--}}

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
        $('article').readmore({
            speed: 150,
            moreLink: '<a href="#">展开更多</a>',
            lessLink: '<a href="#">收起</a>'
        });
    });
</script>
@endsection