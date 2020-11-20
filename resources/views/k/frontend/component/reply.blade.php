<div class="colo-md-12 box-body reply-piece reply-option" data-id="{{encode($reply->id)}}">

    {{--回复头部--}}
    <div class="box-body reply-title-container">

        @if($reply->is_anonymous == 1)
            <a href="javascript:void(0)">
                {{$reply->user->anonymous_name}} (匿名)
                @if(Auth::check()) @if($reply->user->id == Auth::user()->id) @endif @endif
            </a>
        @else
            <a href="{{url('/u/'.encode($reply->user->id))}}" target="_blank">{{$reply->user->name}}</a>
        @endif

        @if($reply->reply_id != $reply->dialog_id)
        @if($reply->reply)
            回复
            @if($reply->reply->is_anonymous == 1)
                <a href="javascript:void(0)">
                    {{$reply->reply->user->anonymous_name}} (匿名)
                    @if(Auth::check()) @if($reply->reply->user->id == Auth::user()->id) @endif @endif
                </a>
            @else
                <a href="{{url('/u/'.encode($reply->reply->user->id))}}" target="_blank">{{$reply->reply->user->name}}</a>
            @endif
        @endif
        @endif
        :

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

            <span class="input-group-addon" style="border-left:0;">
                <div class="checkbox" style="margin:0;line-height:20px;">
                    <label>
                        <input type="checkbox" name="is_anonymous" class="reply-anonymous" style="margin-top:4px;"> 匿名
                    </label>
                </div>
            </span>

            <span class="input-group-btn">
                  <button type="button" class="btn btn-primary btn-flat reply-submit">回复</button>
            </span>
        </div>

    </div>

</div>

