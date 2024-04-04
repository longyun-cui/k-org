@foreach($user_list as $u)
<div class="item-piece item-option item-wrapper user-piece user-option user margin-bottom-4px radius-2px"
     data-user="{{ $u->id or 0 }}"
     data-type="{{ $u->relation_type or 0 }}"
>
    <div class="panel-default box-default item-entity-container">

        <div class="item-table-box">

            <div class="item-left-box">
                <a href="{{ url('/user/'.$u->id) }}">
                    <img class="media-object" src="{{ url(env('DOMAIN_CDN').'/'.$u->portrait_img) }}">
                </a>
            </div>

            <div class="item-right-box">

                <div class="item-row item-title-row">

                    <a href="{{ url('/user/'.$u->id) }}">
                        <b>{{ $u->username or '' }}</b>
                    </a>

                    @if(Auth::check())

                        <span class="tool-inn tool-set _none"><i class="fa fa-cog"></i></span>

                        @if($u->id != Auth::user()->id)
                            @if(count($u->fans_list->whereIn('relation_type', [21,41])) > 0)
                                <span class="tool-inn tool-info follow-remove follow-remove-it" role="button"><i class="fa fa-check"></i> 已收藏</span>
                                {{--<span class="tool-inn tool-info follow-remove follow-remove-it"><i class="fa fa-minus"></i> 取消关注</span>--}}
                            @else
                                <span class="tool-inn tool-info follow-add follow-add-it" role="button"><i class="fa fa-plus"></i> 收藏名片</span>
                            @endif
                        @endif


                        {{--@if($u->relation_with_me == 21)--}}
                            {{--<span class="tool-inn tool-info"><i class="fa fa-exchange"></i> 相互关注</span>--}}
                        {{--@elseif($u->relation_with_me == 41)--}}
                            {{--<span class="tool-inn tool-info"><i class="fa fa-check"></i> 已关注</span>--}}
                        {{--@elseif($u->relation_with_me == 71)--}}
                            {{--<span class="tool-inn tool-info follow-add-it"><i class="fa fa-plus text-yellow"></i> 关注</span>--}}
                        {{--@else--}}
                            {{--<span class="tool-inn tool-info follow-add-it"><i class="fa fa-plus text-yellow"></i> 关注</span>--}}
                        {{--@endif--}}

                        <div class="tool-menu-list _none" style="z-index:999;">
                            <ul>
                                @if($u->relation_with_me == 21)
                                    <li class="follow-remove-it">取消关注</li>
                                    <li class="fans-remove-it">移除粉丝</li>
                                @elseif($u->relation_with_me == 41)
                                    <li class="follow-remove-it">取消关注</li>
                                @elseif($u->relation_with_me == 71)
                                    <li class="fans-remove-it">移除粉丝</li>
                                @endif
                            </ul>
                        </div>

                    @else

                        <span class="tool-inn tool-info follow-add follow-add-it" role="button"><i class="fa fa-plus"></i> 关注</span>

                    @endif

                </div>

                @if(!empty($u->description))
                    <div class="item-row item-info-row">
                        <b style="font-size:14px;color:#444;">{{ $u->description or '' }}</b>
                    </div>
                @endif

                <div class="item-row item-info-row">
                    <span>粉丝 {{ $u->fans_num }}</span>
                    {{--<span> • 内容 {{ $u->item_count }}</span>--}}
                    {{--<span> • 文章 {{ $u->article_count }}</span>--}}
                    {{--<span> • 活动 {{ $u->activity_count }}</span>--}}
                    <span> • 访问 {{ $u->visit_num }}</span>
                </div>

                {{--Email--}}
                @if(!empty($u->email))
                    <div class="item-row item-info-row">
                        <i class="fa fa-envelope text-primary" style="width:16px;"></i>
                        <span class="text-muted">{{ $u->email or '' }}</span>
                    </div>
                @endif
                {{--QQ--}}
                @if(!empty($u->QQ_number))
                    <div class="item-row item-info-row">
                        <i class="fa fa-qq text-primary" style="width:16px;"></i>
                        <a target="_blank" href="tencent://message/?uin={{ $u->QQ_number }}">
                            {{ $u->QQ_number or '' }}
                        </a>
                    </div>
                @endif
                {{--微信号--}}
                @if(!empty($u->wx_id))
                    <div class="item-row item-info-row">
                        <i class="fa fa-weixin text-success" style="width:16px;"></i>
                        <span class="text-muted">{{ $u->wx_id or '' }}</span>
                        @if(!empty($u->wx_qr_code_img))
                        <a class="lightcase-image" href="{{ url(env('DOMAIN_CDN').'/'.$u->wx_qr_code_img) }}">
                            <i class="fa fa-qrcode text-danger" style="width:16px;font-weight:500;"></i>
                        </a>
                        @endif
                    </div>
                @endif
                {{--网站--}}
                @if(!empty($u->website))
                    <div class="item-row item-info-row">
                        <i class="fa fa-globe text-primary" style="width:16px;"></i>
                        <a target="_blank" href="{{ $u->website or '' }}">
                            {{ $u->website or '暂无' }}
                        </a>
                    </div>
                @endif
                {{--联系人姓名--}}
                @if(!empty($u->linkman_name))
                <div class="item-row item-info-row">
                    <i class="fa fa-user text-orange" style="width:16px;"></i>
                    <span class="text-muted">{{ $u->linkman_name or '' }}</span>
                </div>
                @endif
                {{--联系人电话--}}
                @if(!empty($u->linkman_phone))
                <div class="item-row item-info-row">
                    <i class="fa fa-phone text-danger" style="width:16px;"></i>
                    <span class="text-muted">
                        <a href="tel:{{ $u->linkman_phone or '' }}">
                            <strong>{{ $u->linkman_phone or '' }}</strong>
                        </a>
                    </span>
                </div>
                @endif
                {{--联系人微信--}}
                @if(!empty($u->linkman_wx_id))
                    @if(!empty($u->linkman_wx_qr_code_img))
                    <a class="lightcase-image" href="{{ url(env('DOMAIN_CDN').'/'.$u->linkman_wx_qr_code_img) }}">
                        <div class="item-row item-info-row">
                            <i class="fa fa-weixin text-success" style="width:16px;"></i>
                            <span class="text-muted">{{ $u->linkman_wx_id or '' }}</span>
                            <i class="fa fa-qrcode text-danger" style="width:16px;font-weight:500;"></i>
                        </div>
                    </a>
                    @else
                        <div class="item-row item-info-row">
                            <i class="fa fa-weixin text-success" style="width:16px;"></i>
                            <span class="text-muted">{{ $u->linkman_wx_id or '' }}</span>
                        </div>
                    @endif
                @endif
                {{--地址--}}
                @if(!empty($u->area_province) || !empty($u->contact_address))
                    <div class="item-row item-info-row">
                        <i class="fa fa-map-marker text-primary" style="width:16px;"></i>
                        <span class="text-muted">{{ $u->area_province or '' }}</span>
                        <span class="text-muted">{{ $u->area_city or '' }}</span>
{{--                        <span class="text-muted">{{ $u->area_district or '' }}</span>--}}
                        <span class="text-muted">{{ $u->contact_address or '' }}</span>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>
@endforeach