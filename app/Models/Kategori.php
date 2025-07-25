<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $fillable = ['nama_kategori', 'nilai'];


    public function tempatWisatas()
{
    return $this->belongsToMany(Tempat_Wisata::class, 'kategori_tempat_wisata', 'kategori_id', 'tempat_wisata_id');
}

}
