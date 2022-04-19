<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryDemandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_demand', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('demand_id');
            $table->timestamps();
        });

        Schema::table('category_demand', function(Blueprint $table){
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('category_demand');
    }
}
