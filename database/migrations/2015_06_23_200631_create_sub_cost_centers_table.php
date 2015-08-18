<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCostCentersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sub_cost_centers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('cost_center_id')->unsigned();
			$table->string('name');
			$table->string('short_name');
			$table->string('description')->nullable();
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('cost_center_id')->references('id')->on('cost_centers')
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
		Schema::drop('sub_cost_centers');
	}

}
