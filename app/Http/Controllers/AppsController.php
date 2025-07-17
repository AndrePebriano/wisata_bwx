<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Models\Tempat_Wisata;
use App\Models\User;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AppsController extends Controller
{
    /**
     * Halaman beranda untuk user, menampilkan tempat wisata dengan filter.
     */
    public function index(Request $request, RekomendasiService $rekomendasiService)
    {
        $kategoris = Kategori::all();
        $fasilitas = Fasilitas::all();
        $showAll = $request->has('semua');
        $isRekomendasi = $request->has('rekomendasi');

        // Jika user klik "Tampilkan Rekomendasi untuk Saya"
        if ($isRekomendasi) {
            $selectedKategori = (array) $request->input('kategori', []);
            $selectedFasilitas = (array) $request->input('fasilitas', []);
            $harga = $request->input('harga');
            $rating = $request->input('rating');

            $rekomendasi = $rekomendasiService->getRekomendasi($selectedKategori, $selectedFasilitas, $harga, $rating);

            $tempatWisata = collect($rekomendasi)->pluck('tempat');

            if (!$showAll) {
                $tempatWisata = $tempatWisata->take(6);
            }

            return view('apps.home', [
                'kategoris' => $kategoris,
                'fasilitas' => $fasilitas,
                'tempatWisata' => $tempatWisata,
                'showAll' => $showAll,
                'rekomendasi' => $rekomendasi,
                'isRekomendasi' => true
            ]);
        }


        // Filter biasa (tanpa rekomendasi)
        $query = Tempat_Wisata::query();

        if ($request->kategori) {
            $query->whereHas('kategoris', function ($q) use ($request) {
                $q->whereIn('kategoris.id', (array) $request->kategori);
            });
        }

        if ($request->harga) {
            $query->where('harga', '<=', $request->harga);
        }

        if ($request->fasilitas) {
            $query->whereHas('fasilitas', function ($q) use ($request) {
                $q->whereIn('fasilitas.id', (array) $request->fasilitas);
            });
        }

        if ($request->rating) {
            $query->where('rating_rata_rata', '>=', $request->rating);
        }

        $query->orderByDesc('rating_rata_rata');
        $tempatWisata = $showAll ? $query->get() : $query->take(6)->get();

        return view('apps.home', compact('kategoris', 'fasilitas', 'tempatWisata', 'showAll') + ['isRekomendasi' => false]);
    }



    /**
     * Detail tempat wisata.
     */
    public function show($id)
    {
        $wisata = Tempat_Wisata::with(['kategoris', 'fasilitas'])->findOrFail($id);
        return view('apps.detail', compact('wisata'));
    }

    /**
     * Form edit profil user.
     */
    public function edit(Request $request)
    {
        return view('apps.editprofile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Proses update data profil user.
     */


    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        if (!$user) {
            return back()->withErrors(['error' => 'User tidak ditemukan.']);
        }

        // Validasi password lama jika password baru diisi
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['Password lama tidak sesuai.']);
            }
            $user->password = Hash::make($request->password);
        }

        // Update nama dan email
        $user->name = $validatedData['name'] ?? $user->name;
        $user->email = $validatedData['email'] ?? $user->email;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
