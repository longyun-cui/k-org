@extends(env('TEMPLATE_DEFAULT').'frontend.layout.layout')

@section('head_title') {{ $data->username or '' }}的主页 @endsection
@section('header','')
@section('description','')

@section('header_title')  @endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-user', ['data'=>$data])

        </div>


        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.items')

            {{ $items->links() }}

        </div>


        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right pull-right">

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-paste-ad', ['item'=>$data->ad])

            @include(env('TEMPLATE_DEFAULT').'frontend.component.right-sponsor', ['sponsor_list'=>$data->pivot_relation])

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
            moreLink: '<a href="#">更多</a>',
            lessLink: '<a href="#">收起</a>'
        });
    });
</script>
@endsection
