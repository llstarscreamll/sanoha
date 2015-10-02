<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// tabla ordendes de trabajo
		Schema::create('work_orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('authorized_by')->unsigned()->comment('El id del usuario quien autoriza la orden de trabajo');
			$table->integer('vehicle_id')->unsigned()->comment('El id del vehículo en que se realiza la orden de trabajo');
			$table->integer('vehicle_responsable')->unsigned()->comment('El id del empleado responsable del vehículo');
			$table->string('destination')->comment('El lugar o destino donde se llevará a cabo la orden de trabajo');
			$table->string('work_description')->comment('La descripción de las tareas o trabajos a realizar');
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('authorized_by')->references('id')->on('users')
				->onUpdate('cascade')->onDelete('restrict');
				
			$table->foreign('vehicle_id')->references('id')->on('vehicles')
				->onUpdate('cascade')->onDelete('restrict');
				
			$table->foreign('vehicle_responsable')->references('id')->on('employees')
				->onUpdate('cascade')->onDelete('restrict');
		});
		
		// tabla pivote acompañantes internos, las demás personas involucradas en la orden de trabajo
		Schema::create('internal_accompanists', function(Blueprint $table)
		{
			$table->integer('work_order_id')->unsigned()->comment('El id de la orden de trabajo');
			$table->integer('employee_id')->unsigned()->comment('El id del empleado involucrado en la orden de trabajo');
			$table->text('work_report')->nullable()->comment('El reporte o descripción de las tareas realizadas por el empleado en la orden de trabajo');
			$table->integer('reported_by')->unsigned()->nullable()->comment('El id del usuario quien reporta las actividades realizadas');
			$table->dateTime('reported_at')->nullable()->comment('La fecha en que se reportan las actividades realizadas');
			
			$table->foreign('work_order_id')->references('id')->on('work_orders')
				->onUpdate('cascade')->onDelete('restrict');
				
			$table->foreign('employee_id')->references('id')->on('employees')
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
		Schema::drop('internal_accompanists');
		Schema::drop('work_orders');
	}

}
