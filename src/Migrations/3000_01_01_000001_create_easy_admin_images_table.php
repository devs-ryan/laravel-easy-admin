<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEasyAdminImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('easy_admin_images', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('title');
            $table->string('alt')->nullable();
            $table->text('description')->nullable();
            $table->string('width');
            $table->string('height');
            $table->string('size');
            $table->string('model');
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
        Schema::dropIfExists('easy_admin_images');
    }
}
