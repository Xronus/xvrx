@extends('layouts.app')

@section('title', __('main.login'))

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
                            <h5 class="nk-block-title">{{ __('main.login') }}</h5>
                        </div>
                    </div>

                    <div class="col-sm-12 tabs">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <form id="loginForm">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="username">{{ __('main.login_field') }}</label>
                                        </div>
                                        <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="{{ __('main.enter_login') }}" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">{{ __('main.password') }}</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch is-hidden" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input type="password" class="form-control form-control-lg is-hidden" id="password" name="password" placeholder="{{ __('main.enter_password') }}" required>
                                        </div>
                                    </div>
                                    @if(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
                                    <div class="form-group">
                                        <div id="login-turnstile"></div>
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block login-btn">{{ __('main.sign_in') }}</button>
                                    </div>
                                    <div class="xvrx-auth-links">
                                        <a href="{{ route('password.request') }}">Забыл пароль?</a>
                                        <a href="{{ route('register') }}">{{ __('main.register') }}</a>
                                    </div>
                                    <p class="msg none"></p>
                                </form>
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
@if(config('captcha.method') === 'google' && config('recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
@endif
@if(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endif
<script>
$(document).ready(function() {
    var loginTurnstileWidgetId = null;
    @if(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
    if (typeof turnstile !== 'undefined') {
        loginTurnstileWidgetId = turnstile.render('#login-turnstile', { sitekey: '{{ config('turnstile.site_key') }}' });
    } else {
        window.addEventListener('load', function() {
            if (document.getElementById('login-turnstile') && typeof turnstile !== 'undefined') {
                loginTurnstileWidgetId = turnstile.render('#login-turnstile', { sitekey: '{{ config('turnstile.site_key') }}' });
            }
        });
    }
    @endif

    function getFirstError(errors) {
        var order = ['username', 'password', 'recaptcha_token'];
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

    function doLogin(captchaToken) {
        var username = $('input[name="username"]').val();
        var password = $('input[name="password"]').val();
        var data = {
            username: username,
            password: password,
            recaptcha_token: captchaToken || '',
            _token: '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ route("login") }}',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(data) {
                if (data.status) {
                    document.location.href = data.redirect || '{{ route("cabinet") }}';
                } else {
                    $('.msg').removeClass('none').text(data.message || '{{ __("main.login_error") }}');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors && errors.username) {
                        $('input[name="username"]').addClass('error');
                    }
                    if (errors && errors.password) {
                        $('input[name="password"]').addClass('error');
                    }
                    $('.msg').removeClass('none').text(getFirstError(errors));
                } else {
                    $('.msg').removeClass('none').text('{{ __("main.server_error") }}');
                }
            }
        });
    }

    $('.login-btn').click(function (e) {
        e.preventDefault();
        $('input').removeClass('error');

        @if(config('captcha.method') === 'google' && config('recaptcha.site_key'))
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('recaptcha.site_key') }}', {action: 'login'}).then(function(token) {
                doLogin(token);
            });
        });
        @elseif(config('captcha.method') === 'cloudflare' && config('turnstile.site_key'))
        var token = (typeof turnstile !== 'undefined' && loginTurnstileWidgetId !== null) ? turnstile.getResponse(loginTurnstileWidgetId) : '';
        if (!token) {
            $('.msg').removeClass('none').text('{{ __("main.captcha_validation_error") }}');
            return;
        }
        doLogin(token);
        @else
        doLogin('');
        @endif
    });
});
</script>
@endpush
