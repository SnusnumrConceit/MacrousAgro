<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')
                ->comment('идентификатор заказа')
                ->index()
                ->foreign()
                ->references('id')
                ->on('orders')
                ->onDelete('set null');
            $table->decimal('payment_amount', 2)
                ->comment('сумма заказа');
            $table->string('invoice_status_code')
                ->comment('статуса для счёт-фактуры');
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
        Schema::dropIfExists('invoices');
    }
}
