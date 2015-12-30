<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiigoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('siigo', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('NIT')->nullable();
			$table->bigInteger('TERCERO')->nullable();
			$table->string('NOMBRE_TERCERO')->nullable();
			$table->string('DIRECCION_TERCERO')->nullable();
			$table->string('CUENTA')->nullable();
			$table->string('CTA')->nullable();
			$table->string('DESCRIPCION')->nullable();
			$table->string('COMPROBANTE')->nullable();
			$table->date('FECHA')->nullable();
			$table->string('DETALLE')->nullable();
			$table->string('DOCUMENTO_CRUCE')->nullable();
			$table->bigInteger('DEBITOS')->nullable();
			$table->bigInteger('CREDITOS')->nullable();
			$table->bigInteger('SALDO')->nullable();
			$table->bigInteger('SALDO_TOTAL')->nullable();

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('siigo');
	}

}
