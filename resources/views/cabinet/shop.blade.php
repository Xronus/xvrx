@extends('layouts.app')
@section('title', __('main.shop'))
@section('content')
@include('partials._game_ban_warning')
<div class="nk-wrap">
    @include('cabinet.partials.header')

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                @include('cabinet.partials.sidebar', ['active' => 'shop'])

                <div class="nk-content-body">
                    <div class="nk-content-wrap xvrx-shop-page">
                        <div class="nk-block">

                            {{-- Modal overlay --}}
                            <div id="shop-modal-overlay" class="xvrx-modal-overlay" style="display:none;" onclick="closeShopModal()">
                                <div class="xvrx-modal-dialog" onclick="event.stopPropagation()">
                                    <div class="xvrx-modal-icon" id="shop-modal-icon"></div>
                                    <h5 class="xvrx-modal-title" id="shop-modal-title"></h5>
                                    <p class="xvrx-modal-text" id="shop-modal-text"></p>
                                    <button class="btn btn-primary" onclick="closeShopModal()">{{ __('main.close') }}</button>
                                </div>
                            </div>

                            <div class="xvrx-shop-shell">
                                <aside class="xvrx-shop-panel">
                                    <div class="xvrx-shop-balance">
                                        <span class="xvrx-shop-panel-label">{{ __('main.shop_balance') }}</span>
                                        <strong id="shop-balance">{{ $user->bonuses ?? 0 }} <em class="icon ni ni-coins"></em></strong>
                                    </div>

                                    <div class="xvrx-shop-categories">
                                        <span class="xvrx-shop-panel-label">{{ __('main.shop_categories') }}</span>
                                        <a href="#" class="shop-cat-link is-active" onclick="event.preventDefault();filterCat('all')">{{ __('main.shop_all_categories') }}</a>
                                        @foreach($categories as $cat)
                                            <div class="xvrx-shop-category-group">
                                                <a href="#" class="shop-cat-link xvrx-shop-parent-link" data-cat="{{ $cat->id }}" onclick="event.preventDefault();filterCat({{ $cat->id }})">{{ $cat->localizedName() }}</a>
                                                @if($cat->subcategories->isNotEmpty())
                                                    <div class="xvrx-shop-subcategories">
                                                        @foreach($cat->subcategories as $subcat)
                                                            <a href="#" class="shop-cat-link xvrx-shop-subcat-link" data-cat="{{ $subcat->id }}" data-parent="{{ $cat->id }}" onclick="event.preventDefault();filterCat({{ $subcat->id }})">{{ $subcat->localizedName() }}</a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </aside>

                                <section class="xvrx-shop-catalog">
                                    <div class="xvrx-shop-grid" id="shop-items">
                                        @foreach($items as $catId => $catItems)
                                            @foreach($catItems as $item)
                                            @php
                                                $itemCategory = $item->category;
                                                $parentCatId = $itemCategory && (int) $itemCategory->parent_id !== 0 ? $itemCategory->parent_id : $catId;
                                            @endphp
                                            <article class="shop-item xvrx-shop-card" data-cat="{{ $catId }}" data-parent="{{ $parentCatId }}">
                                                <a href="https://www.wowhead.com/ru/item={{ $item->item_entry }}" target="_blank" rel="noopener" class="xvrx-shop-card-icon">
                                                    <img src="{{ $item->icon_name ? 'https://wow.zamimg.com/images/wow/icons/large/' . $item->icon_name . '.jpg' : 'https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg' }}" alt="">
                                                </a>
                                                <div class="xvrx-shop-card-body">
                                                    <h2><a href="https://www.wowhead.com/ru/item={{ $item->item_entry }}" target="_blank" rel="noopener">{{ $item->name_ru ?: '#' . $item->item_entry }}</a></h2>
                                                    <div class="xvrx-shop-card-price">{{ $item->price }} <em class="icon ni ni-coins"></em></div>

                                                    <div class="xvrx-shop-char-area" data-item-id="{{ $item->id }}">
                                                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                                                    </div>
                                                </div>
                                            </article>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </section>
                            </div>
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
function filterCat(catId) {
    var selectedCat = String(catId);

    document.querySelectorAll('.shop-item').forEach(function(item) {
        item.style.display = (catId === 'all' || item.dataset.cat === selectedCat || item.dataset.parent === selectedCat) ? '' : 'none';
    });

    document.querySelectorAll('.shop-cat-link').forEach(function(a) {
        a.classList.toggle('is-active', a.dataset.cat === selectedCat || (catId === 'all' && !a.dataset.cat));
    });
}

