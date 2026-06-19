@extends('layouts.app')

@section('title', __('main.personal_account'))

@section('content')
<div class="nk-wrap">
    <div class="nk-header nk-header-fixed is-light">
        <div class="container-lg wide-xl">
            <div class="nk-header-wrap">
                <div class="nk-header-brand">
                    <a href="{{ route('home') }}" class="logo-link">
                        @php
                            $logoPath = isset($settings) && $settings && $settings->logo_path ? $settings->logo_path : 'powerpuffsite/images/main/logo.png';
                        @endphp
                        <img class="logo-light logo-img" src="{{ asset($logoPath) }}">
                    </a>
                </div>
                <div class="nk-header-tools">
                    <ul class="nk-quick-nav">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: inline-block; margin: 0;">
                                @csrf
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-sm btn-outline-light" style="border: 1px solid rgba(255,255,255,0.2); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                                    <em class="icon ni ni-signout"></em>
                                    <span>{{ __('main.logout') }}</span>
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                <div class="nk-aside bg-transparent" data-content="sideNav" data-toggle-overlay="true" data-toggle-screen="lg" data-toggle-body="true">
                    <div class="nk-sidebar-menu" data-simplebar>
                        <ul class="nk-menu">
                            <li class="nk-menu-item active">
                                <a href="{{ route('cabinet') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-home"></em></span>
                                    <span class="nk-menu-text">{{ __('main.homepage') }}</span>
                                </a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('cabinet.characters') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                    <span class="nk-menu-text">{{ __('main.characters') }}</span>
                                </a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('cabinet.votes') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-star"></em></span>
                                    <span class="nk-menu-text">{{ __('main.voting') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="nk-content-body">
                    <div class="nk-content-wrap">
                        <div class="nk-block">
                            <div class="row g-gs">
                                <div class="col-sm-6">
                                    <div class="card card-bordered xvrx-cabinet-summary-card">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-2">
                                                <div class="card-title">
                                                    <h6 class="title">{{ __('main.bonuses') }}</h6>
                                                </div>
                                            </div>
                                            <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                <div class="nk-sale-data">
                                                    <span class="amount text-primary">{{ $user->bonuses ?? 0 }} <em class="icon ni ni-coins"></em></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-bordered xvrx-cabinet-summary-card">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-2">
                                                <div class="card-title">
                                                    <h6 class="title">{{ __('main.characters_count') }}</h6>
                                                </div>
                                            </div>
                                            <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                <div class="nk-sale-data">
                                                    <span class="amount mt-1">{{ count($characters) }} <em class="icon ni ni-users"></em></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @php
                            $isAdmin = false;
                            if (isset($user->is_admin)) {
                                $isAdminValue = $user->is_admin;
                                if (is_string($isAdminValue)) {
                                    $isAdmin = $isAdminValue === '1' || $isAdminValue === 'true';
                                } elseif (is_int($isAdminValue)) {
                                    $isAdmin = $isAdminValue === 1;
                                } elseif (is_bool($isAdminValue)) {
                                    $isAdmin = $isAdminValue === true;
                                }
                            }
                        @endphp
                        @if($isAdmin)
                        <div class="nk-block">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-2">
                                        <div class="card-title">
                                            <h6 class="title">{{ __('main.admin_panel') }}</h6>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ route('admin.index') }}" class="btn btn-lg btn-primary btn-block">{{ __('main.go_to_admin') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="nk-block">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-help">
                                        <div class="nk-help-img">
                                            <em class="icon ni ni-help-alt" style="font-size: 96px;"></em>
                                        </div>
                                        <div class="nk-help-text">
                                            <h5>{{ __('main.need_help') }}</h5>
                                            <p class="text-soft">{{ __('main.need_help_text') }}</p>
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
</div>
@endsection
