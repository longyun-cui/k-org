<div class="item-piece item-option topic-option {{ $getType or 'item' }}"
     data-id="{{ $item->id or 0}}"
     data-getType="{{ $getType or 'item' }}"
>
    <!-- BEGIN PORTLET-->
    <div class="boxe panel-default- box-default item-entity-container">

        <div class="box-body item-row item-title-row">
            <span>
                <a href="{{ url('/item/'.$item->id) }}" >{{ $item->title or '' }}</a>
            </span>
        </div>

        <div class="box-body item-row item-info-row">
            @if($item->item_type == 88)
                <span class="info-tags text-danger">贴片广告</span>
            @endif
            @if($item->time_type == 11)
                <span class="info-tags text-default">活动</span>
            @endif
            <span><a href="{{ url('/user/'.$item->owner->id) }}">{{ $item->owner->username or '' }}</a></span>
            <span class="pull-right"><a class="show-menu" role="button"></a></span>
            <span class=" text-muted disabled"> • {{ time_show($item->updated_at->timestamp) }}</span>
{{--            <span class=" text-muted disabled"> • {{ $item->updated_at->format('Y-m-d H:i') }}</span>--}}
            <span class=" text-muted disabled"> • 浏览 <span class="text-blue">{{ $item->visit_num }}</span> 次</span>
        </div>

        @if($item->time_type == 1)
            <div class="box-body item-row item-time-row text-muted">
                <div class="colo-md-12">
                    @if(!empty($item->start_time))
                    <span class="label label-success start-time-inn"><b>{{ time_show($item->start_time) }}</b> (开始)</span>
                    @endif
                    @if(!empty($item->end_time))
                    <span style="font-size:12px;">&nbsp;&nbsp;至&nbsp;&nbsp;</span>
                    <span class="label label-danger end-time-inn"><b>{{ time_show($item->end_time) }} (结束)</b></span>
                    @endif
                </div>
            </div>
        @endif

        @if(!empty($item->description))
            <div class="box-body item-row item-description-row text-muted">
                <div class="colo-md-12"> {{ $item->description or '' }} </div>
            </div>
        @endif

        @if(!empty($item->content))
            <div class="box-body item-row item-content-row">
                <article class="colo-md-12"> {!! $item->content or '' !!} </article>
            </div>
        @endif


        {{--tools--}}
        <div class="box-body item-row item-tools-row item-tools-container">

            {{--点赞--}}
            <a class="tool-button favor-btn" data-num="{{$item->favor_num}}" role="button">
                @if(Auth::check())
                    @if($item->others->contains('type', 1))
                        <span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i>
                    @else
                        <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                    @endif
                @else
                    <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                @endif

                @if($item->favor_num) {{$item->favor_num}} @endif </span>
            </a>

            {{--收藏--}}
            <a class="tool-button collect-btn" data-num="{{$item->collect_num}}" role="button">
                @if(Auth::check())
                    @if($item->user_id != Auth::id())
                        @if(count($item->collections))
                            <span class="collect-this-cancel"><i class="fa fa-heart text-red"></i>
                        @else
                            <span class="collect-this"><i class="fa fa-heart-o"></i>
                        @endif
                    @else
                        <span class="collect-mine"><i class="fa fa-heart-o"></i>
                    @endif
                @else
                    <span class="collect-this"><i class="fa fa-heart-o"></i>
                @endif

                @if($item->collect_num) {{$item->collect_num}} @endif </span>
            </a>

            {{--分享--}}
            <a class="tool-button _none" role="button"><i class="fa fa-share"></i> @if($item->share_num) {{$item->share_num}} @endif</a>

            {{--评论--}}
            <a class="tool-button" role="button">
                <i class="fa fa-commenting-o"></i> @if($item->comment_num) {{$item->comment_num}} @endif
            </a>

        </div>


        {{--添加评论--}}
        <div class="box-body item-row comment-container">

            <div class="box-body comment-input-container">
            <form action="" method="post" class="form-horizontal form-bordered topic-comment-form">

                {{csrf_field()}}
                <input type="hidden" name="topic_id" value="{{encode($item->id)}}" readonly>
                <input type="hidden" name="type" value="1" readonly>

                <div class="form-group">
                    <div class="col-md-12">
                        <div><textarea class="form-control" name="content" rows="3" placeholder="请输入你的评论"></textarea></div>
                    </div>
                </div>

                @if($item->type == 2)
                <div class="form-group form-type">
                    <div class="col-md-12">
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
                </div>
                @endif

                <div class="form-group form-type">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="is_anonymous"> 匿名
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top:16px;">
                    <div class="col-md-12 ">
                        <button type="button" class="btn btn-block btn-flat btn-primary comment-submit">提交</button>
                    </div>
                </div>

            </form>
            </div>

            @if($item->type == 2)
            <div class="box-body comment-choice-container">
                <div class="form-group form-type">
                    <div class="btn-group">
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($item->id)}}" checked="checked"
                                           class="comments-get comments-get-default" data-getSort="all"> 全部评论
                                </label>
                            </div>
                        </button>
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($item->id)}}" class="comments-get" data-getSort="positive">
                                    <b class="text-primary">只看【正方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                </label>
                            </div>
                        </button>
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($item->id)}}" class="comments-get" data-getSort="negative">
                                    <b class="text-danger">只看【反方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                </label>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            @else
                <input type="hidden" class="comments-get comments-get-default" data-type="all">
            @endif

            {{--评论列表--}}
            <div class="box-body comment-entity-container">

                {{--@include('frontend.component.commentEntity.item')--}}
                <div class="comment-list-container">
                    {{--@foreach($communications as $comment)--}}
                    {{--@include('frontend.component.comment')--}}
                    {{--@endforeach--}}
                </div>

                <div class="col-md-12" style="margin-top:16px;padding:0;">
                    <button type="button" class="btn btn-block btn-flat btn-more comments-more">更多</button>
                </div>

                {{--@include('frontend.component.commentEntity.topic')--}}

            </div>

        </div>

    </div>
    <!-- END PORTLET-->
</div>

