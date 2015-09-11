<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('position_id')->unsigned(); // foreign key
			$table->integer('sub_cost_center_id')->unsigned(); // foreign key
			$table->string('name');
			$table->string('lastname');
			$table->string('identification_number')->unique();
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('position_id')->references('id')->on('positions');
			$table->foreign('sub_cost_center_id')->references('id')->on('sub_cost_centers');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees');
	}

}
