@foreach($sponsor_list as $v)
<div class="box-body bg-white margin-bottom-4px">

    <div class="item-container">
        <div class="panel-default box-default item-portrait-container _none">
            <a target="_self" href="{{ url('/user/'.$v->id) }}">
                <img src="{{ url(env('DOMAIN_CDN').'/'.$v->portrait_img) }}" alt="">
            </a>
        </div>

        <div class="panel-default box-default item-entity-container with-portrait-">

            <div class="item-row item-info-row text-muted">
                            <span class="item-user-portrait pull-left _pointer _none-">
                                <img src="{{ url(env('DOMAIN_CDN').'/'.$v->portrait_img) }}" alt="">
                            </span>
                <span class="item-user-name pull-left">
                                <a target="_self" href="{{ url('/user/'.$v->id) }}" class="text-hover-red font-sm">{{ $v->username or '' }}</a>
                            </span>
            </div>

            @if(!empty($v->linkman) or !empty($v->contact_phone) or !empty($v->contact_address))
            <div class="item-row item-info-row">
                @if(!empty($v->linkman))
                <div class="margin-8px">
                    <i class="fa fa-user text-orange"></i>
                    &nbsp;
                    <span class="text-muted">{{ $v->linkman or '暂无' }}</span>
                </div>
                @endif
                @if(!empty($v->contact_phone))
                <div class="margin-8px">
                    <i class="fa fa-phone text-success"></i>
                    &nbsp;
                    <span class="text-muted">{{ $v->contact_phone or '暂无' }}</span>
                </div>
                @endif
                @if(!empty($v->contact_address))
                <div class="margin-8px">
                    <i class="fa fa-map-marker text-primary"></i>
                    &nbsp;
                    <span class="text-muted">{{ $v->contact_address or '暂无' }}</span>
                </div>
                @endif
            </div>
            @endif

            <div class="item-row item-info-row text-muted">
                <span class="info-tags text-primary">该组织·赞助商</span>
            </div>

        </div>
    </div>


    <div class="item-row item-content-row _none">
        <div class="media">
            <div class="media-left">
                <a target="_self" href="{{ url('/item/'.$v->id) }}">
                    <img class="media-object grow" src="{{ url(env('DOMAIN_CDN').'/'.$v->portrait_img) }}">
                </a>
            </div>
            <div class="media-body">

                <div class="item-row">
                    @if(!empty($v->description))
                        <article class="colo-md-12 multi-ellipsis multi-ellipsis-row-3">{{{ $v->description or '' }}}</article>
                    @else
                        <article class="colo-md-12 multi-ellipsis multi-ellipsis-row-3">{!! $v->content_show or '' !!}</article>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endforeach
