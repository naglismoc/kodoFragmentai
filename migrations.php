<?php


public function up()
{
    Schema::create('routes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('route_name')->nullable();
        $table->integer('lenght_km')->nullable();
        $table->integer('time_h')->nullable();
        $table->integer('time_d')->nullable();
        $table->integer('coast')->nullable();
        $table->integer('route_prolong')->nullable();
        $table->integer('clear_road')->nullable();
        $table->text('description')->nullable();
        $table->string('videoUrl')->nullable();
        $table->string('startCoordinate')->nullable();
        $table->string('endCoordinate')->nullable();
        $table->string('pickupCoordinate')->nullable();
        $table->integer('status')->default('2');
        $table->foreign('user_id')->references('id')->on('users');
    });
}

?>
