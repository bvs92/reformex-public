<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
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
            $table->string('image');
            $table->boolean('status')->default(true); // activ / inactiv
            $table->boolean('has_form')->default(true); // activ / inactiv
            $table->boolean('show_email')->default(true); // activ / inactiv
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
