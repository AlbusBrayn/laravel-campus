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
        Schema::create('avatars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('skin')->nullable();
            $table->string('clothes')->nullable();
            $table->string('mouth')->nullable();
            $table->string('eyes')->nullable();
            $table->string('eye_brow')->nullable();
            $table->string('top')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('accessories')->nullable();
            $table->string('beard')->nullable();
            $table->string('beard_color')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avatars');
    }
};
