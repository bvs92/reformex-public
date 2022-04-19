<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_message_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_message_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('extension');
            $table->string('mime_type');
            $table->string('path')->nullable();
            $table->timestamps();
        });

        Schema::table('client_message_files', function(Blueprint $table){
            $table->foreign('client_message_id')->references('id')->on('client_messages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('client_message_files');
    }
}
