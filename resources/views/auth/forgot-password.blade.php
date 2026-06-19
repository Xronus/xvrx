@extends('layouts.app')

@section('title', 'Восстановление пароля')

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
                            <h5 class="nk-block-title">Восстановление пароля</h5>
                            <p class="xvrx-auth-hint">Укажите email учетной записи. Если аккаунт существует, мы подготовим ссылку для восстановления.</p>
                        </div>
                    </div>

                    @if(session('status'))
                        <p class="msg xvrx-msg-success">{{ session('status') }}</p>
                    @endif

                    @if($errors->any())
                        <p class="msg">{{ $errors->first() }}</p>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="email">Email</label>
                            </div>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Введите email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary btn-block">Отправить ссылку</button>
                        </div>
                        <div class="xvrx-auth-links xvrx-auth-links-single">
                            <a href="{{ route('login') }}">Назад ко входу</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
