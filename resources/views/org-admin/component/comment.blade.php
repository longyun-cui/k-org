@foreach($datas as $data)
<div class="row notification-option notification-piece"
     data-id="{{ encode($data->id) or '' }}"
     data-course="{{ encode($data->id) or '' }}"
     data-content="{{ encode(0) }}"
>

    <div class="col-md-9">
        <!-- BEGIN PORTLET-->
        <div class="box panel-default box-default">

            {{--header--}}
            <div class="box-header" style="margin:8px 0 0;border-bottom:1px solid #f4f4f4;">

                <a target="_blank" href="{{url('/u/'.encode($data->source_id))}}">{{$data->source->name or ''}}</a>
                @if($data->sort == 1)
                    : {{$data->comment->content or ''}}
                @elseif($data->sort == 2)
                    : {{$data->comment->content or ''}}
                @elseif($data->sort == 3) <i class="fa fa-thumbs-up text-red"></i>赞了我的
                @elseif($data->sort == 5) <i class="fa fa-thumbs-up text-red"></i>赞了我的的评论
                @endif

                <span class="pull-right">{{ $data->created_at->format('Y-n-j H:i') }}</span>

            </div>


            {{----}}
            <div class="box-body text-muted margin" style="background-color: #f4f4f4;">

                <div class="box-body">
                    @if($data->content_id)
                        <a target="_blank" href="{{url('/course/'.encode($data->course_id).'?content='.encode($data->content_id))}}">
                            {{$data->chapter->title or ''}}</a>
                    @else
                        <a target="_blank" href="{{url('/course/'.encode($data->course_id))}}">{{$data->course->title or ''}}</a>
                    @endif
                </div>

                @if($data->sort == 2 || $data->sort == 5)
                <div class="box-footer">
                        <a target="_blank" href="{{url('/u/'.encode($data->reply->user_id))}}">{{$data->reply->user->name or ''}}</a>
                        @if($data->reply->reply_id)
                            回复 <a target="_blank" href="{{url('/u/'.encode($data->reply->reply->user_id))}}">{{$data->reply->reply->user->name or ''}}</a>
                        @endif
                        : {{$data->reply->content or ''}}
                </div>
                @endif

            </div>

            {{--tools--}}
            <div class="box-footer">
                @if($data->sort == 1 || $data->sort == 2)
                @endif
            </div>


        </div>
        <!-- END PORTLET-->
    </div>
</div>
@endforeach

{{ $datas->links() }}