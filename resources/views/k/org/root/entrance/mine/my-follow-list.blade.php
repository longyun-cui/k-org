@extends(env('TEMPLATE_K_ORG').'layout.layout')


@section('head_title')我的关注@endsection

@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动@endsection


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

    <div class="main-body-section main-body-center-section section-wrapper page-root">
        <div class="container-box pull-left margin-bottom-16px">

            @include(env('TEMPLATE_K_ORG').'component.user-list-for-relation',['user_list'=>$user_list])
{{--            {!! $user_list->links() !!}--}}

        </div>
    </div>

    <div class="main-body-section main-body-right-section section-wrapper hidden-xs">

        {{--@include(env('TEMPLATE_ROOT_FRONT').'component.right-side.right-root')--}}
        {{--@include(env('TEMPLATE_K_ORG').'component.right-side.right-root')--}}

    </div>

</div>
@endsection




@section('custom-style')
@endsection




@section('custom-script')
@endsection
