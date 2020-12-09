<div class="box-body bg-white margin-bottom-4px section-user">

    <div class="item-container">
        <div class="panel-default box-default item-portrait-container">
            <a target="_blank" href="{{ url('/user/'.$data->id) }}">
                <img src="{{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}" alt="">
            </a>
        </div>

        <div class="panel-default- box-default item-entity-container with-portrait">
            <div class="item-row item-title-row text-muted">
                <span class="item-user-portrait _none">
                    <img src="{{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}" alt="">
                </span>
                <span class="item-user-name">
                    <b><a href="{{ url('/user/'.$data->id) }}" class="text-hover-red font-sm">{{ $data->username or '' }}</a></b>
                </span>
            </div>
            <div class="item-row item-info-row text-muted">
                <span class="">{{ $data->visit_num or 0 }}次访问</span>
                {{--<span class="info-tags text-danger">作者</span>--}}
            </div>
        </div>

        <div class="item-row item-info-row">
            <div class="margin-4px">
                <i class="fa fa-user text-orange"></i>
                <span class="text-muted">{{ $data->linkman or '暂无' }}</span>
            </div>
            <div class="margin-4px">
                <i class="fa fa-phone text-success"></i>
                <span class="text-muted">{{ $data->contact_phone or '暂无' }}</span>
            </div>
            <div class="margin-4px">
                <i class="fa fa-map-marker text-primary"></i>
                <span class="text-muted">{{ $data->contact_address or '暂无' }}</span>
            </div>
        </div>

        <div class="item-row">
            <div class="tool-inn tool-info follow-add follow-add-it" style="width:100%;text-align:center;" data-user-id="{{ $data->id }}">
                <i class="fa fa-plus"></i>
                <span class="">关注</span>
            </div>
        </div>

    </div>

</div>


<div class="box-body bg-white margin-bottom-8px _none">

    <div class="margin">
        <i class="fa fa-user text-orange"></i>
        <b>{{ $data->username or '' }}</b>
    </div>

</div>