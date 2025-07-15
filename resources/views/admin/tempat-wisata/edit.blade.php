@extends('admin.layouts.app')

@section('content')
<div class="container-fluid" style="padding: 50px; margin-left: 20px;">
    <h1 class="mb-4">Edit Tempat Wisata</h1>

    <form action="{{ route('admin.tempat-wisata.update', $tempatWisata->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama_tempat_wisata">Nama Tempat Wisata</label>
            <input type="text" name="nama_tempat_wisata" class="form-control" 
                   value="{{ old('nama_tempat_wisata', $tempatWisata->nama_tempat_wisata) }}" required>
            @error('nama_tempat_wisata')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <textarea name="lokasi" class="form-control" required>{{ old('lokasi', $tempatWisata->lokasi) }}</textarea>
            @error('lokasi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi', $tempatWisata->deskripsi) }}</textarea>
            @error('deskripsi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="rating_rata_rata">Rating Rata-rata</label>
            <input type="number" step="0.1" name="rating_rata_rata" class="form-control" 
                   value="{{ old('rating_rata_rata', $tempatWisata->rating_rata_rata) }}" required>
            @error('rating_rata_rata')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="harga">Harga Tiket</label>
            <input type="number" name="harga" class="form-control" 
                   value="{{ old('harga', $tempatWisata->harga) }}" required>
            @error('harga')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Gambar lama -->
        @if($tempatWisata->gambar)
            <div class="form-group">
                <label>Gambar Saat Ini:</label><br>
                <img src="{{ asset($tempatWisata->gambar) }}" width="150" class="mb-2">
            </div>
        @endif

        <!-- Upload gambar baru (opsional) -->
        <div class="form-group">
            <label for="gambar">Ganti Gambar (Opsional)</label>
            <input type="file" name="gambar" class="form-control-file" accept="image/*">
            @error('gambar')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Kategori -->
        <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <div class="d-flex flex-wrap">
                @foreach($kategori->chunk(4) as $chunk)
                    <div class="me-4 mr-4">
                        @foreach($chunk as $kat)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="id_kategori[]"
                                    value="{{ $kat->id }}"
                                    id="kat{{ $kat->id }}"
                                    {{ in_array($kat->id, old('id_kategori', $selectedKategori)) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="kat{{ $kat->id }}">
                                    {{ $kat->nama_kategori }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @error('id_kategori')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Fasilitas -->
        <div class="form-group">
            <label for="id_fasilitas">Fasilitas</label>
            <div class="d-flex flex-wrap">
                @foreach($fasilitas->chunk(4) as $chunk)
                    <div class="me-4 mr-4">
                        @foreach($chunk as $fas)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="id_fasilitas[]"
                                    value="{{ $fas->id }}"
                                    id="fas{{ $fas->id }}"
                                    {{ in_array($fas->id, old('id_fasilitas', $selectedFasilitas)) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="fas{{ $fas->id }}">
                                    {{ $fas->nama_fasilitas }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @error('id_fasilitas')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.tempat-wisata.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
