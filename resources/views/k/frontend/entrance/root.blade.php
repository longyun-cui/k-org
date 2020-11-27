@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')

@section('head_title','朝鲜族组织活动网')
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('header','')
@section('description','')


@section('wx_share_title')朝鲜族组织活动网@endsection
@section('wx_share_desc')朝鲜族组织活动分享平台@endsection
@section('wx_share_imgUrl'){{ url('/k-org.cn.png') }}@endsection




@section('sidebar')
    <ul class="sidebar-menu">

        <li class="header">目录</li>

        <li class="treeview {{ $sidebar_menu_root_active or '' }}">
            <a href="{{url('/')}}"><i class="fa fa-list text-orange"></i> <span>平台首页</span></a>
        </li>

        <li class="treeview {{ $sidebar_menu_activity_active or '' }}">
            <a href="{{url('/?type=activity')}}"><i class="fa fa-list text-orange"></i> <span>活动</span></a>
        </li>

        <li class="treeview {{ $sidebar_menu_organization_active or '' }}">
            <a href="{{url('/organization-list')}}"><i class="fa fa-list text-orange"></i> <span>组织</span></a>
        </li>

        <li class="header">Home</li>

        @if(!Auth::check())

            <li class="treeview">
                <a href="{{url('/login')}}"><i class="fa fa-circle-o"></i> <span>登录</span></a>
            </li>
            <li class="treeview">
                <a href="{{url('/register')}}"><i class="fa fa-circle-o"></i> <span>注册</span></a>
            </li>
        @else
            <li class="treeview">
                <a href="{{url('/home')}}"><i class="fa fa-home text-orange"></i> <span>{{ Auth::user()->username }}</span></a>
            </li>
        @endif

    </ul>
@endsection




@section('content')
<div style="display:none;">
    <input type="hidden" id="" value="{{ $encode or '' }}" readonly>
</div>

<div class="container">

    <div class="col-sm-12 col-md-9 container-body-left">

        {{--@foreach($datas as $num => $item)--}}
            {{--@include('frontend.component.topic')--}}
        {{--@endforeach--}}
        @include(env('TEMPLATE_DEFAULT').'frontend.component.items')

        {{ $items->links() }}

    </div>

    <div class="col-sm-12 col-md-3 hidden-xs hidden-sm container-body-right">

        <div class="box-body bg-white margin-bottom-8px right-menu">

            <a href="{{url('/')}}">
                <div class="box-body {{ $sidebar_menu_root_active or '' }}">
                    <i class="fa fa-list text-orange" style="width:24px;"></i><span>首页</span>
                </div>
            </a>

            <a href="{{url('/?type=activity')}}">
                <div class="box-body {{ $sidebar_menu_activity_active or '' }}">
                    <i class="fa fa-list text-orange" style="width:24px;"></i><span>活动</span>
                </div>
            </a>

            <a href="{{url('/organization-list')}}">
                <div class="box-body {{ $sidebar_menu_organization_active or '' }}">
                    <i class="fa fa-list text-orange" style="width:24px;"></i><span>机构</span>
                </div>
            </a>

        </div>

        <div class="box-body bg-white margin-bottom-8px right-home">

            @if(!Auth::check())
            <a href="{{ url('https://open.weixin.qq.com/connect/qrconnect?appid=wxc08005678d8d8736&redirect_uri=http%3A%2F%2Fk-org.cn%2Fweixin%2Flogin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect') }}">
                <div class="box-body">
                    <i class="fa fa-sign-in text-blue" style="width:24px;"></i><span>登录</span>
                </div>
            </a>
            {{--<a href="{{url('/register')}}">--}}
                {{--<div class="box-body {{ $menu_anonymous or '' }}">--}}
                    {{--<i class="fa fa-circle-o text-blue"></i> <span>&nbsp; 注册</span>--}}
                {{--</div>--}}
            {{--</a>--}}
            @else
            {{--<a href="{{url('/home')}}">--}}
                <div class="box-body">
                    <i class="fa fa-home text-blue" style="width:24px;"></i><span>{{ Auth::user()->username }}</span>
                </div>
            {{--</a>--}}
            <a href="{{url('/home')}}">
                <div class="box-body">
                    <i class="fa fa-heart text-blue" style="width:24px;"></i><span>我的收藏</span>
                </div>
            </a>
            @endif

        </div>

    </div>

</div>
@endsection


@section('style')
<style>
    .box-footer a {color:#777;cursor:pointer;}
    .box-footer a:hover {color:orange;cursor:pointer;}
    .comment-container {border-top:2px solid #ddd;}
    .comment-choice-container {border-top:2px solid #ddd;}
    .comment-choice-container .form-group { margin-bottom:0;}
    .comment-entity-container {border-top:2px solid #ddd;}
    .comment-piece {border-bottom:1px solid #eee;}
    .comment-piece:first-child {}
</style>
@endsection

@section('js')
<script>
    $(function() {
        $('article').readmore({
            speed: 150,
            moreLink: '<a href="#">展开更多</a>',
            lessLink: '<a href="#">收起</a>'
        });
    });
</script>
@endsection
