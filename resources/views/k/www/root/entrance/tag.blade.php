@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')
    {{ $head_title or '朝鲜族社群平台' }}
@endsection
@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')朝鲜族社群组织活动平台,发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社区,朝鲜族社群,朝鲜族组织,朝鲜族活动,朝鲜族社群平台,朝鲜族组织平台,朝鲜族活动平台,朝鲜族生活社区@endsection


@section('wx_share_title')#{{ $q or '' }}@endsection
@section('wx_share_desc')朝鲜族社群平台 - 发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www-wx-share.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-center-section section-wrapper page-item">

        @include(env('TEMPLATE_K_WWW').'component.tag-list')



        <div class="container-box pull-left margin-bottom-16px">
            {{--选择所在城市--}}
            <div class="form-group area_select_box">
                <label class="control-label col-md-2 _none"><sup class="text-red">*</sup> 所在城市</label>
                <div class="col-md-8 " style="padding:0">
                    <div class="col-xs-5 col-sm-5 col-md-4 " style="padding:0">
                        <select name="area_province" class="form-control form-filter area_select_province" id="area_province">
                            @if(!empty($data->area_province))
                                <option value="{{ $data->area_province or '' }}">{{ $data->area_province or '' }}</option>
                            @else
                                <option value="">请选择省</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-xs-5 col-sm-5 col-md-4 " style="padding:0">
                        <select name="area_city" class="form-control form-filter area_select_city" id="area_city">
                            @if(!empty($data->area_city))
                                <option value="{{ $data->area_city or '' }}">{{ $data->area_city or '' }}</option>
                            @else
                                <option value="">请先选择省</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 " style="padding:0">
                        <button type="button" class="btn btn-success" id="city-search-submit" style="width:100%;">
                            <i class="fa fa-search"></i> 查询
                        </button>
    {{--                    <select name="area_district" class="form-control form-filter area_select_district" id="area_district">--}}
    {{--                        @if(!empty($data->area_district))--}}
    {{--                            <option value="{{ $data->area_district or '' }}">{{ $data->area_district or '' }}</option>--}}
    {{--                        @else--}}
    {{--                            <option value="">请先选择市</option>--}}
    {{--                        @endif--}}
    {{--                    </select>--}}
                    </div>
                </div>
            </div>
        </div>

        <div class="container-box pull-left margin-bottom-16px">
            @include(env('TEMPLATE_K_COMMON').'component.user-list',['user_list'=>$user_list])
        </div>

        <div class="container-box pull-left margin-bottom-16px">
            @include(env('TEMPLATE_K_WWW').'component.item-list',['item_list'=>$item_list])
        </div>

        {!! $item_list->links() !!}

    </div>


    {{--右侧--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right">

{{--        @if($auth_check)--}}
{{--            @include(env('TEMPLATE_K_COMMON').'component.right-side.right-me')--}}
{{--        @else--}}
{{--            @include(env('TEMPLATE_K_COMMON').'component.right-side.right-root')--}}
{{--        @endif--}}

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




@section('script')
<script>
    $(function() {
    });
</script>
@endsection
