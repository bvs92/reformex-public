<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateStatusToDemands extends Migration
{
    /**
     * Run the migrations.
     * 
     * State: 0 = inactive, 1 = active
     * Status: 0 = neverificat, 1 = verificat, 2 = fals
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->tinyInteger('state')->default('1')->after('message');
            $table->tinyInteger('status')->default('0')->after('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demands', function (Blueprint $table) {
            $table->dropColumn('state');
            $table->dropColumn('status');
        });
    }
}
