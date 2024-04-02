@extends(env('TEMPLATE_K_WWW').'layout.layout')

@section('title','注册')

@section('content')
<div class="register-box">

    <div class="register-logo _none">
        <a href="/admin"></a><b></b> 注册
    </div>

    <div class="register-box-body">

        <p class="login-box-msg">注册一个组织</p>

        <form action="" method="post" id="form-admin-register">

            {{ csrf_field() }}

            {{--<div class="form-group has-feedback">--}}
                {{--<select class="form-control" name="type">--}}
                    {{--<option value="5">企业</option>--}}
                    {{--<option value="11">个人用户</option>--}}
                    {{--<option value="19">个体工商户</option>--}}
                    {{--<option value="1">机关单位</option>--}}
                    {{--<option value="3">事业单位</option>--}}
                    {{--<option value="7">社会团体</option>--}}
                    {{--<option value="9">其他组织机构</option>--}}
                    {{--<option value="0">暂不选择</option>--}}
                {{--</select>--}}
                {{--<span class="glyphicon glyphicon-user form-control-feedback"></span>--}}
            {{--</div>--}}

            <div class="form-group has-feedback-">
                <div class="radio">
                    <input type="radio" name="user_type" value="11" checked="checked"> 组织
                </div>
                <div class="radio">
                    <input type="radio" name="user_type" value="88"> 赞助商
                </div>

            </div>

            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" placeholder="组织名称">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            {{--<div class="form-group has-feedback">--}}
                {{--<input type="text" class="form-control" name="website_name" placeholder="机构域名，唯一标识，仅限英文字符">--}}
                {{--<span class="glyphicon glyphicon-font form-control-feedback"></span>--}}
            {{--</div>--}}
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="mobile" placeholder="手机">
                <span class="glyphicon glyphicon-phone form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="密码" autocomplete="off">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password_confirm" placeholder="确认密码" autocomplete="off">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="text" class="form-control captcha-txt" name="captcha" placeholder="验证码" autocomplete="off">
                {{--<span class="_pointer change_captcha" style="cursor: pointer;">{!! captcha_img() !!}</span>--}}
                <span id="code"></span>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="agree"> 阅读并接受
                            <a href="#">《用户协议》</a>
                            及
                            <a href="#">《隐私权保护声明》</a>
                        </label>
                    </div>
                </div>
                {{--<div class="col-xs-4">--}}
                    {{--<button type="button" class="btn btn-primary btn-block btn-flat" id="admin-register-submit">注册</button>--}}
                {{--</div>--}}
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-primary btn-block btn-flat pull-right" id="admin-register-submit">注册</button>
                </div>
            </div>

        </form>

        <div class="social-auth-links text-center" style="display: none">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> 微信登陆</a>
            <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> 支付宝登陆</a>
        </div>

        <div class="row" style="margin-top:16px;">
            <div class="col-sm-12">
                <a href="/org/login" class="text-center">返回组织管理后台登陆</a>
            </div>
        </div>

        <div class="row" style="margin-top:8px;">
            <div class="col-sm-12">
                <a href="/" class="text-center">返回平台首页</a>
            </div>
        </div>

    </div>

</div>
@endsection


@section('css')
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


@section('js')
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

        // 提交表单
        $("#admin-register-submit").on('click', function() {

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
                        else if(data.data.error_type == 'password') $("input[name=password]").css({"border-color":'red'});
                        else if(data.data.error_type == 'password_confirm') $("input[name=password_confirm]").css({"border-color":'red'});
                    }
                    else
                    {
                        layer.msg(data.msg);
                        $("#form-admin-register").find('input').val('');
                    }

//                    $('input[name=captcha]').val('');
//                    $("#form-admin-register #code").click();
//                    $("#form-admin-register .change_captcha").click();
                }
            };
            $("#form-admin-register").ajaxSubmit(options);

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


    });

</script>
@endsection


