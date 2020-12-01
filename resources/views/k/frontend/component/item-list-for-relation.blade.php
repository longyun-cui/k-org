@foreach($item_list as $i)
<div class="item-piece item-option {{ $getType or 'items' }}"
     data-id="{{ $i->item->id or 0 }}"
     data-item-id="{{ $i->item->id or 0 }}"
     data-getType="{{ $getType or 'items' }}"
>
    <!-- BEGIN PORTLET-->
    <div class="boxe panel-default- box-default item-entity-container">

        <div class="box-body item-row item-title-row">
            <span>
                <a href="{{ url('/item/'.$i->item->id) }}" ><b>{{ $i->item->title or '' }}</b></a>
            </span>
        </div>

        <div class="box-body item-row item-info-row">
            @if($i->item->item_type == 88)
                <span class="info-tags text-danger">广告</span>
            @endif
            @if($i->item->item_type == 11 || $i->item->time_type == 1)
                <span class="info-tags text-default">活动</span>
            @endif
            <span><a href="{{ url('/user/'.$i->item->owner->id) }}">{{ $i->item->owner->username or '' }}</a></span>
            <span class="pull-right"><a class="show-menu" role="button"></a></span>
            <span class=" text-muted disabled"> • {{ time_show($i->item->updated_at->timestamp) }}</span>
            {{--<span class=" text-muted disabled"> • {{ $i->item->updated_at->format('Y-m-d H:i') }}</span>--}}
            <span class=" text-muted disabled"> • 浏览 <span class="text-blue">{{ $i->item->visit_num }}</span> 次</span>
        </div>

        @if($i->item->time_type == 1)
            <div class="box-body item-row item-time-row text-muted">
                <div class="colo-md-12">
                    @if(!empty($i->item->start_time))
                    <span class="label label-success start-time-inn"><b>{{ time_show($i->item->start_time) }}</b> (开始)</span>
                    @endif
                    @if(!empty($i->item->end_time))
                    <span style="font-size:12px;">&nbsp;&nbsp;至&nbsp;&nbsp;</span>
                    <span class="label label-danger end-time-inn"><b>{{ time_show($i->item->end_time) }} (结束)</b></span>
                    @endif
                </div>
            </div>
        @endif

        @if(!empty($i->item->description))
            <div class="box-body item-row item-description-row text-muted">
                <div class="colo-md-12"> {{ $i->item->description or '' }} </div>
            </div>
        @endif

        @if(!empty($i->item->content))
            <div class="box-body item-row item-content-row _none">
                <article class="colo-md-12"> {!! $i->item->content or '' !!} </article>
            </div>
        @endif


        {{--tools--}}
        <div class="box-body item-row item-tools-row item-tools-container">

            {{--点赞&$收藏--}}
            <a class="tool-button operate-btn favor-btn" data-num="{{ $i->item->favor_num or 0 }}" role="button">
                @if(Auth::check())
                    @if($i->item->pivot_item_relation->contains('relation_type', 1))
                        <span class="remove-this-favor"><i class="fa fa-heart text-red"></i></span>
                    @else
                        <span class="add-this-favor"><i class="fa fa-heart-o"></i></span>
                    @endif
                @else
                    <span class="add-this-favor"><i class="fa fa-heart-o"></i></span>
                @endif

                @if($i->item->favor_num)<span class="num">{{ $i->item->favor_num }}</span>@endif
            </a>

            {{--分享--}}
            <a class="tool-button _none" role="button"><i class="fa fa-share"></i> @if($i->item->share_num) {{$i->item->share_num}} @endif</a>

            {{--评论--}}
            <a class="tool-button comment-toggle" role="button">
                <i class="fa fa-commenting-o"></i> @if($i->item->comment_num) {{$i->item->comment_num}} @endif
            </a>

        </div>


        {{--添加评论--}}
        <div class="box-body item-row comment-container"  style="display:none;">

            <div class="box-body comment-input-container">
            <form action="" method="post" class="form-horizontal form-bordered topic-comment-form">

                {{csrf_field()}}
                <input type="hidden" name="topic_id" value="{{encode($i->item->id)}}" readonly>
                <input type="hidden" name="type" value="1" readonly>

                <div class="form-group">
                    <div class="col-md-12">
                        <div><textarea class="form-control" name="content" rows="3" placeholder="请输入你的评论"></textarea></div>
                    </div>
                </div>

                @if($i->item->type == 2)
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

            @if($i->item->type == 2)
            <div class="box-body comment-choice-container">
                <div class="form-group form-type">
                    <div class="btn-group">
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($i->item->id)}}" checked="checked"
                                           class="comments-get comments-get-default" data-getSort="all"> 全部评论
                                </label>
                            </div>
                        </button>
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($i->item->id)}}" class="comments-get" data-getSort="positive">
                                    <b class="text-primary">只看【正方 <i class="fa fa-thumbs-o-up"></i>】</b>
                                </label>
                            </div>
                        </button>
                        <button type="button" class="btn">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="comments-get-{{encode($i->item->id)}}" class="comments-get" data-getSort="negative">
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

                {{--@include('frontend.component.commentEntity.items')--}}
                <div class="comment-list-container">
                    {{--@if($data->type == 1)--}}
                    {{--@foreach($data->communications as $comment)--}}
                    {{--@include('frontend.component.comment')--}}
                    {{--@endforeach--}}
                    {{--@endif--}}
                </div>

                <div class="col-md-12" style="margin-top:16px;padding:0;">
                    <a href="{{ url('/item/'.$i->item->id) }}" target="_blank">
                        <button type="button" class="btn btn-block btn-flat btn-more" data-getType="all">更多</button>
                    </a>
                </div>

                {{--@include('frontend.component.commentEntity.topic')--}}

            </div>

        </div>

    </div>
    <!-- END PORTLET-->
</div>
@endforeach

