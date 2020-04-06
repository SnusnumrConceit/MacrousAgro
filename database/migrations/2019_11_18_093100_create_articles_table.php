<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')
                ->comment('Заголовок новости');
            $table->text('description')
                ->nullable()
                ->comment('Содержимое новости');
            $table->date('publication_date')
                ->comment('Дата публикации');
            $table->tinyInteger('is_publicated')
                ->comment('Флаг публикации');
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
        Schema::dropIfExists('articles');
    }
}
