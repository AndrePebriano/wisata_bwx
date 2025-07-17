<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomendasiHistoris extends Model
{
    use HasFactory;
    protected $table = 'rekomendasi_historis';

    protected $fillable = [
        'user_id',
        'tempat_wisata_id',
        'vektor_kategori',
        'vektor_fasilitas',
        'vektor_harga',
        'vektor_rating',
        'skor_similarity'
    ];

    protected $casts = [
        'vektor_kategori' => 'array',
        'vektor_fasilitas' => 'array',
    ];

    public function tempatWisata()
    {
        return $this->belongsTo(Tempat_Wisata::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
