{{--<!-- Main Footer -->--}}
<footer class="main-footer" style="margin-left:0;margin-bottom:50px;">
    <!-- To the right -->
    <div class="pull-right hidden-xs _none">
        Anything you want
    </div>
    <!-- Default to the left -->
    {{--<strong>Copyright &copy; 上海如哉网络科技有限公司 2017-2020 <a href="#">Company</a>.</strong> All rights reserved. 沪ICP备17052782号-4--}}
    <small>
        {{--注册组织•赞助商请联系管理员--}}
        <a href="{{ url(env('DOMAIN_WWW').'/introduction') }}">平台介绍</a>
{{--        <span style="margin-left:4px;margin-right:4px;">|</span>--}}
{{--        <a href="{{ url(env('DOMAIN_ORG')) }}">组织登录</a>--}}
        <span style="margin-left:4px;margin-right:4px;">|</span>
        <a href="{{ url(env('DOMAIN_WWW').'/mine/my-organization') }}">我的组织机构</a>
        <span style="margin-left:4px;margin-right:4px;">|</span>
        <a href="{{ url(env('DOMAIN_WWW').'/org-register') }}">注册新组织</a>
    </small>
    <br>
    <small>商务合作-电话微信同号 <strong>17721364771</strong></small>
    <br>
    {{--<small>联系电话：</small><strong>17721364771</strong>--}}
    {{--<br>--}}
    <small>版权所有&copy;上海如哉网络科技有限公司</small><span class="_none">(2017-{{ time('Y') }})</span>
    <br class="visible-xs">
    <a target="_blank" href="https://beian.miit.gov.cn"><small>沪ICP备17052782号-4</small></a>

</footer>