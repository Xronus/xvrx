<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('powerpuffsite/images/favicon.ico') }}" type="image/x-icon">
    <title>{{ $news->localized('text') }}</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
</head>
<body class="xvrx-inner-page xvrx-news-show-body">
@include('partials.xvrx-header')

<main class="xvrx-news-show-page">
    <article class="xvrx-news-article">
        <a class="xvrx-back-link" href="{{ route('news.index') }}">
            <i class="ri-arrow-left-line"></i>
            Назад
        </a>

        @if($news->images)
            <figure class="xvrx-news-article-media">
                <img src="{{ asset($news->images) }}" alt="{{ $news->localized('text') }}">
            </figure>
        @endif

        <div class="xvrx-news-article-meta">{{ $news->date }}</div>
        <h1>{{ $news->localized('text') }}</h1>

        @if($news->localized('content'))
            <div class="xvrx-news-article-content">{!! $news->localized('content') !!}</div>
        @endif
    </article>
</main>

@include('partials.xvrx-social')
@include('partials.xvrx-footer')
</body>
</html>
