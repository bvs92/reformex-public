<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandProfessionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demand_professional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_id');
            $table->unsignedBigInteger('professional_id');
            $table->timestamps();
        });

        Schema::table('demand_professional', function(Blueprint $table){
            // $table->foreign('demand_id')->references('id')->on('demands')->onDelete('cascade');
            // $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demand_professional');
    }
}
