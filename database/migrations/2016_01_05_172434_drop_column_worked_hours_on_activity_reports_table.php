<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnWorkedHoursOnActivityReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_reports', function (Blueprint $table) {
            $table->dropColumn('worked_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_reports', function (Blueprint $table) {
            $table->integer('worked_hours')->default(8)->after('price'); // ocho horas de trabajo
        });
    }
}
