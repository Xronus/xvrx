<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Admin Panel — {{ $siteName }}">
    <title>@yield('title', $siteName)</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('powerpuffsite/css/cabinet.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
    <link href="{{ asset('ppadmin-static/assets/css/custom.css') }}?v={{ filemtime(public_path('ppadmin-static/assets/css/custom.css')) }}" rel="stylesheet" type="text/css">
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
            <div class="nk-wrap xvrx-admin-wrap">
                @include('cabinet.partials.header')

                <div class="nk-content">
                    <div class="container wide-xl">
                        <div class="nk-content-inner">
                            @include('admin.partials.sidebar')

                            <div class="nk-content-body">
                                <div class="nk-content-wrap xvrx-cabinet-page xvrx-admin-page">
                                    @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-start" role="alert">
                                        <span class="flex-grow-1">{{ session('success') }}</span>
                                        <button type="button" class="btn-close ms-2 flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif

                                    @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert">
                                        <span class="flex-grow-1">{{ session('error') }}</span>
                                        <button type="button" class="btn-close ms-2 flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif

                                    @if($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert">
                                        <ul class="mb-0 flex-grow-1">
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close ms-2 flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif

                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.xvrx-social')
    @include('partials.xvrx-footer')

    <script src="{{ asset('powerpuffsite/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/bundle.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/scripts.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('powerpuffsite/js/main.js') }}"></script>
    <script src="{{ asset('ppadmin-static/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
