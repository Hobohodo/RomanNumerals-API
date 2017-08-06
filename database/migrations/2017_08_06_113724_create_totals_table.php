<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTotalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('totals', function (Blueprint $table) {
            $table->smallInteger('integer')->primary()->comment('Integers passed in for conversion'); //MAKE UNIQUE
            $table->integer('total')->comment('Number of times this integer has been converted');
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
        Schema::dropIfExists('totals');
    }
}
