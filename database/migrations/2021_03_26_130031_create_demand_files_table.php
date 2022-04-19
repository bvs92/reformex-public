<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('demand_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demand_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('extension');
            $table->string('path')->nullable();
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });

        Schema::table('demand_files', function (Blueprint $table) {
            $table->foreign('demand_id')->references('id')->on('demands')->onDelete('cascade');
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
        Schema::dropIfExists('demand_files');
    }
}
