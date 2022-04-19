<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdRecommendCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_recommend_companies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('cui')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('description');

            $table->boolean('status')->default(true); // activ / inactiv
            $table->boolean('editable')->default(true); // activ / inactiv
            $table->boolean('processing')->default(false);
            $table->boolean('rejected')->default(0);
            $table->boolean('paid')->default(0);
            $table->tinyInteger('type')->default('0');
            $table->boolean('has_form')->default(true); // activ / inactiv
            $table->boolean('show_email')->default(true); // activ / inactiv
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::table('ad_recommend_companies', function (Blueprint $table) {
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
        Schema::dropIfExists('ad_recommend_companies');
    }
}
