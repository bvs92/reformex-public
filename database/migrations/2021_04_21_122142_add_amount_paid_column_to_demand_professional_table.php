<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountPaidColumnToDemandProfessionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demand_professional', function (Blueprint $table) {
            $table->bigInteger('amount_paid')->default(0)->after('professional_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demand_professional', function (Blueprint $table) {
            $table->dropColumn('amount_paid');
        });
    }
}
