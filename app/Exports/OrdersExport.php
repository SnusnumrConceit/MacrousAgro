<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, Responsable, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private $fileName = 'orders.xlsx';

    public function map($order): array
    {
        return [
            $order->id,
            $order->customer->full_name,
            Str::ucfirst(__('orders.statuses.' . $order->order_status_code)),
            $order->invoice->payment_amount,
            $order->created_at->format('d.m.Y H:i:s')
        ];
    }

    public function headings(): array
    {
        return [
            __('orders.export.headings.id'),
            __('orders.export.headings.customer'),
            __('orders.export.headings.status'),
            __('orders.export.headings.price'),
            __('orders.export.headings.created_at')
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $orders = Order::with('customer', 'invoice')->get();
//
//        $orders->push(collect([
//            'id' => '',
//            'customer' => '',
//            'amount' => 'Итого',
//            'invoice' => '=SUM(D2:D' . $orders->count() . ')'
//
//        ]));

        return $orders;
    }
}
