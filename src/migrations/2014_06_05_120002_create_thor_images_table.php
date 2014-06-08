<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThorImagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('alt')->nullable()->default(null);
            $table->string('path')->nullable()->default(null);
            $table->string('imageset'); // gallery name, group, ...
            $table->integer('imageable_id');
            $table->string('imageable_type');
            $table->integer('sorting')->default(0);
            $table->boolean('is_visible')->default(true);
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
        Schema::drop('images');
    }

}
