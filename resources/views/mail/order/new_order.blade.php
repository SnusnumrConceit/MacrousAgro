@component('mail::message')
{{ __('mail.body.new_order', ['full_name' => $user->full_name, 'email' => $user->email, 'order_id' => $order->id]) }}

@component('mail::button', ['url' => route('orders.show', ['order' => $order->id])])
    {{ __('mail.buttons.details') }}
@endcomponent

{{ __('mail.footer') }}<br>
{{ config('app.name') }}
@endcomponent
