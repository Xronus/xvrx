@extends('layouts.app')

@section('title', __('main.top_up_title'))

@section('content')
@include('partials._game_ban_warning')
<div class="nk-wrap">
    @include('cabinet.partials.header')

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                @include('cabinet.partials.sidebar', ['active' => 'shop'])

                <div class="nk-content-body">
                    <div class="nk-content-wrap xvrx-cabinet-page xvrx-topup-page">
                        <div class="xvrx-topup-head">
                            <div>
                                <h1>{{ __('main.top_up_title') }}</h1>
                                <p>{{ __('main.top_up_description') }}</p>
                            </div>
                            <div class="xvrx-topup-current">
                                <span>{{ __('main.shop_balance') }}</span>
                                <strong>{{ $user->bonuses ?? 0 }} <em class="icon ni ni-coins"></em></strong>
                            </div>
                        </div>

                        @foreach(['success', 'status', 'error'] as $flashType)
                            @if(session($flashType))
                                <div class="xvrx-topup-alert is-{{ $flashType }}" role="status">
                                    <em class="icon ni {{ $flashType === 'success' ? 'ni-check-circle' : ($flashType === 'error' ? 'ni-alert-circle' : 'ni-info') }}"></em>
                                    <span>{{ session($flashType) }}</span>
                                </div>
                            @endif
                        @endforeach

                        @if($errors->any())
                            <div class="xvrx-topup-alert is-error" role="alert">
                                <em class="icon ni ni-alert-circle"></em>
                                <span>{{ $errors->first() }}</span>
                            </div>
                        @endif

                        <div class="xvrx-topup-surface">
                            <section class="xvrx-topup-form-section" aria-labelledby="topup-form-title">
                                <h2 id="topup-form-title">{{ __('main.top_up_amount') }}</h2>

                                @if($configured)
                                    <form method="POST" action="{{ route('balance.topup.store') }}" id="topup-form">
                                        @csrf
                                        <label class="xvrx-topup-input-wrap" for="topup-amount">
                                            <input
                                                type="number"
                                                id="topup-amount"
                                                name="amount"
                                                min="{{ $minAmount }}"
                                                max="{{ $maxAmount }}"
                                                step="1"
                                                inputmode="numeric"
                                                value="{{ old('amount', 500) }}"
                                                required
                                                aria-describedby="topup-limits"
                                            >
                                            <span>₽</span>
                                        </label>
                                        <p class="xvrx-topup-limits" id="topup-limits">
                                            {{ __('main.top_up_amount_limits', ['min' => $minAmount, 'max' => number_format($maxAmount, 0, ',', ' ')]) }}
                                        </p>

                                        <div class="xvrx-topup-presets" aria-label="{{ __('main.top_up_quick_amount') }}">
                                            @foreach([100, 500, 1000, 2500] as $preset)
                                                @if($preset >= $minAmount && $preset <= $maxAmount)
                                                    <button type="button" data-topup-amount="{{ $preset }}">{{ number_format($preset, 0, ',', ' ') }} ₽</button>
                                                @endif
                                            @endforeach
                                        </div>

                                        <div class="xvrx-topup-result" aria-live="polite">
                                            <span>{{ __('main.top_up_you_receive') }}</span>
                                            <strong><span id="topup-bonus-preview">0</span> <em class="icon ni ni-coins"></em></strong>
                                        </div>

                                        <button type="submit" class="btn btn-primary xvrx-topup-submit" id="topup-submit">
                                            <span>{{ __('main.top_up_continue') }}</span>
                                            <em class="icon ni ni-arrow-right"></em>
                                        </button>
                                        <p class="xvrx-topup-redirect-note">
                                            <em class="icon ni ni-shield-check"></em>
                                            {{ __('main.top_up_redirect_note') }}
                                        </p>
                                    </form>
                                @else
                                    <div class="xvrx-topup-unavailable">
                                        <em class="icon ni ni-setting-alt"></em>
                                        <div>
                                            <h3>{{ __('main.top_up_not_configured') }}</h3>
                                            <p>{{ __('main.top_up_not_configured_help') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </section>

                            <aside class="xvrx-topup-details">
                                <div>
                                    <span>{{ __('main.top_up_rate') }}</span>
                                    <strong>1 ₽ = {{ $bonusPerRuble }} <em class="icon ni ni-coins"></em></strong>
                                </div>
                                <p>{{ __('main.top_up_credit_note') }}</p>
                            </aside>
                        </div>

                        <section class="xvrx-topup-history" aria-labelledby="topup-history-title">
                            <div class="xvrx-topup-history-head">
                                <h2 id="topup-history-title">{{ __('main.top_up_history') }}</h2>
                                <span>{{ __('main.top_up_last_operations') }}</span>
                            </div>

                            @if($topUps->isEmpty())
                                <p class="xvrx-topup-empty">{{ __('main.top_up_history_empty') }}</p>
                            @else
                                <div class="xvrx-topup-table-wrap">
                                    <table class="xvrx-topup-table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('main.top_up_date') }}</th>
                                                <th>{{ __('main.top_up_amount') }}</th>
                                                <th>{{ __('main.bonuses') }}</th>
                                                <th>{{ __('main.top_up_status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topUps as $topUp)
                                                @php
                                                    $statusLabel = match($topUp->status) {
                                                        \App\Models\BalanceTopUp::STATUS_SUCCEEDED => __('main.top_up_status_succeeded'),
                                                        \App\Models\BalanceTopUp::STATUS_CANCELED => __('main.top_up_status_canceled'),
                                                        \App\Models\BalanceTopUp::STATUS_FAILED => __('main.top_up_status_failed'),
                                                        default => __('main.top_up_status_pending'),
                                                    };
                                                @endphp
                                                <tr>
                                                    <td>{{ $topUp->created_at->format('d.m.Y H:i') }}</td>
                                                    <td>{{ number_format($topUp->amount_kopecks / 100, 2, ',', ' ') }} ₽</td>
                                                    <td>+{{ number_format($topUp->bonus_amount, 0, ',', ' ') }}</td>
                                                    <td><span class="xvrx-topup-status is-{{ $topUp->status }}">{{ $statusLabel }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if($configured)
@push('scripts')
<script>
(function () {
    var amount = document.getElementById('topup-amount');
    var preview = document.getElementById('topup-bonus-preview');
    var form = document.getElementById('topup-form');
    var submit = document.getElementById('topup-submit');
    var rate = {{ $bonusPerRuble }};
    var formatter = new Intl.NumberFormat(document.documentElement.lang || 'ru-RU');

    function updatePreview() {
        var value = Math.max(0, Number.parseInt(amount.value, 10) || 0);
        preview.textContent = formatter.format(value * rate);
    }

    document.querySelectorAll('[data-topup-amount]').forEach(function (button) {
        button.addEventListener('click', function () {
            amount.value = button.dataset.topupAmount;
            amount.focus();
            updatePreview();
        });
    });

    amount.addEventListener('input', updatePreview);
    form.addEventListener('submit', function () {
        submit.disabled = true;
        submit.classList.add('is-loading');
        submit.querySelector('span').textContent = @json(__('main.top_up_redirecting'));
    });
    updatePreview();
})();
</script>
@endpush
@endif
