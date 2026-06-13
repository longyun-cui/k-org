@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')
    {{ $head_title or '朝鲜族社群平台 - 发现身边的朝鲜族社群组织活动' }}
@endsection

@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')朝鲜族社群组织活动平台,发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社区,朝鲜族社群,朝鲜族组织,朝鲜族活动,朝鲜族社群平台,朝鲜族组织平台,朝鲜族活动平台,朝鲜族生活社区@endsection


@section('wx_share_title')朝鲜族社群平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www-wx-share.jpg') }}@endsection




@section('sidebar')
    @include(env('TEMPLATE_K_COMMON').'component.sidebar.sidebar-root')
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    <div id="grid">

    </div>


</div>
@endsection




@section('style')
<style>
    .box-footer a {color:#777;cursor:pointer;}
    .box-footer a:hover {color:orange;cursor:pointer;}
    .comment-choice-container {border-top:2px solid #ddd;}
    .comment-choice-container .form-group { margin-bottom:0;}
</style>
@endsection




@section('script')
<script>
    $(function() {
        // ===== 初始化 Grid =====
        var grid = new tui.Grid({
            // 注意：el 需要是原生 DOM 元素，用 $('#grid')[0]
            el: $('#grid')[0],
            // 或 el: document.getElementById('grid'),

            // v4 列定义格式：header + name
            columns: [
                { header: 'ID',       name: 'id',    width: 80  },
                { header: '姓名',     name: 'name',  width: 120 },
                { header: '年龄',     name: 'age',   width: 80  },
                { header: '邮箱',     name: 'email'           }
            ],

            // 静态测试数据
            data: [
                { id: 1, name: '张三', age: 28, email: 'zhangsan@example.com' },
                { id: 2, name: '李四', age: 32, email: 'lisi@example.com' },
                { id: 3, name: '王五', age: 25, email: 'wangwu@example.com' }
            ],

            // 可选：开启行号、分页等
            rowHeaders: ['checkbox'],
            pageOptions: {
                useClient: true,
                perPage: 10
            }
        });
    });
</script>
@endsection