@component('mail::message')

{{ __('mail.body.new_user', ['full_name' => $user->full_name, 'email' => $user->email]) }}

@component('mail::button', ['url' => route('users.edit', ['user' => $user->id])])
{{ __('mail.buttons.details') }}
@endcomponent

{{ __('mail.footer') }}<br>
{{ config('app.name') }}
@endcomponent
