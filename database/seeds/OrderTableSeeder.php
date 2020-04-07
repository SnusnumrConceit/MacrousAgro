<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Order::class, 5)->create()->each(function ($order) {
            $product = \App\Models\Product::inRandomOrder()->limit(1)->first();

            $order->positions()->create([
                'product_id' => $product->id,
                'order_item_status_code' => array_rand(array_flip(\App\Models\Order::getStatuses()))
            ]);
            $order->invoice()->create([
                'payment_amount' => rand(100, 2000),
                'invoice_status_code' => array_rand(array_flip(\App\Models\Invoice::getStatuses()))
            ]);
        });
    }
}
