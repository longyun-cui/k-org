@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title','我的关注 - 朝鲜族组织平台')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection




@section('wx_share_title')组织列表@endsection
@section('wx_share_desc')朝鲜族社群组织活动分享平台@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-left-section section-wrapper page-item">

        @include(env('TEMPLATE_K_COMMON_FRONT').'component.user-list-for-relation',['user_list'=>$user_list])
        {!! $user_list->links() !!}

    </div>


    {{--右侧--}}
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
</script>
@endsection