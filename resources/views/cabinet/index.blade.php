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
