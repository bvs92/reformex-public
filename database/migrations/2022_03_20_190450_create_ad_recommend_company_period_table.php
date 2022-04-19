<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdRecommendCompanyPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_recommend_company_period', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_recommend_company_id');
            $table->unsignedBigInteger('period_id');
            $table->timestamps();
        });

        Schema::table('ad_recommend_company_period', function (Blueprint $table) {
            $table->foreign('period_id')->references('id')->on('periods')->onDelete('cascade');
            $table->foreign('ad_recommend_company_id')->references('id')->on('ad_recommend_companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_recommend_company_period');
    }
}
