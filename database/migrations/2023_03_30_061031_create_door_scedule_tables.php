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
        Schema::create('door_schedule', function (Blueprint $table) {

            // identifier
            $table->uuid('id')->primary();
            $table->uuid('schedule_id');
            $table->uuid('door_id');

            // timestamp
            $table->timestamps();
        });

        Schema::table('door_schedule', function (Blueprint $table) {

            // relation to schedules table
            $table->foreign('schedule_id')->references('id')->on('schedules')->onUpdate('cascade')->onDelete('cascade');

            // relation to doors table
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
        Schema::dropIfExists('door_schedule');
    }
};
