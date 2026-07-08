@php
    $adminLinks = [
        ['route' => 'admin.index', 'active' => 'admin.index', 'icon' => 'ni-home', 'label' => __('main.home')],
        ['route' => 'admin.howtostart.index', 'active' => 'admin.howtostart.*', 'icon' => 'ni-play-circle', 'label' => __('main.how_to_start')],
        ['route' => 'admin.news.index', 'active' => 'admin.news.*', 'icon' => 'ni-notes', 'label' => __('main.news')],
        ['route' => 'admin.realms.index', 'active' => 'admin.realms.*', 'icon' => 'ni-globe', 'label' => __('main.realms')],
        ['route' => 'admin.socials.index', 'active' => 'admin.socials.*', 'icon' => 'ni-link', 'label' => __('main.social_media')],
        ['route' => 'admin.votes.index', 'active' => 'admin.votes.*', 'icon' => 'ni-star', 'label' => __('main.voting')],
        ['route' => 'admin.races.index', 'active' => 'admin.races.*', 'icon' => 'ni-shield-star', 'label' => __('main.races')],
        ['route' => 'admin.classes.index', 'active' => 'admin.classes.*', 'icon' => 'ni-shield-check', 'label' => __('main.classes')],
        ['route' => 'admin.features.index', 'active' => 'admin.features.*', 'icon' => 'ni-spark', 'label' => __('main.features_title')],
        ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'ni-users', 'label' => __('main.users')],
        ['route' => 'admin.shop.index', 'active' => 'admin.shop.*', 'icon' => 'ni-cart', 'label' => __('main.shop_manage')],
        ['route' => 'admin.shop-categories.index', 'active' => 'admin.shop-categories.*', 'icon' => 'ni-tags', 'label' => __('main.shop_categories')],
        ['route' => 'admin.mail.index', 'active' => 'admin.mail.*', 'icon' => 'ni-mail', 'label' => __('main.mail_settings')],
        ['route' => 'admin.account-parser.index', 'active' => 'admin.account-parser.*', 'icon' => 'ni-reload', 'label' => __('main.account_parser')],
    ];
@endphp

<div class="nk-aside bg-transparent" data-content="sideNav" data-toggle-overlay="true" data-toggle-screen="lg" data-toggle-body="true">
    <div class="nk-sidebar-menu" data-simplebar>
        <ul class="nk-menu">
            @foreach($adminLinks as $link)
            <li class="nk-menu-item {{ request()->routeIs($link['active']) ? 'active' : '' }}">
                <a href="{{ route($link['route']) }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni {{ $link['icon'] }}"></em></span>
                    <span class="nk-menu-text">{{ $link['label'] }}</span>
                </a>
            </li>
            @endforeach
            <li class="nk-menu-item {{ request()->is('powerpuffsiteadmin/logo*') ? 'active' : '' }}">
                <a href="{{ route('admin.logo.index') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-img"></em></span>
                    <span class="nk-menu-text">{{ __('main.logo_management') }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>
