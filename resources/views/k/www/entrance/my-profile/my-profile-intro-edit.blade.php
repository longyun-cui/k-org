@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title','编辑图文介绍 -朝鲜族生活社群 - 如未科技')
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

                <div class="box-header with-border" style="margin:16px 0;">
                    <h3 class="box-title">编辑图文介绍</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>

                <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-intro">
                <div class="box-body">

                    {{ csrf_field() }}

                    {{--描述--}}
                    <div class="form-group">
                        <label class="control-label- col-md-3">描述</label>
                        <div class="col-md-12 ">
                            <textarea class="form-control" name="description" rows="3" placeholder="请填写描述">{!! $data->ext->description or '' !!}</textarea>
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

                </div>
                </form>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12 col-md-offset-2-">
                            <button type="button" onclick="" class="btn btn-success" id="edit-intro-submit"><i class="fa fa-check"></i>提交</button>
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
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-me')
        @else
            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-root')
        @endif

    </div>

</div>
@endsection




@section('custom-script')
<script>
    $(function() {
        // 修改-提交
        $("#edit-intro-submit").on('click', function() {
            var options = {
                url: "{{ url('/mine/my-profile-intro-edit') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "{{ url('/mine/my-profile-intro-index') }}";
                    }
                }
            };
            $("#form-edit-intro").ajaxSubmit(options);
        });
    });
</script>
@endsection