<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_period', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_id');
            $table->unsignedBigInteger('period_id');
            $table->timestamps();
        });

        Schema::table('banner_period', function (Blueprint $table) {
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
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
        Schema::dropIfExists('banner_period');
    }
}
