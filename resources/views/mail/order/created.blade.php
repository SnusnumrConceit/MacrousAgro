@component('mail::message')
{{ __('mail.body.order_created', ['name' => $user->first_name]) }}

@component('mail::table')
<table>
    <thead>
        <th>
            Название
        </th>
        <th>
            Стоимость
        </th>
    </thead>
    <tbody>
    @foreach ($order->positions as $position)
        <tr style="text-align: center">
            <td>
                {{ $position->product->title }}
            </td>
            <td>
                {{ $position->product->price }} ₽
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endcomponent

Итого: {{ $order->invoice->payment_amount }} ₽

{{ __('mail.footer') }} <br>
{{ config('app.name') }}
@endcomponent
