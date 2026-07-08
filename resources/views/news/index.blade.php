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
            <div class="footer__copy">&copy; {{ date('Y') }} {{ $siteName }}. {{ __('main.all_rights_reserved') }} &bull; {{ __('main.designed_by') }} <a href="https://xronus.ru" target="_blank" rel="noopener noreferrer">Xronus Studio</a></div>
        </footer>
    </div>
</section>

@include('partials.xvrx-social')
@include('partials.xvrx-footer')
<script src="{{ asset('powerpuffsite/js/vendor.min.js') }}"></script>
<script src="{{ asset('powerpuffsite/js/main_home.min.js') }}"></script>
<script>
document.addEventListener('click', function(e) {
    const dd = document.getElementById('lang-dropdown');
    if (dd && !e.target.closest('.lang-selector')) {
        dd.classList.remove('lang-show');
        dd.style.display = 'none';
    }
});
document.querySelectorAll('.lang-current').forEach(function(el) {
    el.addEventListener('click', function() {
        const dd = document.getElementById('lang-dropdown');
        dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
    });
});
</script>
</body>
</html>
