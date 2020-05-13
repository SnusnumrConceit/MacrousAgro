<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Invoice;
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
        factory(Order::class, 5)->create()->each(function ($order) {
            $product = Product::inRandomOrder()->limit(1)->first();

            $order->positions()->create([
                'product_id' => $product->id,
                'order_item_status_code' => array_rand(array_flip(Order::getStatuses()))
            ]);
            $order->invoice()->create([
                'payment_amount' => rand(100, 2000),
                'invoice_status_code' => array_rand(array_flip(Invoice::getStatuses()))
            ]);
        });
    }
}
