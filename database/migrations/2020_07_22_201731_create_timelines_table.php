<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('professional_id');
            $table->timestamps();
        });

        Schema::table('timelines', function(Blueprint $table){
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('timelines');
    }
}
