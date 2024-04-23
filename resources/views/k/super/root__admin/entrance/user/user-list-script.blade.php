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
        $("#item-main-body").on('click', ".item-download-qr-code-submit", function() {
            var that = $(this);
            window.open("/download/qr-code?type=user&id="+that.attr('data-id'));
        });

        // 【数据分析】
        $("#item-main-body").on('click', ".item-statistic-submit", function() {
            var that = $(this);
            window.open("/admin/statistic/statistic-user?id="+that.attr('data-id'));
//            window.location.href = "/admin/statistic/statistic-user?id="+that.attr('data-id');
        });

        // 【编辑】
        $("#item-main-body").on('click', ".item-edit-submit", function() {
            var that = $(this);
            window.location.href = "/admin/user/user-edit?id="+that.attr('data-id');
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
                "{{ url('/admin/user/user-login') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    id:that.attr('data-id')
                },
                function(data) {
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        console.log(data);
//                        window.open('/');
                        var temp_window=window.open();
                        if(data.data.user.user_type == 0) temp_window.location="{{ env('DOMAIN_WWW') }}";
                        else if(data.data.user.user_type == 1) temp_window.location="{{ env('DOMAIN_WWW') }}";
                        else if(data.data.user.user_type == 11) temp_window.location="{{ env('DOMAIN_ORG') }}";
                        else if(data.data.user.user_type == 88) temp_window.location="{{ env('DOMAIN_ORG') }}";

                    }
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
        $("#item-main-body").on('click', ".user-admin-enable-submit", function() {
            var that = $(this);
            layer.msg('确定"封禁"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    var index1 = layer.load(1, {
                        shade: [0.3, '#fff'],
                        content: '<span class="loadtip">正在操作…</span>',
                        success: function (layer) {
                            layer.find('.layui-layer-content').css({
                                'padding-top': '40px',
                                'width': '120px',
                            });
                            layer.find('.loadtip').css({
                                'font-size':'20px',
                                'margin-left':'-18px'
                            });
                        }
                    });

                    $.post(
                        "{{ url('/admin/user/user-admin-enable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "user-admin-enable",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            layer.closeAll('loading');
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    )
                    .error(
                        function(xhr, errorText, errorType) {
                            layer.close(index);
                            layer.closeAll('loading');
                            layer.msg(errorText);
                            console.log(xhr);
                            console.log(errorText);
                            console.log(errorType);
                        },
                        "json"
                    );
                }
            });
        });
        // 【禁用】
        $("#item-main-body").on('click', ".user-admin-disable-submit", function() {
            var that = $(this);
            layer.msg('确定"解封"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    var index1 = layer.load(1, {
                        shade: [0.3, '#fff'],
                        content: '<span class="loadtip">正在操作…</span>',
                        success: function (layer) {
                            layer.find('.layui-layer-content').css({
                                'padding-top': '40px',
                                'width': '120px',
                            });
                            layer.find('.loadtip').css({
                                'font-size':'20px',
                                'margin-left':'-18px'
                            });
                        }
                    });

                    $.post(
                        "{{ url('/admin/user/user-admin-disable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "user-admin-disable",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            layer.closeAll('loading');
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    )
                    .error(
                        function(xhr, errorText, errorType) {
                            layer.close(index);
                            layer.closeAll('loading');
                            layer.msg(errorText);
                            console.log(xhr);
                            console.log(errorText);
                            console.log(errorType);
                        },
                        "json"
                    );

                }
            });
        });




        // 【修改-文本-属性】显示
        $(".main-content").on('dblclick', ".modal-show-for-info-text-set", function() {
            var $that = $(this);
            $('.info-text-set-title').html($that.attr("data-name"));
            $('.info-text-set-column-name').html($that.attr("data-column-name"));
            $('input[name=info-text-set-user-id]').val($that.attr("data-id"));
            $('input[name=info-text-set-column-key]').val($that.attr("data-key"));
            $('input[name=info-text-set-operate-type]').val($that.attr('data-operate-type'));
            if($that.attr('data-text-type') == "textarea")
            {
                $('input[name=info-text-set-column-value]').val('').hide();
                $('textarea[name=info-textarea-set-column-value]').text($that.attr("data-value")).show();
            }
            else
            {
                $('textarea[name=info-textarea-set-column-value]').val('').hide();
                $('input[name=info-text-set-column-value]').val($that.attr("data-value")).show();
            }

            $('#item-submit-for-info-text-set').attr('data-text-type',$that.attr('data-text-type'));

            $('#modal-body-for-info-text-set').modal('show');
        });
        // 【修改-文本-属性】取消
        $(".main-content").on('click', "#item-cancel-for-info-text-set", function() {
            var that = $(this);
            $('#modal-body-for-info-text-set').modal('hide').on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
            $('input[name=info-text-set-column-value]').val('');
            $('textarea[name=info-textarea-set-column-value]').val('');
        });
        // 【修改-文本-属性】提交
        $(".main-content").on('click', "#item-submit-for-info-text-set", function() {
            var $that = $(this);
            var $column_key = $('input[name="info-text-set-column-key"]').val();
            if($that.attr('data-text-type') == "textarea")
            {
                var $column_value = $('textarea[name="info-textarea-set-column-value"]').val();
            }
            else
            {
                var $column_value = $('input[name="info-text-set-column-value"]').val();
            }

            // layer.msg('确定"提交"么？', {
            //     time: 0
            //     ,btn: ['确定', '取消']
            //     ,yes: function(index){
            //     }
            // });

            $.post(
                "{{ url('/admin/user/user-info-text-set') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: $('input[name="info-text-set-operate"]').val(),
                    user_id: $('input[name="info-text-set-user-id"]').val(),
                    operate_type: $('input[name="info-text-set-operate-type"]').val(),
                    column_key: $column_key,
                    column_value: $column_value,
                },
                function(data){
                    // layer.close(index);
                    if(!data.success) layer.msg(data.msg);
//                            else location.reload();
                    else
                    {
                        $('#modal-body-for-info-text-set').modal('hide').on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

//                                var $keyword_id = $("#set-rank-bulk-submit").attr("data-keyword-id");
////                                TableDatatablesAjax_inner.init($keyword_id);

                        $('#datatable_ajax').DataTable().ajax.reload(null, false);
//                                $('#datatable_ajax_inner').DataTable().ajax.reload(null, false);
                    }
                },
                'json'
            );

        });




        // 【修改-时间-属性】显示
        $(".main-content").on('dblclick', ".modal-show-for-info-time-set", function() {
            var $that = $(this);
            $('.info-time-set-title').html($that.attr("data-name"));
            $('.info-time-set-column-name').html($that.attr("data-column-name"));
            $('input[name=info-time-set-operate-type]').val($that.attr('data-operate-type'));
            $('input[name=info-time-set-user-id]').val($that.attr("data-id"));
            $('input[name=info-time-set-column-key]').val($that.attr("data-key"));
            $('input[name=info-time-set-time-type]').val($that.attr('data-time-type'));
            if($that.attr('data-time-type') == "datetime")
            {
                $('input[name=info-time-set-column-value]').show();
                $('input[name=info-date-set-column-value]').hide();
                $('input[name=info-time-set-column-value]').val($that.attr("data-value")).attr('data-time-type',$that.attr('data-time-type'));
            }
            else if($that.attr('data-time-type') == "date")
            {
                $('input[name=info-time-set-column-value]').hide();
                $('input[name=info-date-set-column-value]').show();
                $('input[name=info-date-set-column-value]').val($that.attr("data-value")).attr('data-time-type',$that.attr('data-time-type'));
            }

            $('#modal-body-for-info-time-set').modal('show');
        });
        // 【修改-时间-属性】取消
        $(".main-content").on('click', "#item-cancel-for-info-time-set", function() {
            var that = $(this);

            $('#modal-body-for-info-time-set').modal('hide').on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
        });
        // 【修改-时间-属性】提交
        $(".main-content").on('click', "#item-submit-for-info-time-set", function() {
            var $that = $(this);
            var $column_key = $('input[name="info-time-set-column-key"]').val();
            var $time_type = $('input[name="info-time-set-time-type"]').val();
            var $column_value = '';
            if($time_type == "datetime")
            {
                $column_value = $('input[name="info-time-set-column-value"]').val();
            }
            else if($time_type == "date")
            {
                $column_value = $('input[name="info-date-set-column-value"]').val();
            }

            // layer.msg('确定"提交"么？', {
            //     time: 0
            //     ,btn: ['确定', '取消']
            //     ,yes: function(index){
            //     }
            // });

            $.post(
                "{{ url('/admin/user/user-info-text-set') }}",
                        {{--"{{ url('/admin/user/user-info-time-set') }}",--}}
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: $('input[name="info-time-set-operate"]').val(),
                    user_id: $('input[name="info-time-set-user-id"]').val(),
                    operate_type: $('input[name="info-time-set-operate-type"]').val(),
                    column_key: $column_key,
                    column_value: $column_value,
                    time_type: $time_type
                },
                function(data){
                    // layer.close(index);
                    if(!data.success) layer.msg(data.msg);
//                            else location.reload();
                    else
                    {
                        $('#modal-body-for-info-time-set').modal('hide').on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

                        $('#datatable_ajax').DataTable().ajax.reload(null, false);

                    }
                },
                'json'
            );

        });




        // 【修改-select-属性】【显示】
        $(".main-content").on('dblclick', ".modal-show-for-info-select-set", function() {

            $('select[name=info-select-set-column-value]').attr("selected","");
            $('select[name=info-select-set-column-value]').find('option').eq(0).val(0).text('');
            $('select[name=info-select-set-column-value]').find('option:not(:first)').remove();

            var $that = $(this);
            $('.info-select-set-title').html($that.attr("data-id"));
            $('.info-select-set-column-name').html($that.attr("data-name"));
            $('input[name=info-select-set-user-id]').val($that.attr("data-id"));
            $('input[name=info-select-set-column-key]').val($that.attr("data-key"));
//            $('select[name=info-select-set-column-value]').find("option").eq(0).prop("selected",true);
//            $('select[name=info-select-set-column-value]').find("option").eq(0).attr("selected","selected");
//            $('select[name=info-select-set-column-value]').find('option').eq(0).val($that.attr("data-value"));
//            $('select[name=info-select-set-column-value]').find('option').eq(0).text($that.attr("data-option-name"));
//            $('select[name=info-select-set-column-value]').find('option').eq(0).attr('data-id',$that.attr("data-value"));
            $('input[name=info-select-set-operate-type]').val($that.attr('data-operate-type'));


            $('select[name=info-select-set-column-value]').removeClass('select2-user').removeClass('select2-client');
            if($that.attr("data-key") == "receipt_status")
            {
                var $option_html = $('#receipt_status-option-list').html();
            }
            else if($that.attr("data-key") == "trailer_type")
            {
                var $option_html = $('#trailer_type-option-list').html();
            }
            else if($that.attr("data-key") == "trailer_length")
            {
                var $option_html = $('#trailer_length-option-list').html();
            }
            else if($that.attr("data-key") == "trailer_volume")
            {
                var $option_html = $('#trailer_volume-option-list').html();
            }
            else if($that.attr("data-key") == "trailer_weight")
            {
                var $option_html = $('#trailer_weight-option-list').html();
            }
            else if($that.attr("data-key") == "trailer_axis_count")
            {
                var $option_html = $('#trailer_axis_count-option-list').html();
            }
            $('select[name=info-select-set-column-value]').html($option_html);
            $('select[name=info-select-set-column-value]').find("option[value='"+$that.attr("data-value")+"']").attr("selected","selected");


            $('#modal-body-for-info-select-set').modal('show');



        });
        // 【修改-select2-属性】【显示】
        $(".main-content").on('dblclick', ".modal-show-for-info-select2-set", function() {

            $('select[name=info-select-set-column-value]').attr("selected","");
            $('select[name=info-select-set-column-value]').find('option').eq(0).val(0).text('');
            $('select[name=info-select-set-column-value]').find('option:not(:first)').remove();

            var $that = $(this);
            $('.info-select-set-title').html($that.attr("data-id"));
            $('.info-select-set-column-name').html($that.attr("data-name"));
            $('input[name=info-select-set-user-id]').val($that.attr("data-id"));
            $('input[name=info-select-set-column-key]').val($that.attr("data-key"));
            $('input[name=info-select-set-column-key]').prop('data-user-type',$that.attr("data-user-type"));
//            $('select[name=info-select-set-column-value]').find("option").eq(0).prop("selected",true);
//            $('select[name=info-select-set-column-value]').find("option").eq(0).attr("selected","selected");
            $('select[name=info-select-set-column-value]').find('option').eq(0).val($that.attr("data-value"));
            $('select[name=info-select-set-column-value]').find('option').eq(0).text($that.attr("data-option-name"));
            $('select[name=info-select-set-column-value]').find('option').eq(0).attr('data-id',$that.attr("data-value"));
            $('input[name=info-select-set-operate-type]').val($that.attr('data-operate-type'));

            $('#modal-body-for-info-select-set').modal('show');


            if($that.attr("data-key") == "principal_id")
            {
                $('select[name=info-select-set-column-value]').addClass('select2-leader');
                $('.select2-leader').select2({
                    ajax: {
                        url: "{{ url('/admin/select2_user') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                keyword: params.term, // search term
                                page: params.page,
                                // type: $('input[name=info-select-set-column-key]').prop('data-user-type')
                                type: 'principal'
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
            }

        });
        // 【修改-select-属性】【取消】
        $(".main-content").on('click', "#item-cancel-for-info-select-set", function() {
            var that = $(this);
            $('#modal-body-for-info-select-set').modal('hide').on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
        });
        // 【修改-select-属性】【提交】
        $(".main-content").on('click', "#item-submit-for-info-select-set", function() {
            var $that = $(this);
            var $column_key = $('input[name="info-select-set-column-key"]').val();
            var $column_value = $('select[name="info-select-set-column-value"]').val();

            // layer.msg('确定"提交"么？', {
            //     time: 0
            //     ,btn: ['确定', '取消']
            //     ,yes: function(index){
            //     }
            // });

            $.post(
                "{{ url('/admin/user/user-info-select-set') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: $('input[name="info-select-set-operate"]').val(),
                    user_id: $('input[name="info-select-set-user-id"]').val(),
                    operate_type: $('input[name="info-select-set-operate-type"]').val(),
                    column_key: $column_key,
                    column_value: $column_value,
                },
                function(data){
                    // layer.close(index);
                    if(!data.success) layer.msg(data.msg);
//                            else location.reload();
                    else
                    {
                        $('#modal-body-for-info-select-set').modal('hide').on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

//                                var $keyword_id = $("#set-rank-bulk-submit").attr("data-keyword-id");
////                                TableDatatablesAjax_inner.init($keyword_id);

                        $('#datatable_ajax').DataTable().ajax.reload(null, false);
//                                $('#datatable_ajax_inner').DataTable().ajax.reload(null, false);
                    }
                },
                'json'
            );

        });


    });
</script>