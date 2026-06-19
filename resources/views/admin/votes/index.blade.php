@extends('admin.layout')

@section('title', __('main.voting'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.voting_settings') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <img src="https://mmorating.top/favicon.ico" alt="" style="height: 24px; margin-right: 10px;">
                    <h5 class="card-title mb-0">mmorating.top</h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.votes.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.voting_page_url') }}</label>
                                <input type="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $voteTop->url ?? '') }}" placeholder="https://mmorating.top/server/123">
                                @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">{{ __('main.vote_url_hint') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.api_key') }}</label>
                                <input type="text" name="api_key" class="form-control @error('api_key') is-invalid @enderror" value="{{ old('api_key', $voteTop->api_key ?? '') }}" placeholder="mmr_...">
                                @error('api_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">{{ __('main.api_key_hint') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">{{ __('main.bonuses_per_vote') }}</label>
                                <input type="number" name="bonus_amount" class="form-control @error('bonus_amount') is-invalid @enderror" value="{{ old('bonus_amount', $voteTop->bonus_amount ?? 1) }}" min="1">
                                @error('bonus_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 pt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $voteTop->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">{{ __('main.enabled') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ __('main.save_settings') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.instructions') }}</h5>
            </div>
            <div class="card-body">
                <ol class="mb-0" style="padding-left: 18px;">
                    <li class="mb-2">{{ __('main.vote_instruction_1') }} <a href="https://mmorating.top" target="_blank">mmorating.top</a></li>
                    <li class="mb-2">{{ __('main.vote_instruction_2') }}</li>
                    <li class="mb-2">{{ __('main.vote_instruction_3') }}</li>
                    <li class="mb-2">{{ __('main.vote_instruction_4') }}</li>
                    <li class="mb-2">{{ __('main.vote_instruction_5') }} <code>mmr_</code>{{ __('main.vote_instruction_5b') }}</li>
                    <li>{{ __('main.vote_instruction_6') }}</li>
                </ol>
            </div>
        </div>

        @if($voteTop && $voteTop->id)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.status') }}</h5>
            </div>
            <div class="card-body">
                <p><strong>API URL:</strong> <code>{{ $voteTop->api_url }}</code></p>
                <p><strong>{{ __('main.active') }}:</strong>
                    <span class="badge {{ $voteTop->is_active ? 'bg-success' : 'bg-secondary' }}">
                        {{ $voteTop->is_active ? __('main.yes') : __('main.no') }}
                    </span>
                </p>
                <p class="mb-0"><strong>{{ __('main.api_key') }}:</strong>
                    @if($voteTop->api_key)
                    <span class="badge bg-success">{{ __('main.configured') }}</span>
                    @else
                    <span class="badge bg-warning">{{ __('main.not_configured') }}</span>
                    @endif
                </p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
