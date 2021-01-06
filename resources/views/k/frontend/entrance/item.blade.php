@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')

@section('head_title') {{ $item->title or '' }} @endsection
@section('meta_title')@endsection
@section('meta_author')@endsection
@section('meta_description')@endsection
@section('meta_keywords')@endsection


@section('wx_share_title'){{ $item->title or '朝鲜族组织活动网' }}@endsection
@section('wx_share_desc'){{ $item->owner->username or '朝鲜族组织活动网' }}@endsection
@section('wx_share_imgUrl'){{ url(env('DOMAIN_CDN').'/'.$item->owner->portrait_img) }}@endsection




@section('sidebar')

    @include(env('TEMPLATE_DEFAULT').'frontend.component.sidebar-item')

@endsection




@section('header') {{ $item->title or '' }} @endsection
@section('description','')
@section('content')

    <div class="_none">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left margin-bottom-8px">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.item')

        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user', ['data'=>$item->owner])

        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-ad-paste', ['item'=>$user->ad])

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-sponsor', ['sponsor_list'=>$user->pivot_relation])

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

@section('js')
<script>
    $(function() {
        $(".comments-get-default").click();
    });
</script>
@endsection
