<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuantityColumnOnActivityReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activity_reports', function(Blueprint $table)
		{
			$table->decimal('quantity', 8, 2)->default(0)->change();
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
			$table->decimal('quantity', 4, 2)->default(0)->change();
		});
	}

}
