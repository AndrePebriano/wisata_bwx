<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('normalisasi_tempat_wisata', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tempat_wisata_id')->index();
            $table->float('vektor_kategori')->default(0);
            $table->json('vektor_fasilitas');
            $table->float('vektor_harga')->default(0);
            $table->float('vektor_rating')->default(0);
            $table->foreign('tempat_wisata_id')->references('id')->on('tempat_wisatas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normalisasi_tempat_wisata');
    }
};
