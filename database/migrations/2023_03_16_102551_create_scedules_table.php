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
            $table->uuid('id');
            $table->string('name')->unique();
            $table->uuid('user_id');
            $table->uuid('door_id');
            $table->time('time_begin');
            $table->time('time_end');
            $table->boolean('is_repeating');
            $table->boolean('day_0');
            $table->boolean('day_1');
            $table->boolean('day_2');
            $table->boolean('day_3');
            $table->boolean('day_4');
            $table->boolean('day_5');
            $table->boolean('day_6');
            $table->timestamps();
        });

        Schema::table('scedules', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('door_id')->references('id')->on('doors')->onUpdate('cascade')->onDelete('cascade');
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
