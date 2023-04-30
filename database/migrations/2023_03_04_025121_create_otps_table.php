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
        Schema::create('otps', function (Blueprint $table) {

            // identifier
            $table->uuid('id')->primary();
            $table->uuid('user_id');

            // data
            $table->string('code_otp');
            $table->dateTime('valid_until');

            // timestamp
            $table->timestamps();
        });

        Schema::table('otps', function (Blueprint $table) {

            // relation to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otps');
    }
};
