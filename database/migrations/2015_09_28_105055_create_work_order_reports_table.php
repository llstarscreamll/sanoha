<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrderReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('work_order_reports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('work_order_id')->unsigned()->comment('El id de la orden de trabajo');
			$table->text('work_order_report')->nullable()->comment('El reporte de los trabajos realizados por el empleado');
			$table->integer('reported_by')->unsigned()->nullable()->comment('Quien realiza el reporte de las actividades realizadas en la orden de trabajo');
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('work_order_id')->references('id')->on('work_orders')
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
		Schema::drop('work_order_reports');
	}

}
