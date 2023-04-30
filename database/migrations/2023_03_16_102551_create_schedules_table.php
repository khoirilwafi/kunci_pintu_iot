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
        Schema::create('schedules', function (Blueprint $table) {

            // identifier
            $table->uuid('id')->primary();
            $table->uuid('office_id');

            // data
            $table->string('name');
            $table->date('date_begin');
            $table->date('date_end');
            $table->time('time_begin');
            $table->time('time_end');
            $table->boolean('is_repeating')->default(0);
            $table->string('day_repeating');
            $table->enum('status', ['waiting', 'running', 'done'])->default('waiting');

            // timestamp
            $table->timestamps();
        });

        Schema::table('schedules', function (Blueprint $table) {

            // relation to offices table
            $table->foreign('office_id')->references('id')->on('offices')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
