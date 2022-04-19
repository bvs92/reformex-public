<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalitatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localitates', function (Blueprint $table) {
            $table->id();
            $table->string('nume');
            $table->string('diacritice');
            $table->string('judet');
            $table->string('auto');
            $table->bigInteger('zip');
            $table->bigInteger('populatie');
            $table->float('lat', 10, 8);
            $table->float('lng', 10, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localitates');
    }
}
