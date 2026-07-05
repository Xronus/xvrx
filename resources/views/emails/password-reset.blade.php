<x-mail::message>
# {{ __('main.reset_password') }}

{{ $bodyText }}

<x-mail::button :url="$resetUrl">
{{ __('main.reset_password_button') }}
</x-mail::button>

{{ __('main.thanks') }},<br>
{{ $siteName ?? config('app.name') }}
</x-mail::message>
