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
        
        Schema::create('post_title_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('post_title_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title');

            $table->unique(['post_title_id', 'locale']);
            $table->foreign('post_title_id')->references('id')->on('post_titles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_title_translations');
    }
};
