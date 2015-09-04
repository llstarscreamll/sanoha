<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('activity_reports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('sub_cost_center_id')->unsigned(); // foreign key
			$table->integer('employee_id')->unsigned(); // foreign key
			$table->integer('mining_activity_id')->unsigned(); // foreign key
			$table->decimal('quantity', 3, 2)->default(0);
			$table->bigInteger('price')->default(0);
			$table->string('comment')->nullable();
			$table->integer('reported_by')->unsigned(); // foreign key
			$table->dateTime('reported_at');
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('sub_cost_center_id')->references('id')->on('sub_cost_centers')
				->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('employee_id')->references('id')->on('employees')
				->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('mining_activity_id')->references('id')->on('mining_activities')
				->onUpdate('cascade')->onDelete('restrict');
			$table->foreign('reported_by')->references('id')->on('users')
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
		Schema::drop('activity_reports');
	}

}
