<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAreaIdAndAreaChiefToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->integer('area_id')->nullable()->unsigned()->after('id');
			$table->boolean('area_chief')->nullable()->dafault(false);
			
			$table->foreign('area_id')->references('id')->on('areas')
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
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropForeign('users_area_id_foreign');
			$table->dropColumn(['area_id', 'area_chief']);
		});
	}

}
