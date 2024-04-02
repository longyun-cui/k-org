@extends(env('TEMPLATE_K_ORG').'layout.layout')


@section('head_title')
    {{ $head_title or 'ORG组织 - 朝鲜族社群平台 - 发现身边的朝鲜族社群组织活动' }}
@endsection
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族社群平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_ORG').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div class="main-body-section main-section left-section section-wrapper page-item">


        <div class="container-box pull-left margin-bottom-16px">
            @if(!empty($item_list) && count($item_list))
                @include(env('TEMPLATE_K_COMMON').'component.item-list',['item_list'=>$item_list])
            @endif
        </div>


    </div>


    <div class="main-body-section side-section right-section section-wrapper pull-right hidden-xs hidden-sm">


        <div class="fixed-to-top">
        @include(env('TEMPLATE_K_ORG').'component.right-side.right-root')
        {{--@include(env('TEMPLATE_K_ORG').'component.right-me')--}}
        </div>

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
    @include(env('TEMPLATE_K_ORG').'component.item-list-script')
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