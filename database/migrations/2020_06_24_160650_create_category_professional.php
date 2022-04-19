<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryProfessional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_professional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('professional_id');
            $table->timestamps();
        });

        Schema::table('category_professional', function(Blueprint $table){
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('professional_id')->references('id')->on('professionals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_professional');
    }
}
