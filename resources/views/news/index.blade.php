<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('powerpuffsite/images/favicon.ico') }}" type="image/x-icon">
    <meta name="description" content="{{ $settings ? $settings->localizedSiteDescription() : '' }}">
    <meta name="format-detection" content="telephone=no">
    <title>{{ __('main.news') }} - {{ $settings ? $settings->localizedSiteTitle() : 'WoW Server' }}</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('powerpuffsite/css/main_home.min.css') }}">
    <link rel="stylesheet" href="{{ asset('powerpuffsite/css/addition.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
</head>
<body class="news-page-body">
@include('partials.xvrx-header')
<div class="wrapper">
<header class="header">
    <div class="header__wrapper">
        <div class="header__container _container">
            <div class="header__body">
                <button type="button" class="menu__icon icon-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="header__menu menu">
                    <nav class="menu__body" style="--logo-path: url('{{ asset($settings && $settings->logo_path ? $settings->logo_path : 'powerpuffsite/images/main/logo.png') }}');">
                        <ul class="menu__list">
                            <li class="menu__item">
                                <a href="{{ route('home') }}" class="menu__link">{{ __('main.home') }}</a>
                            </li>
                            <li class="menu__item">
                                <a href="{{ route('news.index') }}" class="menu__link">{{ __('main.news') }}</a>
                            </li>
                            <li class="menu__item">
                                <a href="{{ route('home') }}#howtostart" class="menu__link">{{ __('main.download_full_game') }}</a>
                            </li>
                            <li class="menu__item">
                                <a href="{{ route('ladder') }}" class="menu__link">{{ __('main.ladder') }}</a>
                            </li>
                            <li class="menu__item">
                                <a href="{{ route('register') }}" class="menu__link">{{ __('main.registration') }}</a>
                            </li>
                            @auth
                            <li class="menu__item" style="padding-left: 53px;">
                                <a href="{{ route('cabinet') }}" class="actions-header__user menu__link">{{ __('main.personal_account') }}</a>
                            </li>
                            @else
                            <li class="menu__item" style="padding-left: 53px;">
                                <a href="{{ route('login') }}" class="actions-header__user menu__link">{{ __('main.personal_account') }}</a>
                            </li>
                            @endauth
                            @if(isset($activeLangs) && $activeLangs->count() > 1)
                            <li class="menu__item" style="padding-left: 30px; position: relative;">
                                <div class="lang-selector" style="position: relative; display: inline-block;">
                                    <a href="#" class="menu__link lang-current" onclick="event.preventDefault(); document.getElementById('lang-dropdown').classList.toggle('lang-show');" style="display: inline-flex; align-items: center; gap: 5px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                        {{ strtoupper(app()->getLocale()) }}
                                    </a>
                                    <div id="lang-dropdown" style="display: none; position: absolute; top: 100%; right: 0; background: rgba(20,20,30,0.95); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; min-width: 120px; z-index: 999; padding: 5px 0; margin-top: 5px;">
                                        @foreach($activeLangs as $lang)
                                        <a href="{{ route('locale.switch', $lang->code) }}" style="display: block; padding: 8px 16px; color: {{ app()->getLocale() === $lang->code ? '#fff' : '#aaa' }}; text-decoration: none; font-size: 14px; white-space: nowrap;">
                                            {{ $lang->native_name }}
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="page">
    <section class="page__news-all news-all">
        <div class="news-all__container _container">
            <div class="news-all__content">
                <h1 class="news-all__title">{{ __('main.news') }}</h1>
                
                <div class="news-all__search">
                    <form action="{{ route('news.index') }}" method="GET" class="news-search-form">
                        <div class="news-search-form__wrapper">
                            <input type="text" name="search" class="news-search-form__input" placeholder="{{ __('main.search_news') }}..." value="{{ $searchTerm ?? '' }}">
                            <button type="submit" class="news-search-form__button">
                                <span>{{ __('main.search') }}</span>
                            </button>
                            @if(!empty($searchTerm))
                            <a href="{{ route('news.index') }}" class="news-search-form__clear">{{ __('main.clear') }}</a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(!empty($searchTerm))
                <div class="news-all__results">
                    <p>{{ __('main.search_results_for') }}: <strong>"{{ $searchTerm }}"</strong> ({{ $news->total() }} {{ __('main.results_found') }})</p>
                </div>
                @endif

                @if($news->count() > 0)
                <div class="news-all__grid">
                    @foreach($news as $item)
                    <div class="news-all__item">
                        <a href="{{ route('news.show', $item) }}" class="news-all__card">
                            @if($item->images)
                            <div class="news-all__image">
                                <picture>
                                    <img src="{{ asset($item->images) }}" alt="{{ $item->localized('text') }}">
                                </picture>
                            </div>
                            @endif
                            <div class="news-all__content">
                                <div class="news-all__date">{{ $item->date }}</div>
                                <h3 class="news-all__title-item">{{ $item->localized('text') }}</h3>
                                @if($item->localized('content'))
                                <p class="news-all__text">{{ Str::limit(strip_tags($item->localized('content')), 120) }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>

                @if($news->hasPages())
                <div class="news-all__pagination">
                    {{ $news->links() }}
                </div>
                @endif
                @else
                <div class="news-all__empty">
                    <p>{{ __('main.no_news_found') }}</p>
                </div>
                @endif
            </div>
        </div>
    </section>
</main>

<section class="page__features features">
    <div class="features__container _container">
        <div class="info__line gorizontal-line"></div>
        <footer class="footer">
            <div class="footer__copy">WoW Free server &middot; 2026</div>
        </footer>
    </div>
</section>

@include('partials.xvrx-social')
@include('partials.xvrx-footer')
<script src="{{ asset('powerpuffsite/js/jquery-2.1.1.min.js') }}"></script>
<script src="{{ asset('powerpuffsite/js/vendor.min.js') }}"></script>
<script src="{{ asset('powerpuffsite/js/main_home.min.js') }}"></script>
<script>
document.addEventListener('click', function(e) {
    var dd = document.getElementById('lang-dropdown');
    if (dd && !e.target.closest('.lang-selector')) {
        dd.classList.remove('lang-show');
        dd.style.display = 'none';
    }
});
document.querySelectorAll('.lang-current').forEach(function(el) {
    el.addEventListener('click', function() {
        var dd = document.getElementById('lang-dropdown');
        dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
    });
});
</script>
</body>
</html>
