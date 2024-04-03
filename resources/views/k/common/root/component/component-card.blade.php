<div class="right-piece- box-body bg-white margin-bottom-4px- section-user radius-2px">


    <div class="box box-widget widget-user" style="margin-bottom:0px;box-shadow:0 0;">

        {{--背景--}}
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header bg-aqua-active">
            {{--<h3 class="widget-user-username text-center">{{ $data->username or '' }}</h3>--}}
            {{--<h5 class="widget-user-desc">{{ $data->company or '暂无' }}</h5>--}}
            {{--<h5 class="widget-user-desc">{{ $data->position or '暂无' }}</h5>--}}
        </div>

        {{--头像--}}
        <div class="widget-user-image" style="border-radius:50%;">
            <img src="{{ url(env('DOMAIN_CDN').'/'.$data->portrait_img) }}" class="img-circle" alt="User Image" style="border-radius:50%;">
        </div>


        {{--主要信息--}}
        <div class="margin-top-8px margin-bottom-12px">

            {{--姓名--}}
            <h3 class="profile-username text-center">
                @if(!empty($data->true_name))
                    {{ $data->true_name or '' }}
                @else
                    {{ $data->username or '' }}
                @endif
            </h3>

            {{--辅助信息--}}
            {{--公司--}}
            @if(!empty($data->company))
                <p class="text-muted text-center">
                    <b>{{ $data->company or '暂无' }}</b>
                    @if(!empty($data->position))
                    - <b>{{ $data->position or '暂无' }}</b>
                    @endif
                </p>
            @endif
            {{--职位--}}
{{--            @if(!empty($data->position))--}}
{{--                <p class="text-muted text-center"><b>{{ $data->position or '暂无' }}</b></p>--}}
{{--            @endif--}}
            {{--商业说明--}}
            @if(!empty($data->description))
                <p class="text-muted text-center margin-bottom-4px">
                    <small>{{ $data->description or '暂无' }}</small>
                </p>
            @endif

        </div>


        {{--粉丝与访问信息--}}
        <div class="box-footer">
            <div class="row">
                <div class="col-xs-4 border-right">
                    <a href="{{ url('/user/'.$data->id) }}" target="_blank">
                        <div class="description-block">
                            <h5 class="description-header">{{ $data->fans_num or 0 }}</h5>
                            <span class="description-text">内容</span>
                        </div>
                    </a>
                </div>
                <div class="col-xs-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header">{{ $data->fans_num or 0 }}</h5>
                        <span class="description-text">粉丝</span>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="description-block">
                        <h5 class="description-header">{{ $data->visit_num or 0 }}</h5>
                        <span class="description-text">访问</span>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{--<div class="box box-primary">--}}
        <div class="box-body box-profile">

            {{--info--}}
            <ul class="list-group list-group-unbordered">
                @if(!empty($data->contact_phone))
                    <li class="list-group-item">
                        <i class="fa fa-phone text-primary"></i>
                        <span class="text-muted">
                            <a href="tel:{{ $data->contact_phone or '' }}">{{ $data->contact_phone or '暂无' }}</a>
                        </span>
                    </li>
                @endif
                {{--邮箱--}}
                @if(!empty($data->email))
                    <li class="list-group-item">
                        <i class="fa fa-envelope text-primary"></i>
                        <span class="text-muted">{{ $data->email or '暂无' }}</span>
                    </li>
                @endif
                {{--微信--}}
                @if(!empty($data->wx_id))
                    <li class="list-group-item">
                        @if(!empty($data->wx_qr_code_img))
                            <a class="lightcase-image" href="{{ url(env('DOMAIN_CDN').'/'.$data->wx_qr_code_img) }}">
                                <i class="fa fa-weixin text-primary"></i>
                                <span class="text-muted">{{ $data->wx_id or '暂无' }}</span>
                                <i class="fa fa-qrcode text-danger" style="width:16px;font-weight:500;"></i>
                            </a>
                        @else
                            <i class="fa fa-weixin text-primary"></i>
                            <span class="text-muted">{{ $data->wx_id or '暂无' }}</span>
                        @endif
                    </li>
                @endif
                {{--QQ--}}
                @if(!empty($data->QQ_number))
                    <li class="list-group-item">
                        <i class="fa fa-qq text-primary"></i>
                        <a class="" href="tencent://message/?uin={{ $data->QQ_number }}">
                            {{ $data->QQ_number or '暂无' }}
                        </a>
                    </li>
                @endif
                {{--微博--}}
                @if(!empty($data->wb_name))
                    <li class="list-group-item">
                        @if(!empty($data->wb_address))
                            <a target="_blank" href="{{ $data->wb_address }}">
                                <i class="fa fa-weibo text-primary"></i>
                                <span class="">{{ $data->wb_name or '暂无' }}</span>
                            </a>
                        @else
                            <i class="fa fa-weibo text-primary"></i>
                            <span class="text-muted">{{ $data->wb_name or '暂无' }}</span>
                        @endif
                    </li>
                @endif
                {{--网站--}}
                @if(!empty($data->website))
                    <li class="list-group-item">
                        <i class="fa fa-globe text-primary"></i>
                        @if(!empty($data->website))
                            <a target="_blank" href="{{ $data->website or '' }}">
                                {{ $data->website or '暂无' }}
                            </a>
                        @else
                            <span class="text-muted">{{ $data->website or '暂无' }}</span>
                        @endif
                    </li>
                @endif

                {{--联系人姓名--}}
                @if(!empty($data->linkman_name))
                    <li class="list-group-item">
                        <i class="fa fa-user text-primary"></i>
                        <span class="text-muted">{{ $data->linkman_name or '暂无' }}</span>
                    </li>
                @endif
                {{--联系人电话--}}
                @if(!empty($data->linkman_phone))
                    <li class="list-group-item">
                        <i class="fa fa-phone text-primary"></i>
                        <span class="text-muted">
                    <a href="tel:{{ $data->linkman_phone or '' }}">
                        <strong>{{ $data->linkman_phone or '暂无' }}</strong>
                    </a>
                </span>
                    </li>
                @endif
                {{--联系人微信--}}
                @if(!empty($data->linkman_wx_id))
                    <li class="list-group-item">
                        <i class="fa fa-weixin text-primary"></i>
                        <span class="text-muted">{{ $data->linkman_wx_id or '暂无' }}</span>
                        @if(!empty($data->linkman_wx_qr_code_img))
                            <a class="lightcase-image" href="{{ url(env('DOMAIN_CDN').'/'.$data->linkman_wx_qr_code_img) }}">
                                <i class="fa fa-qrcode text-danger" style="width:16px;font-weight:500;"></i>
                            </a>
                        @endif
                    </li>
                @endif
                {{--地址--}}
                @if(!empty($data->contact_address))
                    <li class="list-group-item">
                        <i class="fa fa-map-marker text-primary"></i>
                        <span class="text-muted">{{ $data->area_province or '' }}</span>
                        <span class="text-muted">{{ $data->area_city or '' }}</span>
                        <span class="text-muted">{{ $data->area_district or '' }}</span>
                        <span class="text-muted">{{ $data->contact_address or '' }}</span>
                    </li>
                @endif
                {{--<li class="list-group-item">--}}
                    {{--<b>Followers</b> <a class="pull-right">1,322</a>--}}
                {{--</li>--}}
                {{--<li class="list-group-item">--}}
                    {{--<b>Following</b> <a class="pull-right">543</a>--}}
                {{--</li>--}}
                {{--<li class="list-group-item">--}}
                    {{--<b>Friends</b> <a class="pull-right">13,287</a>--}}
                {{--</li>--}}
            </ul>


            {{--tool--}}
            <div style="margin-top:12px;">

                {{--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>--}}

                @if(!Auth::check())
                    <a href="javascript:void(0);" class="btn btn-danger btn-block follow-add follow-add-it" data-user-id="{{ $data->id }}">
                        <i class="fa fa-star-o"></i>
                        <span class="">收藏名片</span>
                    </a>
                @else
                    @if(Auth::user()->id != $data->id)
                        @if(!empty($is_follow) && $is_follow)
                            <a href="javascript:void(0);" class="btn btn-danger btn-block follow-remove follow-remove-it" data-user-id="{{ $data->id }}">
                                <i class="fa fa-star"></i>
                                <span class="">已收藏</span>
                            </a>
                        @else
                            <a href="javascript:void(0);" class="btn btn-danger btn-block follow-add follow-add-it" data-user-id="{{ $data->id }}">
                                <i class="fa fa-star-o"></i>
                                <span class="">收藏名片</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ url('/my-cards') }}" class="btn btn-block btn-sm btn-primary _none">
                            <i class="fa fa-list-alt"></i>
                            <span class="">我的名片夹</span>
                        </a>
                        <a href="{{ url('/mine/my-card-edit') }}" class="btn btn-block btn-sm btn-primary" data-user-id="{{ $data->id }}">
                            <i class="fa fa-edit"></i>
                            <span class="">编辑名片</span>
                        </a>
                        <a href="{{ url('/mine/my-profile-intro-edit') }}" class="btn btn-block btn-sm btn-primary _none" data-user-id="{{ $data->id }}">
                            <i class="fa fa-edit"></i>
                            <span class="">编辑介绍</span>
                        </a>
{{--                        <label for="myCheckbox">切换开关</label>--}}
{{--                        <input type="checkbox" id="myCheckbox" /> 在平台展示名片--}}
{{--                        <input type="radio" name="myRadio" id="option1" />--}}
{{--                        <input type="radio" name="myRadio" id="option2" />--}}

                        <div style="margin-top:8px;">
                            <input type="checkbox" id="myCheckbox" @if($me->user_show == 1) checked="checked" @endif />
                            在平台中展示名片
{{--                            <input type="checkbox" name="test">--}}
                        </div>
                    @endif
                @endif

            </div>

        </div>
    {{--</div>--}}


    <div class="item-container _none">

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
                <span class="">粉丝 {{ $data->fans_num or 0 }}</span>
                <span class="">•</span>
                <span class="">访问 {{ $data->visit_num or 0 }}</span>
                {{--<span class="info-tags text-danger">作者</span>--}}
            </div>
        </div>


        <div class="item-row">

            @if(!empty($data->contact_phone))
                <div class="item-info-row margin-4px">
                    <i class="fa fa-phone text-primary"></i>
                    <span class="text-muted">{{ $data->contact_phone or '暂无' }}</span>
                </div>
            @endif
            @if(!empty($data->email))
            <div class="item-info-row margin-4px">
                <i class="fa fa-envelope text-primary"></i>
                <span class="text-muted">{{ $data->email or '暂无' }}</span>
            </div>
            @endif
            @if(!empty($data->wx_id))
            <div class="item-info-row margin-4px">
                <i class="fa fa-weixin text-primary"></i>
                <span class="text-muted">{{ $data->wx_id or '暂无' }}</span>
            </div>
            @endif
            @if(!empty($data->QQ_number))
            <div class="item-info-row margin-4px">
                <i class="fa fa-qq text-primary"></i>
                @if(!empty($data->QQ_number))
                    <a class="" href="tencent://message/?uin={{ $data->QQ_number }}">
                        {{ $data->QQ_number or '暂无' }}
                    </a>
                @else
                    <span class="text-muted">{{ $data->QQ_number or '暂无' }}</span>
                @endif
            </div>
            @endif
            @if(!empty($data->wb_name))
                <div class="item-info-row margin-4px">
                    <i class="fa fa-wb text-primary"></i>
                    <span class="text-muted">{{ $data->wb_name or '暂无' }}</span>
                </div>
            @endif
            @if(!empty($data->website))
            <div class="item-info-row margin-4px">
                <i class="fa fa-globe text-primary"></i>
                @if(!empty($data->website))
                    <a target="_blank" href="{{ $data->website or '' }}">
                        {{ $data->website or '暂无' }}
                    </a>
                @else
                    <span class="text-muted">{{ $data->website or '暂无' }}</span>
                @endif
            </div>
            @endif
            @if(!empty($data->contact_address))
                <div class="item-info-row margin-4px">
                    <i class="fa fa-map-marker text-primary"></i>
                    <span class="text-muted">{{ $data->contact_address or '暂无' }}</span>
                </div>
            @endif

        </div>

        @if(!Auth::check())
        <div class="item-row">
            <div class="tool-inn tool-info follow-add follow-add-it" style="width:100%;text-align:center;" data-user-id="{{ $data->id }}">
                <i class="fa fa-star-o"></i>
                <span class="">收藏名片</span>
            </div>
        </div>
        @else
            @if(Auth::user()->id != $data->id)
            <div class="item-row">
                @if(!empty($is_follow) && $is_follow)
                <div class="tool-inn tool-info follow-remove follow-remove-it" style="width:100%;text-align:center;" data-user-id="{{ $data->id }}">
                    <b><i class="fa fa-star"></i></b>
                    <span class="">已收藏</span>
                </div>
                @else
                <div class="tool-inn tool-info follow-add follow-add-it" style="width:100%;text-align:center;" data-user-id="{{ $data->id }}">
                    <i class="fa fa-star-o"></i>
                    <span class="">收藏名片</span>
                </div>
                @endif
            </div>
            @else
            <div class="item-row">
                <a href="{{ url('/my-info/edit') }}">
                    <div class="tool-inn tool-info" style="width:100%;text-align:center;" data-user-id="{{ $data->id }}">
                        <i class="fa fa-edit"></i>
                        <span class="">编辑名片</span>
                    </div>
                </a>
            </div>
            @endif
        @endif

    </div>

