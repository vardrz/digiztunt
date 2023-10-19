<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadutaAntopometrisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baduta_antopometris', function (Blueprint $table) {
            $table->id();
            $table->integer('usia');
            $table->enum('jenis_kelamin', ['lk', 'pr']);
            $table->float('bbuMedian');
            $table->float('bbuMin1sd');
            $table->float('tbuMedian');
            $table->float('tbuMin1sd');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baduta_antopometris');
    }
}
