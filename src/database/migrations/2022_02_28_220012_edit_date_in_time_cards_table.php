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
            $table->date('date')->nullable()->index('date')->after('category_id')->comment('年月日');
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
            $table->dropColumn('date');
        });
    }
};
