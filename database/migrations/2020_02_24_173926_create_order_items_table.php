<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')
                ->comment('идентификатор товара')
                ->foreign()
                ->references('id')
                ->on('products')
                ->onDelete('set null');
            $table->unsignedBigInteger('order_id')
                ->comment('идентификатор заказа')
                ->foreign()
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
            $table->unsignedInteger('order_item_status_code')
                ->comment('статус позиции заказа');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
