@if($item->category != 99)
<div class="item-piece item-option item" data-item="{{ $item->id }}">
    <div class="panel-default box-default item-portrait-container _none">
        <a href="{{ url('/org/'.$item->org->id) }}">
            <img src="{{ url(env('DOMAIN_CDN').'/'.$item->org->portrait_img) }}" alt="">
        </a>
    </div>
    <div class="panel-default box-default item-entity-container">

        <div class="item-row item-title-row">
            <span class="item-user-portrait _none"><img src="{{ url(env('DOMAIN_CDN').'/'.$item->org->portrait_img) }}" alt=""></span>
            <span class="item-user-name _none"><a href="{{ url('/org/'.$item->org->id) }}">{{ $item->org->name or '' }}</a></span>
            <b class="font-lg">{{ $item->title or '' }}</b>
        </div>

        @if($item->time_type == 1)
            <div class="item-row item-content-row margin-bottom-8">
                @if(!empty($item->start_time))
                    <b class="text-blue">{{ time_show($item->start_time) }}</b>
                @endif
                @if(!empty($item->end_time))
                    &nbsp;<b class="font-12px">至</b>&nbsp;
                    <b class="text-blue">{{ time_show($item->end_time) }}</b>
                @endif
            </div>
        @endif

        <div class="item-row item-tools-row font-xs">
            {{--<span> • {{ $item->created_at->format('n月j日 H:i') }}</span>--}}
            <span>{{ time_show($item->created_at) }}</span>
            <span> • </span>
            <span>阅读({{ $item->visit_num }})</span>
            <span> • </span>
            <a class="forward-show" data-toggle="modal" data-target="#modal-forward" role="button">分享({{ $item->share_num }})</a>
            <span> • </span>
            {{--点赞--}}
            <a class="operate-btn" role="button" data-num="{{ $item->favor_num or 0 }}">
                @if(Auth::check() && $item->pivot_item_relation->contains('type', 11))
                    <span class="remove-this-favor" title="取消赞"><i class="fa fa-thumbs-up text-red"></i>(<num>{{ $item->favor_num }}</num>)</span>
                @else
                    <span class="add-this-favor" title="点赞"><i class="fa fa-thumbs-o-up"></i>(<num>{{ $item->favor_num }}</num>)</span>
                @endif
            </a>
        </div>

    </div>
</div>
@endif




@if(($item->category != 99) && ( (!empty($item->content)) || (!empty($item->description))) )
<div class="item-piece item-option item" data-item="{{ $item->id }}">

    @if($item->category == 99)
    <div class="panel-default box-default item-portrait-container _none">
        <a href="{{ url('/org/'.$item->org->id) }}">
            <img src="{{ url(env('DOMAIN_CDN').'/'.$item->org->portrait_img) }}" alt="">
        </a>
    </div>
    @endif

    <div class="item-entity-container">

        {{--content--}}
        <div class="item-row item-content-row">

            {{--description--}}
            @if(!empty($item->description))
                <div class="item-row item-description-row">
                    <div class="colo-md-12 text-muted"> {{ $item->description or '' }} </div>
                </div>
            @endif

            @if($item->category == 99)
                <article class="item-row colo-md-12 multi-ellipsis-3- margin-bottom-8" style="">{!! $item->content or '' !!}</article>
                @if(!empty($item->forward_item))
                    <a href="{{ url('/item/'.$item->forward_item->id) }}" target="_blank">
                        <div class="item-row forward-item-container" role="button">
                            <div class="portrait-box"><img src="{{ url(env('DOMAIN_CDN').'/'.$item->forward_item->org->portrait_img) }}" alt=""></div>
                            <div class="text-box">
                                <div class="text-row forward-item-title">{{ $item->forward_item->title or '' }}</div>
                                <div class="text-row forward-user-name">{{ '@'.$item->forward_item->org->name }}</div>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="item-row forward-item-container" role="button" style="line-height:40px;text-align:center;">
                        内容被作者删除或取消分享。
                    </div>
                @endif
                <div class="item-row item-info-row text-muted">
                    {{--<span> • {{ $item->created_at->format('n月j日 H:i') }}</span>--}}
                    <span>{{ time_show($item->created_at) }}</span>
                    <span> • </span>
                    <span>阅读({{ $item->visit_num }})</span>
                    <span> • </span>
                    {{--点赞--}}
                    <a class="operate-btn" role="button" data-num="{{ $item->favor_num or 0 }}">
                        @if(Auth::check() && $item->pivot_item_relation->contains('type', 11))
                            <span class="remove-this-favor" title="取消赞"><i class="fa fa-thumbs-up text-red"></i>(<num>{{ $item->favor_num }}</num>)</span>
                        @else
                            <span class="add-this-favor" title="点赞"><i class="fa fa-thumbs-o-up"></i>(<num>{{ $item->favor_num }}</num>)</span>
                        @endif
                    </a>
                </div>
            @else
                <article class="colo-md-12"> {!! $item->content or '' !!} </article>
            @endif
        </div>

    </div>

</div>
@endif




@if($item->category == 11)
<div class="item-piece item-piece-2 item-option item" data-item="{{ $item->id }}">

    <div class="item-row navigation-box">
        <div class="item-row prev-content"><span class="label">上一篇:</span> <span class="a-box"></span></div>
        <div class="item-row next-content"><span class="label">下一篇:</span> <span class="a-box"></span></div>
    </div>

</div>
@endif




<div class="item-piece item-option item" data-item="{{ $item->id }}">
    <div class="item-row item-entity-container">

        {{--comment--}}
        <div class="item-row comment-container">

            <input type="hidden" class="comments-get comments-get-default">

            <div class="item-row comment-input-container">
                <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                    {{csrf_field()}}
                    <input type="hidden" name="item_id" value="{{ $item->id or 0}}" readonly>
                    <input type="hidden" name="type" value="1" readonly>

                    <div class="item-row ">
                        <div class="comment-textarea-box">
                            <textarea class="comment-textarea" name="content" rows="2" placeholder="请输入你的评论"></textarea>
                        </div>
                        <div class="comment-button-box">
                            <a href="javascript:void(0);" class="comment-button comment-submit btn-primary" role="button">发 布</a>
                        </div>
                    </div>

                </form>
            </div>


            {{--评论列表--}}
            <div class="item-row comment-entity-container">

                <div class="item-row comment-list-container">
                </div>

                <div class="item-row more-box">
                    <a href="javascript:void(0);"><span class="item-more">没有更多了</span></a>
                </div>

            </div>

        </div>

    </div>
</div>