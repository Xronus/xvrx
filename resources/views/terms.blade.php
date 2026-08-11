<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('main.terms_title') }}</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
</head>
<body>
    @include('partials.xvrx-header')
    <main class="xvrx-section xvrx-legal-page">
        <h1>{{ __('main.terms_title') }}</h1>
        @php
            $settings = site_settings();
            $text = $settings->terms_text ?? __('main.terms_text');
            // Allow safe HTML tags
            $allowed = '<b><i><strong><em><u><h2><h3><h4><p><br><ul><ol><li><a><blockquote><hr><table><thead><tbody><tr><th><td>';
            $text = strip_tags($text, $allowed);
            // Strip ALL attributes from remaining tags to prevent XSS via event handlers / javascript:
            $text = preg_replace('/<(\w+)\s+[^>]*>/i', '<$1>', $text);
        @endphp
        <div>{!! $text !!}</div>
    </main>
    @include('partials.xvrx-footer')
</body>
</html>
