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

//            $('select.form-filter').selectpicker('refresh');
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
            window.open("/download/qr-code?type=item&id="+that.attr('data-id'));
        });

        // 【数据分析】
        $("#item-main-body").on('click', ".item-statistic-submit", function() {
            var that = $(this);
            window.open("/admin/statistic/statistic-item?id="+that.attr('data-id'));
//            window.location.href = "/admin/statistic/statistic-item?id="+that.attr('data-id');
        });

        // 【编辑】
        $("#item-main-body").on('click', ".item-edit-link", function() {
            var that = $(this);
            window.location.href = "/admin/item/item-edit?id="+that.attr('data-id');
        });




        /*
            // 批量操作
         */
        // 【批量操作】全选or反选
        $(".main-list-body").on('click', '#check-review-all', function () {
            $('input[name="bulk-id"]').prop('checked',this.checked);//checked为true时为默认显示的状态
        });

        // 【批量操作】
        $(".main-list-body").on('click', '#operate-bulk-submit', function() {
            var $checked = [];
            $('input[name="bulk-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定"批量审核"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    $.post(
                        "{{ url('/admin/item/item-operate-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "operate-bulk",
                            bulk_keyword_id: $checked,
                            bulk_keyword_status:$('select[name="bulk-operate-status"]').val()
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );

                }
            });

        });

        // 【批量删除】
        $(".main-list-body").on('click', '#delete-bulk-submit', function() {
            var $checked = [];
            $('input[name="bulk-id"]:checked').each(function() {
                $checked.push($(this).val());
            });

            layer.msg('确定"批量删除"么', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){

                    $.post(
                        "{{ url('/admin/item/item-delete-bulk') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-delete-bulk",
                            bulk_keyword_id: $checked
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );

                }
            });

        });




        // 内容【获取详情】
        $("#item-main-body").on('click', ".item-detail-show", function() {
            var that = $(this);
            var $data = new Object();
            $.ajax({
                type:"post",
                dataType:'json',
                async:false,
                url: "{{ url('/admin/item/item-get') }}",
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate:"item-get",
                    id:that.attr('data-id')
                },
                success:function(data){
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        $data = data.data;
                    }
                }
            });
            $('input[name=id]').val(that.attr('data-id'));
            $('.item-user-id').html(that.attr('data-user-id'));
            $('.item-username').html(that.attr('data-username'));
            $('.item-title').html($data.title);
            $('.item-content').html($data.content);
            if($data.attachment_name)
            {
                var $attachment_html = $data.attachment_name+'&nbsp&nbsp&nbsp&nbsp'+'<a href="/all/download-item-attachment?item-id='+$data.id+'">下载</a>';
                $('.item-attachment').html($attachment_html);
            }
            $('#modal-body').modal('show');

        });

        // 内容【删除】
        $("#item-main-body").on('click', ".item-admin-delete-submit", function() {
            var that = $(this);
            layer.msg('确定要"删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-delete') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-delete",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });

        // 内容【恢复】
        $("#item-main-body").on('click', ".item-admin-restore-submit", function() {
            var that = $(this);
            layer.msg('确定要"恢复"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-restore') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-restore",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });

        // 内容【永久删除】
        $("#item-main-body").on('click', ".item-admin-delete-permanently-submit", function() {
            var that = $(this);
            layer.msg('确定要"永久删除"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-delete-permanently') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-delete-permanently",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });

        // 内容【推送】
        $("#item-main-body").on('click', ".item-publish-submit", function() {
            var that = $(this);
            layer.msg('确定要"发布"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-publish') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-publish",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });

        // 内容【设置贴片广告】
        $("#item-main-body").on('click', ".item-ad-set-submit", function() {
            var that = $(this);
            layer.msg('确定要"设置"么，原有广告将被替换？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-ad-set') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-ad-set",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });

        // 内容【取消贴片广告】
        $("#item-main-body").on('click', ".item-ad-cancel-submit", function() {
            var that = $(this);
            layer.msg('确定要"取消"么？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-ad-cancel') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-ad-cancel",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });




        // 【启用】
        $("#item-main-body").on('click', ".item-admin-enable-submit", function() {
            var that = $(this);
            layer.msg('确定"封禁"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-admin-enable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-admin-enable",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });
        // 【禁用】
        $("#item-main-body").on('click', ".item-admin-disable-submit", function() {
            var that = $(this);
            layer.msg('确定"解禁"？', {
                time: 0
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post(
                        "{{ url('/admin/item/item-admin-disable') }}",
                        {
                            _token: $('meta[name="_token"]').attr('content'),
                            operate: "item-admin-disable",
                            id:that.attr('data-id')
                        },
                        function(data){
                            layer.close(index);
                            if(!data.success) layer.msg(data.msg);
                            else
                            {
                                $('#datatable_ajax').DataTable().ajax.reload(null,false);
                            }
                        },
                        'json'
                    );
                }
            });
        });





        // 【修改记录】【显示】
        $(".main-content").on('click', ".item-modal-show-for-modify", function() {
            var that = $(this);
            var $id = that.attr("data-id");

            TableDatatablesAjax_record.init($id);

            $('#modal-body-for-modify-list').modal('show');
        });




        // 【修改-文本-text-属性】【显示】
        $(".main-content").on('dblclick', ".modal-show-for-info-text-set", function() {
            var $that = $(this);
            var $row = $that.parents('tr');

            $('#datatable_ajax').find('tr').removeClass('operating');
            $row.addClass('operating');

            $('.info-text-set-title').html($that.attr("data-id"));
            $('.info-text-set-column-name').html($that.attr("data-name"));
            $('input[name=info-text-set-item-id]').val($that.attr("data-id"));
            $('input[name=info-text-set-column-key]').val($that.attr("data-key"));
            $('input[name=info-text-set-operate-type]').val($that.attr('data-operate-type'));
            // console.log($that.attr("data-value"));
            if($that.attr('data-text-type') == "textarea")
            {
                $('input[name=info-text-set-column-value]').val('').hide();
                $('textarea[name=info-textarea-set-column-value]').val('').val($that.attr("data-value")).show();
            }
            else
            {
                $('textarea[name=info-textarea-set-column-value]').val('').hide();
                $('input[name=info-text-set-column-value]').val($that.attr("data-value")).show();
            }

            $('#item-submit-for-info-text-set').attr('data-text-type',$that.attr('data-text-type'));

            $('#modal-body-for-info-text-set').modal('show');
        });
        // 【修改-文本-text-属性】【取消】
        $(".main-content").on('click', "#item-cancel-for-info-text-set", function() {
            var that = $(this);
            $('#modal-body-for-info-text-set').modal('hide').on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
            $('input[name=info-text-set-column-value]').val('');
            $('textarea[name=info-textarea-set-column-value]').text('');
        });
        // 【修改-文本-text-属性】【提交】
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

            var $row = $('#datatable_ajax').find('tr.operating');

            // layer.msg('确定"提交"么？', {
            //     time: 0
            //     ,btn: ['确定', '取消']
            //     ,yes: function(index){
            //     }
            // });

            $.post(
                "{{ url('/item/item-info-text-set') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: $('input[name="info-text-set-operate"]').val(),
                    item_id: $('input[name="info-text-set-item-id"]').val(),
                    operate_type: $('input[name="info-text-set-operate-type"]').val(),
                    column_key: $column_key,
                    column_value: $column_value,
                },
                function(data){
                    // layer.close(index);
                    if(!data.success)
                    {
                        layer.msg(data.msg);
                    }
                    else
                    {
                        $('#modal-body-for-info-text-set').modal('hide').on("hidden.bs.modal", function () {
                            $("body").addClass("modal-open");
                        });

                        $('input[name=info-text-set-column-value]').val('');
                        $('textarea[name=info-textarea-set-column-value]').text('');

                        // $('#datatable_ajax').DataTable().ajax.reload(null, false);

                        if($that.attr('data-text-type') == "textarea")
                        {
                            $row.find('td[data-key='+$column_key+']').html('<small class="btn-xs bg-yellow">双击查看</small>');
                        }
                        else
                        {
                            $row.find('td[data-key='+$column_key+']').html($column_value);
                        }
                        $row.find('td[data-key='+$column_key+']').attr('data-value',$column_value);
                    }
                },
                'json'
            );

        });




        // 【修改-时间-time-属性】【显示】
        $(".main-content").on('dblclick', ".modal-show-for-info-time-set", function() {
            var $that = $(this);
            $('.info-time-set-title').html($that.attr("data-id"));
            $('.info-time-set-column-name').html($that.attr("data-name"));
            $('input[name=info-time-set-operate-type]').val($that.attr('data-operate-type'));
            $('input[name=info-time-set-item-id]').val($that.attr("data-id"));
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
        // 【修改-时间-time-属性】【取消】
        $(".main-content").on('click', "#item-cancel-for-info-time-set", function() {
            var that = $(this);
            $('#modal-body-for-info-time-set').modal('hide').on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
        });
        // 【修改-时间-time-属性】【提交】
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
                "{{ url('/admin/item/item-info-time-set') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: $('input[name="info-time-set-operate"]').val(),
                    item_id: $('input[name="info-time-set-item-id"]').val(),
                    operate_type: $('input[name="info-time-set-operate-type"]').val(),
                    column_key: $column_key,
                    column_value: $column_value,
                    time_type: $time_type
                },
                function(data){
                    // layer.close(index);
                    if(!data.success)
                    {
                        layer.msg(data.msg);
                    }
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




        // 【修改-radio-属性】【显示】
        $(".main-content").on('dblclick', ".modal-show-for-info-radio-set", function() {

            $('select[name=info-radio-set-column-value]').attr("selected","");
            $('select[name=info-radio-set-column-value]').find('option').eq(0).val(0).text('');
            $('select[name=info-radio-set-column-value]').find('option:not(:first)').remove();

            var $that = $(this);
            $('.info-radio-set-title').html($that.attr("data-id"));
            $('.info-radio-set-column-name').html($that.attr("data-name"));
            $('input[name=info-radio-set-item-id]').val($that.attr("data-id"));
            $('input[name=info-radio-set-column-key]').val($that.attr("data-key"));
            $('input[name=info-radio-set-operate-type]').val($that.attr('data-operate-type'));


            if($that.attr("data-key") == "receipt_need")
            {
                var $option_html = $('#receipt_need-option-list').html();
                $('.radio-box').html($option_html);
                $('input[name=receipt_need][value="'+$that.attr("data-value")+'"]').attr("checked","checked");
            }
            else if($that.attr("data-key") == "is_wx")
            {
                var $option_html = $('#option-list-for-is-wx').html();
                $('.radio-box').html($option_html);
                $('input[name=is_wx][value="'+$that.attr("data-value")+'"]').attr("checked","checked");
            }


            $('#modal-body-for-info-radio-set').modal('show');

        });
        // 【修改-radio-属性】【取消】
        $(".main-content").on('click', "#item-cancel-for-info-radio-set", function() {
            var that = $(this);
            $('#modal-body-for-info-radio-set').modal('hide').on("hidden.bs.modal", function () {
                $("body").addClass("modal-open");
            });
        });
        // 【修改-radio-属性】【提交】
        $(".main-content").on('click', "#item-submit-for-info-radio-set", function() {
            var $that = $(this);
            var $column_key = $('input[name="info-radio-set-column-key"]').val();
            var $column_value = $('#modal-info-radio-set-form').find('input:radio:checked').val();

            // layer.msg('确定"提交"么？', {
            //     time: 0
            //     ,btn: ['确定', '取消']
            //     ,yes: function(index){
            //     }
            // });

            $.post(
                "{{ url('/admin/item/item-info-radio-set') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: $('input[name="info-radio-set-operate"]').val(),
                    item_id: $('input[name="info-radio-set-item-id"]').val(),
                    operate_type: $('input[name="info-radio-set-operate-type"]').val(),
                    column_key: $column_key,
                    column_value: $column_value,
                },
                function(data){
                    // layer.close(index);
                    if(!data.success)
                    {
                        layer.msg(data.msg);
                    }
                    else
                    {
                        $('#modal-body-for-info-radio-set').modal('hide').on("hidden.bs.modal", function () {
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




        // 【修改-select-属性】【显示】
        $(".main-content").on('dblclick', ".modal-show-for-info-select-set", function() {

            $('select[name=info-select-set-column-value]').attr("selected","");
            $('select[name=info-select-set-column-value]').find('option').eq(0).val(0).text('');
            $('select[name=info-select-set-column-value]').find('option:not(:first)').remove();

            var $that = $(this);
            $('.info-select-set-title').html($that.attr("data-id"));
            $('.info-select-set-column-name').html($that.attr("data-name"));
            $('input[name=info-select-set-item-id]').val($that.attr("data-id"));
            $('input[name=info-select-set-column-key]').val($that.attr("data-key"));
//            $('select[name=info-select-set-column-value]').find("option").eq(0).prop("selected",true);
//            $('select[name=info-select-set-column-value]').find("option").eq(0).attr("selected","selected");
//            $('select[name=info-select-set-column-value]').find('option').eq(0).val($that.attr("data-value"));
//            $('select[name=info-select-set-column-value]').find('option').eq(0).text($that.attr("data-option-name"));
//            $('select[name=info-select-set-column-value]').find('option').eq(0).attr('data-id',$that.attr("data-value"));
            $('input[name=info-select-set-operate-type]').val($that.attr('data-operate-type'));


            $('#modal-body-for-info-select-set').find('select[name=info-select-set-column-value2]').next('.select2-container').hide();
            $('#modal-body-for-info-select-set').find('select[name=info-select-set-column-value2]').hide();

            if($that.attr("data-key") == "location_city")
            {
                $('select[name=info-select-set-column-value]').removeClass('select2-city');
                $('select[name=info-select-set-column-value2]').removeClass('select2-district');
                var $option_html = $('#location-city-option-list').html();

                $('#modal-body-for-info-select-set').find('select[name=info-select-set-column-value2]').show();
            }
            else if($that.attr("data-key") == "teeth_count")
            {
                var $option_html = $('#option-list-for-teeth-count').html();
            }
            else if($that.attr("data-key") == "channel_source")
            {
                var $option_html = $('#option-list-for-channel-source').html();
            }
            else if($that.attr("data-key") == "inspected_result")
            {
                var $option_html = $('#option-list-for-inspected-result').html();
            }
            else if($that.attr("data-key") == "client_id")
            {
                var $option_html = $('#option-list-for-client').html();
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
            $('input[name=info-select-set-item-id]').val($that.attr("data-id"));
            $('input[name=info-select-set-column-key]').val($that.attr("data-key"));
            $('input[name=info-select-set-column-key2]').val($that.attr("data-key2"));
//            $('select[name=info-select-set-column-value]').find("option").eq(0).prop("selected",true);
//            $('select[name=info-select-set-column-value]').find("option").eq(0).attr("selected","selected");
            $('select[name=info-select-set-column-value]').find('option').eq(0).val($that.attr("data-value"));
            $('select[name=info-select-set-column-value]').find('option').eq(0).text($that.attr("data-option-name"));
            $('select[name=info-select-set-column-value]').find('option').eq(0).attr('data-id',$that.attr("data-value"));
            $('input[name=info-select-set-operate-type]').val($that.attr('data-operate-type'));

            $('#modal-body-for-info-select-set').modal('show');
            $('select[name=info-select-set-column-value2]').hide();


            if($that.attr("data-key") == "location_city")
            {
                $('select[name=info-select-set-column-value2]').show();

                var $district_list = [
                    ['东城区','西城区','海淀区','朝阳区','丰台区','门头沟区','石景山区','房山区','通州区','顺义区','昌平区','大兴区','怀柔区','平谷区','延庆区','密云区','其他'],
                    ['和平区','河东区','河西区','南开区','河北区','红桥区','滨海新区','东丽区','西青区','津南区','北辰区','武清区','宝坻区','宁河区','静海区','蓟州区','其他'],
                    ['黄浦区','徐汇区','长宁区','静安区','普陀区','虹口区','杨浦区','闵行区','宝山区','嘉定区','浦东新区','金山区','松江区','青浦区','奉贤区','崇明区','其他'],
                    ['越秀区','荔湾区','海珠区','天河区','白云区','黄埔区','南沙区','番禺区','花都区','从化区','增城区','其他'],

                    ['玄武区','秦淮区','建邺区','鼓楼区','浦口区','栖霞区','雨花台区','江宁区','六合区','溧水区','高淳区','江北新区','其他'],
                    ['海曙区','江北区','北仑区','镇海区','鄞州区','奉化区','象山县','宁海县','余姚市','慈溪市','其他'],
                    // ['渝中区','大渡口区','江北区','沙坪坝区','九龙坡区','南岸区','北碚区','渝北区','巴南区','其他'],
                    ['渝中区','大渡口区','江北区','沙坪坝区','九龙坡区','南岸区','北碚 bèi 区','渝北区','巴南区','万州区','涪fú陵区','永川区','璧山区','大足区','綦qí江区','江津区','合川区','黔qián江区','长寿区','南川区','铜梁区','潼tóng南区','荣昌区','开州区','梁平区','武隆区','城口县','丰都县','垫江县','忠县','云阳县','奉节县','巫山县','巫溪县','石柱土家族自治县','秀山土家族苗族自治县','酉阳土家族苗族自治县','彭水苗族土家族自治县','其他'],
                    ['锦江区','青羊区','金牛区','武侯区','成华区','龙泉驿区','新都区','郫都区','温江区','双流区','青白江区','新津区','都江堰市','彭州市','邛崃市','崇州市','简阳市','金堂县','大邑县','蒲江县','其他'],

                    ['上城区','拱墅区','西湖区','滨江区','萧山区','余杭区','临平区','钱塘区','富阳区','临安区','建德市','桐庐县','淳安县','其他'],
                    ['姑苏区','虎丘区','吴中区','相城区','吴江区','工业园区','常熟市','张家港市','昆山市','太仓市','其他'],
                    ['江岸区','江汉区','硚口区','汉阳区','武昌区','青山区','洪山区','东西湖区','汉南区','蔡甸区','江夏区','黄陂区','新洲区','其他'],
                    ['新城区','碑林区','莲湖区','雁塔区','灞桥区','未央区','阎良区','临潼区','长安区','高陵区','鄠邑区','蓝田县','周至县','其他'],
                    ['芙蓉区','天心区','岳麓区','开福区','雨花区','望城区','浏阳市','宁乡市','长沙县','其他'],
                    ['云岩区','南明区','花溪区','乌当区','白云区','观山湖区','修文县','息烽县','开阳县','清镇市','其他'],
                    ['东湖区','西湖区','青云谱区','青山湖区','新建区','红谷滩区','南昌县','进贤县','安义县','其他'],

                    ['金坛区','武进区','新北区','天宁区','钟楼区','溧阳市','其他'],
                    ['鹿城','龙湾','瓯海','洞头','瑞安','乐清','龙港','永嘉','平阳','苍南','文成','泰顺','其他'],
                    ['越城区','柯桥区','上虞区','新昌县','诸暨市','其他'],
                    ['椒江区','黄岩区','路桥区','天台县','仙居县','三门县','临海市','温岭市','玉环市','其他'],
                    ['麒麟区','宣威市','沾益区','马龙区','师宗县','富源县','陆良县','罗平县','会泽县','其他'],
                    ['花山区','雨山区','博望区','当涂县','含山县','和县','其他'],
                    ['清新区','清城区','东城区','新城区','阳山县','佛冈县','连南县','连山县','英德市','连州市','其他'],
                    ['其他']
                ];

                var $option_html = $('#location-city-option-list').html();
                $('select[name=info-select-set-column-value]').html($option_html);
                $('select[name=info-select-set-column-value]').find("option[value='"+$that.attr("data-value")+"']").attr("selected","selected");

                $('select[name=info-select-set-column-value]').removeClass('select2-project').addClass('select2-city');
                $('select[name=info-select-set-column-value2]').removeClass('select2-project').addClass('select2-district');

                $('select[name=info-select-set-column-value2]').show();

                var $city_index = $(".select2-city").find('option:selected').attr('data-index');
                $(".select2-district").html('<option value="">选择区划</option>');
                $.each($district_list[$city_index], function($i,$val) {
                    $(".select2-district").append('<option value="' + $val + '">' + $val + '</option>');
                });
                $('.select2-district').find("option[value='"+$that.attr("data-value2")+"']").attr("selected","selected");

                $('.select2-city').select2();
                $('.select2-district').select2();


                $(".select2-city").change(function() {

                    $that = $(this);

                    var $city_index = $that.find('option:selected').attr('data-index');

                    $(".select2-district").html('<option value="">选择区划</option>');

                    $.each($district_list[$city_index], function($i,$val) {

                        $(".select2-district").append('<option value="' + $val + '">' + $val + '</option>');
                    });

                    $('.select2-district').select2();
                });
            }
            else if($that.attr("data-key") == "project_id")
            {
                $('select[name=info-select-set-column-value]').removeClass('select2-city').addClass('select2-project');
                $('.select2-project').select2({
                    ajax: {
                        url: "{{ url('/admin/item/item_select2_project') }}",
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
            var $column_key2 = $('input[name="info-select-set-column-key2"]').val();
            var $column_value = $('select[name="info-select-set-column-value"]').val();
            var $column_value2 = $('select[name="info-select-set-column-value2"]').val();

            // layer.msg('确定"提交"么？', {
            //     time: 0
            //     ,btn: ['确定', '取消']
            //     ,yes: function(index){
            //     }
            // });

            $.post(
                "{{ url('/item/item-info-select-set') }}",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: $('input[name="info-select-set-operate"]').val(),
                    item_id: $('input[name="info-select-set-item-id"]').val(),
                    operate_type: $('input[name="info-select-set-operate-type"]').val(),
                    column_key: $column_key,
                    column_key2: $column_key2,
                    column_value: $column_value,
                    column_value2: $column_value2,
                },
                function(data){
                    // layer.close(index);
                    if(!data.success)
                    {
                        layer.msg(data.msg);
                    }
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