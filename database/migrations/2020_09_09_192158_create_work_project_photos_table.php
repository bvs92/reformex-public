<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkProjectPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_project_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_project_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('extension');
            $table->string('mime_type')->nullable();
            $table->timestamps();

            $table->foreign('work_project_id')->references('id')->on('work_projects')->onDelete('cascade');
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
        Schema::dropIfExists('work_project_photos');
    }
}
