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
        Schema::create('door_scedule', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('scedule_id');
            $table->uuid('door_id');
            $table->timestamps();
        });

        Schema::table('door_scedule', function (Blueprint $table) {
            $table->foreign('scedule_id')->references('id')->on('scedules')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('door_scedule');
    }
};
