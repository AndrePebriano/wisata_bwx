<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        // Debug jika perlu: cek apakah field terkirim
        // dd($request->all());

        // Validasi data input
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'nilai' => 'required|numeric'
        ]);

        // Insert data secara eksplisit
        Kategori::create([
            'nama_kategori' => $validated['nama_kategori'],
            'nilai' => $validated['nilai'],
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(Kategori $kategori)
    {
        return view('admin.kategori.show', compact('kategori'));
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'nilai' => 'required|numeric'
        ]);

        $kategori->update([
            'nama_kategori' => $validated['nama_kategori'],
            'nilai' => $validated['nilai'],
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
