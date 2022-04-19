<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdRecommendCompanyRejectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('ad_recommend_company_reject_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_recommend_company_id');
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::table('ad_recommend_company_reject_messages', function (Blueprint $table) {
            $table->foreign('ad_recommend_company_id', 'ads_r_c_r_m_foreign')->references('id')->on('ad_recommend_companies')->onDelete('cascade');
            // ads_r_c_r_m_foreign = foreign for ad_recommend_company_reject_messages
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_recommend_company_reject_messages');
    }
}
