@extends(env('TEMPLATE_K_WWW').'layout.layout')

@section('head_title','注册组织 - 如未科技')
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
                    <h3 class="box-title">注册组织机构</h3>
                    <div class="box-tools pull-right">
                    </div>
                </div>

                <form action="" method="post" class="form-horizontal form-bordered" id="form-org-register">
                    <div class="box-body">

                        {{ csrf_field() }}
                        <input type="hidden" name="operate[type]" value="{{ $operate or 'create' }}" readonly>
                        <input type="hidden" name="operate[id]" value="{{ $operate_id or 0 }}" readonly>

                        {{--类别--}}
                        <div class="form-group form-category">
{{--                            <label class="control-label col-md-2">类型</label>--}}
                            <div class="col-md-12">
                                <div class="btn-group">

                                        <button type="button" class="btn">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="user_type" value="11" checked="checked"> 社群/组织
                                                </label>
                                            </div>
                                        </button>

                                        <button type="button" class="btn">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="user_type" value="88"> 企业/商铺/店铺
                                                </label>
                                            </div>
                                        </button>

                                </div>
                            </div>
                        </div>

                        {{--登录手机--}}
                        <div class="form-group has-feedback">
                            {{--                            <label class="control-label- col-md-6">登录手机</label>--}}
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="mobile" placeholder="登录手机">
                                <span class="form-control-feedback fa fa-mobile"> 登录手机</span>
                            </div>
                        </div>

                        {{--名称--}}
                        <div class="form-group has-feedback">
                            {{--<label class="control-label- col-md-2">名称</label>--}}
                            <div class="col-md-12 ">
                                <input type="text" class="form-control" name="username" placeholder="名称">
                                <span class="form-control-feedback fa fa-user"> 名称</span>
                            </div>
                        </div>

                        {{--选择所在城市--}}
                        <div class="form-group area_select_box">
{{--                            <label class="control-label col-md-12 pull-left" style="width:100%;clear:both;">所在城市</label>--}}
                            <div class="col-xs-4 " style="padding-right:0;">
                                <select name="area_province" class="form-control form-filter area_select_province" id="area_province">
                                    <option value="">请选择省</option>
                                </select>
                            </div>
                            <div class="col-xs-4 " style="padding-left:0;padding-right:0;">
                                <select name="area_city" class="form-control form-filter area_select_city" id="area_city">
                                    <option value="">请先选择省</option>
                                </select>
                            </div>
                            <div class="col-xs-4 " style="padding-left:0;">
                                <select name="area_district" class="form-control form-filter area_select_district" id="area_district">
                                    <option value="">请先选择市</option>
                                </select>
                            </div>
                        </div>
                        {{--电话--}}
{{--                        <div class="form-group has-feedback">--}}
{{--                            <label class="control-label- col-md-2">联系电话</label>--}}
{{--                            <div class="col-md-12 ">--}}
{{--                                <input type="text" class="form-control" name="contact_phone" placeholder="联系电话">--}}
{{--                                <span class="form-control-feedback fa fa-phone"> 联系电话</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--地址--}}
{{--                        <div class="form-group has-feedback">--}}
{{--                            <label class="control-label- col-md-2">联系地址</label>--}}
{{--                            <div class="col-md-12 ">--}}
{{--                                <input type="text" class="form-control" name="contact_address" placeholder="联系地址">--}}
{{--                                <span class="form-control-feedback fa fa-location-arrow"> 联系地址</span>--}}

