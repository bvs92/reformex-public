<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_ticket_id');
            $table->string('name');
            $table->string('extension');
            $table->string('mime_type');
            $table->string('path')->nullable();
            $table->timestamps();
        });

        Schema::table('response_files', function(Blueprint $table){
            $table->foreign('response_ticket_id')->references('id')->on('response_tickets')->onDelete('cascade');
            // $table->foreign('file_id')->references('id')->on('response_tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('response_files');
    }
}
