<div class="colo-md-12 box-body comment-piece comment-option" style="padding:8px 8px;" data-id="{{encode($comment->id)}}">


    {{--评论头部--}}
    <div class="box-body comment-title-container">

        <a href="{{url('/u/'.encode($comment->user->id))}}" target="_blank">{{$comment->user->name}}</a>

        <span class="pull-right text-muted disabled">{{ $comment->created_at->format('n月j日 H:i') }}</span>

        <span class="pull-right text-muted disabled comment-reply-toggle" role="button" data-num="{{$comment->comment_num}}">
            回复 @if($comment->comment_num){{$comment->comment_num}}@endif
        </span>

        <span class="comment-favor-btn" data-num="{{$comment->favor_num or 0}}">
            @if(Auth::check())
                @if(count($comment->favors))
                    <span class="pull-right text-muted disabled comment-favor-this-cancel" data-parent=".comment-option" role="button">
                        <i class="fa fa-thumbs-up text-red"></i>
                @else
                    <span class="pull-right text-muted disabled comment-favor-this" data-parent=".comment-option" role="button">
                        <i class="fa fa-thumbs-o-up"></i>
                @endif
            @else
                <span class="pull-right text-muted disabled comment-favor-this" data-parent=".comment-option" role="button">
                    <i class="fa fa-thumbs-o-up"></i>
            @endif

            @if($comment->favor_num){{$comment->favor_num}}@endif </span>
        </span>

    </div>


    {{--评论内容--}}
    <div class="box-body comment-content-container">
        <p> {{ $comment->content }} </p>
    </div>


    {{--回复评论--}}
    <div class="box-body comment-reply-input-container">

        <div class="input-group margin">
            <input type="text" class="form-control comment-reply-content">

            <span class="input-group-btn">
                <button type="button" class="btn btn-primary btn-flat comment-reply-submit">回复</button>
            </span>
        </div>

    </div>


    {{--回复内容--}}
    <div class="box-body reply-container">

        <div class="reply-list-container">
            {{--@if(count($comment->dialogs))--}}
                {{--@foreach($comment->dialogs as $reply)--}}
                    {{--@component('frontend.component.reply',['reply'=>$reply])--}}
                    {{--@endcomponent--}}
                {{--@endforeach--}}
            {{--@endif--}}
        </div>

        @if($comment->dialogs_count)
            <div class="box-body more-box reply-more-box">
                <button type="button" class="btn btn-block btn-more replies-more"
                    data-more="{{$comment->dialog_more}}"
                    data-maxId="{{$comment->dialog_max_id}}"
                    data-minId="{{$comment->dialog_min_id}}"
                >{!! $comment->dialog_more_text !!}</button>
            </div>
        @endif

    </div>

</div>

