<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('demand_id');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('client_messages', function(Blueprint $table){
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
        Schema::dropIfExists('client_messages');
    }
}
