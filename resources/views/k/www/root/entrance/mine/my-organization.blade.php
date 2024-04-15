@extends(env('TEMPLATE_K_WWW').'layout.layout')


@section('head_title')我的社群组织 - 朝鲜族社群平台@endsection

@section('meta_title')朝鲜族社群平台@endsection
@section('meta_author')@endsection
@section('meta_description')发现身边的朝鲜族社群组织活动@endsection
@section('meta_keywords')朝鲜族,朝鲜族社群,朝鲜族组织,朝鲜族活动@endsection


@section('wx_share_title')朝鲜族社群平台@endsection
@section('wx_share_desc')发现身边的朝鲜族社群组织活动@endsection
@section('wx_share_imgUrl'){{ url('/custom/k/k-www.jpg') }}@endsection




@section('sidebar')
{{--    @include(env('TEMPLATE_K_COMMON').'component.sidebar.sidebar-root')--}}
@endsection
@section('header','')
@section('description','')
@section('content')
<div class="container">

    {{--我的社群组织--}}
    <div class="main-body-section main-body-center-section section-wrapper page-item">

        @include(env('TEMPLATE_K_COMMON').'component.user-list-for-mine',['user_list'=>$user_list])
        {!! $user_list->links() !!}

    </div>

    {{--注册新组织--}}
    <div class="main-body-section main-body-center-section section-wrapper page-item">
        <div class="item-piece item-option item-wrapper user-piece user-option user margin-bottom-4px radius-2px">
            <div class="panel-default box-default item-entity-container text-center">
                <div><a href="{{ url(env('DOMAIN_WWW').'/org-register') }}">注册一个社群组织</a></div>
            </div>
        </div>
    </div>

</div>
@endsection




@section('custom-style')
<style>
</style>
@endsection




@section('custom-script')
<script>
    $(function() {
        // 登录
        $('.user-login-to-org-submit').on('click', function(event){
            var $that = $(this);
            var $org_id = $that.attr('data-id');
            console.log($org_id);


            $.post(
                "/mine/my-org-login",
                {
                    _token: $('meta[name="_token"]').attr('content'),
                    operate: "my-org-login",
                    org_id: $org_id
                },
                function(data){
                    if(!data.success) layer.msg(data.msg);
                    else
                    {
                        {{--layer.closeAll();--}}
                        {{--var temp_window=window.open();--}}
                        {{--temp_window.location="{{ env('DOMAIN_ORG') }}";--}}
                        window.location.href = "{{ env('DOMAIN_ORG') }}";
                    }
                },
                'json'
            );

        });
    });
</script>
@endsection