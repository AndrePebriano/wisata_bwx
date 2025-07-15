<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tempat_Wisata extends Model
{
    use HasFactory;

    protected $table = 'tempat_wisatas';

    protected $fillable = [
        'nama_tempat_wisata',
        'lokasi',
        'deskripsi',
        'rating_rata_rata',
        'harga',
        'gambar',
        'kategori_id',
        'fasilitas_id'
    ];

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_tempat_wisata', 'tempat_wisata_id', 'kategori_id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_tempat_wisata', 'tempat_wisata_id', 'fasilitas_id');
    }

    // Relasi: tempat wisata memiliki banyak review
    // public function reviews()
    // {
    //     return $this->hasMany(Review::class, 'tempat_wisata_id');
    // }

    // Relasi: tempat wisata memiliki banyak favorit
    // public function favorites()
    // {
    //     return $this->hasMany(Favorite::class, 'tempat_wisata_id');
    // }

    // Dapatkan rating rata-rata
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }

    // Cek apakah tempat ini difavoritkan oleh user tertentu
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'tempat_wisata_id', 'user_id')
            ->withTimestamps();
    }
}
