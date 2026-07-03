<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', __('main.site_management'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('ppadmin-static/assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('ppadmin-static/assets/css/preloader.min.css') }}" type="text/css" />
    <link href="{{ asset('ppadmin-static/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ppadmin-static/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ppadmin-static/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('ppadmin-static/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://kit.fontawesome.com/803ae4140f.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>
<body>
<div id="layout-wrapper">
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box">
                    <a href="{{ route('home') }}" class="logo logo-dark">
                        @php
                            $settings = \App\Models\SiteSetting::first();
                            $logoPath = $settings && $settings->logo_path ? $settings->logo_path : 'powerpuffsite/images/main/logo.png';
                        @endphp
                        <span class="logo-sm">
                            <img src="{{ asset($logoPath) }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset($logoPath) }}" alt="" height="20">
                        </span>
                    </a>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->username }}</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('cabinet') }}"><i class="bx bx-user font-size-16 align-middle me-1"></i> {{ __('main.personal_account') }}</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> {{ __('main.logout') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="vertical-menu">
        <div data-simplebar class="h-100">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title" data-key="t-menu">{{ __('main.menu') }}</li>
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <i class="fas fa-home"></i>
                            <span>{{ __('main.home') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.howtostart.index') }}">
                            <i class="fas fa-play-circle"></i>
                            <span>{{ __('main.how_to_start') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.news.index') }}">
                            <i class="fas fa-newspaper"></i>
                            <span>{{ __('main.news') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.realms.index') }}">
                            <i class="fab fa-phoenix-framework"></i>
                            <span>{{ __('main.realms') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.socials.index') }}">
                            <i class="fas fa-link"></i>
                            <span>{{ __('main.social_media') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.votes.index') }}">
                            <i class="fas fa-vote-yea"></i>
                            <span>{{ __('main.voting') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.races.index') }}">
                            <i class="fas fa-hat-wizard"></i>
                            <span>{{ __('main.races') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.classes.index') }}">
                            <i class="fas fa-shield-alt"></i>
                            <span>{{ __('main.classes') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.features.index') }}">
                            <i class="fas fa-star"></i>
                            <span>{{ __('main.features_title') }}</span>
                        </a>
                    </li>
                    <li class="menu-title" data-key="t-management">{{ __('main.management') }}</li>
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i>
                            <span>{{ __('main.users') }}</span>
                        </a>
                    </li>
                    <li class="menu-title" data-key="t-settings">{{ __('main.settings') }}</li>
                    <li>
                        <a href="{{ route('admin.languages.index') }}">
                            <i class="fas fa-globe"></i>
                            <span>{{ __('main.languages') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.mail.index') }}">
                            <i class="fas fa-envelope"></i>
                            <span>{{ __('main.mail_settings') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.account-parser.index') }}">
                            <i class="fas fa-sync-alt"></i>
                            <span>{{ __('main.account_parser') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/powerpuffsiteadmin/logo') }}">
                            <i class="fas fa-image"></i>
                            <span>{{ __('main.logo_management') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script> © Powerpuff website
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="https://powerpuff.pro/" target="_blank" rel="noopener noreferrer" class="text-decoration-none d-inline-flex align-items-center gap-1 footer-developed">
                            <span class="text-muted">Developed by</span>
                            <img src="https://wow1.powerpuff.pro/powerpuffsite/fonts/powerpuff.png" alt="Powerpuff - website creation" height="20" class="d-inline-block">
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script src="{{ asset('ppadmin-static/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('ppadmin-static/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('ppadmin-static/assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('ppadmin-static/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('ppadmin-static/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('ppadmin-static/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('ppadmin-static/assets/libs/pace-js/pace.min.js') }}"></script>
<script src="{{ asset('ppadmin-static/assets/js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
