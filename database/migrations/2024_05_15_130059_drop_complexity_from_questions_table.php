<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropComplexityFromQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('complixty');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->integer('complixty')->default(1); // or set the original default value
        });
    }
}
