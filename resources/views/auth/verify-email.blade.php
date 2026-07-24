@extends('layouts.app')

@section('title', __('main.verify_email_title'))

@section('content')
<div class="nk-wrap nk-wrap-nosidebar">
    <div class="nk-content">
        <div class="nk-split nk-split-page nk-split-md">
            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container xvrx-verify-card bg-white">
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
                            <h5 class="nk-block-title">{{ __('main.verify_email_title') }}</h5>
                            <p class="xvrx-auth-hint">{{ __('main.verify_email_text') }}</p>
                        </div>
                    </div>

                    @if(session('status'))
                        <p class="msg xvrx-msg-success">{{ session('status') }}</p>
                    @endif

                    @auth
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('main.resend_verification') }}</button>
                            </div>
                        </form>
                    @else
                        <div class="form-group">
                            <button type="button" class="btn btn-lg btn-primary btn-block" disabled>{{ __('main.resend_verification') }}</button>
                        </div>
                        <div class="xvrx-auth-links xvrx-auth-links-single">
                            <a href="{{ route('login') }}">{{ __('main.back_to_login') }}</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
