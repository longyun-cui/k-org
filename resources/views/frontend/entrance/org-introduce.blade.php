@extends('frontend.layout.layout')


{{--html.head--}}
@section('head_title'){{ $data->name or '介绍' }} - {{ config('website.website_name') }}@endsection
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
    @include('frontend.component.header-user')
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

                    @include('frontend.component.org-introduce', ['org'=>$data])

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
