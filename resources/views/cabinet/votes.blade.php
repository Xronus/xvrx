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
