@extends(env('TEMPLATE_ADMIN').'org.layout.layout')


@section('head_title','成员列表 - 组织后台管理系统 - 朝鲜族组织活动平台 - 如未科技')


@section('header','')
@section('description','组织后台管理系统 - 朝鲜族组织活动平台 - 如未科技')
@section('breadcrumb')
    <li><a href="{{url('/org')}}"><i class="fa fa-home"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="box box-info">

            <div class="box-header with-border" style="margin:16px 0;">
                <h3 class="box-title">成员列表</h3>

                <div class="caption pull-right _none">
                    <i class="icon-pin font-blue"></i>
                    <span class="caption-subject font-blue sbold uppercase"></span>
                    <a href="{{ url('/org/user/member-create') }}">
                        <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-plus"></i> 添加成员</button>
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
                            <th>序号</th>
                            <th></th>
                            {{--<th></th>--}}
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
                    <div class="col-md-offset-0 col-md-9">
                        <button type="button" onclick="" class="btn btn-primary _none"><i class="fa fa-check"></i> 提交</button>
                        <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
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
                    'url': "{{ url('/org/user/my-member-list') }}",
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
                        "title": "序号",
                        "data": null,
                        "targets": 0,
                        "orderable": false
                    },
//                    {
//                        "width": "48px",
//                        "title": "用户ID",
//                        "data": "id",
//                        "orderable": true,
//                        render: function(data, type, row, meta) {
//                            return row.relation_user.id;
//                        }
//                    },
                    {
                        'className': "text-left",
                        "width":"",
                        "title": "成员名",
                        "data": "id",
                        "orderable": false,
                        render: function(data, type, row, meta) {
                            return '<a target="_blank" href="/user/'+row.relation_user.id+'">'+row.relation_user.username+'</a>';
                        }
                    },
                    {
                        "width": "128px",
                        "title": "添加时间",
                        "data": 'updated_at',
                        "orderable": true,
                        render: function(data, type, row, meta) {
//                            return data;
                            var $date = new Date(data*1000);
                            var $year = $date.getFullYear();
                            var $month = ('00'+($date.getMonth()+1)).slice(-2);
                            var $day = ('00'+($date.getDate())).slice(-2);
                            var $hour = ('00'+$date.getHours()).slice(-2);
                            var $minute = ('00'+$date.getMinutes()).slice(-2);
                            var $second = ('00'+$date.getSeconds()).slice(-2);
                            return $year+'-'+$month+'-'+$day;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute;
//                            return $year+'-'+$month+'-'+$day+'&nbsp;&nbsp;'+$hour+':'+$minute+':'+$second;

//                            newDate = new Date();
//                            newDate.setTime(data * 1000);
//                            return newDate.toLocaleString('chinese',{hour12:false});
//                            return newDate.toLocaleDateString();
                        }
                    },
                    {
                        "width": "192px",
                        "data": 'id',
                        "orderable": false,
                        render: function(data, type, row, meta) {

                            var html =
//                                '<a class="btn btn-xs item-download-qrcode-submit" data-id="'+value+'">下载二维码</a>'+
//                                '<a class="btn btn-xs item-statistics-submit" data-id="'+value+'">流量统计</a>'+
//                                '<a class="btn btn-xs" href="/item/edit?id='+value+'">编辑</a>'+
                                    '<a class="btn btn-xs btn-danger item-member-remove-submit" data-pivot-id="'+data+'" data-user-id="'+row.relation_user.id+'" >移除成员</a>'+
                                '';
                            return html;
                        }
                    }
                ],
                "drawCallback": function (settings) {

                    let startIndex = this.api().context[0]._iDisplayStart;//获取本页开始的条数
                    this.api().column(0).nodes().each(function(cell, i) {
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
@include(env('TEMPLATE_ADMIN').'org.entrance.user.user-script')
@endsection
