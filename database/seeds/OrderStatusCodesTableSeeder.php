<?php

use Illuminate\Database\Seeder;

class OrderStatusCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\OrderItemStatusCode::updateOrCreate(['description' => 'в обработке'], ['description' => 'в обработке']);

        \App\Models\OrderItemStatusCode::updateOrCreate(['description' => 'отменён'], ['description' => 'отменён']);

        \App\Models\OrderItemStatusCode::updateOrCreate(['description' => 'оплачен'], ['description' => 'отменён']);

        \App\Models\OrderItemStatusCode::updateOrCreate(['description' => 'завершён'], ['description' => 'завершён']);
    }
}
