<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhoneToEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('employees', function(Blueprint $table)
		{
			$table->string('phone')->nullable()->default('')->after('email')->comment('El número(s) telefónico del empleado');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('employees', function(Blueprint $table)
		{
			$table->dropColumn('phone');
		});
	}

}
