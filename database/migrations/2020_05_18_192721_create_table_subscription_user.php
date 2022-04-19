<?php

use Illuminate\Database\Migrations\Migration;

class CreateTableSubscriptionUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('subscription_user', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('subscription_id');
        //     $table->unsignedBigInteger('user_id');
        //     $table->timestamps();
        // });

        // Schema::table('subscription_user', function(Blueprint $table){
        //     $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('subscription_user');
    }
}
