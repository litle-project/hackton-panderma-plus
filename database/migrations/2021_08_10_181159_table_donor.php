<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableDonor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->increments('donor_id');
            $table->integer('user_id');
            $table->enum('type', ['seeker', 'giver']);
            $table->integer('category_id');
            $table->string('cover')->nullable();
            $table->string('title');
            $table->string('phone');
            $table->longText('description');
            $table->integer('total_need')->nullable();
            $table->text('address');
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->dateTime('deadline')->nullable()->default(date('Y-m-d H:i:s'));
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
        Schema::drop('donors');
    }
}