(function loadShopCharacters() {
    var LABELS = {
        selectChar: '{{ __('main.shop_select_char') }}',
        buy: '{{ __('main.shop_buy') }}',
        noChars: '{{ __('main.no_characters') }}'
    };

    function esc(s) {
        return String(s).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    fetch('{{ route('api.characters', ['mode' => 'minimal']) }}', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        var areas = document.querySelectorAll('.xvrx-shop-char-area');
        areas.forEach(function(area) {
            var itemId = area.dataset.itemId;
            if (data.ok && data.count > 0) {
                var html = '<select class="form-select form-select-sm xvrx-shop-character-select" id="char-select-' + itemId + '" onchange="document.getElementById(\'buy-btn-' + itemId + '\').disabled = !this.value">';
                html += '<option value="">' + LABELS.selectChar + '</option>';
                data.characters.forEach(function(c) {
                    html += '<option value="' + esc(c.name) + '">' + esc(c.name) + ' (' + Number(c.level) + ')</option>';
                });
                html += '</select>';
                html += '<button class="btn btn-primary xvrx-shop-buy-btn" id="buy-btn-' + itemId + '" onclick="buyItem(' + itemId + ')" disabled>' + LABELS.buy + '</button>';
                area.innerHTML = html;
            } else {
                area.innerHTML = '<p class="xvrx-shop-empty-character">' + LABELS.noChars + '</p>';
            }
        });
    })
    .catch(function() {
        document.querySelectorAll('.xvrx-shop-char-area').forEach(function(area) {
            area.innerHTML = '<p class="xvrx-shop-empty-character">' + LABELS.noChars + '</p>';
        });
    });
})();
function showShopModal(msg, ok) {
    document.getElementById('shop-modal-icon').innerHTML = ok
        ? '<em class="icon ni ni-check-circle" style="font-size:48px;color:#28a745;"></em>'
        : '<img src="{{ asset('xvrx-assets/images/shop-purchase-error.png') }}" alt="" class="xvrx-shop-error-icon">';
    document.getElementById('shop-modal-title').textContent = ok ? '{{ __('main.shop_purchase_ok') }}' : '{{ __('main.shop_error_title') }}';
    document.getElementById('shop-modal-text').textContent = msg;
    document.getElementById('shop-modal-overlay').style.display = 'flex';
}
function closeShopModal() {
    document.getElementById('shop-modal-overlay').style.display = 'none';
}

function buyItem(itemId) {
    var sel = document.getElementById('char-select-' + itemId);
    var btn = document.getElementById('buy-btn-' + itemId);
    if (!sel || !sel.value || !btn) return;

    var charName = sel.value;
    btn.disabled = true;
    btn.textContent = '...';

    fetch('{{ route('shop.buy') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({item_id: itemId, character_name: charName})
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.ok) {
            document.getElementById('shop-balance').innerHTML = data.balance + ' <em class="icon ni ni-coins"></em>';
            document.querySelectorAll('.xvrx-shop-balance-value').forEach(function(el) {
                el.innerHTML = data.balance;
            });
        }
        showShopModal(data.message, data.ok);
        btn.disabled = false;
        btn.textContent = '{{ __('main.shop_buy') }}';
    })
    .catch(function(e) {
        console.error('Buy error:', e);
        showShopModal('{{ __('main.server_error') }}', false);
        btn.disabled = false;
        btn.textContent = '{{ __('main.shop_buy') }}';
    });
}
</script>
@endpush
