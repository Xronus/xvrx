@extends('layouts.app')

@section('title', __('main.voting'))

@section('content')
@include('partials._game_ban_warning')
<div class="nk-wrap">
    @include('cabinet.partials.header')

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                @include('cabinet.partials.sidebar', ['active' => 'votes'])

                <div class="nk-content-body">
                    <div class="nk-content-wrap xvrx-cabinet-page">
                        <div class="nk-block-head xvrx-cabinet-head">
                            <div class="nk-block-head-content">
                                <span class="xvrx-cabinet-eyebrow">{{ __('main.personal_account') }}</span>
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
                            <div class="xvrx-vote-grid">
                                @forelse($voteTops as $top)
                                <article class="xvrx-vote-card">
                                    <div class="xvrx-vote-card-main">
                                        <div class="xvrx-vote-logo">
                                            @if($top->image)
                                            <img src="{{ asset($top->image) }}" alt="{{ $top->name }}">
                                            @else
                                            <em class="icon ni ni-star"></em>
                                            @endif
                                        </div>
                                        <div>
                                            <h5>{{ $top->name }}</h5>
                                            <div class="xvrx-vote-reward">
                                                <em class="icon ni ni-gift"></em>
                                                {{ __('main.reward') }}: {{ $top->bonus_amount }} {{ __('main.bonuses_unit') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="xvrx-vote-actions">
                                        <a href="{{ $top->url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <em class="icon ni ni-external me-1"></em> {{ __('main.vote') }}
                                        </a>

                                        @if(in_array($top->id, $todayLogs))
                                        <button class="btn btn-success btn-sm xvrx-vote-claimed" disabled>
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
                                </article>
                                @empty
                                <div class="xvrx-cabinet-empty">
                                    <span><em class="icon ni ni-star"></em></span>
                                    <p>{{ __('main.no_vote_tops') }}</p>
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
