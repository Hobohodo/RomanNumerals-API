<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumeralsTable extends Migration
{
    /**
     * Run the migrations.
     * The reason I am not using integer as the primary key is in case of changing scope in future - maybe unrealistic
     * for this test.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('numerals', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger("integer")->unique(); // No point duplicating converted numerals
            $table->string("numeral");
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
        Schema::dropIfExists('numerals');
    }
}
