@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')


@section('head_title','朝鲜族组织平台')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族组织平台@endsection
@section('wx_share_desc')平台介绍@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
    <div style="display:none;">
        <input type="hidden" id="" value="{{ $encode or '' }}" readonly>
    </div>

    <div class="container">

        {{--左侧--}}
        <div class="main-body-section main-body-left-section section-wrapper">

            <div class="item-piece item-option">
                <div class="box-body item-row item-content-row">
                    <div class="item-row">
                        <h4>平台介绍</h4>
                    </div>


                    @if(!empty($data->description))
                        <div class="item-row item-description-row text-muted margin-bottom-8px">
                            {{ $data->description or '' }}
                        </div>
                    @endif

                    <div class="item-row">
                        @if(!empty($data->content))
                            {!! $data->content or '' !!}
                        @else
                            <small>暂无简介</small>
                        @endif
                    </div>
                </div>
            </div>

        </div>


        {{--右侧--}}
        <div class="main-body-section main-body-right-section section-wrapper">

            @if($auth_check)
                @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-me')
            @else
                @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-root')
            @endif

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
    <script>
        $(function() {
//            $('article').readmore({
//                speed: 150,
//                moreLink: '<a href="#">展开更多</a>',
//                lessLink: '<a href="#">收起</a>'
//            });
        });
    </script>
@endsection
