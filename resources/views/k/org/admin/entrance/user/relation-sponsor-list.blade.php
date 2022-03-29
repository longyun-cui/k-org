@extends(env('TEMPLATE_K_ORG_ADMIN').'layout.layout')


@section('head_title','赞助商列表 - 管理员后台 - 如未科技')


@section('header','')
@section('description','管理员后台-如未科技')
@section('breadcrumb')
    <li><a href="{{url('/org')}}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="{{ url('/org/user/my-sponsor-list') }}"><i class="fa fa-list"></i>我的赞助商</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info" id="item-content-body">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">赞助商列表</h3>

                <div class="caption pull-right _none">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                    <a href="{{ url('/org/user/user-create') }}">
                        <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-plus"></i> 添加组织/赞助商</button>
                    </a>
                </div>

                <div class="pull-right _none">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="box-body datatable-body item-main-body" id="item-main-body">


                <div class="row col-md-12 datatable-search-row">
                    <div class="input-group">

                        <input type="text" class="form-control form-filter item-search-keyup" name="username" placeholder="用户名" />

                        <button type="button" class="form-control btn btn-flat btn-success filter-submit" id="filter-submit">
                            <i class="fa fa-search"></i> 搜索
                        </button>
                        <button type="button" class="form-control btn btn-flat btn-default filter-cancel">
                            <i class="fa fa-circle-o-notch"></i> 重置
                        </button>

                    </div>
                </div>


                <!-- datatable start -->
                <table class='table table-striped table-bordered' id='datatable_ajax'>
                    <thead>
                        <tr role='row' class='heading'>
                            <th>选择</th>
                            <th>序号</th>
                            <th>ID</th>
                            <th></th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!-- datatable end -->
            </div>


            <div class="box-footer">
                <div class="row" style="margin:16px 0;">
                    <div class="col-md-offset-0 col-md-4 col-sm-8 col-xs-12">
                        {{--<button type="button" class="btn btn-primary"><i class="fa fa-check"></i> 提交</button>--}}
                        {{--<button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>--}}
                        <div class="input-group">
                            <span class="input-group-addon"><input type="checkbox" id="check-all"></span>
                            <input type="text" class="form-control" name="bulk-sponsor" id="bulk-sponsor" placeholder="批量关联我的赞助商" readonly>
                            <span class="input-group-addon btn btn-default" id="sponsor-relation-bulk-submit"><i class="fa fa-check"></i>关联</span>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- END PORTLET-->
    </div>
</div>


