<div class="nk-aside bg-transparent" data-content="sideNav" data-toggle-overlay="true" data-toggle-screen="lg" data-toggle-body="true">
    <div class="nk-sidebar-menu" data-simplebar>
        <ul class="nk-menu">
            <li class="nk-menu-item {{ $active == 'home' ? 'active' : '' }}">
                <a href="{{ route('cabinet') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-home"></em></span>
                    <span class="nk-menu-text">{{ __('main.homepage') }}</span>
                </a>
            </li>
            <li class="nk-menu-item {{ $active == 'characters' ? 'active' : '' }}">
                <a href="{{ route('cabinet.characters') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                    <span class="nk-menu-text">{{ __('main.characters') }}</span>
                </a>
            </li>
            <li class="nk-menu-item {{ $active == 'shop' ? 'active' : '' }}">
                <a href="{{ route('shop') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-cart"></em></span>
                    <span class="nk-menu-text">{{ __('main.shop') }}</span>
                </a>
            </li>
            <li class="nk-menu-item {{ $active == 'votes' ? 'active' : '' }}">
                <a href="{{ route('cabinet.votes') }}" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-star"></em></span>
                    <span class="nk-menu-text">{{ __('main.voting') }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>