</div>


@if(!empty($data->ext->content) || !empty($data->ext->description))
<div class=" item-piece item-option margin-top-4px padding-16px border-bottom-0" style="border-bottom:0;">
    <article class="readmore-content item-piece item-option padding-0 border-bottom-0" style="border-bottom:0;">

        <div class="item-row margin-bottom-4px- text-center _none">
            <h4>{{ $data->ext->title or '我的介绍' }}</h4>
        </div>

        @if(!empty($data->ext->description))
            <div class="item-row item-description-row with-background margin-bottom-8px _none">
                <div class="text-row text-description-row text-muted">
                    {{ $data->ext->description or '暂无描述' }}
                </div>
            </div>
        @endif

        @if(!empty($data->ext->content))
        <div class="item-row">
            {!! $data->ext->content or '' !!}
        </div>
        @else
        <div class="item-row text-center">
            <span>暂无介绍</span>
        </div>
        @endif

    </article>
</div>
{{--<a href="#" class="readmore-js-toggle">Read More</a>--}}
@endif


<style>
    .box.widget-user { margin-bottom:0; box-shadow:0 0; }
    .widget-user .widget-user-header { height:70px; }
    .widget-user .widget-user-image { position:relative; top:0; margin-top:-40px; margin-left:-40px; margin-bottom:4px; }
    .widget-user .widget-user-image>img { width:80px; min-height:80px; border-radius:0; }
    .widget-user .profile-username { margin-top:4px; margin-bottom:4px; font-size:16px; }
    .description-block { margin:4px 0; }
    .description-block .description-text { font-size:12px; }
    .box-footer { padding:4px; }
    .widget-user .box-footer { padding-top:4px; border-bottom: 1px solid #f4f4f4; }
    .list-group { margin-bottom:0; }
    .list-group-item { padding:4px 12px; border:1px solid #eeeeee; font-size:12px; }
    .list-group-item:first-child { border-top:0; }
    .list-group-item:last-child { border-bottom:0; }
    .list-group-item i { width:20px; text-align:center; }
</style>