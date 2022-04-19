<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProjectCategoryWorkProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_category_work_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_category_id');
            $table->unsignedBigInteger('work_project_id');
            $table->timestamps();

            $table->foreign('project_category_id')->references('id')->on('project_categories')->onDelete('cascade');
            $table->foreign('work_project_id')->references('id')->on('work_projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_category_work_project');
    }
}
