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
        \App\Models\OrderStatusCode::firstOrCreate(['id' => 1, 'description' => 'в обработке'], ['id' => 1, 'description' => 'в обработке']);

        \App\Models\OrderStatusCode::firstOrCreate(['id' => 2, 'description' => 'отменён'], ['id' => 2, 'description' => 'отменён']);

        \App\Models\OrderStatusCode::firstOrCreate(['id' => 3, 'description' => 'оплачен'], ['id' => 3, 'description' => 'оплачен']);

        \App\Models\OrderStatusCode::firstOrCreate(['id' => 4, 'description' => 'завершён'], ['id' => 4, 'description' => 'завершён']);
    }
}
