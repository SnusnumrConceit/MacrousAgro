@component('mail::message')

{{ __('mail.body.welcome', ['name' => $user->first_name, 'app_name' => config('app.name')]) }}

@component('mail::button', ['url' => config('app.url')])
{{ __('mail.buttons.landing') }}
@endcomponent

{{ __('mail.footer') }}<br>
{{ config('app.name') }}
@endcomponent
