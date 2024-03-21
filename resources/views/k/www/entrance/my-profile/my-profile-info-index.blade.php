@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title','基本信息 - 朝鲜族生活社群')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title')朝鲜族生活社群@endsection
@section('wx_share_desc')朝鲜族社群组织活动分享平台@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON_FRONT').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div class="main-body-section main-body-left-section section-wrapper page-root">

        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PORTLET-->
                <div class="box box-info">

                    <div class="box-header with-border">
                        <h3 class="box-title">基本信息</h3>
                        <div class="pull-right">
                            <a href="{{url('/mine/my-profile-info-edit')}}">
                                <button type="button" onclick="" class="btn btn-success pull-right"><i class="fa fa-edit"></i>编辑信息</button>
                            </a>
                        </div>
                    </div>

                    <form class="form-horizontal form-bordered">
                        <div class="box-body">
                            {{--名称--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">用户名：</label>
                                <div class="col-md-8 ">
                                    <label class="control-label">{{  $data->username or '' }}</label>
                                </div>
                            </div>
                            {{--真实姓名--}}
                            <div class="form-group _none">
                                <label class="control-label col-md-2">真实姓名：</label>
                                <div class="col-md-8 ">
                                    <label class="control-label">{{  $data->true_name or '' }}</label>
                                </div>
                            </div>
                            {{--职业--}}
                            <div class="form-group _none">
                                <label class="control-label col-md-2">职业：</label>
                                <div class="col-md-8 ">
                                    <label class="control-label">{{  $data->telephone or '' }}</label>
                                </div>
                            </div>
                            {{--联系电话--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">联系电话：</label>
                                <div class="col-md-8 ">
                                    <label class="control-label">{{  $data->mobile or '' }}</label>
                                </div>
                            </div>
                            {{--联系邮箱--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">联系邮箱：</label>
                                <div class="col-md-8 ">
                                    <label class="control-label">{{  $data->email or '' }}</label>
                                </div>
                            </div>
                            {{--描述--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">个人签名：</label>
                                <div class="col-md-8 ">
                                    <label class="">{{  $data->description or '' }}</label>
                                </div>
                            </div>
                            {{--头像--}}
                            <div class="form-group">
                                <label class="control-label col-md-2">头像：</label>
                                <div class="col-md-8 ">
                                    <div style="width:100px;height:100px;">
                                        <img src="{{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}" alt="">
                                    </div>
                                </div>
                            </div>
                            {{--qrcode--}}
                            <div class="form-group _none">
                                <label class="control-label col-md-2">二维码：</label>
                                <div class="col-md-8 ">
                                    <a class="btn btn-success _left" target="_blank" href="/admin/download/qrcode">下载首页二维码</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="box-footer">
                        <div class="row" style="margin:8px 0;">
                            <div class="col-md-8">
                                <a href="{{url('/mine/my-profile-info-edit')}}">
                                    <button type="button" onclick="" class="btn btn-success"><i class="fa fa-edit"></i>编辑信息</button>
                                </a>
                                <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PORTLET-->


                <div class="box box-info _none">

                    <div class="box-header">
                        <h3 class="box-title">修改密码</h3>
                        <div class="pull-right">
                            <a href="{{url('/mine/my-profile-password-reset')}}">
                                <button type="button" onclick="" class="btn btn-primary pull-right"><i class="fa "></i>修改密码</button>
                            </a>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row" style="margin:8px 0;">
                            <div class="col-md-8 col-md-offset-2">
                                <a href="{{url('/mine/my-profile-password-reset')}}">
                                    <button type="button" onclick="" class="btn btn-primary"><i class="fa "></i>修改密码</button>
                                </a>
                                <button type="button" onclick="history.go(-1);" class="btn btn-default">返回</button>
                            </div>
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




@section('custom-style')
<style>
</style>
@endsection




@section('custom-script')
<script>
</script>
@endsection