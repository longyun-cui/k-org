@extends(env('TEMPLATE_K_WWW').'layout.layout')

@section('head_title','编辑名片 - 如未科技')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')@endsection
@section('wx_share_desc')@endsection
@section('wx_share_imgUrl')@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div class="main-body-section main-body-left-section section-wrapper page-root">
        <div class="container-box pull-left margin-bottom-16px">

            <div class="box box-info form-container">

                <div class="box-header with-border" style="margin:8px 0;">
                    <h3 class="box-title">编辑名片</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>

                <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-info">
                    <div class="box-body">

                        {{ csrf_field() }}
                        <input type="hidden" name="operate[type]" value="{{ $operate or 'create' }}" readonly>
                        <input type="hidden" name="operate[id]" value="{{ $operate_id or 0 }}" readonly>

                        {{--名称--}}
                        <div class="form-group _none">
                            <label class="control-label- col-md-2">昵称</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="name" placeholder="请输入用户名" value="{{ $data->username or '' }}">
                            </div>
                        </div>
                        {{--真实姓名--}}
                        <div class="form-group">
                            <label class="control-label- col-md-6">姓名(名片展示)</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="true_name" placeholder="真实姓名" value="{{ $data->true_name or '' }}">
                            </div>
                        </div>
                        {{--真实姓名--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">单位名称</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="company" placeholder="单位名称" value="{{ $data->company or '' }}">
                            </div>
                        </div>
                        {{--职位--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">职位</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="position" placeholder="职位" value="{{ $data->position or '' }}">
                            </div>
                        </div>
                        {{--商业描述--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">商业描述</label>
                            <div class="col-md-12 ">
{{--                                <textarea class="form-control" name="business_description" rows="3" cols="" placeholder="商业描述">{{ $data->business_description or '' }}</textarea>--}}
                                <input type="text" class="form-control" name="business_description" placeholder="商业描述" value="{{ $data->business_description or '' }}">
                            </div>
                        </div>
                        {{--地址--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">联系地址</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="contact_address" placeholder="联系地址" value="{{ $data->contact_address or '' }}">
                            </div>
                        </div>
                        {{--电话--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">联系电话</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="contact_phone" placeholder="联系电话" value="{{ $data->contact_phone or '' }}">
                            </div>
                        </div>
                        {{--邮箱--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="control-label- col-md-2">联系邮箱</label>--}}
{{--                            <div class="col-md-12 ">--}}
{{--                                <input type="text" class="form-control" name="email" placeholder="邮箱" value="{{ $data->email or '' }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--QQ--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="control-label- col-md-2">QQ</label>--}}
{{--                            <div class="col-md-12 ">--}}
{{--                                <input type="text" class="form-control" name="QQ_number" placeholder="QQ" value="{{ $data->QQ_number or '' }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--微信号--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">微信号</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="wx_id" placeholder="微信号" value="{{ $data->wx_id or '' }}">
                            </div>
                        </div>
                        {{--微信二维码--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">微信二维码</label>
                            <div class="col-md-12 fileinput-group">

                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        @if(!empty($data->wx_qr_code_img))
                                            <img src="{{ url(env('DOMAIN_CDN').'/'.$data->wx_qr_code_img) }}" alt="" />
                                        @endif
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail">
                                    </div>
                                    <div class="btn-tool-group">
                            <span class="btn-file">
                                <button class="btn btn-sm btn-primary fileinput-new">选择图片</button>
                                <button class="btn btn-sm btn-warning fileinput-exists">更改</button>
                                <input type="file" name="wx_qr_code" />
                            </span>
                                        <span class="">
                                <button class="btn btn-sm btn-danger fileinput-exists" data-dismiss="fileinput">移除</button>
                            </span>
                                    </div>
                                </div>
                                <div id="titleImageError1" style="color:#a94442"></div>

                            </div>
                        </div>
                        {{--微博名称--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="control-label- col-md-2">微博名称</label>--}}
{{--                            <div class="col-md-12 ">--}}
{{--                                <input type="text" class="form-control" name="wb_name" placeholder="微博名称" value="{{ $data->weibo_name or '' }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--微博地址--}}
{{--                        <div class="form-group">--}}
{{--                            <label class="control-label- col-md-2">微博地址</label>--}}
{{--                            <div class="col-md-12 ">--}}
{{--                                <input type="text" class="form-control" name="wb_address" placeholder="微博地址，请携带http或https" value="{{ $data->weibo_address or '' }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--网站--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">网站</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="website" placeholder="网站地址，请携带http或https" value="{{ $data->website or '' }}">
                            </div>
                        </div>
                        {{--描述--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">个人签名</label>
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="description" placeholder="个人签名" value="{{ $data->description or '' }}">
{{--                                <textarea class="form-control" name="description" rows="3" cols="" placeholder="个人签名">{{ $data->description or '' }}</textarea>--}}
                            </div>
                        </div>

                        {{--图文介绍--}}
                        <div class="form-group">
                            <label class="control-label- col-md-3">图文介绍</label>
                            <div class="col-md-12 ">
                                <div>
                                @include('UEditor::head')
                                <!-- 加载编辑器的容器 -->
                                    <script id="container" name="content" type="text/plain">{!! $data->ext->content or '' !!}</script>
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

                        {{--头像--}}
                        <div class="form-group">
                            <label class="control-label- col-md-2">头像</label>
                            <div class="col-md-12 fileinput-group">

                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        @if(!empty($data->portrait_img))
                                            <img src="{{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}" alt="" />
                                        @endif
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail">
                                    </div>
                                    <div class="btn-tool-group">
                            <span class="btn-file">
                                <button class="btn btn-sm btn-primary fileinput-new">选择图片</button>
                                <button class="btn btn-sm btn-warning fileinput-exists">更改</button>
                                <input type="file" name="portrait" />
                            </span>
                                        <span class="">
                                <button class="btn btn-sm btn-danger fileinput-exists" data-dismiss="fileinput">移除</button>
                            </span>
                                    </div>
                                </div>
                                <div id="titleImageError" style="color: #a94442"></div>

                            </div>
                        </div>

                    </div>
                </form>

                <div class="box-footer">
                    <div class="row" style="margin:8px 0;">
                        <div class="col-md-10 col-md-offset-2-">
                            <button type="button" onclick="" class="btn btn-primary" id="edit-info-submit"><i class="fa fa-check"></i>提交</button>
                            <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm">

        @if($auth_check)
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-me')
        @else
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-root')
        @endif

    </div>

</div>
@endsection




@section('style')
<style>
</style>
@endsection




@section('script')
<script>
    $(function() {

        $("#edit-info-submit").on('click', function() {
            var options = {
                url: "/mine/my-card-edit",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
//                        location.href = "/my-info/index";
//                         location.href = "/mine/my-card-index";
                        location.href = "/user/{{ $me->id }}";
                    }
                }
            };
            $("#form-edit-info").ajaxSubmit(options);
        });
    });
</script>
@endsection