<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEmployeeSubCostCenterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('employee_sub_cost_center');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('employee_sub_cost_center', function(Blueprint $table)
		{
			$table->integer('employee_id')->unsigned(); 		// foreign key
			$table->integer('sub_cost_center_id')->unsigned(); 	// foreign key
			$table->boolean('active')->default(1);
			$table->timestamps();
			
			$table->foreign('employee_id')->references('id')->on('employees')
				->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('sub_cost_center_id')->references('id')->on('sub_cost_centers')
				->onUpdate('cascade')->onDelete('restrict');
		});
	}

}
