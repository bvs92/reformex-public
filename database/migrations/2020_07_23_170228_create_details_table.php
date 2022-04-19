<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Status: 0 = activ, 1 = inactiv
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_id');
            $table->integer('status')->default(0);
            $table->integer('offers')->default(3);
            $table->timestamps();
        });

        Schema::table('details', function (Blueprint $table) {
            $table->foreign('demand_id')->references('id')->on('demands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details');
    }
}
