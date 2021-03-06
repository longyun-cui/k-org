@extends('org-admin.layout.layout')

@section('head_title')
    @if($operate == 'create') 添加内容 @else 编辑内容 @endif
@endsection

@section('header')
    @if($operate == 'create') 添加内容 @else 编辑内容 @endif
@endsection

@section('description')
@endsection

@section('breadcrumb')
    <li><a href="{{url('/org-admin')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="{{url('/org-admin/item/list')}}"><i class="fa "></i>内容列表</a></li>
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
                <input type="hidden" name="operate" value="{{ $operate or 'create' }}" readonly>
                <input type="hidden" name="id" value="{{ $encode_id or encode(0) }}" readonly>

                {{--类别--}}
                <div class="form-group form-category">
                    <label class="control-label col-md-2">类别</label>
                    <div class="col-md-8">
                        <div class="btn-group">

                            @if($operate == 'edit')
                                <label class="btn" role="button">
                                    <input type="radio" name="category-" value="{{ $data->category or 0 }}" checked="checked">
                                    @if($data->category == 1) 文章
                                    @elseif($data->category == 11) 活动
                                    @elseif($data->category == 88) 赞助广告
                                    @endif
                                </label>
                            @elseif($operate == 'create')
                                <label class="btn" role="button">
                                    <input type="radio" name="category" value="1" checked="checked"> 文章
                                </label>

                                <label class="btn" role="button">
                                    <input type="radio" name="category" value="11"> 活动
                                </label>

                                <label class="btn" role="button">
                                    <input type="radio" name="category" value="88"> 赞助广告
                                </label>
                            @endif

                        </div>
                    </div>
                </div>

                {{--时间选择器--}}
                <div class="form-group article-show time-show _none">
                    <label class="control-label col-md-2">选择时间</label>
                    <div class="col-md-8 ">
                        <div class="col-md-6" style="padding-left:0;">
                            <input type="text" class="form-control" name="start_time" placeholder="选择开始时间" value="{{$data->start_time or ''}}">
                        </div>
                        <div class="col-md-6" style="padding-right:0;">
                            <input type="text" class="form-control" name="end_time" placeholder="选择结束时间" value="{{$data->end_time or ''}}">
                        </div>
                    </div>
                </div>

                @if($operate == 'edit' && $data->time_type == 1)
                    <div class="form-group">
                        <label class="control-label col-md-2">选择时间</label>
                        <div class="col-md-8 ">
                            <div class="col-md-6" style="padding-left:0;">
                                <input type="text" readonly class="form-control" name="start_time-" value="@if($data->start_time != 0){{ time_show($data->start_time) }}@endif">
                            </div>
                            <div class="col-md-6" style="padding-right:0;">
                                <input type="text" readonly class="form-control" name="end_time-" value="@if($data->end_time != 0){{ time_show($data->end_time) }}@endif">
                            </div>
                        </div>
                    </div>
                @endif

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
                                    <img src="{{ url(env('DOMAIN_CDN').'/'.$data->cover_pic) }}" alt="" />
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

                {{--分享--}}
                @if($operate == 'create')
                    <div class="form-group form-type">
                        <label class="control-label col-md-2">分享</label>
                        <div class="col-md-8">
                            <div class="btn-group">

                                <label class="btn" role="button">
                                    <input type="radio" name="is_shared" value="11" checked="checked"> 仅自己可见
                                </label>
                                <label class="btn" role="button">
                                    <input type="radio" name="is_shared" value="41"> 关注可见
                                </label>
                                <label class="btn" role="button">
                                    <input type="radio" name="is_shared" value="100"> 所有人可见
                                </label>

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

        // 提交
        $("#edit-item-submit").on('click', function() {
            var options = {
                url: "/org-admin/item/edit",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "/org-admin/item/list";
                    }
                }
            };
            $("#form-edit-item").ajaxSubmit(options);
        });


        // 【选择类别】
        $("#form-edit-item").on('click', "input[name=category]", function() {
            var $value = $(this).val();

            if($value == 1) {
                $('.article-show').show();

                // checkbox
//                if($("input[name=time_type]").is(':checked')) {
//                    $('.time-show').show();
//                } else {
//                    $('.time-show').hide();
//                }
                // radio
                var $time_type = $("input[name=time_type]:checked").val();
                if($time_type == 1) {
                    $('.time-show').show();
                } else {
                    $('.time-show').hide();
                }
            } else {
                $('.article-show').hide();
            }

            if($value == 11) {
                $('.time-show').show();
            } else {
                $('.time-show').hide();
            }

        });


        // 【选择时间】
        $("#form-edit-item").on('click', "input[name=time_type]", function() {
            // checkbox
//            if($(this).is(':checked')) {
//                $('.time-show').show();
//            } else {
//                $('.time-show').hide();
//            }
            // radio
            var $value = $(this).val();
            if($value == 1) {
                $('.time-show').show();
            } else {
                $('.time-show').hide();
            }
        });


        $('input[name=start_time]').datetimepicker({
            format:"YYYY-MM-DD HH:mm"
        });
        $('input[name=end_time]').datetimepicker({
            format:"YYYY-MM-DD HH:mm"
        });

    });
</script>
@endsection
