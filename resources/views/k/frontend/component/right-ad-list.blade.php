@foreach($ad_list as $item)
<div class="box-body bg-white margin-bottom-4px">
    <div class="item-container bg-white">

        @if(!empty($item->cover_pic))
        <figure class="image-container padding-top-1-2">
            <div class="image-box">
                <a class="clearfix zoom-" target="_self"  href="{{ url('/item/'.$item->id) }}">
                    <img class="grow" src="{{ env('DOMAIN_CDN').'/'.$item->cover_pic }}" alt="Property Image">
                    {{--@if(!empty($item->cover_pic))--}}
                    {{--<img class="grow" src="{{ url(env('DOMAIN_CDN').'/'.$item->cover_pic) }}">--}}
                    {{--@else--}}
                    {{--<img class="grow" src="{{ url('/common/images/notexist.png') }}">--}}
                    {{--@endif--}}
                </a>
                {{--<span class="btn btn-warning">热销中</span>--}}
            </div>
        </figure>
        @endif

        <figure class="text-container clearfix">
            <div class="text-box">
                <div class="text-title-row multi-ellipsis-1">
                    <a href="{{ url('/item/'.$item->id) }}"><c>{{ $item->title or '' }}</c></a> &nbsp;
                </div>
                <div class="text-title-row multi-ellipsis-1">
                    <span class="info-tags text-danger">{{ $ad_tag or '该组织•贴片广告' }}</span>
                </div>

                @if($item->time_type == 1)
                    <div class="text-description-row">
                        {{--<div>--}}
                        {{--<i class="fa fa-cny"></i> <span class="font-18px color-red"><b>{{ $item->custom->price or '' }}</b></span>--}}
                        {{--</div>--}}
                    </div>
                @endif

                <div class="text-title-row multi-ellipsis-1 with-border-top _none" style="display:none;">
                    <a href="{{ url('/org/'.$item->id) }}" style="color:#ff7676;font-size:13px;">
                        <img src="{{ url(env('DOMAIN_CDN').'/'.$item->cover_pic) }}" class="title-portrait" alt="">
                        <c>{{ $item->title or '' }}</c>
                    </a>
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

    </div>
</div>
@endforeach