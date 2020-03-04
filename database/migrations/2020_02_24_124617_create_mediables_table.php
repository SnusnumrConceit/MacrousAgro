<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mediable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('media_id')->foreign()->references('id')->on('media')->onDelete('cascade');
            $table->string('mediable_id')->index()->comment('id полиморфа модели');
            $table->string('mediable_type', 50)->index()->comment('наименование полиморфа модели');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        if (Schema::hasColumn('mediable', 'media_id'))
//        {
//            Schema::table('mediable', function (Blueprint $table) {
//                $table->dropForeign(['media_id']);
//            });
//        }

        Schema::dropIfExists('mediable');
    }
}
