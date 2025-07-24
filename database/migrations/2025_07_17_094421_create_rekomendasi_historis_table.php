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
        Schema::create('rekomendasi_historis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tempat_wisata_id')->constrained()->onDelete('cascade');
            $table->json('user_vector');
            $table->json('vektor_kategori');  // hasil normalisasi kategori
            $table->json('vektor_fasilitas'); // hasil normalisasi fasilitas
            $table->decimal('vektor_harga', 5, 3); // hasil normalisasi harga
            $table->decimal('vektor_rating', 5, 3); // hasil normalisasi rating
            $table->decimal('skor_similarity', 6, 4); // skor cosine similarity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_historis');
    }
};
