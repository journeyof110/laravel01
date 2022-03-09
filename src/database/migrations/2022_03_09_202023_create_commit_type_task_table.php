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
        Schema::create('commit_type_task', function (Blueprint $table) {
            $table->id()->comment('コミット種別作業ID');
            $table->foreignId('commit_type_id')->comment('コミット種別ID')->constrained();
            $table->foreignId('task_id')->comment('作業ID')->constrained();
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
        Schema::dropIfExists('commit_type_task');
    }
};
