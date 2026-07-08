@php
    $realm = $realms->first();
    $siteTitle = $settings && $settings->title ? $settings->title : 'Xronus';
    $heroTitle = $settings && $settings->main__title ? $settings->main__title : 'Твоя история начинается здесь';
    $heroText = $settings && $settings->main__text ? $settings->main__text : 'Отважные подвиги, загадочные земли и судьбоносные встречи — всё это ждёт тебя. Начни своё путешествие прямо сейчас!';
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('powerpuffsite/images/favicon.ico') }}" type="image/x-icon">
    <meta name="description" content="{{ $settings ? $settings->localizedSiteDescription() : '' }}">
    <meta name="format-detection" content="telephone=no">
    <title>{{ $siteTitle }}</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
</head>
<body class="xvrx-home">
@include('partials.xvrx-header')

<main>
    <section class="xvrx-hero">
        <video class="xvrx-hero-video" src="{{ asset('xvrx-assets/images/impressivewow-hero.mp4') }}" poster="{{ asset('xvrx-assets/images/wotlk-page-bg-2.png') }}" loop muted autoplay playsinline></video>
        <div class="xvrx-hero-shade"></div>
        <div class="xvrx-hero-magic xvrx-hero-magic-one" aria-hidden="true"></div>
        <div class="xvrx-hero-magic xvrx-hero-magic-two" aria-hidden="true"></div>
        <div class="xvrx-hero-runes" aria-hidden="true"></div>

        <div class="xvrx-hero-content">
            <p class="xvrx-eyebrow">{{ $realm ? $realm->name : 'WotLK' }} {{ $realm ? $realm->patch : '3.3.5a' }}</p>
            <h1>{{ $heroTitle }}</h1>
            <p>{{ $heroText }}</p>
            <div class="xvrx-actions">
                <a class="xvrx-button xvrx-button-primary" href="{{ route('register') }}">{{ __('main.start_journey') }}</a>
                <a class="xvrx-button xvrx-button-ghost" href="#howtostart">{{ __('main.download_full_game') }}</a>
            </div>
        </div>

        <div class="xvrx-hero-stats">
            <div class="xvrx-hero-stat-main">
                <strong>{{ $realm ? $realm->localized('name') : 'Xronus' }}</strong>
                <span>{{ $realm ? $realm->localized('description') : 'Wrath of the Lich King 3.3.5a' }}</span>
            </div>
            <div>
                <strong>{{ $realm ? $realm->rate : 'x5' }}</strong>
                <span>{{ __('main.rate') }}</span>
            </div>
            <div>
                <strong>{{ $realm ? $realm->patch : '3.3.5a' }}</strong>
                <span>{{ __('main.patch') }}</span>
            </div>
            <div>
                @if($onlineError ?? false)
                    <strong class="text-muted">—</strong>
                @else
                    <strong>{{ (int)($onlineCount ?? 0) }}</strong>
                @endif
                <span>{{ __('main.online') }}</span>
            </div>
        </div>
    </section>

    <section class="xvrx-section xvrx-news-section" id="news">
        <div class="xvrx-section-head">
            <div>
                <p class="xvrx-eyebrow">{{ __('main.world_chronicles') }}</p>
                <h2>{{ __('main.news') }}</h2>
            </div>
            <a class="xvrx-button xvrx-button-ghost" href="{{ route('news.index') }}">{{ __('main.all_news') }}</a>
        </div>

        @if($news->count() > 0)
            <div class="xvrx-news-carousel">
                <button class="xvrx-carousel-btn xvrx-carousel-prev" type="button" aria-label="{{ __('main.previous_news') }}"><i class="ri-arrow-left-s-line"></i></button>
                <div class="xvrx-news-track">
                    @foreach($news as $item)
                        <article class="xvrx-news-card">
                            <a href="{{ route('news.show', $item) }}">
                                <div class="xvrx-news-media">
                                    <img src="{{ asset($item->images ?: 'xvrx-assets/images/title_texture.jpg') }}" alt="">
                                </div>
                                <div class="xvrx-news-body">
                                    <time>{{ $item->date }}</time>
                                    <h3>{{ $item->localized('text') }}</h3>
                                    @if($item->localized('content'))
                                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->localized('content')), 130) }}</p>
                                    @endif
                                    <span>{{ __('main.read_more') }}</span>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
                <button class="xvrx-carousel-btn xvrx-carousel-next" type="button" aria-label="{{ __('main.next_news') }}"><i class="ri-arrow-right-s-line"></i></button>
            </div>
        @else
            <div class="xvrx-empty">{{ __('main.no_news_yet') }}</div>
        @endif
    </section>

    @if($realm)
        <section class="xvrx-section xvrx-realm-section">
            <div class="xvrx-realm-copy">
                <p class="xvrx-eyebrow">{{ __('main.realm_status') }}</p>
                <h2>{{ $realm && $realm->full_name ? $realm->full_name : \App\Models\SiteSetting::first()?->title ?? 'xVRx' }}</h2>
                <p>{{ $realm->localized('description') }}</p>
            </div>
            <div class="xvrx-realm-grid">
                <div><strong>{{ $realm->rate }}</strong><span>{{ __('main.experience') }}</span></div>
                <div><strong>{{ $realm->proffesion }}</strong><span>{{ __('main.professions') }}</span></div>
                <div><strong>{{ $realm->gold }}</strong><span>{{ __('main.gold') }}</span></div>
                <div><strong>{{ $realm->rep }}</strong><span>{{ __('main.reputation') }}</span></div>
                <div><strong>{{ $realm->loot }}</strong><span>{{ __('main.loot') }}</span></div>
                <div><strong>{{ $realm->honor }}</strong><span>{{ __('main.honor_points') }}</span></div>
            </div>
        </section>
    @endif

    <section class="xvrx-section xvrx-download-section" id="howtostart">
        <div class="xvrx-section-head">
            <div>
                <p class="xvrx-eyebrow">{{ __('main.how_to_start') }}</p>
                <h2>{{ __('main.download_full_game') }}</h2>
            </div>
            <span class="xvrx-download-size">{{ __('main.client_size_text') }} {{ $hts->client_size ?? '25.6 GB' }}</span>
        </div>
        <div class="xvrx-download-grid">
            <div class="xvrx-download-card xvrx-download-main">
                <h3>{{ __('main.wotlk_client') }}</h3>
                <p>{{ __('main.download_problems_text') }}</p>
                <div class="xvrx-download-links">
                    @if(($hts->google_drive_active ?? false) && ($hts->google_drive_url ?? ''))
                        <a class="xvrx-button xvrx-button-primary" href="{{ $hts->google_drive_url }}" target="_blank" rel="noopener noreferrer">Google Drive</a>
                    @endif
                    @if(($hts->yandex_disk_active ?? false) && ($hts->yandex_disk_url ?? ''))
                        <a class="xvrx-button xvrx-button-primary" href="{{ $hts->yandex_disk_url }}" target="_blank" rel="noopener noreferrer">Yandex Disk</a>
                    @endif
                    @if(($hts->mega_active ?? false) && ($hts->mega_url ?? ''))
                        <a class="xvrx-button xvrx-button-primary" href="{{ $hts->mega_url }}" target="_blank" rel="noopener noreferrer">MegaNZ</a>
                    @endif
                    @if(($hts->torrent_active ?? false) && ($hts->torrent_url ?? ''))
                        <a class="xvrx-button xvrx-button-ghost" href="{{ $hts->torrent_url }}" target="_blank" rel="noopener noreferrer">Torrent</a>
                    @endif
                </div>
            </div>
            <div class="xvrx-download-card">
                <h3>{{ __('main.launcher') }}</h3>
                <p>{{ $hts ? $hts->localizedLauncherDescription() : '' }}</p>
                <a class="xvrx-button xvrx-button-ghost" href="{{ $hts->launcher_url ?? '#' }}" target="_blank" rel="noopener noreferrer">
                    {{ $hts ? $hts->localizedLauncherText() : __('main.launcher') }}
                </a>
            </div>
            <div class="xvrx-download-card">
                <h3>{{ __('main.system_requirements') }}</h3>
                <dl class="xvrx-req-list">
                    <div><dt>{{ __('main.storage') }}</dt><dd>{{ $hts->req_storage_rec ?? '30 GB' }}</dd></div>
                    <div><dt>Windows</dt><dd>{{ $hts->req_windows_rec ?? 'Windows 10' }}</dd></div>
                    <div><dt>{{ __('main.ram') }}</dt><dd>{{ $hts->req_ram_rec ?? '6 GB' }}</dd></div>
                    <div><dt>{{ __('main.video_card') }}</dt><dd>{{ $hts->req_gpu_rec ?? '1024 MB' }}</dd></div>
                </dl>
            </div>
        </div>
    </section>

</main>

@include('partials.xvrx-social')

@include('partials.xvrx-footer')

<script>
(function () {
    var root = document.querySelector('.xvrx-news-carousel');
    if (!root) return;
    var track = root.querySelector('.xvrx-news-track');
    var prev = root.querySelector('.xvrx-carousel-prev');
    var next = root.querySelector('.xvrx-carousel-next');
    function step() {
        var card = track.querySelector('.xvrx-news-card');
        return card ? card.getBoundingClientRect().width + 18 : 340;
    }
    prev.addEventListener('click', function () { track.scrollBy({ left: -step(), behavior: 'smooth' }); });
    next.addEventListener('click', function () { track.scrollBy({ left: step(), behavior: 'smooth' }); });
})();
</script>
</body>
</html>
