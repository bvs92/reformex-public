<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToQuotesTable extends Migration
{
    /**
     * Run the migrations.
     * Adaugat pentru a facilita accesul la USER (profesionist).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('professional_id'); // user_id al profesionistului.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
