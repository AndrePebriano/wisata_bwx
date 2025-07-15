<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Models\Tempat_Wisata;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
$totalWisata = Tempat_Wisata::count();
    $totalKategori = \App\Models\Kategori::count();
    $totalFasilitas = \App\Models\Fasilitas::count();

    $wisataList = Tempat_Wisata::with('kategori')
        ->orderByDesc('rating') // Jika ada kolom rating, atau pakai ->withAvg() jika dari relasi
        ->take(5);
        // ->get();

    $wisataTerbaru = Tempat_Wisata::with('kategori')
        ->latest()
        ->take(5);
        // ->get();

    return view('admin.dashboard', compact('totalWisata', 'totalKategori', 'totalFasilitas', 'wisataList', 'wisataTerbaru'));
    }

    /**
     * Tampilkan form edit profil.
     */
    public function edit(Request $request)
    {
        return view('admin.editprofile', [
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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::find(Auth::id());

        if (!$user) {
            return back()->withErrors(['error' => 'User tidak ditemukan.']);
        }

        // Validasi password lama jika ingin ubah password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }
            $user->password = Hash::make($request->password);
        }

        // Update nama dan email
        $user->name = $validatedData['name'] ?? $user->name;
        $user->email = $validatedData['email'] ?? $user->email;

        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && Storage::disk('public')->exists('photos/' . $user->photo)) {
                Storage::disk('public')->delete('photos/' . $user->photo);
            }

            $filename = time() . '.' . $request->file('photo')->extension();
            $request->file('photo')->storeAs('public/photos', $filename);
            $user->photo = $filename;
        }

        $user->save();

        return redirect()->route('admin.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
