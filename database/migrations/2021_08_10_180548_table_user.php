<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('full_name');
            $table->string('photo_profile')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->text('password');
            $table->date('birthday')->nullable();
            $table->boolean('is_verified');
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('verification_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
