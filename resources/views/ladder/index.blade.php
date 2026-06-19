<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('powerpuffsite/images/favicon.ico') }}" type="image/x-icon">
    <title>{{ __('main.ladder_title') }}</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
</head>
<body class="xvrx-inner-page xvrx-ladder-body">
@include('partials.xvrx-header')

<main class="xvrx-ladder-page">
    <section class="xvrx-ladder-wrap">
        <div class="xvrx-ladder-head">
            <p class="xvrx-eyebrow">PvP Ladder</p>
            <h1>{{ __('main.ladder_title') }}</h1>
            <p>{{ __('main.ladder_description') }}</p>
        </div>

        <nav class="xvrx-ladder-tabs" aria-label="{{ __('main.ladder_title') }}">
            <a href="{{ route('ladder', ['list' => 'time_played']) }}" class="{{ isset($mode) && $mode === 'time_played' ? 'active' : '' }}">{{ __('main.ladder_tab_time_played') }}</a>
            <a href="{{ route('ladder', ['list' => 'honorable_kills']) }}" class="{{ isset($mode) && $mode === 'honorable_kills' ? 'active' : '' }}">{{ __('main.ladder_tab_honorable_kills') }}</a>
            <a href="{{ route('ladder', ['type' => 2]) }}" class="{{ isset($mode) && $mode === 'arena' && $type == 2 ? 'active' : '' }}">{{ __('main.arena_2v2') }}</a>
            <a href="{{ route('ladder', ['type' => 3]) }}" class="{{ isset($mode) && $mode === 'arena' && $type == 3 ? 'active' : '' }}">{{ __('main.arena_3v3') }}</a>
            <a href="{{ route('ladder', ['type' => 5]) }}" class="{{ isset($mode) && $mode === 'arena' && $type == 5 ? 'active' : '' }}">{{ __('main.arena_5v5') }}</a>
        </nav>

        <div class="xvrx-ladder-table-shell">
            @if(isset($mode) && $mode === 'arena')
                <table class="xvrx-ladder-table">
                    <thead>
                        <tr>
                            <th>{{ __('main.rank') }}</th>
                            <th>{{ __('main.ladder_team_name') }}</th>
                            <th>{{ __('main.ladder_members') }}</th>
                            <th>{{ __('main.rating') }}</th>
                            <th>{{ __('main.ladder_season_stats') }}</th>
                            <th>{{ __('main.ladder_week_stats') }}</th>
                            <th>{{ __('main.ladder_win_percent') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ladderData as $row)
                            <tr>
                                <td>{{ $row['place'] }}</td>
                                <td>{{ $row['team_name'] }}</td>
                                <td>{{ $row['members'] ?: '-' }}</td>
                                <td>{{ $row['rating'] }}</td>
                                <td>{{ $row['season_wins'] }} / {{ $row['season_losses'] }}</td>
                                <td>{{ $row['week_wins'] }} / {{ $row['week_losses'] }}</td>
                                <td>{{ $row['win_percent'] }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="xvrx-ladder-empty">{{ __('main.no_ladder_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @elseif(isset($mode) && $mode === 'honorable_kills')
                <table class="xvrx-ladder-table">
                    <thead>
                        <tr>
                            <th>{{ __('main.rank') }}</th>
                            <th>{{ __('main.player') }}</th>
                            <th>{{ __('main.ladder_honorable_kills') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ladderData as $row)
                            <tr>
                                <td>{{ $row['place'] }}</td>
                                <td>{{ $row['name'] }}</td>
                                <td>{{ number_format($row['count']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="xvrx-ladder-empty">{{ __('main.no_ladder_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="xvrx-ladder-table">
                    <thead>
                        <tr>
                            <th>{{ __('main.rank') }}</th>
                            <th>{{ __('main.player') }}</th>
                            <th>{{ __('main.ladder_time_played') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ladderData as $row)
                            <tr>
                                <td>{{ $row['place'] }}</td>
                                <td>{{ $row['name'] }}</td>
                                <td>{{ \App\Http\Controllers\LadderController::formatTime($row['totaltime']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="xvrx-ladder-empty">{{ __('main.no_ladder_data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </section>
</main>

@include('partials.xvrx-social')
@include('partials.xvrx-footer')
</body>
</html>
