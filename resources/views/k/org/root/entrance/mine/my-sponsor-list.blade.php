@extends(env('TEMPLATE_K_ORG').'layout.layout')


@section('head_title')我的赞助商@endsection

@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动@endsection


@section('wx_share_title')朝鲜族社群平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www.jpg') }}@endsection




@section('sidebar')
{{--    @include(env('TEMPLATE_K_ORG').'component.sidebar.sidebar-root')--}}
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--我的社群组织--}}
{{--    <div class="main-body-section main-body-center-section section-wrapper page-root">--}}
{{--        <div class="container-box pull-left margin-bottom-16px">--}}

{{--            @if(count($user_list))--}}
{{--                @include(env('TEMPLATE_K_ORG').'component.user-list-for-relation',['user_list'=>$user_list])--}}
{{--                {!! $user_list->links() !!}--}}
{{--            @else--}}
{{--            @endif--}}

{{--        </div>--}}
{{--    </div>--}}

    {{--我的社群组织--}}
    <div class="main-body-section main-body-center-section section-wrapper page-root">
        <div class="container-box pull-left margin-bottom-16px-">

            @include(env('TEMPLATE_K_COMMON').'component.user-list-for-sponsor',['user_list'=>$user_list])
            {!! $user_list->links() !!}

        </div>
    </div>

    {{--添加赞助商--}}
    <div class="main-body-section main-body-center-section section-wrapper page-item">
        <div class="item-piece item-option item-wrapper user-piece user-option user margin-bottom-4px radius-2px">
            <div class="panel-default box-default item-entity-container text-center">
                <a href="/mine/sponsor-add">添加一个赞助商</a>
            </div>
        </div>
    </div>

</div>
@endsection




@section('custom-style')
@endsection




@section('custom-script')
<script>
    $(function() {

        // 启用
        $('.user-sponsor-open-submit').on('click', function(event){
            var $that = $(this);
            var $id = $that.attr('data-id');
            var $user_id = $that.attr('data-user-id');

            $.post(
                "/mine/sponsor-open",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: "sponsor-open",
                    id: $id,
                    user_id: $user_id
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        {{--layer.closeAll();--}}
                        location.reload();
                        // location.href = location.href;
                        // location.replace(location.href);
                    }
                },
                'json'
            ).error(function() {
                layer.msg("服务器错误");
            });
        });

        // 禁用
        $('.user-sponsor-close-submit').on('click', function(event){
            var $that = $(this);
            var $id = $that.attr('data-id');
            var $user_id = $that.attr('data-user-id');

            $.post(
                "/mine/sponsor-close",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: "sponsor-close",
                    id: $id,
                    user_id: $user_id
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        {{--layer.closeAll();--}}
                        location.reload();
                        // location.href = location.href;
                        // location.replace(location.href);
                    }
                },
                'json'
            ).error(function() {
                layer.msg("服务器错误");
            });
        });

    });
</script>
@endsection
