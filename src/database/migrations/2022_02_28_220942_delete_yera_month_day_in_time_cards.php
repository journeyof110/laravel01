<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_cards', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('month');
            $table->dropColumn('day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_cards', function (Blueprint $table) {
            $table->year('year')->after('category_id')->comment('年');
            $table->unsignedTinyInteger('month')->after('year')->comment('月');
            $table->unsignedTinyInteger('day')->after('month')->comment('日');
        });
    }
};