<div class="modal fade" id="modal-password-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">修改密码</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="form-change-password-modal">
                        <div class="box-body">

                            {{csrf_field()}}
                            <input type="hidden" name="operate" value="change-password" readonly>
                            <input type="hidden" name="id" value="0" readonly>

                            {{--类别--}}


                            {{--用户ID--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">新密码</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <input type="password" class="form-control form-filter" name="user-password" value="">
                                    6-20位英文、数值、下划线构成
                                </div>
                            </div>
                            {{--用户名--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">确认密码</label>
                                <div class="col-md-8 control-label" style="text-align:left;">
                                    <input type="password" class="form-control form-filter" name="user-password-confirm" value="">
                                </div>
                            </div>


                        </div>
                    </form>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-change-password-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default" id="item-change-password-cancel">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-body">
    <div class="col-md-8 col-md-offset-2" id="edit-ctn" style="margin-top:64px;margin-bottom:64px;background:#fff;">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box- box-info- form-container">

                    <div class="box-header with-border" style="margin:16px 0;">
                        <h3 class="box-title">代理商充值</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <form action="" method="post" class="form-horizontal form-bordered" id="form-edit-modal">
                    <div class="box-body">

                        {{csrf_field()}}
                        <input type="hidden" name="operate" value="recharge" readonly>
                        <input type="hidden" name="id" value="0" readonly>

                        {{--类别--}}


                        {{--用户ID--}}
                        <div class="form-group">
                            <label class="control-label col-md-2">用户ID</label>
                            <div class="col-md-8 control-label" style="text-align:left;">
                                <span class="recharge-user-id"></span>
                            </div>
                        </div>
                        {{--用户名--}}
                        <div class="form-group">
                            <label class="control-label col-md-2">用户名</label>
                            <div class="col-md-8 control-label" style="text-align:left;">
                                <span class="recharge-username"></span>
                            </div>
                        </div>
                        {{--真实姓名--}}
                        <div class="form-group">
                            <label class="control-label col-md-2">充值金额</label>
                            <div class="col-md-8 ">
                                <input type="text" class="form-control" name="recharge-amount" placeholder="充值金额" value="">
                            </div>
                        </div>
                        {{--备注--}}
                        <div class="form-group">
                            <label class="control-label col-md-2">备注</label>
                            <div class="col-md-8 ">
                                {{--<input type="text" class="form-control" name="description" placeholder="描述" value="">--}}
                                <textarea class="form-control" name="description" rows="3" cols="100%"></textarea>
                            </div>
                        </div>
                        {{--说明--}}
                        <div class="form-group">
                            <label class="control-label col-md-2">说明</label>
                            <div class="col-md-8 control-label" style="text-align:left;">
                                <span class="">正数为充值，负数为退款，退款金额不能超过资金余额。</span>
                            </div>
                        </div>


                    </div>
                    </form>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <button type="button" class="btn btn-success" id="item-recharge-submit"><i class="fa fa-check"></i> 提交</button>
                                <button type="button" class="btn btn-default" id="item-recharge-cancel">取消</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->
            </div>
        </div>
    </div>
</div>
@endsection


@section('custom-script')
<script>
    var TableDatatablesAjax = function () {
        var datatableAjax = function () {

            var dt = $('#datatable_ajax');
            var ajax_datatable = dt.DataTable({
//                "aLengthMenu": [[20, 50, 200, 500, -1], ["20", "50", "200", "500", "全部"]],
                "aLengthMenu": [[40, 50, 200], ["40", "50", "200"]],
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": {
                    'url': "{{ url('/org/user/relation-sponsor-list') }}",
                    "type": 'POST',
                    "dataType" : 'json',
                    "data": function (d) {
                        d._token = $('meta[name="_token"]').attr('content');
                        d.username = $('input[name="username"]').val();
//                        d.nickname 	= $('input[name="nickname"]').val();
//                        d.certificate_type_id = $('select[name="certificate_type_id"]').val();
//                        d.certificate_state = $('select[name="certificate_state"]').val();
//                        d.admin_name = $('input[name="admin_name"]').val();
//
//                        d.created_at_from = $('input[name="created_at_from"]').val();
//                        d.created_at_to = $('input[name="created_at_to"]').val();
//                        d.updated_at_from = $('input[name="updated_at_from"]').val();
//                        d.updated_at_to = $('input[name="updated_at_to"]').val();

                    },
                },
                "pagingType": "simple_numbers",
                "order": [],
                "orderCellsTop": true,
                "columns": [
                    {
                        "width": "48px",
                        "title": "选择",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<label><input type="checkbox" name="bulk-sponsor-id" class="minimal" value="'+data+'"></label>';
                        }
                    },
                    {
                        "width": "48px",
                        "title": "序号",
                        "data": null,
                        "targets": 0,
                        'orderable': false
                    },
                    {
                        'width': "48px",
                        "title": "ID",
                        "data": "id",
                        'orderable': true,
                        render: function(data, type, row, meta) {
                            return data;
                        }
                    },
                    {
                        'className': "text-left",
                        'width': "",
                        "title": "赞助商",
                        "data": "id",
                        'orderable': false,
                        render: function(data, type, row, meta) {
                            return '<a target="_blank" href="/admin/user/agent?id='+data+'">'+row.username+'</a>';
                        }
                    },
                    {
                        'width': "160px",
                        "title": "操作",
                        'data': 'id',
                        'orderable': false,
                        render: function(data, type, row, meta) {

                            var html =
//                                '<a class="btn btn-xs item-enable-submit" data-id="'+value+'">启用</a>'+
//                                '<a class="btn btn-xs item-disable-submit" data-id="'+value+'">禁用</a>'+
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
                                {{--'<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+--}}
//                                '<a class="btn btn-xs btn-primary item-recharge-show" data-id="'+data+'"">关联我的赞助商</a>'+
                                '<a class="btn btn-xs btn-primary item-sponsor-relation-submit" data-id="'+data+'"">关联为我的赞助商</a>'+
                                '';
                            return html;
                        }
                    }
                ],
                "drawCallback": function (settings) {

                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
                    this.api().column(1).nodes().each(function(cell, i) {
                        cell.innerHTML =  startIndex + i + 1;
                    });

                    ajax_datatable.$('.tooltips').tooltip({placement: 'top', html: true});
                    $("a.verify").click(function(event){
                        event.preventDefault();
                        var node = $(this);
                        var tr = node.closest('tr');
                        var nickname = tr.find('span.nickname').text();
                        var cert_name = tr.find('span.certificate_type_name').text();
                        var action = node.attr('data-action');
                        var certificate_id = node.attr('data-id');
                        var action_name = node.text();

                        var tpl = "{{trans('labels.crc.verify_user_certificate_tpl')}}";
                        layer.open({
                            'title': '警告',
                            content: tpl
                                .replace('@action_name', action_name)
                                .replace('@nickname', nickname)
                                .replace('@certificate_type_name', cert_name),
                            btn: ['Yes', 'No'],
                            yes: function(index) {
                                layer.close(index);
                                $.post(
                                    '/admin/medsci/certificate/user/verify',
                                    {
                                        action: action,
                                        id: certificate_id,
                                        _token: '{{csrf_token()}}'
                                    },
                                    function(json){
                                        if(json['response_code'] == 'success') {
                                            layer.msg('操作成功!', {time: 3500});
                                            ajax_datatable.ajax.reload();
                                        } else {
                                            layer.alert(json['response_data'], {time: 10000});
                                        }
                                    }, 'json');
                            }
                        });
                    });
                },
                "language": { url: '/common/dataTableI18n' },
            });


            dt.on('click', '.filter-submit', function () {
                ajax_datatable.ajax.reload();
            });

            dt.on('click', '.filter-cancel', function () {
                $('textarea.form-filter, select.form-filter, input.form-filter', dt).each(function () {
                    $(this).val("");
                });

                $('select.form-filter').selectpicker('refresh');

                ajax_datatable.ajax.reload();
            });

        };
        return {
            init: datatableAjax
        }
    }();
    $(function () {
        TableDatatablesAjax.init();
    });
</script>
<script>
    $(function() {

        // 【搜索】
        $(".item-main-body").on('click', ".filter-submit", function() {
            $('#datatable_ajax').DataTable().ajax.reload();
        });
        // 【重置】
        $(".item-main-body").on('click', ".filter-cancel", function() {
            $('textarea.form-filter, input.form-filter, select.form-filter').each(function () {
                $(this).val("");
            });

//                $('select.form-filter').selectpicker('refresh');
            $('select.form-filter option').attr("selected",false);
            $('select.form-filter').find('option:eq(0)').attr('selected', true);

            $('#datatable_ajax').DataTable().ajax.reload();
        });
        // 【查询】回车
        $(".item-main-body").on('keyup', ".item-search-keyup", function(event) {
            if(event.keyCode ==13)
            {
                $("#filter-submit").click();
            }
        });


        // 【下载二维码】
        $("#item-main-body").on('click', ".item-download-qrcode-submit", function() {
            var that = $(this);
            window.open("/download-qrcode?sort=org-item&id="+that.attr('data-id'));
        });

        // 【数据分析】
        $("#item-main-body").on('click', ".item-statistics-submit", function() {
            var that = $(this);
            window.open("/statistics/item?id="+that.attr('data-id'));
        });

        // 【编辑】
        $("#item-main-body").on('click', ".item-edit-submit", function() {
            var that = $(this);
            window.location.href = "/admin/user/user-edit?id="+that.attr('data-id');
        });



        // 【关联】【赞助商】
        $("#item-main-body").on('click', ".item-sponsor-relation-submit", function() {
            var that = $(this);
            layer.msg('确定"关联"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/org/user/sponsor-relation') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"sponsor-relation",
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg("操作完成");
                                location.href = "{{ url('/org/user/my-sponsor-list') }}";
                            }
                        },
                        'json'
                    );
                }
            });
        });

        // 【批量选择】全选or反选
        $("#item-content-body").on('click', '#check-all', function () {
            $('input[name="bulk-sponsor-id"]').prop('checked',this.checked);//checked为true时为默认显示的状态
        });
        // 【批量关联】【赞助商】
        $("#item-content-body").on('click', '#sponsor-relation-bulk-submit', function() {
            var $checked = [];
            $('input[name="bulk-sponsor-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定"批量关联"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/org/user/sponsor-relation-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "sponsor-relation-bulk",
                            bulk_sponsor_id: $checked
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg("操作完成");
                                location.href = "{{ url('/org/user/my-sponsor-list') }}";
                            }
                        },
                        'json'
                    );
                }
            });

        });



        // 显示【修改密码】
        $("#item-main-body").on('click', ".item-change-password-show", function() {
            var that = $(this);
            $('input[name=id]').val(that.attr('data-id'));
            $('input[name=user-password]').val('');
            $('input[name=user-password-confirm]').val('');
            $('#modal-password-body').modal('show');
        });
        // 【修改密码】取消
        $("#modal-password-body").on('click', "#item-change-password-cancel", function() {
            $('input[name=id]').val('');
            $('input[name=user-password]').val('');
            $('input[name=user-password-confirm]').val('');
            $('#modal-password-body').modal('hide');
        });
        // 【修改密码】提交
        $("#modal-password-body").on('click', "#item-change-password-submit", function() {
            var that = $(this);
            layer.msg('确定"修改"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    var options = {
                        url: "{{ url('/admin/user/change-password') }}",
                        type: "post",
                        dataType: "json",
                        // target: "#div2",
                        success: function (data) {
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
                                $('#modal-password-body').modal('hide');
                            }
                        }
                    };
                    $("#form-change-password-modal").ajaxSubmit(options);
                }
            });
        });




        // 显示【充值】
        $("#item-main-body").on('click', ".item-recharge-show", function() {
            var that = $(this);
            $('input[name=id]').val(that.attr('data-id'));
            $('.recharge-user-id').html(that.attr('data-id'));
            $('.recharge-username').html(that.attr('data-name'));
            $('#modal-body').modal('show');
        });
        // 【充值】取消
        $("#modal-body").on('click', "#item-recharge-cancel", function() {
            $('.recharge-user-id').html('');
            $('.recharge-username').html('');
            $('#modal-body').modal('hide');
        });
        // 【充值】提交
        $("#modal-body").on('click', "#item-recharge-submit", function() {
            var that = $(this);
            layer.msg('确定"充值"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    var options = {
                        url: "{{ url('/admin/user/agent-recharge') }}",
                        type: "post",
                        dataType: "json",
                        // target: "#div2",
                        success: function (data) {
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg(data.msg);
                                location.reload();
                            }
                        }
                    };
                    $("#form-edit-modal").ajaxSubmit(options);
                }
            });
        });


        // 关闭【充值限制】
        $("#item-main-body").on('click', ".item-recharge-limit-close-submit", function() {
            var that = $(this);
            layer.msg('确定"关闭"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/user/agent-recharge-limit-close') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"recharge-limit-close",
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg("操作完成");
                                location.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });
        // 开启【充值限制】
        $("#item-main-body").on('click', ".item-recharge-limit-open-submit", function() {
            var that = $(this);
            layer.msg('确定"开启"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/user/agent-recharge-limit-open') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"recharge-limit-open",
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg("操作完成");
                                location.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });




        // 关闭【二级代理】
        $("#item-main-body").on('click', ".item-sub-agent-close-submit", function() {
            var that = $(this);
            layer.msg('确定"关闭二级代理"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/user/agent-sub-agent-close') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"sub-agent-close",
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg("操作完成");
                                location.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });
        // 开启【二级代理】
        $("#item-main-body").on('click', ".item-sub-agent-open-submit", function() {
            var that = $(this);
            layer.msg('确定"开启二级代理"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/user/agent-sub-agent-open') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate:"sub-agent-open",
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg("操作完成");
                                location.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });




        // 【登录】
        $("#item-main-body").on('click', ".item-login-submit", function() {
            var that = $(this);
            $.post(
                "{{ url('/admin/user/agent-login') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    id:that.attr('data-id')
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else window.open('/agent/');
                },
                'json'
            );
        });




        // 【删除】
        $("#item-main-body").on('click', ".item-delete-submit", function() {
            var that = $(this);
            layer.msg('确定"删除"么?', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/user/agent-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                layer.msg("操作完成");
                                location.reload();
                            }
                        },
                        'json'
                    );
                }
            });
        });




        // 【启用】
        $("#item-main-body").on('click', ".item-enable-submit", function() {
            var that = $(this);
            layer.msg('确定启用该"产品"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/item/enable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.reload();
                        },
                        'json'
                    );
                }
            });
        });
        // 【禁用】
        $("#item-main-body").on('click', ".item-disable-submit", function() {
            var that = $(this);
            layer.msg('确定禁用该"产品"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/item/disable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            id:that.attr('data-id')
                        },
                        function(data){
                            if(!data.success) layer.msg(data.msg);
                            else location.reload();
                        },
                        'json'
                    );
                }
            });
        });

    });
</script>
@endsection
