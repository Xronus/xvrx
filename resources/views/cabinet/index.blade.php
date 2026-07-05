@extends('layouts.app')

@section('title', __('main.personal_account'))

@section('content')
@include('partials._game_ban_warning')
<div class="nk-wrap">
    @include('cabinet.partials.header')

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                @include('cabinet.partials.sidebar', ['active' => 'home'])

                <div class="nk-content-body">
                    <div class="nk-content-wrap xvrx-cabinet-page">
                        <div class="nk-block">
                            <div class="xvrx-cabinet-hero">
                                <div>
                                    <span class="xvrx-cabinet-eyebrow">{{ __('main.personal_account') }}</span>
                                    <h4 class="nk-block-title">{{ __('main.homepage') }}</h4>
                                    <p class="text-soft">{{ __('main.need_help_text') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="nk-block">
                            <div class="row g-gs xvrx-cabinet-stats">
                                <div class="col-sm-6">
                                    <div class="xvrx-cabinet-stat-card">
                                        <span class="xvrx-cabinet-stat-icon"><em class="icon ni ni-coins"></em></span>
                                        <div>
                                            <span class="xvrx-shop-panel-label">{{ __('main.bonuses') }}</span>
                                            <strong><span class="xvrx-shop-balance-value">{{ $user->bonuses ?? 0 }}</span></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="xvrx-cabinet-stat-card">
                                        <span class="xvrx-cabinet-stat-icon"><em class="icon ni ni-users"></em></span>
                                        <div>
                                            <span class="xvrx-shop-panel-label">{{ __('main.characters_count') }}</span>
                                            <strong>{{ count($characters) }}</strong>
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
                            <div class="xvrx-cabinet-action-panel">
                                <span class="xvrx-cabinet-action-icon"><em class="icon ni ni-shield-star"></em></span>
                                <div>
                                    <h6>{{ __('main.admin_panel') }}</h6>
                                    <p>{{ __('main.go_to_admin') }}</p>
                                </div>
                                <a href="{{ route('admin.index') }}" class="btn btn-primary">{{ __('main.go_to_admin') }}</a>
                            </div>
                        </div>
                        @endif

                        <div class="nk-block">
                            <div class="xvrx-cabinet-help-panel">
                                <span class="xvrx-cabinet-action-icon"><em class="icon ni ni-help-alt"></em></span>
                                <div>
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
@endsection
