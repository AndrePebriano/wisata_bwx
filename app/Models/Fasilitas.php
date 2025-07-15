<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas'; // nama tabel sesuai database
    protected $fillable = ['nama_fasilitas', 'nilai'];

    public function tempatWisatas()
{
    return $this->belongsToMany(Tempat_Wisata::class, 'fasilitas_tempat_wisata', 'fasilitas_id', 'tempat_wisata_id');
}

}