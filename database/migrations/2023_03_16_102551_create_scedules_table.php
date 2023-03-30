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
        Schema::create('scedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('user_id');
            $table->date('date_running');
            $table->time('time_begin');
            $table->time('time_end');
            $table->boolean('is_repeating')->nullable();
            $table->boolean('day_0')->nullable();
            $table->boolean('day_1')->nullable();
            $table->boolean('day_2')->nullable();
            $table->boolean('day_3')->nullable();
            $table->boolean('day_4')->nullable();
            $table->boolean('day_5')->nullable();
            $table->boolean('day_6')->nullable();
            $table->timestamps();
        });

        Schema::table('scedules', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scedules');
    }
};
