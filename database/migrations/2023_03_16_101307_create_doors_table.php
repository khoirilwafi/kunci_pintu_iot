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
        Schema::create('doors', function (Blueprint $table) {

            // identifier
            $table->uuid('id')->primary();
            $table->uuid('office_id');
            $table->uuid('socket_id')->unique()->nullable();

            // data
            $table->string('name');
            $table->string('device_name')->unique()->nullable();
            $table->string('device_pass')->unique()->nullable();
            $table->string('key')->nullable();
            $table->boolean('is_lock')->default(1);

            // timestamp
            $table->timestamps();
        });

        Schema::table('doors', function (Blueprint $table) {

            // relation to offices table
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doors');
    }
};
