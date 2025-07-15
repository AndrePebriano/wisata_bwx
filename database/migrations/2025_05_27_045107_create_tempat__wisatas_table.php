<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempatWisatasTable extends Migration
{
    public function up()
    {
        Schema::create('tempat_wisatas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tempat_wisata', 50);
            $table->string('gambar');
            $table->text('lokasi');
            $table->text('deskripsi');
            $table->float('rating_rata_rata');
            $table->integer('harga')->nullable();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('tempat_wisatas');
    }
}
