<div class="item-piece item-option item">
    <div class="panel-default box-default item-portrait-container _none">
        <a href="{{ url('/org/'.$org->id) }}">
            <img src="{{ url(env('DOMAIN_CDN').'/'.$org->logo) }}" alt="">
        </a>
    </div>
    <div class="panel-default box-default item-entity-container">

        <div class="item-row item-title-row">
            <span class="item-user-portrait _none"><img src="{{ url(env('DOMAIN_CDN').'/'.$org->logo) }}" alt=""></span>
            <span class="item-user-name _none"><a href="{{ url('/org/'.$org->id) }}">{{ $org->name or '' }}</a></span>
            <b class="font-lg">{{ $org->name or '' }}</b>
        </div>

        <div class="item-row item-tools-row font-xs">
            <span>阅读({{ $org->visit_num }})</span>
            <span> • </span>
            <a class="forward-show" data-toggle="modal" data-target="#modal-forward" role="button">分享({{ $org->share_num }})</a>
            <span> • </span>
            {{--点赞--}}
            {{--<a class="operate-btn" role="button" data-num="{{ $item->favor_num or 0 }}">--}}
                {{--@if(Auth::check() && $item->pivot_item_relation->contains('type', 11))--}}
                    {{--<span class="remove-this-favor" title="取消赞"><i class="fa fa-thumbs-up text-red"></i>(<num>{{ $item->favor_num }}</num>)</span>--}}
                {{--@else--}}
                    {{--<span class="add-this-favor" title="点赞"><i class="fa fa-thumbs-o-up"></i>(<num>{{ $item->favor_num }}</num>)</span>--}}
                {{--@endif--}}
            {{--</a>--}}
        </div>

    </div>
</div>




{{--@if(($item->category != 99) && ( (!empty($item->content)) || (!empty($item->description))) )--}}
{{--<div class="item-piece item-option item" data-item="{{ $item->id }}">--}}

    {{--@if($item->category == 99)--}}
    {{--<div class="panel-default box-default item-portrait-container _none">--}}
        {{--<a href="{{ url('/org/'.$org->id) }}">--}}
            {{--<img src="{{ url(env('DOMAIN_CDN').'/'.$org->portrait_img) }}" alt="">--}}
        {{--</a>--}}
    {{--</div>--}}
    {{--@endif--}}

    {{--<div class="item-entity-container">--}}

        {{--content--}}
        {{--<div class="item-row item-content-row">--}}

            {{--description--}}
            {{--@if(!empty($item->description))--}}
                {{--<div class="item-row item-description-row">--}}
                    {{--<div class="colo-md-12 text-muted"> {{ $item->description or '' }} </div>--}}
                {{--</div>--}}
            {{--@endif--}}
            {{----}}
        {{--</div>--}}

    {{--</div>--}}

{{--</div>--}}
{{--@endif--}}


