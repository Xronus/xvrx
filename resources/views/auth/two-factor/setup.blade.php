@extends('layouts.app')

@section('title', __('main.two_factor_auth'))

@section('content')
<div class="nk-wrap">
    @include('cabinet.partials.header')

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                @include('cabinet.partials.sidebar', ['active' => 'home'])

                <div class="nk-content-body">
                    <div class="nk-content-wrap xvrx-cabinet-page xvrx-two-factor-page">
                        <div class="nk-block">
                            <div class="xvrx-cabinet-hero">
                                <div>
                                    <span class="xvrx-cabinet-eyebrow">{{ __('main.two_factor_auth') }}</span>
                                    <h4 class="nk-block-title">Google Authenticator</h4>
                                    <p class="text-soft">{{ __('main.two_factor_setup_description') }}</p>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($enabled)
                        {{-- 2FA is already enabled --}}
                        <div class="nk-block">
                            <div class="xvrx-cabinet-action-panel xvrx-two-factor-status">
                                <span class="xvrx-cabinet-action-icon"><em class="icon ni ni-check-circle"></em></span>
                                <div>
                                    <h6>{{ __('main.two_factor_auth') }}</h6>
                                    <p>{{ __('main.two_factor_enabled', ['date' => $user->totp_confirmed_at->format('d.m.Y')]) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="nk-block">
                            <div class="xvrx-two-factor-card">
                                <div class="xvrx-two-factor-card-inner">
                                    <h6 class="title">{{ __('main.two_factor_disable_title') }}</h6>
                                    <p class="text-soft mb-3">{{ __('main.two_factor_disable_description') }}</p>
                                    <form id="disable-2fa-form" method="POST" action="{{ route('admin.2fa.disable') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label" for="disable-code">{{ __('main.two_factor_code') }}</label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                                   id="disable-code" name="code" maxlength="6" inputmode="numeric"
                                                   placeholder="{{ __('main.two_factor_code_placeholder') }}" required>
                                            @error('code')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-danger">{{ __('main.two_factor_disable') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                        {{-- Not set up yet --}}
                        <div class="nk-block" id="setup-section">
                            <div class="xvrx-two-factor-card">
                                <div class="xvrx-two-factor-card-inner xvrx-two-factor-intro" id="setup-initial">
                                    <span class="xvrx-cabinet-action-icon"><em class="icon ni ni-lock-alt"></em></span>
                                    <h5 class="title mb-3">{{ __('main.two_factor_setup_title') }}</h5>
                                    <p class="text-soft mb-4">{{ __('main.two_factor_setup_description') }}</p>
                                    <button type="button" class="btn btn-primary" id="generate-key-btn">
                                        {{ __('main.two_factor_setup_generate') }}
                                    </button>
                                </div>

                                <div class="xvrx-two-factor-card-inner" id="setup-qr" style="display: none;">
                                    <div class="xvrx-two-factor-grid">
                                        <div class="xvrx-two-factor-qr-panel">
                                            <h6 class="title">{{ __('main.two_factor_setup_step1') }}</h6>
                                            <div id="qr-code-container" class="xvrx-two-factor-qr"></div>
                                            <p class="text-soft small mt-2">{{ __('main.two_factor_setup_manual') }}</p>
                                            <code id="manual-secret" class="xvrx-two-factor-secret"></code>
                                        </div>

                                        <div class="xvrx-two-factor-confirm">
                                            <h6 class="title">{{ __('main.two_factor_setup_step2') }}</h6>
                                            <form id="confirm-setup-form" class="mt-3">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="text" class="form-control text-center xvrx-two-factor-code-input"
                                                           id="confirm-code" name="code" maxlength="6" inputmode="numeric"
                                                           placeholder="{{ __('main.two_factor_code_placeholder') }}" required>
                                                    <span class="invalid-feedback d-block" id="confirm-error" style="display: none !important;"></span>
                                                </div>
                                                <button type="submit" class="btn btn-success mt-2">
                                                    {{ __('main.two_factor_setup_confirm') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Recovery codes modal (shown after successful setup) --}}
                        <div class="nk-block" id="recovery-section" style="display: none;">
                            <div class="xvrx-two-factor-card xvrx-two-factor-recovery">
                                <div class="xvrx-two-factor-card-inner">
                                    <h6 class="title text-warning">{{ __('main.two_factor_recovery_title') }}</h6>
                                    <p class="text-soft">{{ __('main.two_factor_recovery_description') }}</p>
                                    <div class="xvrx-two-factor-recovery-list" id="recovery-codes-list"></div>
                                    <button type="button" class="btn btn-primary" id="copy-recovery-btn">
                                        {{ __('main.copy') }}
                                    </button>
                                    <a href="{{ route('admin.2fa.challenge') }}" class="btn btn-success ms-2">
                                        {{ __('main.two_factor_recovery_done') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var setupInitial = document.getElementById('setup-initial');
        var setupQr = document.getElementById('setup-qr');
        var recoverySection = document.getElementById('recovery-section');
        var generateBtn = document.getElementById('generate-key-btn');
        var confirmForm = document.getElementById('confirm-setup-form');
        var confirmError = document.getElementById('confirm-error');

        if (generateBtn) {
            generateBtn.addEventListener('click', function () {
                generateBtn.disabled = true;
                generateBtn.textContent = '{{ __("main.loading") }}...';

                fetch('{{ route('admin.2fa.generate') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        document.getElementById('qr-code-container').innerHTML = data.qr_code
                            ? '<img src="' + data.qr_code + '" alt="QR Code" class="xvrx-two-factor-qr-image">'
                            : '';
                        document.getElementById('manual-secret').textContent = data.secret;
                        setupInitial.style.display = 'none';
                        setupQr.style.display = '';
                    } else {
                        alert(data.message || 'Error generating key.');
                    }
                })
                .catch(function () {
                    alert('{{ __("main.error_occurred") }}');
                })
                .finally(function () {
                    generateBtn.disabled = false;
                    generateBtn.textContent = '{{ __("main.two_factor_setup_generate") }}';
                });
            });
        }

        if (confirmForm) {
            confirmForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var code = document.getElementById('confirm-code').value;
                if (code.length !== 6) return;

                var btn = confirmForm.querySelector('button[type="submit"]');
                btn.disabled = true;

                fetch('{{ route('admin.2fa.confirm') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ code: code }),
                })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        setupQr.style.display = 'none';
                        document.getElementById('setup-section').style.display = 'none';

                        // Show recovery codes
                        var list = document.getElementById('recovery-codes-list');
                        list.innerHTML = '';
                        data.recovery_codes.forEach(function (c) {
                            list.innerHTML += '<code class="xvrx-two-factor-recovery-code">' + c + '</code>';
                        });
                        recoverySection.style.display = '';

                        // Setup copy button
                        document.getElementById('copy-recovery-btn').addEventListener('click', function () {
                            navigator.clipboard.writeText(data.recovery_codes.join('\n')).then(function () {
                                alert('{{ __("main.copied") }}');
                            });
                        });
                    } else {
                        confirmError.textContent = data.message;
                        confirmError.style.display = 'block !important';
                    }
                })
                .catch(function () {
                    confirmError.textContent = '{{ __("main.error_occurred") }}';
                    confirmError.style.display = 'block !important';
                })
                .finally(function () {
                    btn.disabled = false;
                });
            });
        }

        // Auto-redirect to setup if 2FA already enabled
        @if($enabled)
        // Show the disabled state, user can disable or go to challenge
        @endif
    });
</script>
@endpush
