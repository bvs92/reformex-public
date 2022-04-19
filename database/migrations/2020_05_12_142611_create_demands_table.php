<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demands', function (Blueprint $table) {
            $table->id();
            // $table->string('uuid');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('subject');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('city');
            // $table->string('_geoloc');
            $table->float('lat', 8, 6);
            $table->float('lng', 8, 6);
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('demands', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demands');
    }
}
