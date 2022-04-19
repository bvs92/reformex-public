<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerRejectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_reject_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_id');
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::table('banner_reject_messages', function (Blueprint $table) {
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_reject_messages');
    }
}
