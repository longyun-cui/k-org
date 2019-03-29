@extends('frontend.layout.layout')


{{--html.head--}}
@section('head_title'){{ config('website.website_name') }}@endsection
@section('meta_author')@endsection
@section('meta_title')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection



{{--微信分享--}}
@section('wx_share_title'){{ config('website.website_name') }}@endsection
@section('wx_share_desc')如未改变生活@endsection
@section('wx_share_imgUrl'){{ url('/softdoc_white_1.png') }}@endsection




{{--header--}}
@section('component-header')
    @include('frontend.component.header-root')
@endsection


{{--footer--}}
@section('component-footer')
    @include('frontend.component.footer')
@endsection


{{--custom-content--}}
@section('custom-body')

    <main class="main-body">

        <section class="module-container" style="padding:32px 0;">
            <section class="main-container">
                <div class="row">

                    <header class="module-row module-header-container text-center">
                        <div class="wow slideInLeft module-title-row title-with-double-line color-1 border-light title-h3"><b>活动</b></div>
                        {{--<div class="wow slideInRight module-subtitle-row color-5 title-h4"><b>description-1</b></div>--}}
                    </header>

                    <div class="module-row module-body-container bg-light-">
                        @include('frontend.component.item-list-2', ['items'=>$activity_items])
                    </div>

                    <footer class="module-row module-footer-container text-center">
                        {{--{{{ $items->links() }}}--}}
                        <a href="{{ url('/item-list?category=activity') }}" class="view-more style-dark">查看更多 <i class="fa fa-hand-o-right"></i></a>
                    </footer>

                </div>
            </section>
        </section>

        <section class="module-container bg-blue-1" style="padding:32px 0;">
            <section class="main-container">
                <div class="row">

                    <header class="module-row module-header-container text-center">
                        <div class="wow slideInLeft module-title-row title-with-double-line color-e border-light title-h3"><b>组织</b></div>
                        {{--<div class="wow slideInRight module-subtitle-row color-5 title-h4"><b>description-1</b></div>--}}
                    </header>

                    <div class="module-row module-body-container bg-light-">
                        @include('frontend.component.org-list-2', ['items'=>$org_list])
                    </div>

                    <footer class="module-row module-footer-container text-center">
                        {{--{{{ $org_list->links() }}}--}}
                        <a href="{{ url('/org-list') }}" class="view-more style-light">查看更多 <i class="fa fa-hand-o-right"></i></a>
                    </footer>

                </div>
            </section>
            {{--@include('frontend.'.env('TEMPLATE').'.module.root-item-list', ['items'=>$items])--}}
        </section>

        <section class="module-container" style="padding:32px 0;">
            <section class="main-container">
                <div class="row">

                    <header class="module-row module-header-container text-center">
                        <div class="wow slideInLeft module-title-row title-with-double-line color-1 border-light title-h3"><b>文章</b></div>
                        {{--<div class="wow slideInRight module-subtitle-row color-5 title-h4"><b>description-1</b></div>--}}
                    </header>

                    <div class="module-row module-body-container bg-light-">
                        @include('frontend.component.item-list-2', ['items'=>$article_items])
                    </div>

                    <footer class="module-row module-footer-container text-center">
                        {{--{{{ $items->links() }}}--}}
                        <a href="{{ url('/item-list?category=article') }}" class="view-more style-dark">查看更多 <i class="fa fa-hand-o-right"></i></a>
                    </footer>

                </div>
            </section>
        </section>

    </main>

    <main class="main-sidebar-fixed">
        @include('frontend.module.sidebar-root')
    </main>

    @include('frontend.component.modal-forward')

@endsection




{{--css--}}
@section('custom-css')
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
        });
    </script>
@endsection
