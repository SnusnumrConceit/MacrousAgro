<?php

use Illuminate\Database\Seeder;

class OrderItemStatusCodesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\OrderStatusCode::updateOrCreate(['description' => 'в обработке'], ['description' => 'в обработке']);

        \App\Models\OrderStatusCode::updateOrCreate(['description' => 'отменён'], ['description' => 'отменён']);

        \App\Models\OrderStatusCode::updateOrCreate(['description' => 'отправлен'], ['description' => 'завершён']);

        \App\Models\OrderStatusCode::updateOrCreate(['description' => 'завершён'], ['description' => 'завершён']);
    }
}
