@foreach($notification_list as $num => $val)
<div class="item-piece item-option" data-notification="{{ $val->id }}">
    <!-- BEGIN PORTLET-->
    <div class="panel-default box-default item-portrait-container">
        <a href="{{ url('/user/'.$val->source->id) }}">
            <img src="{{ url(env('DOMAIN_CDN').'/'.$val->source->portrait_img) }}" alt="">
        </a>
    </div>

    <div class="panel-default box-default item-entity-container with-portrait">

        {{--header--}}
        <div class="item-row item-info-row text-muted">
            <span class="item-user-portrait _none"><img src="{{ url(env('DOMAIN_CDN').'/'.$val->source->portrait_img) }}" alt=""></span>
            <span class="item-user-name"><a href="{{ url('/user/'.$val->source->id) }}"><b>{{ $val->source->username or '' }}</b></a></span>

            @if($val->notification_type == 1)
                <span>评论了你的文章</span>
            @elseif($val->notification_type == 2)
                <span>回复了你的评论</span>
            @elseif($val->notification_type == 3)
                <span>回复了你的评论</span>
            @elseif($val->notification_type == 11)
                <span></span>
            @elseif($val->notification_type == 12)
                <span></span>
            @else
                <span></span>
            @endif

            <div class="pull-right">
                <a class="" role="button">
                    {{ time_show($val->created_at->timestamp) }}
                    {{--{{ time_show($val->created_at->getTimestamp()) }}--}}
                </a>
            </div>

        </div>


        <div class="item-row item-content-row margin-top-8 margin-bottom-8">
        @if($val->notification_type == 0)
        @elseif($val->notification_type == 1)
            {{{ $val->communication->content or '' }}}
        @elseif($val->notification_type == 2)
            {{--<span>回复</span>--}}
            {{--<a href="{{ url('/user/'.$val->reply->user->id) }}" target="_blank" class="user-link">--}}
                {{--{{ $val->reply->user->name }}--}}
            {{--</a>--}}
            {{--<span>:</span>--}}
            {{--<span>{{ $val->communication->content or '' }}</span>--}}
        @elseif($val->notification_type == 11)
            <span>给你点赞 <i class="fa fa-thumbs-o-up text-red"></i></span>
        @elseif($val->notification_type == 12)
            <span><i class="fa fa-thumbs-o-up text-red"></i> 赞了你的回复</span>
        @elseif($val->notification_type == 13)
            <span><i class="fa fa-thumbs-o-up text-red"></i> 赞了你的评论</span>
        @else
            <span></span>
        @endif
        </div>


        @if(!empty($val->item))
            <a href="{{ url('/item/'.$val->item->id) }}" target="_blank">
                <div class="item-row forward-item-container" role="button">
                    <div class="portrait-box"><img src="{{ url(env('DOMAIN_CDN').'/'.$val->item->owner->portrait_img) }}" alt=""></div>
                    <div class="text-box">
                        @if($val->item->category == 99)
                            <div class="text-row forward-item-title">
                                {{ $val->item->content or '' }}
                            </div>
                            <div class="text-row forward-user-name">
                                转发{{ '@'.$val->item->forward_item->owner->username }} : {{ $val->item->forward_item->title or '' }}
                            </div>
                        @else
                            <div class="text-row forward-item-title">
                                {{ $val->item->title or '' }}
                            </div>
                            <div class="text-row forward-user-name">{{ '@'.$val->item->owner->username }}</div>
                        @endif
                    </div>
                </div>
                @if(in_array($val->notification_type, [2,3,12,13]))
                <div class="item-row item-comment-container" role="button" style="margin-top:-8px;">
                    <div class="">
                        <span class="user-link"><b>{{ $val->reply->user->username or '' }}</b></span>
                        @if(!empty($val->reply->reply->id))
                        <span class="font-12px">回复</span>
                        <span class="user-link"><b>{{ $val->reply->reply->user->username or '' }}</b></span>
                        @endif
                        <span>:</span>
                        <span class="">{{ $val->reply->content or '' }}</span>
                    </div>
                </div>
                @endif
                @if(in_array($val->notification_type, [2,3]))
                <div class="item-row item-comment-container margin-top-4" role="button">
                    <div class="">
                        <span class="user-link"><b>{{ $val->source->username or '' }}</b></span>
                        <span class="font-12px">回复</span>
                        <span class="user-link">{{ $val->reply->user->username }}</span>
                        <span>:</span>
                        <span class="">{{{ $val->communication->content or '' }}}</span>
                    </div>
                </div>
                @endif
            </a>
        @else
            <div class="item-row forward-item-container" role="button" style="line-height:40px;text-align:center;">
                内容被作者删除或取消分享。
            </div>
        @endif

        {{--tools--}}
        <div class="item-row item-tools-row _none">

            <div class="pull-right">


            </div>

        </div>


        {{--comment--}}
        <div class="item-row comment-container _none">

            <input type="hidden" class="comments-get comments-get-default">

            <div class="comment-input-container">
                <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                    {{csrf_field()}}
                    <input type="hidden" name="item_id" value="{{ $val->id or 0}}" readonly>
                    <input type="hidden" name="type" value="1" readonly>

                    <div class="item-row ">
                        <div class="comment-textarea-box">
                            <textarea class="comment-textarea" name="content" rows="2" placeholder="请输入你的评论"></textarea>
                        </div>
                        @if($val->category == 7)
                            <div class="item-row ">
                                <div class="btn-group">
                                    <button type="button" class="btn">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="support" value="0" checked="checked"> 只评论
                                            </label>
                                        </div>
                                    </button>
                                    <button type="button" class="btn">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="support" value="1"> 支持正方
                                            </label>
                                        </div>
                                    </button>
                                    <button type="button" class="btn">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="support" value="2"> 支持反方
                                            </label>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="comment-button-box">
                            <a href="javascript:void(0);" class="comment-button comment-submit btn-primary" role="button">发 布</a>
                        </div>
                    </div>

                </form>
            </div>

        </div>

    </div>
    <!-- END PORTLET-->
</div>
@endforeach

@if($notification_style == "paginate")
    {{{ $notification_list->links() }}}
@endif