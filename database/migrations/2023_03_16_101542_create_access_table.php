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
        Schema::create('access', function (Blueprint $table) {

            // identifier
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('door_id');

            // data
            $table->time('time_begin');
            $table->time('time_end');
            $table->date('date_begin')->default(null)->nullable();
            $table->date('date_end')->default(null)->nullable();
            $table->boolean('is_temporary')->nullable();
            $table->boolean('is_running')->nullable();

            // timestamp
            $table->timestamps();
        });

        Schema::table('access', function (Blueprint $table) {

            // relation to users table
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('access');
    }
};
