@extends('layouts.app')

@section('title', __('main.reset_password'))

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
                            <h5 class="nk-block-title">{{ __('main.reset_password') }}</h5>
                            <p class="xvrx-auth-hint">{{ __('main.reset_password_hint') }}</p>
                        </div>
                    </div>

                    @if(session('status'))
                        <p class="msg xvrx-msg-success">{{ session('status') }}</p>
                    @endif

                    @if($errors->any())
                        <p class="msg">{{ $errors->first() }}</p>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">{{ __('main.new_password') }}</label>
                            </div>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="{{ __('main.enter_password') }}" required autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password_confirmation">{{ __('main.confirm_new_password') }}</label>
                            </div>
                            <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="{{ __('main.enter_password_again') }}" required autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('main.reset_password_button') }}</button>
                        </div>
                        <div class="xvrx-auth-links xvrx-auth-links-single">
                            <a href="{{ route('login') }}">{{ __('main.back_to_login') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
