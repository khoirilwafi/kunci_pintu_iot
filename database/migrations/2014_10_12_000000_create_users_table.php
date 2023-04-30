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
        Schema::create('users', function (Blueprint $table) {

            // indentifier
            $table->uuid('id')->primary();
            $table->uuid('added_by')->nullable();

            // authentication parameter
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();

            // profile
            $table->string('name')->unique();
            $table->string('phone')->unique();
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->enum('role', ['moderator', 'operator', 'pengguna']);
            $table->string('avatar')->nullable();

            // timestamp data
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {

            // self-reference relation
            $table->foreign('added_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
