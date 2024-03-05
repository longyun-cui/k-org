@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title','图文介绍 - 朝鲜族生活社群 - 如未科技')
@section('meta_author')@endsection
@section('meta_title')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','朝鲜族生活社群 - 如未科技')
@section('breadcrumb')
@endsection
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-left-section section-wrapper page-item">
        <div class="main-body-left-container bg-white">


            <div class="box box-info form-container">

                <div class="box-header with-border" style="margin: 15px 0;">
                    <h3 class="box-title">图文介绍</h3>
                    <div class="pull-right">
                        <a href="{{ url('/mine/my-profile-intro-edit') }}">
                            <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-edit"></i> 编辑图文</button>
                        </a>
                    </div>
                </div>

                <form class="form-horizontal form-bordered">
                <div class="box-body">
                    {{--描述--}}
                    <div class="form-group">
                        {{--<label class="control-label col-md-3">描述/简介：</label>--}}
                        <div class="col-md-12 item-row item-description-row with-background margin-bottom-16px">
                            {{ $data->ext->description or '暂无描述' }}
                        </div>
                    </div>
                    {{--图文介绍--}}
                    <div class="form-group">
                        {{--<label class="control-label col-md-3">描述/简介：</label>--}}
                        <div class="col-md-12">
                            {!! $data->ext->content or '' !!}
                        </div>
                    </div>
                </div>
                </form>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12 col-md-offset-3-">
                            <a href="{{ url('/mine/my-profile-intro-edit') }}">
                                <button type="button" onclick="" class="btn btn-success"><i class="fa fa-edit"></i> 编辑图文</button>
                            </a>
                            <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    {{--右侧--}}
    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm">

        @if($auth_check)
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-me')
        @else
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.right-side.right-root')
        @endif

    </div>

</div>
@endsection




@section('custom-style')
    <style>
    </style>
@endsection




@section('custom-script')
<script>
    $(function() {
        // with plugin options
        $("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});
    });
</script>
@endsection