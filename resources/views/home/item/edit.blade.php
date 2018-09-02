@extends('home.layout.layout')

@section('head_title')
    @if($operate == 'create') 添加内容 @else 编辑内容 @endif
@endsection

@section('header')
@endsection

@section('description')
@endsection

@section('breadcrumb')
    <li><a href="{{url('/home')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="{{url('/home/item/list')}}"><i class="fa "></i>内容列表</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info form-container">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title"> @if($operate == 'create') 添加内容 @else 编辑内容 @endif </h3>
                <div class="box-tools pull-right">
                </div>
            </div>

            <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-item">
            <div class="box-body">

                {{csrf_field()}}
                <input type="hidden" name="operate" value="{{$operate or 'create'}}" readonly>
                <input type="hidden" name="id" value="{{$encode_id or encode(0)}}" readonly>

                {{--类别--}}
                <div class="form-group form-category _none">
                    <label class="control-label col-md-2">类别</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            <button type="button" class="btn radio">
                                <div class="">
                                    <label>
                                        <input type="radio" name="category" value="0"
                                               @if($operate == 'create' || ($operate == 'edit' && $data->category == 0)) checked="checked" @endif> 未定义
                                    </label>
                                </div>
                            </button>

                            <button type="button" class="btn">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="category" value="2"
                                               @if($operate == 'edit' && $data->category == 2) checked="checked" @endif> 关于我们
                                    </label>
                                </div>
                            </button>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">时间</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            <button type="button" class="btn checkbox">
                                <label>
                                    <input type="checkbox" name="time_type" value="1"
                                           @if($operate == 'edit' && $data->time_type == 1) checked="checked" @endif> 选择时间
                                </label>
                            </button>

                        </div>
                    </div>
                </div>

                {{--标题--}}
                <div class="form-group">
                    <label class="control-label col-md-2">标题</label>
                    <div class="col-md-8 ">
                        <div><input type="text" class="form-control" name="title" placeholder="请输入标题" value="{{$data->title or ''}}"></div>
                    </div>
                </div>
                {{--说明--}}
                <div class="form-group">
                    <label class="control-label col-md-2">描述</label>
                    <div class="col-md-8 ">
                        <div><textarea class="form-control" name="description" rows="3" placeholder="描述">{{$data->description or ''}}</textarea></div>
                    </div>
                </div>
                {{--内容--}}
                <div class="form-group">
                    <label class="control-label col-md-2">介绍详情</label>
                    <div class="col-md-8 ">
                        <div>
                            @include('UEditor::head')
                            <!-- 加载编辑器的容器 -->
                            <script id="container" name="content" type="text/plain">{!! $data->content or '' !!}</script>
                            <!-- 实例化编辑器 -->
                            <script type="text/javascript">
                                var ue = UE.getEditor('container');
                                ue.ready(function() {
                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                });
                            </script>
                        </div>
                    </div>
                </div>

                {{--cover 封面图片--}}
                <div class="form-group">
                    <label class="control-label col-md-2">封面图片</label>
                    <div class="col-md-8 fileinput-group">

                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail">
                                @if(!empty($data->cover_pic))
                                    <img src="{{url(env('DOMAIN_CDN').'/'.$data->cover_pic)}}" alt="" />
                                @endif
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail">
                            </div>
                            <div class="btn-tool-group">
                                <span class="btn-file">
                                    <button class="btn btn-sm btn-primary fileinput-new">选择图片</button>
                                    <button class="btn btn-sm btn-warning fileinput-exists">更改</button>
                                    <input type="file" name="cover" />
                                </span>
                                <span class="">
                                    <button class="btn btn-sm btn-danger fileinput-exists" data-dismiss="fileinput">移除</button>
                                </span>
                            </div>
                        </div>
                        <div id="titleImageError" style="color: #a94442"></div>

                    </div>
                </div>

                @if($operate == 'create')
                <div class="form-group">
                    <label class="control-label col-md-2">待办事</label>
                    <div class="col-md-8">
                        <div class="btn-group">
                            <button type="button" class="btn checkbox">
                                <label>
                                    <input type="checkbox" name="is_working" value="1"> 添加到我的待办事
                                </label>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                {{--分享--}}
                @if($operate == 'create')
                    <div class="form-group form-type">
                        <label class="control-label col-md-2">分享</label>
                        <div class="col-md-8">
                            <div class="btn-group">

                                <button type="button" class="btn radio">
                                    <label>
                                        <input type="radio" name="is_shared" value="11" checked="checked"> 仅自己可见
                                    </label>
                                </button>
                                <button type="button" class="btn radio">
                                    <label>
                                        <input type="radio" name="is_shared" value="41"> 关注可见
                                    </label>
                                </button>
                                <button type="button" class="btn radio">
                                    <label>
                                        <input type="radio" name="is_shared" value="100"> 所有人可见
                                    </label>
                                </button>

                            </div>
                        </div>
                    </div>
                @endif

            </div>
            </form>

            <div class="box-footer">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="button" class="btn btn-primary" id="edit-item-submit"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endsection


@section('js')
<script>
    $(function() {
        // 修改幻灯片信息
        $("#edit-item-submit").on('click', function() {
            var options = {
                url: "/home/item/edit",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "/home/item/list";
                    }
                }
            };
            $("#form-edit-item").ajaxSubmit(options);
        });
    });
</script>
@endsection
