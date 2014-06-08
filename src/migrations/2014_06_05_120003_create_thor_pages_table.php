<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThorPagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('taxonomy')->nullable()->default('page');
            $table->string('controller')->nullable()->default(null);
            $table->string('action')->nullable()->default(null);
            $table->string('view')->nullable()->default(null);
            $table->boolean('is_https')->nullable()->default(false);
            $table->boolean('is_indexable')->nullable()->default(true);
            $table->boolean('is_deletable')->nullable()->default(true);
            $table->integer('sorting')->nullable()->default(0);
            $table->string('status')->nullable()->default('draft');
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->foreign('parent_id')->references('id')->on('pages');
            $table->timestamps();
        });

        Schema::create('page_texts', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('page_id')->unsigned();
            $table->integer('language_id')->unsigned();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('language_id')->references('language_id')->on('languages')->onDelete('cascade');
            $table->string('title')->nullable()->default(null);
            $table->text('content')->nullable()->default(null);
            $table->text('slug')->nullable()->default(null);
            $table->string('window_title')->nullable()->default(null);
            $table->string('meta_description')->nullable()->default(null);
            $table->string('meta_keywords')->nullable()->default(null);
            $table->string('canonical_url')->nullable()->default(null);
            $table->string('redirect_url')->nullable()->default(null);
            $table->string('redirect_code')->nullable()->default(null);
            $table->boolean('is_translated')->nullable()->default(null);
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
        Schema::table('page', function(Blueprint $table) {
            $table->dropForeign('page_parent_id_foreign');
        });

        Schema::table('page_texts', function(Blueprint $table) {
            $table->dropForeign('page_texts_page_id_foreign');
            $table->dropForeign('page_texts_language_id_foreign');
        });
        Schema::drop('page_texts');
        Schema::drop('pages');
    }

}
