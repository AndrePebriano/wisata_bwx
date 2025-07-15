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
        Schema::create('kategori_tempat_wisata', function (Blueprint $table) {
    $table->foreignId('kategori_id')->constrained()->onDelete('cascade');
    $table->foreignId('tempat_wisata_id')->constrained()->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('fasilitas_tempat_wisata', function (Blueprint $table) {
    $table->foreignId('fasilitas_id')->constrained()->onDelete('cascade');
    $table->foreignId('tempat_wisata_id')->constrained()->onDelete('cascade');
});
    }
};
