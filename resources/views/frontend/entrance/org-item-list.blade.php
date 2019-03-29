@extends('frontend.layout.layout')


{{--html.head--}}
@section('head_title'){{ $data->name or '如未' }} - {{ config('website.website_name') }}@endsection
@section('meta_author')@endsection
@section('meta_title')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection




{{--微信分享--}}
@section('wx_share_title'){{ $data->name or '如未' }}@endsection
@section('wx_share_desc')@endsection
@section('wx_share_imgUrl'){{ url(env('DOMAIN_CDN').'/'.$data->logo) }}@endsection




{{--header--}}
@section('component-header')
    @include('frontend.component.header-org')
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

                    <header class="module-row module-header-container text-center _none">
                        <div class="wow slideInLeft module-title-row title-with-double-line color-1 border-light title-h3"><b>Title</b></div>
                        {{--<div class="wow slideInRight module-subtitle-row color-5 title-h4"><b>description-1</b></div>--}}
                    </header>

                    <div class="module-row module-body-container">
                        @include('frontend.component.item-list-1', ['items'=>$items])
                    </div>

                    <footer class="module-row module-footer-container text-center">
                        {{{ $items->appends(['category' => request('category')])->links() }}}
                        {{--<a href="{{ url('/item/list') }}" class="view-more style-dark">查看更多 <i class="fa fa-hand-o-right"></i></a>--}}
                    </footer>

                </div>
            </section>
        </section>

    </main>

    <main class="main-sidebar-fixed">
        @include('frontend.module.sidebar-org')
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
