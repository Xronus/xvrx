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
                                <strong id="character-count"><span class="spinner-border spinner-border-sm" role="status"></span></strong>
                            </div>
                        </div>

                        <div class="nk-block">
                            {{-- Loading --}}
                            <div id="characters-loading" class="xvrx-character-grid">
                                @for ($i = 0; $i < 3; $i++)
                                <div class="xvrx-character-card xvrx-skeleton">
                                    <div class="xvrx-character-card-top">
                                        <div class="xvrx-character-avatar"></div>
                                        <div><div style="width:100px;height:16px;background:rgba(255,255,255,.06);border-radius:4px;margin-bottom:4px"></div><div style="width:56px;height:12px;background:rgba(255,255,255,.04);border-radius:4px"></div></div>
                                    </div>
                                    <div class="xvrx-character-meta">
                                        @for ($j = 0; $j < 4; $j++)
                                        <div><span style="width:40px;height:11px;background:rgba(255,255,255,.04);border-radius:3px;display:block;margin-bottom:3px"></span><strong style="width:70px;height:13px;background:rgba(255,255,255,.06);border-radius:3px;display:block"></strong></div>
                                        @endfor
                                    </div>
                                </div>
                                @endfor
                            </div>

                            {{-- Loaded grid --}}
                            <div id="characters-grid" class="xvrx-character-grid" style="display:none;"></div>

                            {{-- Error --}}
                            <div id="characters-error" class="xvrx-cabinet-empty" style="display:none;">
                                <span><em class="icon ni ni-alert-c"></em></span>
                                <p id="characters-error-msg">{{ __('main.server_error') }}</p>
                                <button class="btn btn-sm btn-outline-primary mt-3" onclick="loadCharacters()">{{ __('main.retry') }}</button>
                            </div>

                            {{-- Empty --}}
                            <div id="characters-empty" class="xvrx-cabinet-empty" style="display:none;">
                                <span><em class="icon ni ni-users"></em></span>
                                <p>{{ __('main.no_characters') }}</p>
                            </div>

                            {{-- Card template --}}
                            <template id="character-card-template">
                                <article class="xvrx-character-card">
                                    <div class="xvrx-character-card-top">
                                        <div class="xvrx-character-avatar">
                                            <img src="" alt="" loading="lazy" data-field="race_icon">
                                        </div>
                                        <div>
                                            <h5 data-field="name"></h5>
                                            <span data-field="level_text"></span>
                                        </div>
                                        <span class="xvrx-status-badge" data-field="status_badge"></span>
                                    </div>
                                    <div class="xvrx-character-meta">
                                        <div>
                                            <span><img src="" alt="" class="xvrx-faction-icon" data-field="race_icon_small">@lang('main.race')</span>
                                            <strong data-field="race_name"></strong>
                                        </div>
                                        <div>
                                            <span><img src="" alt="" class="xvrx-faction-icon" data-field="class_icon">@lang('main.class')</span>
                                            <strong data-field="class_name"></strong>
                                        </div>
                                        <div>
                                            <span><img src="" alt="" class="xvrx-faction-icon" data-field="faction_icon">@lang('main.faction')</span>
                                            <strong data-field="faction"></strong>
                                        </div>
                                        <div>
                                            <span>@lang('main.last_login')</span>
                                            <strong data-field="last_login"></strong>
                                        </div>
                                    </div>
                                </article>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var MSG = {
        level: '{{ __('main.level') }}',
        online: '{{ __('main.online') }}',
        offline: '{{ __('main.offline') }}',
        serverError: '{{ __('main.server_error') }}'
    };

    var states = {
        loading: document.getElementById('characters-loading'),
        grid: document.getElementById('characters-grid'),
        error: document.getElementById('characters-error'),
        empty: document.getElementById('characters-empty')
    };

    function show(state) {
        Object.keys(states).forEach(function(k) { states[k].style.display = 'none'; });
        if (states[state]) states[state].style.display = '';
    }

    function updateCount(n) {
        var el = document.getElementById('character-count');
        if (el) el.textContent = n;
    }

    function renderCards(characters) {
        var template = document.getElementById('character-card-template');
        var grid = states.grid;
        grid.innerHTML = '';

        characters.forEach(function(c) {
            var card = template.content.cloneNode(true);

            card.querySelector('[data-field="name"]').textContent = c.name;
            card.querySelector('[data-field="level_text"]').textContent = MSG.level + ' ' + c.level;
            card.querySelector('[data-field="race_name"]').textContent = c.race_name;
            card.querySelector('[data-field="class_name"]').textContent = c.class_name;
            card.querySelector('[data-field="faction"]').textContent = c.faction;
            card.querySelector('[data-field="last_login"]').textContent = c.last_login;

            // Status badge
            var badge = card.querySelector('[data-field="status_badge"]');
            if (c.online) {
                badge.textContent = MSG.online;
                badge.className = 'xvrx-status-badge is-online';
            } else {
                badge.textContent = MSG.offline;
                badge.className = 'xvrx-status-badge is-offline';
            }

            // Images
            card.querySelector('[data-field="race_icon"]').src = c.race_icon;
            card.querySelector('[data-field="race_icon"]').alt = c.race_name;
            card.querySelector('[data-field="race_icon_small"]').src = c.race_icon_small;
            card.querySelector('[data-field="class_icon"]').src = c.class_icon;
            card.querySelector('[data-field="faction_icon"]').src = c.faction_icon;

            grid.appendChild(card);
        });
    }

    window.loadCharacters = function() {
        show('loading');
        fetch('{{ route('api.characters') }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.ok) {
                document.getElementById('characters-error-msg').textContent = data.message || MSG.serverError;
                show('error');
                updateCount('—');
                return;
            }
            updateCount(data.count);
            if (data.count === 0) {
                show('empty');
                return;
            }
            renderCards(data.characters);
            show('grid');
        })
        .catch(function() {
            show('error');
            updateCount('—');
        });
    };

    loadCharacters();
})();
</script>
@endpush
