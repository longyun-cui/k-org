@extends(env('TEMPLATE_K_ORG_FRONT').'layout.layout')


@section('head_title','图文介绍 - 组织管理 - 朝鲜族组织活动平台 - 如未科技')
@section('meta_author')@endsection
@section('meta_title')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_ORG_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','组织管理 - 朝鲜族组织活动平台 - 如未科技')
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
                        <a href="{{url('/org/mine/my-info-introduction-edit')}}">
                            <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-edit"></i> 编辑图文</button>
                        </a>
                    </div>
                </div>

                <form class="form-horizontal form-bordered">
                <div class="box-body">
                    {{--描述/简介--}}
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
                        <div class="col-md-8 col-md-offset-3-">
                            <a href="{{ url('/org/mine/my-info-edit') }}">
                                <button type="button" onclick="" class="btn btn-success"><i class="fa fa-edit"></i> 编辑信息</button>
                            </a>
                            <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


    {{--右侧--}}
    <div class="main-body-section main-body-right-section section-wrapper hidden-xs">
        @include(env('TEMPLATE_K_ORG_FRONT').'component.right-side.right-root')
    </div>

</div>
@endsection




@section('style')
    <style>
        .info-img-block {width: 100px;height: 100px;overflow: hidden;}
        .info-img-block img {width: 100%;}
    </style>
@endsection




@section('js')
<script>
    $(function() {
        // with plugin options
        $("#input-id").fileinput({'showUpload':false, 'previewFileType':'any'});
    });
</script>
@endsection
