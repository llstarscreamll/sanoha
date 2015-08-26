<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWorkedHoursColumnToactivityReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activity_reports', function(Blueprint $table)
		{
			$table->integer('worked_hours')->default(1)->after('price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activity_reports', function(Blueprint $table)
		{
			$table->dropColumn('worked_hours');
		});
	}

}
