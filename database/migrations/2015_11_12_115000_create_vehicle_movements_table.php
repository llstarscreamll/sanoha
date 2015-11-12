<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleMovementsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_movements', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('work_order_id')->unsigned();
            $table->integer('registered_by')->unsigned();
            $table->string('action');
            $table->bigInteger('mileage');
            $table->string('fuel_level');
            $table->string('internal_cleanliness');
            $table->string('external_cleanliness');
            $table->string('paint_condition');
            $table->string('bodywork_condition');
            $table->string('right_front_wheel_condition');
            $table->string('left_front_wheel_condition');
            $table->string('rear_right_wheel_condition');
            $table->string('rear_left_wheel_condition');
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('work_order_id')->references('id')->on('work_orders')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('registered_by')->references('id')->on('users')
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
        Schema::drop('vehicle_movements');
    }

}
