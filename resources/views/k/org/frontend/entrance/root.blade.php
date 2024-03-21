@extends(env('TEMPLATE_K_ORG_FRONT').'layout.layout')


@section('head_title')
    {{ $head_title or 'ORG组织 - 朝鲜族生活社群 - 发现身边的朝鲜族社群组织' }}
@endsection
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族生活社群@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_ORG_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div class="main-body-section main-section right-section section-wrapper page-item">


        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ $menu_active_for_item_all or '' }}"><a href="/org/" data-toggle="tab-">全部内容</a></li>
                <li class="{{ $menu_active_for_item_unpublished or '' }}"><a href="/org?item-type=unpublished" data-toggle="tab-">待发布内容</a></li>
            </ul>
            <div class="tab-content" style="width:100%; padding:10px 0;float:left;">
                <div class="active tab-pane" id="all">
                    @if(!empty($item_list) && count($item_list))
                        @include(env('TEMPLATE_K_COMMON_FRONT').'component.item-list',['item_list'=>$item_list])
                    @endif
                </div>
                {{--<div class="tab-pane" id="timeline">--}}
                {{--</div>--}}

                {{--<div class="tab-pane" id="settings">--}}
                {{--</div>--}}
            </div>
        </div>

        {{--<div class="container-box pull-left margin-bottom-16px">--}}
            {{--@include(env('TEMPLATE_K_ORG_FRONT').'component.item-list',['item_list'=>$item_list])--}}
        {{--</div>--}}

        {{--{!! $item_list->links() !!}--}}

    </div>


    <div class="main-body-section side-section left-section section-wrapper pull-right hidden-xs hidden-sm">


        <div class="fixed-to-top">
        @include(env('TEMPLATE_K_ORG_FRONT').'component.right-side.right-root')
        {{--@include(env('TEMPLATE_K_ORG_FRONT').'component.right-me')--}}
        </div>

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
    @include(env('TEMPLATE_K_ORG_FRONT').'component.item-list-script')
@endsection
@section('script')
<script>
    $(function() {
//        $('article').readmore({
//            speed: 150,
//            moreLink: '<a href="#">展开更多</a>',
//            lessLink: '<a href="#">收起</a>'
//        });
    });
</script>
@endsection