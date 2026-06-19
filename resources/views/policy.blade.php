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
        <p>{{ __('main.policy_text') }}</p>
    </main>
    @include('partials.xvrx-footer')
</body>
</html>
