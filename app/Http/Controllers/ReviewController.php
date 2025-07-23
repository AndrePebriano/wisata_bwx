<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tempat_Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'required|string|max:1000',
        ]);

        Review::create([
            'tempat_wisata_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'ulasan' => $request->ulasan
        ]);

        // Update rata-rata rating setelah insert
        $wisata = Tempat_Wisata::find($id);
        $wisata->rating_rata_rata = $wisata->reviews()->avg('rating');
        $wisata->save();

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}
