<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThorModulesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name')->nullable()->default(null);
            $table->string('display_name')->nullable()->default(null);
            $table->string('icon')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->boolean('is_pageable')->nullable()->default(null);
            $table->string('model_class')->nullable()->default(null);
            $table->string('controller_class')->nullable()->default(null);
            $table->text('metadata')->nullable()->default(null);
            $table->boolean('is_active')->nullable()->default(null);
            $table->integer('sorting')->nullable()->default(null);
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
        Schema::drop('modules');
    }

}
