<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimelineIdToClientMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('timeline_id')->after('id');


            // $table->foreign('timeline_id')->references('id')->on('timeline')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_messages', function (Blueprint $table) {
            $table->dropColumn('timeline_id');
        });
    }
}
