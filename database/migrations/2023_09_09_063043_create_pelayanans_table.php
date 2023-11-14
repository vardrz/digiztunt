<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelayanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelayanans', function (Blueprint $table) {
            $table->id();
            $table->string('id_balita')->references('id')->on('balitas');
            $table->string('nik_balita', 16)->references('nik')->on('balitas');
            $table->date('tgl_pelayanan');
            $table->float('tb');
            $table->float('bb');
            $table->float('lingkar_kepala');
            $table->integer('usia');
            $table->enum('verif', ['y', 'n'])->default('n');
            $table->float('bbu')->nullable();
            $table->float('tbu')->nullable();
            // $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelayanans');
    }
}
