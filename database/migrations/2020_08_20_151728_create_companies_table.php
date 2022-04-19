<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('year_made')->nullable();
            $table->unsignedInteger('workers')->nullable();
            $table->string('cui')->nullable();
            $table->string('register_number')->nullable();
            $table->string('city')->nullable();
            $table->string('administrative')->nullable();
            $table->string('address')->nullable();
            // $table->text('bio')->nullable();
            // $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
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
        Schema::dropIfExists('companies');
    }
}
