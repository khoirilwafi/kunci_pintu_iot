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
            $table->uuid('id')->primary();
            $table->uuid('office_id');
            $table->string('device_id')->unique()->nullable();
            $table->uuid('socket_id')->nullable();
            $table->string('name');
            $table->boolean('is_lock')->default(1);
            $table->string('key')->unique()->nullable();
            $table->timestamps();
        });

        Schema::table('doors', function (Blueprint $table) {
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('socket_id')->references('id')->on('sockets')->onDelete('set null')->onUpdate('cascade');
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
