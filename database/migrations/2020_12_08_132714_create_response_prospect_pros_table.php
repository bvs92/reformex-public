<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseProspectProsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_prospect_pros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prospect_id');
            $table->unsignedBigInteger('user_id'); // responding user.
            $table->boolean('response')->nullable();
            $table->timestamps();
        });

        Schema::table('response_prospect_pros', function(Blueprint $table){
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('response_prospect_pros');
    }
}
