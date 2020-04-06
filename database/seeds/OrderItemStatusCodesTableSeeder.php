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
        \App\Models\OrderItemStatusCode::firstOrCreate(['description' => 'в обработке'], ['id' => 1, 'description' => 'в обработке']);

        \App\Models\OrderItemStatusCode::firstOrCreate(['description' => 'отменён'], ['id' => 2, 'description' => 'отменён']);

        \App\Models\OrderItemStatusCode::firstOrCreate(['description' => 'отправлен'], ['id' => 3, 'description' => 'отправлен']);

        \App\Models\OrderItemStatusCode::firstOrCreate(['description' => 'завершён'], ['id' => 4, 'description' => 'завершён']);
    }
}
