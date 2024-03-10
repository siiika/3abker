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
            $table->string('father_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('age')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('father_name');
            $table->dropColumn('phone');
            $table->string('age')->nullable(false)->change();
        });
    }
}
