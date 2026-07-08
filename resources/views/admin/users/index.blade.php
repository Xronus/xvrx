@extends('admin.layout')

@section('title', __('main.users'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.users') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">{{ __('main.add_user') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0">{{ __('main.users_list') }}</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm"
                                   value="{{ $searchTerm }}"
                                   placeholder="{{ __('main.search_users') }}"
                                   style="margin-right: 2rem;">
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('main.search') }}</button>
                            @if($searchTerm)
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary ms-2">{{ __('main.clear') }}</a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="max-width:130px;">{{ __('main.login_field') }}</th>
                                <th style="max-width:160px;">{{ __('main.email') }}</th>
                                <th style="width: 70px;">{{ __('main.bonuses') }}</th>
                                <th style="width: 55px;">{{ __('main.characters_count') }}</th>
                                <th style="width: 80px;">{{ __('main.registration_date') }}</th>
                                <th style="width: 50px;">{{ __('main.site_status') }}</th>
                                <th style="width: 50px;">{{ __('main.game_status') }}</th>
                                <th style="width: 44px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td style="max-width:130px;white-space:nowrap;">
                                    <strong class="d-inline-block text-truncate" style="max-width:100px;vertical-align:middle;">{{ $user->username }}</strong>
                                    @if($user->is_admin)
                                    <img src="https://wow.zamimg.com/images/wow/icons/medium/achievement_pvp_h_15.jpg" alt="{{ __('main.admin') }}" title="{{ __('main.admin') }}" style="width:20px;height:20px;vertical-align:middle;margin-left:4px;">
                                    @endif
                                </td>
                                <td class="text-truncate" style="max-width:160px;">{{ $user->email ?? '-' }}</td>
                                <td>{{ $user->bonuses ?? 0 }}</td>
                                <td>
                                    @php
                                        $charCount = 0;
                                        try {
                                            $gameAccId = \DB::connection('game_auth')->table('account')
                                                ->where('username', strtoupper($user->username))
                                                ->value('id');
                                            if ($gameAccId) {
                                                $charCount = \DB::connection('game_char')->table('characters')
                                                    ->where('account', $gameAccId)
                                                    ->count();
                                            }
                                        } catch (\Exception $e) {
                                            $charCount = 0;
                                        }
                                    @endphp
                                    {{ $charCount }}
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('d.m.Y H:i') : '-' }}</td>
                                {{-- Site status --}}
                                <td class="text-center">
                                    @if($user->isBanned())
                                    <img src="https://wow.zamimg.com/images/wow/icons/medium/misc_rnrredxbutton.jpg" alt="{{ __('main.banned') }}" @if($user->ban_reason) title="{{ $user->ban_reason }}" data-bs-toggle="tooltip" @endif style="width:24px;height:24px;">
                                    @elseif($user->is_admin)
                                    <img src="https://wow.zamimg.com/images/wow/icons/medium/achievement_pvp_h_15.jpg" alt="{{ __('main.admin') }}" title="{{ __('main.admin') }}" style="width:24px;height:24px;">
                                    @else
                                    <img src="https://wow.zamimg.com/images/wow/icons/medium/misc_rnrgreengobutton.jpg" alt="{{ __('main.active') }}" title="{{ __('main.active') }}" style="width:24px;height:24px;">
                                    @endif
                                </td>

                                {{-- Game status --}}
                                <td class="text-center">
                                    @php
                                        $gameAccountId = $gameAccounts[strtoupper($user->username)] ?? null;
                                        $gameBanReason = $gameAccountId ? ($gameBanned[$gameAccountId] ?? null) : null;
                                    @endphp
                                    @if($gameBanReason !== null)
                                    <img src="https://wow.zamimg.com/images/wow/icons/medium/misc_rnrredxbutton.jpg" alt="{{ __('main.banned') }}" title="{{ $gameBanReason }}" data-bs-toggle="tooltip" style="width:24px;height:24px;">
                                    @else
                                    <img src="https://wow.zamimg.com/images/wow/icons/medium/misc_rnrgreengobutton.jpg" alt="{{ __('main.active') }}" title="{{ __('main.active') }}" style="width:24px;height:24px;">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary" title="{{ __('main.edit') }}">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">{{ __('main.no_users') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
