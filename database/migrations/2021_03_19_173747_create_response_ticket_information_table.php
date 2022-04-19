<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponseTicketInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_ticket_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_ticket_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('ticket_id');
            $table->timestamp('read_at')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::table('response_ticket_information', function (Blueprint $table) {
            $table->foreign('response_ticket_id')->references('id')->on('response_tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('response_ticket_information');
    }
}
