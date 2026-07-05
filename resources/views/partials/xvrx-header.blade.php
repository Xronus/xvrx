@php
    $xvrxSettings = $settings ?? null;
    $xvrxSiteTitle = $siteName ?? 'Xronus';
    $xvrxLogoPath = $xvrxSettings && !empty($xvrxSettings->logo_path) ? $xvrxSettings->logo_path : null;
@endphp
<header class="xvrx-header">
    <a class="xvrx-brand" href="{{ route('home') }}" aria-label="{{ $xvrxSiteTitle }}">
        @if($xvrxLogoPath)
            <img src="{{ asset($xvrxLogoPath) }}" alt="">
        @else
            <span class="xvrx-brand-mark">VR</span>
        @endif
        <span>{{ $xvrxSiteTitle }}</span>
    </a>
    <input type="checkbox" id="xvrx-menu-toggle" class="xvrx-menu-toggle">
    <label for="xvrx-menu-toggle" class="xvrx-menu-button" aria-label="Меню"><span></span><span></span><span></span></label>
    <nav class="xvrx-nav">
        <a href="{{ route('home') }}">{{ __('main.home') }}</a>
        <a href="{{ route('news.index') }}">{{ __('main.news') }}</a>
        <a href="{{ route('home') }}#howtostart">{{ __('main.download_full_game') }}</a>
        <a href="{{ route('ladder') }}">{{ __('main.ladder') }}</a>
        <a href="{{ route('register') }}">{{ __('main.registration') }}</a>
        @auth
            <div class="xvrx-dropdown">
                <a class="xvrx-account" href="#" onclick="event.preventDefault();this.parentElement.classList.toggle('open')" aria-label="{{ __('main.personal_account') }}">
                    <i class="ri-user-3-line"></i>{{ auth()->user()->username }}
                </a>
                <div class="xvrx-dropdown-menu">
                    <a href="{{ route('cabinet') }}"><i class="ri-user-3-line"></i>{{ __('main.personal_account') }}</a>
                    <hr>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><i class="ri-logout-box-r-line"></i>{{ __('main.logout') }}</button>
                    </form>
                </div>
            </div>
        @else
            <a class="xvrx-account" href="{{ route('login') }}"><i class="ri-user-3-line"></i>{{ __('main.personal_account') }}</a>
        @endauth
    </nav>
</header>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var header = document.querySelector('.xvrx-header');
    if (!header) return;
    function syncHeaderState() {
        header.classList.toggle('is-scrolled', window.scrollY > 24);
    }
    syncHeaderState();
    window.addEventListener('scroll', syncHeaderState, { passive: true });

    // Close dropdown on outside click
    document.addEventListener('click', function (e) {
        var dropdowns = document.querySelectorAll('.xvrx-dropdown.open');
        dropdowns.forEach(function (d) {
            if (!d.contains(e.target)) d.classList.remove('open');
        });
    });
});
</script>
