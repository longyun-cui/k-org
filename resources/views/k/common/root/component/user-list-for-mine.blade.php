@forelse($user_list as $data)
<div class="item-piece item-option item-wrapper user-piece user-option user margin-bottom-4px radius-2px"
     data-user="{{ $data->mine_user->id or 0 }}"
     data-type="{{ $data->relation_type or 0 }}"
>
    <div class="panel-default box-default item-entity-container">

        <div class="item-table-box">

            <div class="item-left-box">
                <a href="{{ url('/user/'.$data->mine_user->id) }}">
                    <img class="media-object" src="{{ url(env('DOMAIN_CDN').'/'.$data->mine_user->portrait_img) }}">
                </a>
            </div>

            <div class="item-right-box">

                <div class="item-row item-title-row">

                    <a href="{{ url('/user/'.$data->mine_user->id) }}">
                        <b>{{ $data->mine_user->username or '' }}</b>
                    </a>

                    @if(Auth::check() && $data->relation_user_id != Auth::user()->id)

                        <span class="tool-inn tool-set _none"><i class="fa fa-cog"></i></span>

                        @if($data->relation_type == 21)
                            <span class="tool-inn tool-info follow-remove follow-remove-it"><i class="fa fa-exchange"></i> 相互关注</span>
                        @elseif($data->relation_type == 41)
                            <span class="tool-inn tool-info follow-remove follow-remove-it"><i class="fa fa-check"></i> 已关注</span>
                        @elseif($data->relation_type == 71)
                            <span class="tool-inn tool-info follow-add follow-add-it"><i class="fa fa-plus text-yellow"></i> 关注</span>
                        @else
                            <span class="tool-inn tool-info follow-add follow-add-it"><i class="fa fa-plus text-yellow"></i> 关注</span>
                        @endif

                        <div class="tool-menu-list _none" style="z-index:999;">
                            <ul>
                                @if(in_array($data->relation_type, [21,41]))
                                    <li class="follow-remove-it" role="button"><i class="fa fa-minus"></i> 取消关注</li>
                                @endif
                                @if(in_array($data->relation_type, [21,71]))
                                    <li class="fans-remove-it" role="button"><i class="fa fa-minus"></i> 移除粉丝</li>
                                @endif
                            </ul>
                        </div>

                    @endif

                </div>

                <div class="item-row item-info-row">
                    <span>粉丝 {{ $data->mine_user->fans_num }}</span>
                    {{--<span> • 内容 {{ $data->mine_user->item_count }}</span>--}}
                    {{--<span> • 文章 {{ $data->mine_user->article_count }}</span>--}}
                    {{--<span> • 活动 {{ $data->mine_user->activity_count }}</span>--}}
                    <span> • 访问 {{ $data->mine_user->visit_num }}</span>
                </div>

                {{--Email--}}
                @if(!empty($data->mine_user->email))
                    <div class="item-row item-info-row">
                        <i class="fa fa-envelope text-primary" style="width:16px;"></i>
                        <span class="text-muted">{{ $data->mine_user->email or '暂无' }}</span>
                    </div>
                @endif
                {{--QQ--}}
                @if(!empty($data->mine_user->QQ_number))
                    <div class="item-row item-info-row">
                        <i class="fa fa-qq text-primary" style="width:16px;"></i>
                        <a target="_blank" href="tencent://message/?uin={{ $data->mine_user->QQ_number }}">
                            {{ $data->QQ_number or '暂无' }}
                        </a>
                    </div>
                @endif
                {{--微信号--}}
                @if(!empty($data->mine_user->wx_id))
                    <div class="item-row item-info-row">
                        <i class="fa fa-weixin text-success" style="width:16px;"></i>
                        <span class="text-muted">{{ $data->mine_user->wx_id or '暂无' }}</span>
                        @if(!empty($data->mine_user->wx_qr_code_img))
                            <a class="lightcase-image" href="{{ url(env('DOMAIN_CDN').'/'.$data->mine_user->wx_qr_code_img) }}">
                                <i class="fa fa-qrcode text-danger" style="width:16px;font-weight:500;"></i>
                            </a>
                        @endif
                    </div>
                @endif
                {{--网站--}}
                @if(!empty($data->mine_user->website))
                    <div class="item-row item-info-row">
                        <i class="fa fa-globe text-primary" style="width:16px;"></i>
                        <a target="_blank" href="{{ $data->mine_user->website or '' }}">
                            {{ $data->mine_user->website or '暂无' }}
                        </a>
                    </div>
                @endif
                {{--联系人姓名--}}
                @if(!empty($data->mine_user->linkman_name))
                    <div class="item-row item-info-row">
                        <i class="fa fa-user text-orange" style="width:16px;"></i>
                        <span class="text-muted">{{ $data->mine_user->linkman_name or '暂无' }}</span>
                    </div>
                @endif
                {{--联系人电话--}}
                @if(!empty($data->mine_user->linkman_phone))
                    <div class="item-row item-info-row">
                        <i class="fa fa-phone text-danger" style="width:16px;"></i>
                        <span class="text-muted">
                        <a href="tel:{{ $data->mine_user->linkman_phone or '' }}">
                            <strong>{{ $data->mine_user->linkman_phone or '暂无' }}</strong>
                        </a>
                    </span>
                    </div>
                @endif
                {{--联系人微信--}}
                @if(!empty($data->mine_user->linkman_wx_id))
                    <div class="item-row item-info-row">
                        <i class="fa fa-weixin text-success" style="width:16px;"></i>
                        <span class="text-muted">{{ $data->mine_user->linkman_wx_id or '暂无' }}</span>
                        @if(!empty($data->mine_user->linkman_wx_qr_code_img))
                            <a class="lightcase-image" href="{{ url(env('DOMAIN_CDN').'/'.$data->mine_user->linkman_wx_qr_code_img) }}">
                                <i class="fa fa-qrcode text-danger" style="width:16px;font-weight:500;"></i>
                            </a>
                        @endif
                    </div>
                @endif
                {{--地址--}}
                @if(!empty($data->mine_user->area_province) || !empty($data->mine_user->contact_address))
                    <div class="item-row item-info-row copy-btn" data-title="地址" data-text="{{ $data->mine_user->area_province or '' }}{{ $data->mine_user->area_city or '' }}{{ $data->mine_user->area_district or '' }}{{ $data->mine_user->contact_address or '' }}">
                        <i class="fa fa-map-marker text-primary" style="width:16px;"></i>
                        <span class="text-muted">{{ $data->mine_user->area_province or '' }}</span>
                        <span class="text-muted">{{ $data->mine_user->area_city or '' }}</span>
                        <span class="text-muted">{{ $data->mine_user->area_district or '' }}</span>
                        <span class="text-muted">{{ $data->mine_user->contact_address or '' }}</span>
                    </div>
                @endif

                @if(!empty($data->mine_user->description))
                <div class="item-row item-info-row">
                    {{ $data->mine_user->description or '暂无简介' }}
                </div>
                @endif

                <div class="item-row margin-top-12px">
                    <a class="btn btn-block- btn-sm btn-primary user-edit-submit" data-id="{{ $data->mine_user->id }}">
                        <i class="fa fa-edit"></i>
                        <span class="">编辑名片</span>
                    </a>
                    <a class="btn btn-block- btn-sm btn-success user-login-to-org-submit" data-id="{{ $data->mine_user->id }}">
                        <i class="fa fa-sign-in"></i>
                        <span class="">登录·进行更多操作</span>
                    </a>
                </div>


            </div>

        </div>

    </div>
</div>
@empty
    <div class="item-piece item-option item-wrapper user-piece user-option user margin-bottom-4px radius-2px">
        <div class="panel-default box-default item-entity-container text-center">
            <div>暂时没有任何组织机构</div>
        </div>
    </div>
@endforelse