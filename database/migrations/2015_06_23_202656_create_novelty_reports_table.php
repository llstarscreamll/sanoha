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
			$table->integer('novelty_id')->unsigned(); // foreign key
			$table->integer('employee_id')->unsigned(); // foreign key
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('novelty_id')->references('id')->on('novelties')
				->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('employee_id')->references('id')->on('employees')
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
		Schema::drop('novelty_reports');
	}

}
