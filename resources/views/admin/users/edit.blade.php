@extends('admin.layout')

@section('title', __('main.edit_user'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.edit_user') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line me-1"></i> {{ __('main.back') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form id="user-edit-form" action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.login_field') }}</label>
                        <input type="text" class="form-control" value="{{ $user->username }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.email') }}</label>
                        <input type="text" class="form-control" value="{{ $user->email ?? '-' }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('main.email_verified_at') }}</label>
                        <input type="text" class="form-control" value="{{ $user->email_verified_at ? $user->email_verified_at->format('d.m.Y H:i') : __('main.not_verified') }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="bonuses">{{ __('main.bonuses') }}</label>
                        <input type="number" name="bonuses" id="bonuses" class="form-control @error('bonuses') is-invalid @enderror" value="{{ old('bonuses', $user->bonuses) }}" min="0">
                        @error('bonuses')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="is_admin" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_admin">{{ __('main.admin') }}</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('main.save') }}</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Ban cards --}}
    <div class="col-md-6">
        {{-- Site ban --}}
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.site_ban_title') }}</h5>
            </div>
            <div class="card-body">
                @if($user->isBanned())
                <div class="alert alert-warning mb-3">
                    <strong>{{ __('main.banned') }}</strong>
                    @if($user->ban_reason)<p class="mb-0 mt-1">{{ $user->ban_reason }}</p>@endif
                    <small class="d-block mt-1">{{ __('main.since') }} {{ $user->banned_at->format('d.m.Y H:i') }}</small>
                </div>
                @endif
                <div class="mb-3"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="banned" value="1" id="banned" form="user-edit-form" {{ old('banned', $user->isBanned()) ? 'checked' : '' }}>
                    <label class="form-check-label" for="banned">{{ __('main.banned') }}</label>
                </div></div>
                <div class="mb-0">
                    <label class="form-label" for="ban_reason">{{ __('main.ban_reason') }}</label>
                    <input type="text" name="ban_reason" id="ban_reason" form="user-edit-form" class="form-control" value="{{ old('ban_reason', $user->ban_reason) }}" placeholder="{{ __('main.ban_reason_placeholder') }}">
                </div>
            </div>
        </div>

        {{-- Game ban --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('main.game_ban_title') }}</h5>
            </div>
            <div class="card-body">
                @if($gameBanned)
                <div class="alert alert-danger mb-3">
                    <strong>{{ __('main.banned') }}</strong>
                    @if($gameBanReason)<p class="mb-0 mt-1">{{ $gameBanReason }}</p>@endif
                    @if($gameBanDate)<small class="d-block mt-1">{{ __('main.since') }} {{ date('d.m.Y H:i', $gameBanDate) }}</small>@endif
                </div>
                @endif
                <div class="mb-3"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="game_banned" value="1" id="game_banned" form="user-edit-form" {{ old('game_banned', $gameBanned) ? 'checked' : '' }}>
                    <label class="form-check-label" for="game_banned">{{ __('main.banned') }}</label>
                </div></div>
                <div class="mb-0">
                    <label class="form-label" for="game_ban_reason">{{ __('main.ban_reason') }}</label>
                    <input type="text" name="game_ban_reason" id="game_ban_reason" form="user-edit-form" class="form-control" value="{{ old('game_ban_reason', $gameBanReason) }}" placeholder="{{ __('main.ban_reason_placeholder') }}">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
