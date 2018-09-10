@foreach($items as $num => $item)
<div class="item-piece item-option" data-item="{{ $item->id }}">
    <!-- BEGIN PORTLET-->
    <div class="panel-default box-default item-entity-container">

        {{--header--}}
        <div class="box-body item-info-row text-muted">
            <span><a href="{{ url('/u/'.encode($item->user->id)) }}">{{ $item->user->name or '' }}</a></span>
            {{--<span> • </span>--}}
            {{--<span>{{ $item->created_at->format('n月j日 H:i') }}</span>--}}
            {{--<span> • </span>--}}
            {{--<span>{{ time_show($item->created_at) }}</span>--}}
            <span> • </span>
            <span class="item-plus-box" role="button">
                <i class="fa fa-plus-square-o item-plus-button"></i>
                <ul class="item-plus-list">
                    <li class="add-this-collection"><i class="fa fa-star-o"></i> 收藏</li>
                    <li class="add-this-todolist"><i class="fa fa-check-square-o"></i> 添加到待办事</li>
                    @if($item->time_type == 1)
                        <li class="add-this-schedule"><i class="fa fa-calendar-plus-o"></i> 添加为日程</li>
                    @endif
                </ul>
            </span>
        </div>

        <div class="box-body item-title-row">
            <a href="{{ url('/item/'.$item->id) }}"><b>{{ $item->title or '' }}</b></a>
        </div>

        {{--description--}}
        {{--@if(!empty($item->description))--}}
            {{--<div class="box-body item-description-row">--}}
                {{--<div class="colo-md-12 text-muted"> {!! $item->description or '' !!} </div>--}}
            {{--</div>--}}
        {{--@endif--}}

        {{--content--}}
            <div class="box-body item-content-row">
                <div class="media">
                    <div class="media-left">
                        @if(!empty($item->cover_pic))
                            <a href="{{ url('/item/'.$item->id) }}">
                                <img class="media-object" src="{{ url(env('DOMAIN_CDN').'/'.$item->cover_pic )}}">
                            </a>
                        @else
                            <a href="{{ url('/item/'.$item->id) }}">
                                <img class="media-object" src="{{ $item->img_tags[2][0] or '' }}">
                            </a>
                        @endif
                    </div>
                    <div class="media-body">
                        <div class="clearfix">
                            @if(!empty($item->description))
                                <article class="colo-md-12">{{{ $item->description or '' }}}</article>
                            @else
                                <article class="colo-md-12">{!! $item->content_show or '' !!}</article>
                            @endif

                        </div>
                    </div>
                </div>
                {{--<article class="colo-md-12"> {!! $item->content or '' !!} </article>--}}
            </div>

        {{--tools--}}
        <div class="box-footer item-tools-row">


            <div class="pull-left">

                <a class="margin" role="button">
                    {{ time_show($item->created_at->timestamp) }}
                    {{--{{ time_show($item->created_at->getTimestamp()) }}--}}
                </a>

            </div>

            <div class="pull-right">

                <a class="margin _none" role="button">
                    ({{ $item->share_num or 0 }})
                </a>

                <a class="margin" href="{{ url('/item/'.$item->id) }}" role="button" data-num="{{ $item->visit_num or 0 }}">
                    阅读({{ $item->visit_num or 0 }})
                </a>

                <a class="margin comment-toggle" role="button" data-num="{{ $item->comment_num or 0 }}">
                    评论({{ $item->comment_num or 0 }})
                </a>

                {{--点赞--}}
                <a class="margin operate-btn" role="button" data-num="{{ $item->favor_num or 0 }}">
                    @if(Auth::check() && $item->pivot_item_relation->contains('type', 9))
                        <span class="remove-this-favor" title="取消赞"><i class="fa fa-thumbs-up text-red"></i>(<num>{{ $item->favor_num }}</num>)</span>
                    @else
                        <span class="add-this-favor" title="点赞"><i class="fa fa-thumbs-o-up"></i>(<num>{{ $item->favor_num }}</num>)</span>
                    @endif
                </a>


            </div>

        </div>


        {{--comment--}}
        <div class="box-body comment-container _none">

            <input type="hidden" class="comments-get comments-get-default">

            <div class="box-body comment-input-container">
                <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                    {{csrf_field()}}
                    <input type="hidden" name="course_id" value="{{encode($item->id)}}" readonly>
                    <input type="hidden" name="type" value="1" readonly>

                    <div class="form-group">
                        <div class="col-md-9">
                            <div><textarea class="form-control" name="content" rows="1" placeholder="请输入你的评论"></textarea></div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-block btn-primary comment-submit">提交</button>
                        </div>
                    </div>

                    <div class="form-group">
                    </div>

                </form>
            </div>


            {{--评论列表--}}
            <div class="box-body comment-entity-container">

                <div class="comment-list-container">
                </div>

                <div class="col-md-12 more-box">
                    <a href="{{url('/course/'.encode($item->id))}}" target="_blank">
                        <button type="button" class="btn btn-block btn-flat btn-default item-more"></button>
                    </a>
                </div>

            </div>

        </div>

    </div>
    <!-- END PORTLET-->
</div>
@endforeach