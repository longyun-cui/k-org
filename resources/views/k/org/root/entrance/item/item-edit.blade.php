@extends(env('TEMPLATE_K_ORG').'layout.layout')


@section('head_title')
    {{ $title_text }} - 组织管理 - 朝鲜族组织活动平台 - 如未科技
@endsection




@section('header', '')
@section('description', '组织管理 - 朝鲜族组织活动平台 - 如未科技')
@section('breadcrumb')
@endsection
@section('content')
<div class="container">

    {{--左侧--}}
    <div class="main-body-section main-body-center-section section-wrapper page-item">
        <div class="main-body-left-container bg-white">

            <div class="box box-info form-container">

                <div class="box-header with-border" style="margin:16px 0;">
                    <h3 class="box-title">{{ $title_text or '' }}</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>

                <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-item">
                <div class="box-body">

                    {{ csrf_field() }}
                    <input type="hidden" name="operate" value="{{ $operate or 'create' }}" readonly>
                    <input type="hidden" name="operate_id" value="{{ $operate_id or 0 }}" readonly>
                    <input type="hidden" name="operate_category" value="{{ $operate_category or 'create' }}" readonly>
                    <input type="hidden" name="operate_type" value="{{ $operate_type or 'item' }}" readonly>
                    <input type="hidden" name="operate_item_category" value="{{ $operate_item_category or 'item' }}" readonly>
                    <input type="hidden" name="operate_item_type" value="{{ $operate_item_type or 'item' }}" readonly>


                    {{--类别--}}
                    <div class="form-group form-category">
                        <label class="control-label- col-md-9">类别</label>
                        <div class="col-md-12">
                            <div class="btn-group">

                                <button type="button" class="btn">
                                    <div class="radio">
                                        <label>
                                            @if($operate == 'create' || ($operate == 'edit' && $data->item_type == 1))
                                                <input type="radio" name="item_type" value="1" checked="checked"> 文章
                                            @else
                                                <input type="radio" name="item_type" value="1"> 文章
                                            @endif

                                        </label>
                                    </div>
                                </button>
                                <button type="button" class="btn">
                                    <div class="radio">
                                        <label>
                                            @if($operate == 'edit' && $data->item_type == 11)
                                                <input type="radio" name="item_type" value="11" checked="checked"> 活动
                                            @else
                                                <input type="radio" name="item_type" value="11"> 活动
                                            @endif
                                        </label>
                                    </div>
                                </button>
                                <button type="button" class="btn">
                                    <div class="radio">
                                        <label>
                                            @if($operate == 'edit' && $data->item_type == 88)
                                                <input type="radio" name="item_type" value="88" checked="checked"> 广告
                                            @else
                                                <input type="radio" name="item_type" value="88"> 广告
                                            @endif
                                        </label>
                                    </div>
                                </button>

                            </div>
                        </div>
                    </div>

                    {{--活动时间--}}
                    <div class="form-group activity-box" @if($operate_item_type != "activity") style="display:none;" @endif>
{{--                        <label class="control-label- col-md-9">活动时间</label>--}}
                        <div class="col-md-12 ">
                            <div class="col-sm-6 col-md-6 padding-0 has-feedback">
                                <input type="text" class="form-control" name="start" placeholder="开始时间"
                                       @if(!empty($data->start_time)) value="{{ date("Y-m-d H:i",$data->start_time) }}" @endif
                                >
                                <span class="form-control-feedback fa fa-clock-o"> 开始时间</span>
                            </div>
                            <div class="col-sm-6 col-md-6 padding-0 has-feedback">
                                <input type="text" class="form-control" name="end" placeholder="结束时间"
                                       @if(!empty($data->end_time)) value="{{ date("Y-m-d H:i",$data->end_time) }}" @endif
                                >
                                <span class="form-control-feedback fa fa-clock-o"> 结束时间</span>
                            </div>
                        </div>
                    </div>
                    {{--活动地点--}}
                    <div class="form-group has-feedback activity-box" @if($operate_item_type != "activity") style="display:none;" @endif>
{{--                        <label class="control-label- col-md-9">活动地点</label>--}}
                        <div class="col-md-12 ">
                            <input type="text" class="form-control" name="address" placeholder="活动地点" value="{{ $data->address or '' }}">
                            <span class="form-control-feedback fa fa-location-arrow"> 活动地点</span>
                        </div>
                    </div>


                    {{--标题--}}
                    <div class="form-group has-feedback">
{{--                        <label class="control-label- col-md-9"><sup class="text-red">*</sup> 标题</label>--}}
                        <div class="col-md-12 ">
                            <input type="text" class="form-control" name="title" placeholder="标题" value="{{ $data->title or '' }}">
                            <span class="form-control-feedback fa fa-file-text-o"> 标题</span>
                        </div>
                    </div>

                    {{--描述--}}
                    <div class="form-group has-feedback">
{{--                        <label class="control-label- col-md-9">描述</label>--}}
                        <div class="col-md-12 ">
                            <textarea class="form-control" name="description" rows="3" placeholder="描述">{{$data->description or ''}}</textarea>
                            <span class="form-control-feedback fa fa-file-text"> 描述</span>
                        </div>
                    </div>

                    {{--链接地址--}}
                    <div class="form-group _none">
                        <label class="control-label- col-md-9">链接地址</label>
                        <div class="col-md-12 ">
                            <input type="text" class="form-control" name="link_url" placeholder="链接地址" value="{{ $data->link_url or '' }}">
                        </div>
                    </div>

                    {{--目录--}}
                    <div class="form-group _none">
                        <label class="control-label- col-md-9">目录</label>
                        <div class="col-md-12 ">
                            <select class="form-control" onchange="select_menu()">
                                <option data-id="0">未分类</option>
                                {{--@if(!empty($data->menu_id))--}}
                                    {{--@foreach($menus as $v)--}}
                                        {{--<option data-id="{{$v->id}}" @if($data->menu_id == $v->id) selected="selected" @endif>{{$v->title}}</option>--}}
                                    {{--@endforeach--}}
                                {{--@else--}}
                                    {{--@foreach($menus as $v)--}}
                                        {{--<option data-id="{{$v->id}}">{{$v->title}}</option>--}}
                                    {{--@endforeach--}}
                                {{--@endif--}}
                            </select>
                            <input type="hidden" value="{{ $data->menu_id or 0 }}" name="menu_id-" id="menu-selected">
                        </div>
                    </div>
                    {{--目录--}}
                    <div class="form-group _none">
                        <label class="control-label- col-md-9">添加目录</label>
                        <div class="col-md-12 ">
                            <select name="menus[]" id="menus" multiple="multiple" style="width:100%;">
                                {{--<option value="{{$data->people_id or 0}}">{{$data->people->name or '请选择作者'}}</option>--}}
                            </select>
                        </div>
                    </div>

                    {{--内容--}}
                    <div class="form-group">
                        <label class="control-label- col-md-9">内容详情</label>
                        <div class="col-md-12 ">
                            <div>
                                @include('UEditor::head')
                                <!-- 加载编辑器的容器 -->
                                <script id="container" name="content" type="text/plain">{!! $data->content or '' !!}</script>
                                <!-- 实例化编辑器 -->
                                <script type="text/javascript">
                                    var ue = UE.getEditor('container');
                                    ue.ready(function() {
                                        ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');  // 此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                    {{--多图展示--}}
                    <div class="form-group _none">
                        <label class="control-label- col-md-9">多图展示</label>
                        <div class="col-md-12 fileinput-group">
                            @if(!empty($data->custom2))
                                @foreach($data->custom2 as $img)
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail">
                                            <img src="{{ url(env('DOMAIN_CDN').'/'.$img->img) }}" alt="" />
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                            @endif
                        </div>

                        <div class="col-md-12 col-md-offset-2 ">
                            <input id="multiple-images" type="file" class="file-" name="multiple_images[]" multiple >
                        </div>
                    </div>

                    {{--cover 封面图片--}}
                    <div class="form-group">
                        <label class="control-label- col-md-9">封面图片</label>
                        <div class="col-md-12 fileinput-group">

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

                    {{--attachment 附件--}}
                    <div class="form-group _none">
                        <label class="control-label- col-md-9">附件</label>
                        <div class="col-md-12 fileinput-group">

                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail">
                                    <a target="_blank" href="/all/download-item-attachment?item-id={{ $data->id or 0 }}">
                                        {{ $data->attachment_name or '' }}
                                    </a>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail">
                                </div>
                                <div class="btn-tool-group">
                                    <span class="btn-file">
                                        <button class="btn btn-sm btn-primary fileinput-new">选择附件</button>
                                        <button class="btn btn-sm btn-warning fileinput-exists">更改</button>
                                        <input type="file" name="attachment" />
                                    </span>
                                    <span class="">
                                        <button class="btn btn-sm btn-danger fileinput-exists" data-dismiss="fileinput">移除</button>
                                    </span>
                                </div>
                            </div>
                            <div id="titleImageError" style="color: #a94442"></div>

                        </div>
                    </div>

                    {{--启用--}}
                    @if($operate == 'create')
                        <div class="form-group form-type _none">
                            <label class="control-label- col-md-9">启用</label>
                            <div class="col-md-12">
                                <div class="btn-group">

                                    <button type="button" class="btn">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="active-" value="0"> 暂不启用
                                            </label>
                                        </div>
                                    </button>
                                    <button type="button" class="btn">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="active-" value="1" checked="checked"> 启用
                                            </label>
                                        </div>
                                    </button>

                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                </form>

                <div class="box-footer">
                    <div class="row-">
                        <div class="col-md-12 col-md-offset-2-">
                            <button type="button" class="btn btn-success" id="edit-item-submit"><i class="fa fa-check"></i> 提交</button>
                            <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{--右侧--}}
{{--    <div class="main-body-section main-body-right-section section-wrapper hidden-xs">--}}
{{--        @include(env('TEMPLATE_K_ORG_FRONT').'component.right-side.right-root')--}}
{{--    </div>--}}

</div>
@endsection




@section('custom-css')
{{--<link rel="stylesheet" href="https://cdn.bootcss.com/select2/4.0.5/css/select2.min.css">--}}
<link rel="stylesheet" href="{{ asset('/lib/css/select2-4.0.5.min.css') }}">
@endsection
@section('custom-style')
    <style>
        .activity-box {}
        .form-horizontal .has-feedback.padding-0 .form-control-feedback { right:0; }
    </style>
@endsection




@section('custom-script')
{{--<script src="https://cdn.bootcss.com/select2/4.0.5/js/select2.min.js"></script>--}}
<script src="{{ asset('/lib/js/select2-4.0.5.min.js') }}"></script>
<script>
    $(function() {

        $("#multiple-images").fileinput({
            allowedFileExtensions : [ 'jpg', 'jpeg', 'png', 'gif' ],
            showUpload: false
        });


        // 【选择时间】
        $("#form-edit-item").on('click', "input[name=item_type]", function() {
            // checkbox
//            if($(this).is(':checked')) {
//                $('.time-show').show();
//            } else {
//                $('.time-show').hide();
//            }
            // radio
            var $value = $(this).val();
            if($value == 11) {
                $('.activity-box').show();
            } else {
                $('.activity-box').hide();
            }
        });


        $('input[name=start]').datetimepicker({
            locale: moment.locale('zh-cn'),
            format:"YYYY-MM-DD HH:mm"
        });
        $('input[name=end]').datetimepicker({
            locale: moment.locale('zh-cn'),
            format:"YYYY-MM-DD HH:mm"
        });

        // 添加or编辑
        $("#edit-item-submit").on('click', function() {
            var options = {
                url: "{{ url('/mine/item/item-create') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "{{ url('/') }}";
                    }
                }
            };
            $("#form-edit-item").ajaxSubmit(options);
        });

        $('#menus').select2({
            ajax: {
                url: "{{url('/mine/item/select2_menus')}}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
//                    console.log(data);
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            theme: 'classic'
        });

    });
</script>
@endsection
