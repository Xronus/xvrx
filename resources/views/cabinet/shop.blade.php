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
                                        <a href="#" class="shop-cat-link" data-cat="{{ $cat->id }}" onclick="event.preventDefault();filterCat({{ $cat->id }})">{{ $cat->localizedName() }}</a>
                                        @endforeach
                                    </div>
                                </aside>

                                <section class="xvrx-shop-catalog">
                                    <div class="xvrx-shop-grid" id="shop-items">
                                        @foreach($items as $catId => $catItems)
                                            @foreach($catItems as $item)
                                            <article class="shop-item xvrx-shop-card" data-cat="{{ $catId }}">
                                                <a href="https://www.wowhead.com/ru/item={{ $item->item_entry }}" target="_blank" rel="noopener" class="xvrx-shop-card-icon">
                                                    <img src="{{ $item->icon_name ? 'https://wow.zamimg.com/images/wow/icons/large/' . $item->icon_name . '.jpg' : 'https://wow.zamimg.com/images/wow/icons/large/inv_misc_questionmark.jpg' }}" alt="">
                                                </a>
                                                <div class="xvrx-shop-card-body">
                                                    <h2><a href="https://www.wowhead.com/ru/item={{ $item->item_entry }}" target="_blank" rel="noopener">{{ $item->name_ru ?: '#' . $item->item_entry }}</a></h2>
                                                    <div class="xvrx-shop-card-price">{{ $item->price }} <em class="icon ni ni-coins"></em></div>

                                                    @if(count($characters))
                                                    <select class="form-select form-select-sm xvrx-shop-character-select" id="char-select-{{ $item->id }}" onchange="document.getElementById('buy-btn-{{ $item->id }}').disabled = !this.value">
                                                        <option value="">{{ __('main.shop_select_char') }}</option>
                                                        @foreach($characters as $c)
                                                        <option value="{{ $c->name }}">{{ $c->name }} ({{ $c->level }})</option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-primary xvrx-shop-buy-btn" id="buy-btn-{{ $item->id }}" onclick="buyItem({{ $item->id }})" disabled>{{ __('main.shop_buy') }}</button>
                                                    @else
                                                    <p class="xvrx-shop-empty-character">{{ __('main.no_characters') }}</p>
                                                    @endif
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
    document.querySelectorAll('.shop-item').forEach(function(item) {
        item.style.display = (catId === 'all' || item.dataset.cat === String(catId)) ? '' : 'none';
    });
    document.querySelectorAll('.shop-cat-link').forEach(function(a) {
        a.classList.toggle('is-active', a.dataset.cat === String(catId) || (catId === 'all' && !a.dataset.cat));
    });
}
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
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
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
