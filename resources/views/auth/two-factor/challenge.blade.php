@extends('layouts.app')

@section('title', __('main.two_factor_challenge_title'))

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
                                    <h4 class="nk-block-title">{{ __('main.two_factor_challenge_title') }}</h4>
                                    <p class="text-soft">{{ __('main.two_factor_challenge_description') }}</p>
                                </div>
                            </div>
                        </div>

                        @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="nk-block">
                            <div class="xvrx-two-factor-card">
                                <div class="xvrx-two-factor-card-inner xvrx-two-factor-challenge-card">
                                    <div class="xvrx-two-factor-intro">
                                        <span class="xvrx-cabinet-action-icon"><em class="icon ni ni-lock-alt"></em></span>
                                        <h5 class="title">{{ __('main.two_factor_code') }}</h5>
                                        <p class="text-soft">{{ __('main.two_factor_challenge_description') }}</p>
                                    </div>

                                    {{-- TOTP code form --}}
                                    <form method="POST" action="{{ route('admin.2fa.verify') }}" id="challenge-form" class="xvrx-two-factor-form">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text"
                                                   class="form-control text-center xvrx-two-factor-code-input @error('code') is-invalid @enderror"
                                                   name="code" id="challenge-code"
                                                   maxlength="6" inputmode="numeric" autocomplete="one-time-code"
                                                   placeholder="{{ __('main.two_factor_code_placeholder') }}"
                                                   required autofocus>
                                            @error('code')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary xvrx-two-factor-primary" id="verify-btn">
                                            {{ __('main.two_factor_verify') }}
                                        </button>
                                    </form>

                                    {{-- Recovery code form (hidden by default) --}}
                                    <div id="recovery-form-wrapper" class="xvrx-two-factor-recovery-form" style="display: none;">
                                        <p class="text-soft text-center">{{ __('main.two_factor_recovery_description_short') }}</p>
                                        <form method="POST" action="{{ route('admin.2fa.verify') }}" class="xvrx-two-factor-form">
                                            @csrf
                                            <div class="form-group">
                                                <input type="text" class="form-control text-center"
                                                       name="code" id="recovery-code"
                                                       placeholder="{{ __('main.two_factor_recovery_code_placeholder') }}"
                                                       maxlength="255">
                                            </div>
                                            <button type="submit" class="btn btn-outline-warning mt-2">
                                                {{ __('main.two_factor_recovery_use') }}
                                            </button>
                                        </form>
                                    </div>

                                    <div class="xvrx-two-factor-secondary-actions">
                                        <button type="button" class="xvrx-two-factor-ghost" id="toggle-recovery">
                                            {{ __('main.two_factor_recovery_use') }}
                                        </button>
                                        <a href="{{ route('cabinet') }}" class="xvrx-two-factor-ghost">
                                            &larr; {{ __('main.back_to_cabinet') }}
                                        </a>
                                    </div>
                                </div>
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
    document.getElementById('toggle-recovery').addEventListener('click', function () {
        var wrapper = document.getElementById('recovery-form-wrapper');
        wrapper.style.display = wrapper.style.display === 'none' ? '' : 'none';
    });

    // Auto-focus first digit and auto-submit on 6 digits
    var challengeInput = document.getElementById('challenge-code');
    if (challengeInput) {
        challengeInput.addEventListener('input', function () {
            if (this.value.length === 6) {
                document.getElementById('verify-btn').disabled = true;
                document.getElementById('challenge-form').submit();
            }
        });

        // Only allow digits
        challengeInput.addEventListener('keypress', function (e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    }
</script>
@endpush
