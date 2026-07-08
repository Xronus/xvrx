<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName)</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('powerpuffsite/css/cabinet.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
    <link rel="shortcut icon" href="{{ asset('powerpuffsite/images/favicon.ico') }}">
    @stack('styles')
</head>
<body class="nk-body bg-white npc-default has-aside dark-mode">
    @include('partials.xvrx-header')
    <div class="xvrx-page-backdrop" aria-hidden="true">
        <img src="{{ asset('xvrx-assets/images/wotlk-page-bg-2.png') }}" alt="">
    </div>
    <div class="nk-app-root">
        <div class="nk-main">
            @yield('content')
        </div>
    </div>
    @include('partials.xvrx-social')
    @include('partials.xvrx-footer')

    <script src="{{ asset('powerpuffsite/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/bundle.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/scripts.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
