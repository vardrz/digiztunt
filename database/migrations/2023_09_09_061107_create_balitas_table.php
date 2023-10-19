<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tgl_lahir');
            $table->string('nik', 16)->unique();
            $table->enum('jenis_kelamin', ['lk', 'pr']);
            $table->string('nama_ibu');
            $table->string('nik_ibu', 16);
            $table->string('nama_ayah');
            $table->string('nik_ayah', 16);
            $table->string('no_kk', 16);
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->enum('status', ['Stunting', 'Normal'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balitas');
    }
}
