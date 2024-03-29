@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')
    {{ $head_title or '朝鲜族平台' }}
@endsection
@section('meta_title')朝鲜族社群组织活动平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动@endsection


@section('wx_share_title')#{{ $q or '' }}@endsection
@section('wx_share_desc')朝鲜族组织平台@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-left-section section-wrapper">

        @include(env('TEMPLATE_K_WWW').'component.tag-list')

        <div class="container-box pull-left margin-bottom-16px">
            @if(request('type') != 'activity')
                @include(env('TEMPLATE_K_COMMON_FRONT').'component.user-list',['user_list'=>$user_list])
            @endif
        </div>

        <div class="container-box pull-left margin-bottom-16px">
            @include(env('TEMPLATE_K_WWW').'component.item-list',['item_list'=>$item_list])
        </div>

        {!! $item_list->links() !!}

    </div>


    {{--右侧--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right">

        @if($auth_check)
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-me')
        @else
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-root')
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
