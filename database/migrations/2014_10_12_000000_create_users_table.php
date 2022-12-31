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
            $table->id();
            $table->bigInteger('school_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->integer('otp_code')->nullable();
            $table->integer('otp_reset_time')->nullable();
            $table->integer('forget_code')->nullable();
            $table->integer('forget_expire')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_banned')->default(false);
            $table->boolean('hide_location')->default(true);
            $table->boolean('is_muted')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->integer('status')->default(1);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
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
