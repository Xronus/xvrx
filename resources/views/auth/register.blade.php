@extends('layouts.app')

@section('title', __('main.registration'))

@push('styles')
<style>
.register-btn.btn-loading { opacity: .65; pointer-events: none; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="nk-wrap nk-wrap-nosidebar">
    <div class="nk-content">
        <div class="nk-split nk-split-page nk-split-md">
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                <div class="nk-block nk-block-middle nk-auth-body">
                    <div class="brand-logo pb-5">
                        <a href="{{ route('home') }}" class="logo-link">
                            @php
                                $logoPath = isset($settings) && $settings && $settings->logo_path ? $settings->logo_path : 'powerpuffsite/images/main/logo.png';
                            @endphp
                            <img class="logo-light logo-img" src="{{ asset($logoPath) }}" alt="logo">
                        </a>
                    </div>
                    <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title">{{ __('main.registration') }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-12 tabs">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <form id="registerForm">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label" for="name">{{ __('main.login_field') }}<small style="display: block;font-size: 11px;">({{ __('main.login_hint') }})</small></label>
                                        <input type="text" class="form-control form-control-lg" id="name" name="username" placeholder="{{ __('main.enter_your_login') }}" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="email">{{ __('main.email') }}</label>
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="{{ __('main.enter_email') }}" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="password">{{ __('main.password') }} <small style="display: block;font-size: 11px;">({{ __('main.password_hint') }})</small></label>
                                        <div class="form-control-wrap">
                                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch is-hidden" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg is-hidden" id="password" name="password" placeholder="{{ __('main.enter_password') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="password_confirmation">{{ __('main.confirm_password') }}</label>
                                        <div class="form-control-wrap">
                                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch" data-target="password_confirmation">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="{{ __('main.enter_password_again') }}" required>
                                        </div>
                                    </div>
                                    @if(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
                                    <div class="form-group">
                                        <div id="register-turnstile"></div>
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <div class="custom-control custom-control-xs custom-checkbox flex-wrap">
                                            <input type="checkbox" class="custom-control-input" id="ok" name="ok" value="1" checked required>
                                            <label class="custom-control-label" for="ok">
                                                {{ __('main.agree_to') }} <a tabindex="-1" href="{{ route('terms') }}">{{ __('main.terms_of_service') }}</a> &amp;
                                                <a tabindex="-1" href="{{ route('policy') }}">{{ __('main.privacy_policy') }}</a>.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block register-btn">{{ __('main.register') }}</button>
                                    </div>
                                    <p class="msg none"></p>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="form-note-s2 pt-4">
                        {{ __('main.have_account') }}
                        <a href="{{ route('login') }}"><strong>{{ __('main.sign_in_link') }}</strong></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(config('captcha.method') === 'google' && config('recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
@endif
@if(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
<script>
$(document).ready(function() {
    var registerTurnstileWidgetId = null;
    @if(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
    if (typeof turnstile !== 'undefined') {
        registerTurnstileWidgetId = turnstile.render('#register-turnstile', { sitekey: '{{ config('turnstile.site_key') }}' });
    } else {
        window.addEventListener('load', function() {
            if (document.getElementById('register-turnstile') && typeof turnstile !== 'undefined') {
                registerTurnstileWidgetId = turnstile.render('#register-turnstile', { sitekey: '{{ config('turnstile.site_key') }}' });
            }
        });
    }
    @endif

    function getFirstError(errors) {
        var order = ['username', 'email', 'password', 'password_confirmation', 'recaptcha_token'];
        for (var i = 0; i < order.length; i++) {
            var field = order[i];
            if (errors && errors[field] && errors[field][0]) {
                return errors[field][0];
            }
        }

        if (errors) {
            var keys = Object.keys(errors);
            for (var j = 0; j < keys.length; j++) {
                if (errors[keys[j]] && errors[keys[j]][0]) {
                    return errors[keys[j]][0];
                }
            }
        }

        return '{{ __("main.validation_error") }}';
    }

    function doRegister(captchaToken) {
        var $btn = $('.register-btn');
        $btn.prop('disabled', true).addClass('btn-loading').html('<span class="spinner-border spinner-border-sm me-2"></span>{{ __("main.registering") }}');
        $('.msg').addClass('none');
        var formData = new FormData();
        formData.append('username', $('input[name="username"]').val());
        formData.append('password', $('input[name="password"]').val());
        formData.append('password_confirmation', $('input[name="password_confirmation"]').val());
        formData.append('email', $('input[name="email"]').val());
        formData.append('recaptcha_token', captchaToken || '');
        formData.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url: '{{ route("register") }}',
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            complete: function() {
                $btn.prop('disabled', false).removeClass('btn-loading').text('{{ __("main.register") }}');
            },
            success: function(data) {
                if (data.status) {
                    $('.msg').removeClass('none').text(data.message);
                    var redirect = data.redirect;
                    setTimeout(function () {
                        if (redirect && (redirect.startsWith('/') || redirect.startsWith(window.location.origin))) {
                            document.location.href = redirect;
                        } else {
                            document.location.href = '{{ route("cabinet") }}';
                        }
                    }, 1500);
                } else {
                    if (data.type === 1 && data.fields) {
                        data.fields.forEach(function (field) {
                            $('input[name="' + field + '"]').addClass('error');
                        });
                    }
                    $('.msg').removeClass('none').text(data.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(function(field) {
                            $('input[name="' + field + '"]').addClass('error');
                        });
                        $('.msg').removeClass('none').text(getFirstError(errors));
                    } else {
                        $('.msg').removeClass('none').text('{{ __("main.validation_error") }}');
                    }
                } else {
                    $('.msg').removeClass('none').text((xhr.responseJSON && xhr.responseJSON.message) || '{{ __("main.server_error") }}');
                }
            }
        });
    }

    $('.register-btn').click(function (e) {
        e.preventDefault();
        $('input').removeClass('error');

        @if(config('captcha.method') === 'google' && config('recaptcha.site_key'))
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('recaptcha.site_key') }}', {action: 'register'}).then(function(token) {
                doRegister(token);
            });
        });
        @elseif(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
        var token = (typeof turnstile !== 'undefined' && registerTurnstileWidgetId !== null) ? turnstile.getResponse(registerTurnstileWidgetId) : '';
        if (!token) {
            $('.msg').removeClass('none').text('{{ __("main.captcha_validation_error") }}');
            return;
        }
        doRegister(token);
        @else
        doRegister('');
        @endif
    });
});
</script>
@endpush
