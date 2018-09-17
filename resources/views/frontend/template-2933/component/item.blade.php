<div class="item-piece item-option item" data-item="{{ $item->id }}">
    <div class="panel-default box-default item-entity-container">

        <div class="item-row item-title-row">
            <b>{{ $item->title or '' }}</b>
        </div>

        <div class="item-row item-info-row text-muted">
            {{--<span> • {{ $item->created_at->format('n月j日 H:i') }}</span>--}}
            <span>{{ time_show($item->created_at) }}</span>
            <span> • 阅读({{ $item->visit_num }})</span>
            <span> • </span>
            {{--点赞--}}
            <a class="operate-btn" role="button" data-num="{{ $item->favor_num or 0 }}">
                @if(Auth::check() && $item->pivot_item_relation->contains('type', 9))
                    <span class="remove-this-favor" title="取消赞"><i class="fa fa-thumbs-up text-red"></i>(<num>{{ $item->favor_num }}</num>)</span>
                @else
                    <span class="add-this-favor" title="点赞"><i class="fa fa-thumbs-o-up"></i>(<num>{{ $item->favor_num }}</num>)</span>
                @endif
        </div>

    </div>
</div>


<div class="item-piece item-option item" data-item="{{ $item->id }}">
    <div class="item-entity-container">

        {{--description--}}
        {{--@if(!empty($item->description))--}}
        {{--<div class="box-body item-description-row">--}}
        {{--<div class="colo-md-12 text-muted"> {!! $item->description or '' !!} </div>--}}
        {{--</div>--}}
        {{--@endif--}}

        {{--content--}}
        <div class="item-row item-content-row">
            <article class="colo-md-12"> {!! $item->content or '' !!} </article>
        </div>

    </div>
</div>


<div class="item-piece item-option item" data-item="{{ $item->id }}">
    <div class="item-entity-container">

        {{--comment--}}
        <div class="item-row comment-container">

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
                        <div class="comment-button-box">
                            <a href="javascript:void(0);" class="comment-button comment-submit btn-primary" role="button">发 布</a>
                        </div>
                    </div>

                </form>
            </div>


            {{--评论列表--}}
            <div class="comment-entity-container">

                <div class="comment-list-container">
                </div>

                <div class="more-box">
                    <a href="javascript:void(0);">
                        <span class="item-more">没有更多了</span>
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>