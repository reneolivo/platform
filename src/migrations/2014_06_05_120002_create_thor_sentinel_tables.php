<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThorSentinelTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the roles table
        Schema::create('roles', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name')->unique();
            $table->string('display_name')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->timestamps();
        });

        // Creates the user_roles (Many-to-Many relation) table
        Schema::create('user_roles', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users'); // assumes a users table
            $table->foreign('role_id')->references('id')->on('roles');
            $table->timestamps();
        });

        // Creates the permissions table
        Schema::create('permissions', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name')->unique();
            $table->string('display_name')->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->timestamps();
        });

        // Creates the role_permissions (Many-to-Many relation) table
        Schema::create('role_permissions', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');
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
        Schema::table('user_roles', function(Blueprint $table) {
            $table->dropForeign('user_roles_user_id_foreign');
            $table->dropForeign('user_roles_role_id_foreign');
        });

        Schema::table('role_permissions', function(Blueprint $table) {
            $table->dropForeign('role_permissions_permission_id_foreign');
            $table->dropForeign('role_permissions_role_id_foreign');
        });

        Schema::drop('user_roles');
        Schema::drop('role_permissions');
        Schema::drop('roles');
        Schema::drop('permissions');
    }

}
