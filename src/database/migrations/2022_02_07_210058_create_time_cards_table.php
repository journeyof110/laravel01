<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('category_id')->nullable()->comment('カテゴリ');
            $table->year('year')->comment('年');
            $table->unsignedTinyInteger('month')->comment('月');
            $table->unsignedTinyInteger('day')->comment('日');
            $table->time('start_time')->nullable()->comment('開始時間');
            $table->time('end_time')->nullable()->comment('終了時間');
            $table->text('memo')->nullable()->comment('メモ');
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
        Schema::dropIfExists('time_cards');
    }
}
