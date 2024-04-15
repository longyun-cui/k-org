@extends(env('TEMPLATE_K_ORG').'layout.layout')


@section('head_title')添加赞助商@endsection

@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动@endsection


@section('wx_share_title')朝鲜族社群平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_ORG').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

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


                    {{--赞助商--}}
                    <div class="form-group">
                        <label class="control-label col-md-2">选择赞助商</label>
                        <div class="col-md-8 ">
                            <select class="form-control" name="user_id" id="select2-sponsor" data-type="sponsor">
                                <option data-id="0" value="0">未指定</option>
                            </select>
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

    <div class="main-body-section main-body-right-section section-wrapper hidden-xs">

        {{--@include(env('TEMPLATE_ROOT_FRONT').'component.right-side.right-root')--}}
        {{--@include(env('TEMPLATE_K_ORG').'component.right-side.right-root')--}}

    </div>

</div>
@endsection




@section('custom-css')
    <link rel="stylesheet" href="{{ asset('/resource/component/css/select2-4.0.5.min.css') }}">
@endsection
@section('custom-style')
@endsection




@section('custom-js')
    <script src="{{ asset('/resource/component/js/select2-4.0.5.min.js') }}"></script>
@endsection
@section('custom-script')
<script>
    $(function() {

        // 添加or编辑
        $("#edit-item-submit").on('click', function() {
            var options = {
                url: "{{ url('/mine/sponsor-add') }}",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        layer.msg(data.msg);
                        location.href = "{{ url('/mine/my-sponsor-list') }}";
                    }
                }
            };
            $("#form-edit-item").ajaxSubmit(options);
        });


        //
        $('#select2-sponsor').select2({
            ajax: {
                url: "{{ url('/mine/select2_user') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term, // search term
                        page: params.page,
                        type: $('#select2-sponsor').prop('data-type')
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;
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
