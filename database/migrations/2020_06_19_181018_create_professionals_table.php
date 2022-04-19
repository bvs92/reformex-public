<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfessionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // $table->string('name')->nullable();
            $table->string('city')->nullable();
            $table->string('administrative')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->unsignedBigInteger('range')->nullable(); // in m
            $table->timestamps();
        });

        Schema::table('professionals', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('professionals');
    }
}
