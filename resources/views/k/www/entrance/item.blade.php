@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title') {{ $item->title or '朝鲜族组织平台' }} @endsection
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title'){{ $item->title or '朝鲜族组织平台' }}@endsection
@section('wx_share_desc'){{ '@'.$item->owner->username }}@endsection
@section('wx_share_imgUrl'){{ url(env('DOMAIN_CDN').'/'.$item->owner->portrait_img) }}@endsection




@section('sidebar-toggle','_none')
@section('sidebar')
    {{--@include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar-item')--}}
@endsection
@section('header') {{ $item->title or '' }} @endsection
@section('description','')
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-left-section section-wrapper page-item">

        @include(env('TEMPLATE_K_WWW').'component.item')

    </div>


    {{--右侧-作者-用户名片--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right">
        {{--@include(env('TEMPLATE_K_WWW').'component.right-side.right-user', ['data'=>$item->owner])--}}
        @include(env('TEMPLATE_K_WWW').'component.right-side.right-user', ['data'=>$user])
    </div>


    {{--右侧-作者-赞助商--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right" style="clear:right;">

        {{--@if(!empty($user->ad))--}}
            {{--<div class="item-row margin-top-4px margin-bottom-2px pull-right">--}}
                {{--<strong>Ta的贴片广告</strong>--}}
            {{--</div>--}}
        {{--@endif--}}
        @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-ad-paste', ['item'=>$user->ad])

        @if(count($user->pivot_sponsor_list))
        <div class="item-row margin-top-16px margin-bottom-2px pull-right">
            <strong>Ta的赞助商</strong>
        </div>
        @endif
        @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-sponsor', ['sponsor_list'=>$user->pivot_sponsor_list])

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
        $(".comments-get-default").click();
    });
</script>
@endsection