{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group has-feedback">
                            <div class="col-md-12 ">
                                <input type="text" class="form-control captcha-txt" name="captcha" placeholder="验证码" autocomplete="off">
                                {{--<span class="_pointer change_captcha" style="cursor: pointer;">{!! captcha_img() !!}</span>--}}
                                <span id="code"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div>
                                    <div class="row-">
                                        <label>
                                            <input type="checkbox" name="agree"> 阅读并接受
                                            <a href="javascript:void(0);">《用户协议》</a>
                                            及
                                            <a href="javascript:void(0);">《隐私权保护声明》</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="box-footer">
                    <div class="row" style="margin:8px 0;">
                        <div class="col-md-10 col-md-offset-2-">
                            <button type="button" onclick="" class="btn btn-primary" id="org-register-submit"><i class="fa fa-check"></i>提交</button>
                            <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="main-body-section main-body-right-section section-wrapper pull-right hidden-xs hidden-sm">

{{--        @if($auth_check)--}}
{{--            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-me')--}}
{{--        @else--}}
{{--            @include(env('TEMPLATE_K_COMMON_FRONT').'component.menu-for-root')--}}
{{--        @endif--}}

    </div>

</div>
@endsection




@section('style')
<style>
    #code {
        display: inline-block;
        padding: 4px 16px;
        margin-top: 4px;
        margin-bottom: 8px;
        width: 100px;
        height: 32px;
        font-size: 20px;
        color: #fff;
        background-color: black;
        text-align: center;
        vertical-align: middle;
    }
</style>
@endsection




@section('script')
<script>

    //	 验证码刷新
    function code(){
        var str="qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPLKJHGFDSAZXCVBNM";
        var str1=0;
        for(var i=0; i<4;i++){
            str1+=str.charAt(Math.floor(Math.random()*62))
        }
        str1=str1.substring(1);
        $("#code").text(str1);
    }


    $(function() {

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('input[name=mobile]').focus(function(){
            $(this).css({"border-color":''});
        });
        $('input[name=username]').focus(function(){
            $(this).css({"border-color":''});
        });
        $('select[name=area_province]').focus(function(){
            $(this).css({"border-color":''});
        });

        $('input[name=mobile]').change(function() {
            if($(this).val() == '') $(this).css({"border-color":'red'});
            else $(this).css({"border-color":''});
        });
        $('input[name=username]').change(function() {
            if($(this).val() == '') $(this).css({"border-color":'red'});
            else $(this).css({"border-color":''});
        });
        $('select[name=area_province]').change(function() {
            if($(this).val() == '') $(this).css({"border-color":'red'});
            else $(this).css({"border-color":''});
        });


        // 更换验证码
        $(".change_captcha").on('click', function() {
            var that = $(this);
            $.post("/common/change_captcha", {'_token': $('meta[name="_token"]').attr('content')}, function(result) {
                that.find('img').attr('src', result.data.src);
//                that.html(result.data.img);
            }, 'json');
        });

        code();

        $("#code").click(code);

        $("#org-register-submit").on('click', function() {

            var $mobile = $("input[name=mobile]").val();
            var $username = $("input[name=username]").val();
            var $province = $("select[name=area_province]").val();

            if($mobile == '')
            {
                $("input[name=mobile]").css({"border-color":'red'});
                return;
            }
            if($mobile == "" || $mobile.length != 11)
            {
                layer.msg('请正确输入电话！');
                $("input[name=mobile]").css({"border-color":'red'});
                return false;
            }
            else
            {
                var $rule = /^1[3-9]\d{9}$/;
                var isMobile = $rule.test($mobile);
                if(!isMobile)
                {
                    layer.msg('请正确输入电话！');
                    $("input[name=mobile]").css({"border-color":'red'});
                    return false;
                }
            }

            if($username == '')
            {
                $("input[name=username]").css({"border-color":'red'});
                return;
            }
            if($province == '')
            {
                $("select[name=area_province]").css({"border-color":'red'});
                return;
            }


            if(!$('input[name=agree]').is(':checked'))
            {
                layer.msg('请先同意用户协议和隐私权保护声明！');
                return false;
            }



            // 验证码
            var $captcha = $(".captcha-txt");
            var captcha_txt = $(".captcha-txt").val();
            if(captcha_txt == "" || captcha_txt.toUpperCase() != $("#code").text().toUpperCase())
            {

                layer.msg("验证码不正确");
//                $(".tips5").text("验证码不正确").css({"color":'red',"margin-top":"5px"});
                $(".captcha-txt").css({"border-color":'red'});
                return;
            }
            else
            {
                $(".captcha-txt").css({"border-color":''});
            }


            var options = {
                url: "/org-register",
                type: "post",
                dataType: "json",
                // target: "#div2",
                success: function (data) {
                    if(!data.success)
                    {
                        layer.msg(data.msg);
                        $("input").css({"border-color":''});

                        if(data.data.error_type == 'username') $("input[name=username]").css({"border-color":'red'});
                        else if(data.data.error_type == 'mobile') $("input[name=mobile]").css({"border-color":'red'});
                        // else if(data.data.error_type == 'password') $("input[name=password]").css({"border-color":'red'});
                        // else if(data.data.error_type == 'password_confirm') $("input[name=password_confirm]").css({"border-color":'red'});
                    }
                    else
                    {
                        layer.msg(data.msg);
                        $("#form-org-register").find('input').val('');
                    }

//                    $('input[name=captcha]').val('');
//                    $("#form-admin-register #code").click();
//                    $("#form-admin-register .change_captcha").click();
                }
            };
            $("#form-org-register").ajaxSubmit(options);
        });
    });
</script>
@endsection