<div class="nk-header nk-header-fixed is-light">
    <div class="container-lg wide-xl">
        <div class="nk-header-wrap">
            <div class="nk-header-brand">
                <a href="{{ route('home') }}" class="logo-link">
                    @php $logoPath = isset($settings) && $settings && $settings->logo_path ? $settings->logo_path : 'powerpuffsite/images/main/logo.png'; @endphp
                    <img class="logo-light logo-img" src="{{ asset($logoPath) }}">
                </a>
            </div>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:inline-block;margin:0;">
                            @csrf
                            <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-sm btn-outline-light" style="border:1px solid rgba(255,255,255,.2);text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;">
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
