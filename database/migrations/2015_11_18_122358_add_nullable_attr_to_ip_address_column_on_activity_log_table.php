<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableAttrToIpAddressColumnOnActivityLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('activity_log', function(Blueprint $table)
		{
			$table->string('ip_address', 64)->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('activity_log', function(Blueprint $table)
		{
			$table->string('ip_address', 64)->change();
		});
	}

}
