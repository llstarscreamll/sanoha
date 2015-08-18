<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoveltyReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('novelty_reports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('sub_cost_center_id')->unsigned(); // foreign key
			$table->integer('employee_id')->unsigned(); // foreign key
			$table->integer('novelty_id')->unsigned(); // foreign key
			$table->string('comment')->nullable();
			$table->dateTime('reported_at');
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('sub_cost_center_id')->references('id')->on('sub_cost_centers')
				->onUpdate('cascade')->onDelete('restrict');
			
			$table->foreign('employee_id')->references('id')->on('employees')
				->onUpdate('cascade')->onDelete('restrict');
			
			$table->foreign('novelty_id')->references('id')->on('novelties')
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
		Schema::drop('novelty_reports');
	}

}
