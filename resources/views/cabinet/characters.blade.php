@extends('layouts.app')

@section('title', __('main.characters'))

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
                            <li class="nk-menu-item active">
                                <a href="{{ route('cabinet.characters') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                    <span class="nk-menu-text">{{ __('main.characters') }}</span>
                                </a>
                            </li>
                            <li class="nk-menu-item">
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
