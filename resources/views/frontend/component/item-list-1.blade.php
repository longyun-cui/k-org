@foreach($items as $num => $item)
<div class="item-piece item-option"
     data-item="{{ $item->id }}"
     data-calendar-days="{{ $item->calendar_days or '' }}"
>
    <!-- BEGIN PORTLET-->
    <div class="panel-default box-default item-portrait-container">
        <a target="_blank" href="{{ url('/org/'.$item->org->id) }}">
            <img src="{{ url(env('DOMAIN_CDN').'/'.$item->org->logo) }}" alt="">
        </a>
    </div>

    <div class="panel-default box-default item-entity-container with-portrait">

        {{--header--}}
        <div class="item-row item-info-row text-muted">
            <span class="item-user-portrait _none"><img src="{{ url(env('DOMAIN_CDN').'/'.$item->org->logo) }}" alt=""></span>
            <span class="item-user-name">
                <a target="_blank" href="{{ url('/org/'.$item->org->id) }}" class="text-hover-red font-sm">{{ $item->org->name or '' }}</a>
            </span>
            {{--<span> • </span>--}}
            {{--<span>{{ $item->created_at->format('n月j日 H:i') }}</span>--}}
            {{--<span> • </span>--}}
            {{--<span>{{ time_show($item->created_at) }}</span>--}}
        </div>


        @if($item->category != 99)
            <div class="item-row item-title-row">
                <a target="_blank" href="{{ url('/item/'.$item->id) }}" class="color-6 text-hover-blue font-md"><b>{{ $item->title or '' }}</b></a>
            </div>
        @endif

        @if($item->time_type == 1)
        <div class="item-row item-content-row _none">
            @if(!empty($item->start_time))
                <b class="text-blue">{{ time_show($item->start_time) }}</b>
            @endif
            @if(!empty($item->end_time))
                &nbsp;<b class="font-12px">至</b>&nbsp;
                <b class="text-blue">{{ time_show($item->end_time) }}</b>
            @endif
        </div>
        @endif

        {{--description--}}
        {{--@if(!empty($item->description))--}}
            {{--<div class="box-body item-description-row">--}}
                {{--<div class="colo-md-12 text-muted"> {!! $item->description or '' !!} </div>--}}
            {{--</div>--}}
        {{--@endif--}}

        {{--content--}}
        @if($item->category != 99)
        <div class="item-row item-content-row">
            <div class="media">
                <div class="media-left">
                    @if(!empty($item->cover_pic))
                        <a target="_blank" href="{{ url('/item/'.$item->id) }}">
                            <img class="media-object grow" src="{{ url(env('DOMAIN_CDN').'/'.$item->cover_pic) }}">
                        </a>
                    @else
                        <a target="_blank" href="{{ url('/item/'.$item->id) }}">
                            <img class="media-object grow" src="{{ $item->img_tags[2][0] or '' }}">
                        </a>
                    @endif
                </div>
                <div class="media-body">

                    @if($item->time_type == 1)
                        <div class="item-row item-content-row">
                            @if(!empty($item->start_time))
                                <span class="text-blue">{{ time_show($item->start_time) }}</span>
                            @endif
                            @if(!empty($item->end_time))
                                <span class="font-xs">至</span>
                                <span class="text-blue">{{ time_show($item->end_time) }}</span>
                                <span class="font-xs">(截止)</span>
                            @endif
                        </div>
                    @endif

                    <div class="item-row">
                        @if(!empty($item->description))
                            <article class="colo-md-12 multi-ellipsis multi-ellipsis-row-3">{{{ $item->description or '' }}}</article>
                        @else
                            <article class="colo-md-12 multi-ellipsis multi-ellipsis-row-3">{!! $item->content_show or '' !!}</article>
                        @endif
                    </div>
                </div>
            </div>
            {{--<article class="colo-md-12"> {!! $item->content or '' !!} </article>--}}
        </div>
        @endif

        @if($item->category == 99)

            @if(!empty($item->content))
            <div class="item-row">
                {!! $item->content_show or '' !!}
            </div>
            @endif

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

        @endif

        {{--tools--}}
        <div class="item-row item-tools-row">

            <div class="pull-left">
                <a class="font-sm" role="button">
                    {{ time_show($item->created_at->timestamp) }}
                    {{--{{ time_show($item->created_at->getTimestamp()) }}--}}
                </a>
            </div>

            <div class="pull-right">

                @if($item->category != 99)
                <a class="margin forward-show" href="" data-toggle="modal" data-target="#modal-forward" role="button">
                    分享({{ $item->share_num or 0 }})
                </a>
                @endif

                @if($item->category != 99)
                <a class="margin" href="{{ url('/item/'.$item->id) }}" role="button" data-num="{{ $item->visit_num or 0 }}">
                    阅读({{ $item->visit_num or 0 }})
                </a>
                @endif

                <a class="margin comment-toggle" role="button" data-num="{{ $item->comment_num or 0 }}">
                    评论({{ $item->comment_num or 0 }})
                </a>

                {{--点赞--}}
                <a class="margin operate-btn" role="button" data-num="{{ $item->favor_num or 0 }}">
                    @if(Auth::check() && $item->pivot_item_relation->contains('type', 11))
                        <span class="remove-this-favor" title="取消赞"><i class="fa fa-thumbs-up text-red"></i>(<num>{{ $item->favor_num }}</num>)</span>
                    @else
                        <span class="add-this-favor" title="点赞"><i class="fa fa-thumbs-o-up"></i>(<num>{{ $item->favor_num }}</num>)</span>
                    @endif
                </a>

            </div>

        </div>


        {{--comment--}}
        <div class="item-row comment-container _none">

            <input type="hidden" class="comments-get comments-get-default">

            <div class="comment-input-container">
                <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                    {{csrf_field()}}
                    <input type="hidden" name="item_id" value="{{ $item->id or 0}}" readonly>
                    <input type="hidden" name="type" value="1" readonly>

                    <div class="item-row ">
                        <div class="comment-textarea-box">
                            <textarea class="comment-textarea" name="content" rows="2" placeholder="请输入你的评论"></textarea>
                        </div>
                        @if($item->category == 7)
                        <div class="item-row ">
                            <div class="btn-group">
                                <label class="btn" role="button">
                                    <input type="radio" name="support" value="0" checked="checked"> 只评论
                                </label>
                                <label class="btn" role="button">
                                    <input type="radio" name="support" value="1"> 支持正方
                                </label>
                                <label class="btn" role="button">
                                    <input type="radio" name="support" value="2"> 支持反方
                                </label>
                            </div>
                        </div>
                        @endif
                        <div class="comment-button-box">
                            <a href="javascript:void(0);" class="comment-button comment-submit btn-primary" role="button">发 布</a>
                        </div>
                    </div>

                </form>
            </div>

            @if($item->category == 7)
                <div class="item-row">
                    <div class="btn-group">
                        <label class="btn" role="button">
                            <input type="radio" name="get-support" value="0" checked="checked"> 全部评论
                        </label>
                        <label class="btn" role="button">
                            <input type="radio" name="get-support" value="1"> 只看正方
                        </label>
                        <label class="btn" role="button">
                            <input type="radio" name="get-support" value="2"> 只看反方
                        </label>
                    </div>
                </div>
            @endif


            {{--评论列表--}}
            <div class="item-row comment-entity-container">

                <div class="item-row comment-list-container">
                </div>

                <div class="item-row more-box">
                    <a href="{{ url('/item/'.$item->id) }}" target="_blank">
                        <span class="item-more">没有更多了</span>
                    </a>
                </div>

            </div>

        </div>

    </div>
    <!-- END PORTLET-->
</div>
@endforeach
@if(!empty($items_type))
    @if($items_type == 'paginate')
        {{{ $items->links() }}}
    @endif
@endif