@extends('layouts.app')

@section('title', __('main.voting'))

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
                            <li class="nk-menu-item">
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
                            <li class="nk-menu-item active">
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
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="nk-block-title">{{ __('main.vote_for_server') }}</h4>
                                <p class="text-soft">{{ __('main.vote_description') }}</p>
                            </div>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="nk-block">
                            <div class="row g-gs">
                                @forelse($voteTops as $top)
                                <div class="col-md-6">
                                    <div class="card card-bordered">
                                        <div class="card-inner">
                                            <div class="d-flex align-items-center mb-3">
                                                @if($top->image)
                                                <img src="{{ asset($top->image) }}" alt="{{ $top->name }}" style="height: 40px; margin-right: 15px;">
                                                @endif
                                                <div>
                                                    <h6 class="title mb-0">{{ $top->name }}</h6>
                                                    <span class="text-soft">{{ __('main.reward') }}: {{ $top->bonus_amount }} {{ __('main.bonuses_unit') }}</span>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <a href="{{ $top->url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <em class="icon ni ni-external me-1"></em> {{ __('main.vote') }}
                                                </a>

                                                @if(in_array($top->id, $todayLogs))
                                                <button class="btn btn-success btn-sm" disabled>
                                                    <em class="icon ni ni-check me-1"></em> {{ __('main.claimed') }}
                                                </button>
                                                @else
                                                <form method="POST" action="{{ route('cabinet.votes.claim', $top) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <em class="icon ni ni-gift me-1"></em> {{ __('main.claim_reward') }}
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="card card-bordered">
                                        <div class="card-inner text-center text-soft py-4">
                                            {{ __('main.no_vote_tops') }}
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
