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
        Schema::create('access_logs', function (Blueprint $table) {

            // identifier
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('door_id');
            $table->uuid('office_id');

            // data
            $table->text('log');

            // timestamp
            $table->timestamps();
        });

        Schema::table('access_logs', function (Blueprint $table) {

            // relation to users table
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            // relation to doors table
            $table->foreign('door_id')->references('id')->on('doors')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('access_logs');
    }
};
