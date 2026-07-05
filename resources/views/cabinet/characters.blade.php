@extends('layouts.app')

@section('title', __('main.characters'))

@section('content')
@include('partials._game_ban_warning')
<div class="nk-wrap">
    @include('cabinet.partials.header')

    <div class="nk-content">
        <div class="container wide-xl">
            <div class="nk-content-inner">
                @include('cabinet.partials.sidebar', ['active' => 'characters'])

                <div class="nk-content-body">
                    <div class="nk-content-wrap">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="nk-block-title">{{ __('main.characters') }}</h4>
                                <p class="text-soft">{{ __('main.characters_list') }}</p>
                            </div>
                        </div>

                        <div class="nk-block">
                            @if($characters->count() > 0)
                            <div class="card card-bordered">
                                <div class="card-inner p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>{{ __('main.nickname') }}</th>
                                                    <th>{{ __('main.level') }}</th>
                                                    <th>{{ __('main.race') }}</th>
                                                    <th>{{ __('main.class') }}</th>
                                                    <th>{{ __('main.faction') }}</th>
                                                    <th>{{ __('main.status') }}</th>
                                                    <th>{{ __('main.last_login') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($characters as $char)
                                                <tr>
                                                    <td><strong>{{ $char->name }}</strong></td>
                                                    <td>{{ $char->level }}</td>
                                                    <td>{{ $char->race_name }}</td>
                                                    <td>{{ $char->class_name }}</td>
                                                    <td>
                                                        {{ $char->faction }}
                                                    </td>
                                                    <td>
                                                        @if($char->online)
                                                        {{ __('main.online') }}
                                                        @else
                                                        {{ __('main.offline') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $char->last_login }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="card card-bordered">
                                <div class="card-inner text-center text-soft py-4">
                                    {{ __('main.no_characters') }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
