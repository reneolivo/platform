<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThorUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the users table
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('username')->index();
            $table->string('display_name')->nullable()->default(null);
            $table->string('email')->index();
            $table->string('password');
            $table->string('remember_token')->nullable()->default(null);
            $table->timestamps();
        });

        // Creates password reminders table
        Schema::create('password_reminders', function(Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_reminders');
        Schema::drop('users');
    }

}
