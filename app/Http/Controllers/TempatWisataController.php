<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tempat_Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
use Illuminate\Support\Facades\Storage;

class TempatWisataController extends Controller
{
    public function index()
    {
        $tempatWisatas = Tempat_Wisata::with(['kategoris', 'fasilitas'])->get();
        return view('admin.tempat-wisata.index', compact('tempatWisatas'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        $fasilitas = Fasilitas::all();
        return view('admin.tempat-wisata.create', compact('kategori', 'fasilitas'));
    }

    
public function store(Request $request)
{
    $validated = $request->validate([
        'nama_tempat_wisata' => 'required|string|max:255',
        'lokasi' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'rating_rata_rata' => 'required|numeric|between:0,5',
        'harga' => 'nullable|numeric|min:0',
        'gambar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'id_kategori' => 'required|array',
        'id_kategori.*' => 'exists:kategoris,id',
        'id_fasilitas' => 'nullable|array',
        'id_fasilitas.*' => 'exists:fasilitas,id',
    ]);

    // Upload gambar
    $gambarPath = null;
    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $gambarPath = 'images/' . $filename;
    }

    $tempatWisata = Tempat_Wisata::create([
        'nama_tempat_wisata' => $validated['nama_tempat_wisata'],
        'lokasi' => $validated['lokasi'],
        'deskripsi' => $validated['deskripsi'],
        'rating_rata_rata' => $validated['rating_rata_rata'],
        'harga' => $validated['harga'],
        'gambar' => $gambarPath,
    ]);

    $tempatWisata->kategoris()->attach($validated['id_kategori']);
    if (!empty($validated['id_fasilitas'])) {
        $tempatWisata->fasilitas()->attach($validated['id_fasilitas']);
    }

    return redirect()->route('admin.tempat-wisata.index')->with('success', 'Tempat wisata berhasil ditambahkan.');
}

    public function edit($id)
    {
        $tempatWisata = Tempat_Wisata::findOrFail($id);
        $kategori = Kategori::all();
        $fasilitas = Fasilitas::all();
$selectedKategori = $tempatWisata->kategoris->pluck('id')->toArray();
$selectedFasilitas = $tempatWisata->fasilitas->pluck('id')->toArray();

        return view('admin.tempat-wisata.edit', compact(
            'tempatWisata', 'kategori', 'fasilitas', 'selectedKategori', 'selectedFasilitas'
        ));
    }

  public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nama_tempat_wisata' => 'required|string|max:255',
        'lokasi' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'rating_rata_rata' => 'required|numeric|between:0,5',
        'harga' => 'nullable|numeric|min:0',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'id_kategori' => 'required|array',
        'id_kategori.*' => 'exists:kategoris,id',
        'id_fasilitas' => 'nullable|array',
        'id_fasilitas.*' => 'exists:fasilitas,id',
    ]);

    $tempatWisata = Tempat_Wisata::findOrFail($id);

    if ($request->hasFile('gambar')) {
        if ($tempatWisata->gambar && file_exists(public_path($tempatWisata->gambar))) {
            unlink(public_path($tempatWisata->gambar));
        }

        $file = $request->file('gambar');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        $tempatWisata->gambar = 'images/' . $filename;
    }

    $tempatWisata->update([
        'nama_tempat_wisata' => $validated['nama_tempat_wisata'],
        'lokasi' => $validated['lokasi'],
        'deskripsi' => $validated['deskripsi'],
        'rating_rata_rata' => $validated['rating_rata_rata'],
        'harga' => $validated['harga'],
        'gambar' => $tempatWisata->gambar,
    ]);

    $tempatWisata->kategoris()->sync($validated['id_kategori']);
    $tempatWisata->fasilitas()->sync($validated['id_fasilitas'] ?? []);

    return redirect()->route('admin.tempat-wisata.index')->with('success', 'Tempat wisata berhasil diperbarui.');
}


    public function destroy($id)
    {
        $tempatWisata = Tempat_Wisata::findOrFail($id);

        // Hapus gambar dari storage
        if ($tempatWisata->gambar && Storage::disk('public')->exists($tempatWisata->gambar)) {
            Storage::disk('public')->delete($tempatWisata->gambar);
        }

        $tempatWisata->delete();

        return redirect()->route('admin.tempat-wisata.index')->with('success', 'Tempat wisata berhasil dihapus.');
    }
}
