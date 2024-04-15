@foreach($item_list as $num => $item)
<div class="item-piece item-option item-wrapper border-color-1 radius-4px {{ $getType or 'items' }}"
     data-item="{{ $item->id or 0}}"
     data-id="{{ $item->id or 0}}"
     data-item-id="{{ $item->id or 0}}"
     data-getType="{{ $getType or 'items' }}"
>

    <div class="item-container model-left-right image-right bg-white">


        {{--头部--}}
        <figure class="item-header-block">
            <div class="text-box">

                <div class="item-title-row">
                    <a class="clearfix zoom" target="_self" href="{{ url('/item/'.$item->id) }}">
                        <span class="multi-ellipsis-2"><b>{{ $item->title or '' }}</b></span>
                    </a>
                </div>

            </div>
        </figure>


        {{--主体--}}
        <figure class="item-body-block item-entity-container-">

            {{--cover 封面图片--}}
            @if(!empty($item->cover_picture))
                {{--@if(@getimagesize(env('DOMAIN_CDN').'/'.$item->cover_picture))--}}
                <a class="clearfix- zoom" target="_self" href="{{ url('/item/'.$item->id) }}">
                <figure class="image-container pull-right">
                    <div class="image-box">
                        <img data-action="zoom" src="{{ $item->cover_picture or '' }}" alt="Property Image">
                        {{--<img data-action="zoom" src="{{ env('DOMAIN_CDN').'/'.$item->cover_pic }}" alt="Property Image">--}}
                        {{--<span class="btn btn-warning">热销中</span>--}}
                    </div>
                </figure>
                </a>
            @endif

            {{--文本--}}
            <figure class="text-container pull-left @if(!empty($item->cover_picture)) with-image @else without-image @endif">
                <div class="text-box with-border-top">

                    {{--基本信息--}}
                    <div class="item-info-row">

                        <a href="{{ url('/user/'.$item->owner->id) }}">
                            {{ $item->owner->username or '' }}
                        </a>

                        {{--是否发布--}}
                        @if($item->is_published == 0)
                            <lable class="info-tags bg-yellow">
                                <i class="icon ion-paper-airplane"></i> 待发布
                            </lable>
                        @elseif($item->item_active == 1)
                        @endif

                        {{--广告--}}
                        @if($item->is_published == 88)
                            <span class="info-tags text-default pull-left-">广告</span>
                        @endif

                        {{----}}
                        <a href="{{ url('/user/'.$item->owner->id) }}" style="color:#ff7676;font-size:13px;">
                        <span class="item-user-portrait _none-">
                            <img src="{{ url(env('DOMAIN_CDN').'/'.$item->owner->portrait_img) }}" alt="">
                            {{--<img src="/common/images/bg/background-image.png" data-src="{{ url(env('DOMAIN_CDN').'/'.$item->owner->portrait_img) }}" alt="">--}}
                        </span>
                        </a>
{{--                        <span class=""> • {{ date_show($item->updated_at->timestamp) }}</span>--}}

                        {{--待发布--}}
                        @if($item->is_published == 0)
                        <span class=""> • </span>
                        <a href="{{ url('/mine/item/item-edit?item-id='.$item->id) }}">编辑</a>
                        <span class=""> • </span>
                        <a href="javascript:void(0)" class="item-publish-this">发布</a>
                        @endif

                    </div>


                    @if($item->time_type == 1)
                    <div class="row-sm margin-top-4px">
                        <div class="text-row text-time-row text-description-row multi-ellipsis-1">
                            @if(!empty($item->start_time))
                                <span class="label label-success start-time-inn"><b>{{ time_show($item->start_time) }}</b></span>
                            @endif
                            @if(!empty($item->end_time))
                                <span class="font-12px"> 至 </span>
                                <span class="label label-danger end-time-inn">
                                    <b>{{ time_show($item->end_time) }}</b>
                                    @if(empty($item->start_time))(截止)@endif
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!empty($item->address))
                        <div class="text-row text-info-row multi-ellipsis-row-2 margin-top-4px margin-bottom-4px">
                            <i class="icon ion-location text-blue" style="width:16px;text-align:center;"></i>
                            <span class="">{{ $item->address or '' }}</span>
                        </div>
                    @endif

                    <div class="row-sm margin-top-4px">
                        <div class="text-description-row multi-ellipsis-3">
                            @if(!empty($item->description))
                                {{--{{ $item->description or '' }}--}}
                                {!! $item->description or '' !!}
                                <br>
                            @endif
                            {{ $item->content_show or '' }}
                        </div>
                    </div>

                    <div class="text-title-row multi-ellipsis-1 _none">
                        <span class="info-tags text-danger">该组织•贴片广告</span>
                    </div>

                </div>


                <div class="text-box with-border-top text-center clearfix _none">
                    <a target="_self" href="{{ url('/item/'.$item->id) }}">
                        <button class="btn btn-default btn-flat btn-3d btn-clicker" data-hover="点击查看" style="border-radius:0;">
                            <strong>查看详情</strong>
                        </button>
                    </a>
                </div>




            </figure>
        </figure>


        {{--尾部--}}
        <figure class="item-footer-block">

            <div class="item-info-row">

                {{--浏览--}}
                <a class="tool-button" href="{{ url('/item/'.$item->id) }}" role="button">
                    <span>
                        阅读 @if($item->visit_num){{ $item->visit_num }} @endif
                    </span>
                </a>
                {{--评论--}}
                <a class="tool-button comment-toggle" href="{{ url('/item/'.$item->id) }}" role="button">
                    <span>
                        评论 @if($item->comment_num) {{ $item->comment_num }} @endif
                    </span>
                </a>


                {{--点赞--}}
                <small class="tool-button operate-btn favor-btn" data-num="{{ $item->favor_num or 0 }}" role="button">
                    @if(!empty($me))
                        @if($item->pivot_item_relation->contains('relation_type', 1))
                            <a class="remove-this-favor">
                                <i class="fa fa-heart text-red"></i>
                                <span class="num">@if($item->favor_num){{ $item->favor_num }}@endif</span>
                            </a>
                        @else
                            <a class="add-this-favor">
                                <i class="fa fa-heart-o"></i>
                                <span class="num">@if($item->favor_num){{ $item->favor_num }}@endif</span>
                            </a>
                        @endif
                    @else
                        <a class="add-this-favor">
                            <i class="fa fa-heart-o"></i>
                            <span class="num">@if($item->favor_num){{ $item->favor_num }}@endif</span>
                        </a>
                    @endif
                </small>


                {{--收藏--}}
                <small class="tool-button operate-btn collection-btn" data-num="{{ $item->collection_num or 0 }}" role="button">
                    @if(!empty($me))
                        @if($item->pivot_item_relation->contains('relation_type', 21))
                            <a class="remove-this-collection">
                                <i class="fa fa-star text-red"></i>
                                <span class="num">@if($item->collection_num){{ $item->collection_num }}@endif</span>
                            </a>
                        @else
                            <a class="add-this-collection">
                                <i class="fa fa-star-o"></i>
                                <span class="num">@if($item->collection_num){{ $item->collection_num }}@endif</span>
                            </a>
                        @endif
                    @else
                        <a class="add-this-collection">
                            <i class="fa fa-star-o"></i>
                            <span class="num">@if($item->collection_num){{ $item->collection_num }}@endif</span>
                        </a>
                    @endif
                </small>

                {{--分享--}}
                <a class="tool-button _none" role="button">
                    <i class="fa fa-share"></i> @if($item->share_num) {{ $item->share_num }} @endif
                </a>


            </div>

        </figure>

    </div>



</div>
@endforeach

