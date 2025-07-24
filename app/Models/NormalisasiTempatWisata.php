<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormalisasiTempatWisata extends Model
{
    use HasFactory;
    protected $table = 'normalisasi_tempat_wisata';

    protected $fillable = [
        'tempat_wisata_id',
        'vektor_kategori',
        'vektor_fasilitas',
        'vektor_harga',
        'vektor_rating',
    ];

    protected $casts = [
        'vektor_fasilitas' => 'array',
    ];

    public function tempat()
    {
        return $this->belongsTo(Tempat_Wisata::class, 'tempat_wisata_id');
    }
}
