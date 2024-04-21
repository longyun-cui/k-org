@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')社群组织 - 朝鲜族社群平台@endsection

@section('meta_title')朝鲜族社群组织活动平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动,朝鲜族社群平台,朝鲜族组织平台,朝鲜族活动平台@endsection


@section('wx_share_title')朝鲜族社群平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div class="main-body-section main-body-left-section section-wrapper page-item">

        {{--@include(env('TEMPLATE_K_WWW').'component.left-tag')--}}


        {{--注册新组织--}}
        <div class="main-body-section main-body-center-section section-wrapper page-item">
            <div class="item-piece item-option item-wrapper user-piece user-option user margin-bottom-4px radius-2px">
                <div class="panel-default box-default item-entity-container text-center">
                    <div><a href="{{ url(env('DOMAIN_WWW').'/org-register') }}">注册一个社群组织</a></div>
                </div>
            </div>
        </div>


        @include(env('TEMPLATE_K_COMMON').'component.user-list',['user_list'=>$user_list])
        {!! $user_list->links() !!}


    </div>

    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm">

{{--        @if($auth_check)--}}
{{--            @include(env('TEMPLATE_K_COMMON').'component.menu-for-me')--}}
{{--        @else--}}
{{--            @include(env('TEMPLATE_K_COMMON').'component.menu-for-root')--}}
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