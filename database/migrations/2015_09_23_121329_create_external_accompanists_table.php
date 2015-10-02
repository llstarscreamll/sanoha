<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalAccompanistsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('external_accompanists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('work_order_id')->unsigned()->comment('El id de la orden de trabajo'); // foreign key
			$table->string('fullname')->comment('El nombre completo del acompañante externo');
			$table->timestamps();
			
			$table->foreign('work_order_id')->references('id')->on('work_orders')
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
		Schema::drop('external_accompanists');
	}

}
