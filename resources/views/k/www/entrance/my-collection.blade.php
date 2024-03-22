@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title','我的收藏 - 朝鲜族组织平台')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族组织平台@endsection
@section('wx_share_desc')朝鲜族社群组织活动分享平台@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div class="main-body-section main-body-left-section section-wrapper page-item">

{{--        @include(env('TEMPLATE_K_COMMON_FRONT').'component.item-list-for-relation',['item_list'=>$item_list])--}}
        @include(env('TEMPLATE_K_COMMON_FRONT').'component.item-list',['item_list'=>$item_list])
        {!! $item_list->links() !!}

    </div>


    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm">

{{--        @if($auth_check)--}}
{{--            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-me')--}}
{{--        @else--}}
{{--            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-root')--}}
{{--        @endif--}}

    </div>

</div>
@endsection




@section('style')
<style>
</style>
@endsection



@section('script')
<script>
    $(function() {
    });
</script>
@endsection