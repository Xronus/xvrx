@extends('layouts.app')

@section('title', __('main.characters'))

@section('content')
@include('partials._game_ban_warning')
<div class="nk-wrap">
    @include('cabinet.partials.header')

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                @include('cabinet.partials.sidebar', ['active' => 'characters'])

                <div class="nk-content-body">
                    <div class="nk-content-wrap xvrx-cabinet-page">
                        <div class="nk-block-head xvrx-cabinet-head">
                            <div class="nk-block-head-content">
                                <span class="xvrx-cabinet-eyebrow">{{ __('main.personal_account') }}</span>
                                <h4 class="nk-block-title">{{ __('main.characters') }}</h4>
                                <p class="text-soft">{{ __('main.characters_list') }}</p>
                            </div>
                            <div class="xvrx-cabinet-head-count">
                                <span>{{ __('main.characters_count') }}</span>
                                <strong>{{ $characters->count() }}</strong>
                            </div>
                        </div>

                        <div class="nk-block">
                            @if($characters->count() > 0)
                            <div class="xvrx-character-grid">
                                @foreach($characters as $char)
                                <article class="xvrx-character-card">
                                    <div class="xvrx-character-card-top">
                                        <div class="xvrx-character-avatar">
                                            {{ mb_substr($char->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h5>{{ $char->name }}</h5>
                                            <span>{{ __('main.level') }} {{ $char->level }}</span>
                                        </div>
                                        <span class="xvrx-status-badge {{ $char->online ? 'is-online' : 'is-offline' }}">
                                            {{ $char->online ? __('main.online') : __('main.offline') }}
                                        </span>
                                    </div>

                                    <div class="xvrx-character-meta">
                                        <div>
                                            <span>{{ __('main.race') }}</span>
                                            <strong>{{ $char->race_name }}</strong>
                                        </div>
                                        <div>
                                            <span>{{ __('main.class') }}</span>
                                            <strong>{{ $char->class_name }}</strong>
                                        </div>
                                        <div>
                                            <span>{{ __('main.faction') }}</span>
                                            <strong>{{ $char->faction }}</strong>
                                        </div>
                                        <div>
                                            <span>{{ __('main.last_login') }}</span>
                                            <strong>{{ $char->last_login }}</strong>
                                        </div>
                                    </div>
                                </article>
                                @endforeach
                            </div>
                            @else
                            <div class="xvrx-cabinet-empty">
                                <span><em class="icon ni ni-users"></em></span>
                                <p>{{ __('main.no_characters') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
