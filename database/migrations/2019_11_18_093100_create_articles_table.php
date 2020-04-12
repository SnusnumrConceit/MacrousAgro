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
                ->unique()
                ->comment('Заголовок новости');
            $table->text('description')
                ->nullable()
                ->comment('Содержимое новости');
            $table->date('publication_date')
                ->index()
                ->comment('Дата публикации');
            $table->tinyInteger('is_publicated')
                ->index()
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
