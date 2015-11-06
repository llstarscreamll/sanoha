<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeOwnersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employee_owners', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned(); // foreign key
			$table->integer('employee_id')->unsigned(); // foreign key
			
			$table->foreign('user_id')->references('id')->on('users')
				->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('employee_id')->references('id')->on('employees')
				->onUpdate('cascade')->onDelete('restrict');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employee_owners');
	}

}
