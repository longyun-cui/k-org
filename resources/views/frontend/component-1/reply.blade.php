<div class="colo-md-12 box-body reply-piece reply-option" data-id="{{encode($reply->id)}}">

    {{--回复头部--}}
    <div class="box-body reply-title-container">

        <a href="{{url('/u/'.encode($reply->user->id))}}">{{$reply->user->name}}</a>

        @if($reply->reply_id != $reply->dialog_id)
        @if($reply->reply)
            回复 <a href="{{url('/u/'.encode($reply->reply->user->id))}}">{{$reply->reply->user->name}}</a> :
        @endif
        @endif

        {{ $reply->content }} <br>

    </div>


    {{--回复工具--}}
    <div class="box-body reply-tools-container">

        <span class="pull-left text-muted disabled">{{ $reply->created_at->format('n月j日 H:i') }}</span>

        <span class="pull-right text-muted disabled reply-toggle" role="button" data-num="{{$reply->comment_num}}">
            回复 @if($reply->comment_num){{$reply->comment_num}}@endif
        </span>

        <span class="comment-favor-btn" data-num="{{$reply->favor_num or 0}}">
            @if(Auth::check())
                @if(count($reply->favors))
                    <span class="pull-right text-muted disabled comment-favor-this-cancel" data-parent=".reply-option" role="button">
                        <i class="fa fa-thumbs-up text-red"></i>
                @else
                    <span class="pull-right text-muted disabled comment-favor-this" data-parent=".reply-option" role="button">
                        <i class="fa fa-thumbs-o-up"></i>
                @endif
            @else
                <span class="pull-right text-muted disabled comment-favor-this" data-parent=".reply-option" role="button">
                    <i class="fa fa-thumbs-o-up"></i>
            @endif

            @if($reply->favor_num){{$reply->favor_num}}@endif </span>
        </span>

    </div>


    {{--回复输入框--}}
    <div class="box-body reply-input-container">

        <div class="input-group margin">
            <input type="text" class="form-control reply-content">
            <span class="input-group-btn">
                <button type="button" class="btn btn-primary btn-flat reply-submit">回复</button>
            </span>
        </div>

    </div>

</div>

