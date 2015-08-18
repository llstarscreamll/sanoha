<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCostCenterOwnerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sub_cost_center_owner', function(Blueprint $table)
		{
			$table->integer('user_id')->unsigned(); // foreign key
			$table->integer('sub_cost_center_id')->unsigned(); // foreign key

			$table->foreign('user_id')->references('id')->on('users')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('sub_cost_center_id')->references('id')->on('sub_cost_centers')
				->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sub_cost_center_owner');
	}

}
