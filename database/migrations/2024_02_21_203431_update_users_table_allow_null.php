<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableAllowNull extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Update the column to allow null
            $table->string('father_name')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('age')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert the column to disallow null
            $table->string('father_name')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('age')->nullable(false)->change();
        });
    }
}
