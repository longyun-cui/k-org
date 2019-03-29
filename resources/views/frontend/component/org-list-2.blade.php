@foreach($items as $n => $v)
<div class="item-col col-lg-3 col-md-3 col-sm-6 col-xs-6 item-option" style=""
     data-item="{{ $v->id }}"
     data-calendar-days="{{ $v->calendar_days or '' }}"
>
    <div class="item-container bg-white">

        <figure class="image-container padding-top-2-3">
            <div class="image-box">
                <a class="clearfix zoom-" target="_blank"  href="{{ url('/org/'.$v->id) }}">
                    <img class="grow" src="{{ url(env('DOMAIN_CDN').'/'.$v->logo) }}">
                </a>
                <span class="btn btn-warning">热销中</span>
            </div>
        </figure>

        <figure class="text-container clearfix">
            <div class="text-box">
                <div class="text-title-row multi-ellipsis-1">
                    <a href="{{ url('/org/'.$v->id) }}" style="color:#ff7676;font-size:13px;">
                        <img src="{{ url(env('DOMAIN_CDN').'/'.$v->logo) }}" class="title-portrait" alt="">
                        <c>{{ $v->name or '' }}</c>
                    </a>
                </div>
            </div>
            <div class="text-box with-border-top text-center clearfix _none">
                <a target="_blank" href="{{ url('/item/'.$v->id) }}">
                    <button class="btn btn-default btn-flat btn-3d btn-clicker" data-hover="点击查看" style="border-radius:0;">
                        <strong>查看详情</strong>
                    </button>
                </a>
            </div>
        </figure>

    </div>
</div>
@endforeach