<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // user_id of Professional User
            $table->unsignedBigInteger('demand_id');
            $table->text('message');
            $table->tinyInteger('status')->default('0');
            $table->timestamps();
        });

        Schema::table('demand_reports', function(Blueprint $table){
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('demand_id')->references('id')->on('demands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_reports');
    }
}
