@extends(env('TEMPLATE_ADMIN').'org.layout.layout')


@section('head_title','图文详情 - 组织后台管理系统 - 朝鲜族组织活动平台 - 如未科技')
@section('meta_author')@endsection
@section('meta_title')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('header','')
@section('description','组织后台管理系统 - 朝鲜族组织活动平台 - 如未科技')
@section('breadcrumb')
    <li><a href="{{ url('/org') }}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info form-container">

            <div class="box-header with-border" style="margin: 15px 0;">
                <h3 class="box-title">图文详情</h3>
                <div class="pull-right">
                    <a href="{{ url('/org/introduction/edit') }}">
                        <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-edit"></i> 编辑图文</button>
                    </a>
                </div>
            </div>

            <form class="form-horizontal form-bordered">
            <div class="box-body">

                {{--标题--}}
                <div class="form-group">
                    <label class="control-label col-md-2">标题：</label>
                    <div class="col-md-8 ">
                        <div>{{ $data->title or '' }}</div>
                    </div>
                </div>
                {{--描述--}}
                <div class="form-group">
                    <label class="control-label col-md-2">描述：</label>
                    <div class="col-md-8 ">
                        <div>{{ $data->description or '' }}</div>
                    </div>
                </div>
                {{--图文详情--}}
                <div class="form-group">
                    <label class="control-label col-md-2">图文详情：</label>
                    <div class="col-md-8 ">
                        <div>{!! $data->content or '' !!}</div>
                    </div>
                </div>
                {{--cover--}}
                <div class="form-group">
                    <label class="control-label col-md-2">封面图片：</label>
                    <div class="col-md-8 ">
                        <div class="info-img-block" style="width:120px;height:120px;overflow:hidden;">
                            @if(!empty($data->cover_pic))
                            <img src="{{ url(env('DOMAIN_CDN').'/'.$data->cover_pic) }}" alt="">
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            </form>

            <div class="box-footer">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <a href="{{ url('/org/introduction/edit') }}">
                            <button type="button" onclick="" class="btn btn-success"><i class="fa fa-edit"></i> 编辑图文</button>
                        </a>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
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
