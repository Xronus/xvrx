@extends('admin.layout')

@section('title', __('main.users'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">{{ __('main.users') }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0">{{ __('main.users_list') }}</h5>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2" 
                                   value="{{ $searchTerm }}" 
                                   placeholder="{{ __('main.search_users') }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i> {{ __('main.search') }}
                            </button>
                            @if($searchTerm)
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary ms-2">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">{{ __('main.id') }}</th>
                                <th>{{ __('main.login_field') }}</th>
                                <th>{{ __('main.email') }}</th>
                                <th style="width: 100px;">{{ __('main.bonuses') }}</th>
                                <th style="width: 100px;">{{ __('main.characters_count') }}</th>
                                <th style="width: 120px;">{{ __('main.registration_date') }}</th>
                                <th style="width: 90px;">{{ __('main.site_status') }}</th>
                                <th style="width: 90px;">{{ __('main.game_status') }}</th>
                                <th style="width: 120px;">{{ __('main.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->username }}</strong>
                                    @if($user->is_admin)
                                    <span class="badge bg-danger ms-1">{{ __('main.admin') }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->email ?? '-' }}</td>
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
                                <td>
                                    @if($user->isBanned())
                                    <span class="badge bg-warning text-dark" @if($user->ban_reason) title="{{ $user->ban_reason }}" data-bs-toggle="tooltip" @endif>{{ __('main.banned') }}</span>
                                    @elseif($user->is_admin)
                                    <span class="badge bg-danger">{{ __('main.admin') }}</span>
                                    @else
                                    <span class="badge bg-success">{{ __('main.active') }}</span>
                                    @endif
                                </td>

                                {{-- Game status --}}
                                <td>
                                    @php
                                        $gameAccountId = $gameAccounts[strtoupper($user->username)] ?? null;
                                        $gameBanReason = $gameAccountId ? ($gameBanned[$gameAccountId] ?? null) : null;
                                    @endphp
                                    @if($gameBanReason !== null)
                                    <span class="badge bg-danger" title="{{ $gameBanReason }}" data-bs-toggle="tooltip">{{ __('main.banned') }}</span>
                                    @else
                                    <span class="badge bg-secondary">{{ __('main.active') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary" title="{{ __('main.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">{{ __('main.no_users') }}</td>
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
