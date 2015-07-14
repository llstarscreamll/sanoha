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
			$table->integer('employee_id')->unsigned(); // foreign key
			$table->integer('mining_activity_id')->unsigned(); // foreign key
			$table->integer('quantity')->default(0);
			$table->integer('price')->default('0');
			$table->string('comment')->nullable();
			$table->integer('reported_by')->unsigned(); // foreign key
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('employee_id')->references('id')->on('employees')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('mining_activity_id')->references('id')->on('mining_activities')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('reported_by')->references('id')->on('users')
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
		Schema::drop('activity_reports');
	}

}
