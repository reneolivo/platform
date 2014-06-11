<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThorFilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->text('caption')->nullable()->default(null);
            $table->string('path');
            $table->string('type')->nullable()->default(null); // image, document, ...
            $table->string('group')->nullable()->default(null); // gallery name, group, ...
            $table->integer('fileable_id');
            $table->string('fileable_type');
            $table->integer('sorting')->default(0);
            $table->timestamp('published_at')->nullable()->default(null);
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
        Schema::drop('files');
    }

}
