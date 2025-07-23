<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $fillable = [
        'tempat_wisata_id',
        'user_id',
        'rating',
        'ulasan'
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
