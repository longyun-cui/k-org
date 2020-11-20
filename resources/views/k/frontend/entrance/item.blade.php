@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')

@section('head_title') {{ $item->title or '' }} @endsection
@section('header') {{ $item->title or '' }} @endsection
@section('description','')

@section('content')

    <div class="_none">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.item')

        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user', ['data'=>$item->owner])

        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-paste-ad', ['item'=>$user->ad])

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-sponsor', ['sponsor_list'=>$user->pivot_relation])

        </div>

    </div>

@endsection



@section('style')
<style>
    .box-footer a {color:#777;cursor:pointer;}
    .box-footer a:hover {color:orange;cursor:pointer;}
    .comment-container {}
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
        $(".comments-get-default").click();
    });
</script>
@endsection
