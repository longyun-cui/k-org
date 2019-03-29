@extends('frontend.layout.layout')


{{--html.head--}}
@section('head_title'){{ $item->title or '内容详情' }}{{ " - @".$item->org->name }}@endsection
@section('meta_author')@endsection
@section('meta_title')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection




{{--微信分享--}}
@section('wx_share_title'){{ $item->title or '内容详情 - '.config('website.website_name') }}@endsection
@section('wx_share_desc'){{ "@".$item->org->name }}@endsection
@section('wx_share_imgUrl'){{ url(env('DOMAIN_CDN').'/'.$item->org->portrait_img) }}@endsection




{{--header--}}
@section('component-header')
    @include('frontend.component.header-item')
@endsection


{{--footer--}}
@section('component-footer')
    @include('frontend.component.footer')
@endsection


{{--custom-content--}}
@section('custom-body')

    <main class="main-body">

        <section class="module-container" style="padding:32px 0;">
            <section class="main-container-xs">
                <div class="row">

                    @include('frontend.component.item', ['item'=>$item])

                </div>
            </section>
        </section>

    </main>

    <main class="main-sidebar-fixed">
        @include('frontend.module.sidebar-item')
    </main>

    @include('frontend.component.modal-forward')

@endsection




{{--css--}}
@section('custom-css')
    @if($item->category == 18)
        <link rel="stylesheet" type="text/css" href="{{ asset('templates/jiaoben912/css/default.css') }}" />
        <link type="text/css" rel="stylesheet" href="{{ asset('templates/jiaoben912/css/component.css') }}" media="all" />
    @endif
@endsection
{{--style--}}
@section('custom-style')
<style>
</style>
@endsection


{{--js--}}
@section('custom-js')
@endsection
{{--script--}}
@section('custom-script')
<script>
    $(function() {
        $(".comments-get-default").click();
    });
</script>
@endsection
