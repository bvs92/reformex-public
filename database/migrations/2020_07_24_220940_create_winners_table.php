<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('professional_id');
            // $table->unsignedBigInteger('timeline_id');
            $table->integer('status')->default(0);
            $table->timestamps();
        });

        Schema::table('winners', function (Blueprint $table) {
            // $table->foreign('demand_id')->references('id')->on('demands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winners');
    }
}
