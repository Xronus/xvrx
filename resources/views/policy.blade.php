<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('main.policy_title') }}</title>
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('xvrx-assets/css/xvrx-laravel.css') }}">
</head>
<body>
    @include('partials.xvrx-header')
    <main class="xvrx-section xvrx-legal-page">
        <h1>{{ __('main.policy_title') }}</h1>
        @php
            $settings = site_settings();
            $text = $settings->policy_text ?? __('main.policy_text');
            $allowed = '<b><i><strong><em><u><h2><h3><h4><p><br><ul><ol><li><a><blockquote><hr><table><thead><tbody><tr><th><td>';
            $text = strip_tags($text, $allowed);
            $text = preg_replace('/\s+on\w+\s*=\s*(["\']).*?\1/i', '', $text);
            $text = preg_replace('/\s+on\w+\s*=\s*[^\s>]+/i', '', $text);
            $text = preg_replace('/((?:href|src)\s*=\s*(["\']))javascript\s*:/i', '$1#', $text);
        @endphp
        <div>{!! $text !!}</div>
    </main>
    @include('partials.xvrx-footer')
</body>
</html>
