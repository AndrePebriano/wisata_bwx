@extends('admin.layouts.app')

@section('content')
<div class="container-fluid" style="padding: 50px; margin-left: 20px;">
    <h1 class="mb-4">Tambah Tempat Wisata</h1>

    <form action="{{ route('admin.tempat-wisata.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="nama_tempat_wisata">Nama Tempat Wisata</label>
            <input type="text" name="nama_tempat_wisata" class="form-control" value="{{ old('nama_tempat_wisata') }}" required>
            @error('nama_tempat_wisata')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <textarea name="lokasi" class="form-control" required>{{ old('lokasi') }}</textarea>
            @error('lokasi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="rating_rata_rata">Rating Rata-rata</label>
            <input type="number" step="0.1" name="rating_rata_rata" class="form-control" value="{{ old('rating_rata_rata') }}" required min="0" max="5">
            @error('rating_rata_rata')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="harga">Harga Tiket</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required min="0">
            @error('harga')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Upload Gambar -->
        <div class="form-group">
            <label for="gambar">Gambar Tempat Wisata</label>
            <input type="file" name="gambar" class="form-control-file" accept="image/*" required>
            @error('gambar')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Multi select Kategori -->
        <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <div class="d-flex flex-wrap">
                @foreach($kategori->chunk(4) as $chunk)
                    <div class="me-4">
                        @foreach($chunk as $kat)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="id_kategori[]"
                                    value="{{ $kat->id }}"
                                    id="kat{{ $kat->id }}"
                                    {{ (collect(old('id_kategori'))->contains($kat->id)) ? 'checked' : '' }}
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

        <!-- Multi select Fasilitas -->
        <div class="form-group">
            <label for="id_fasilitas">Fasilitas</label>
            <div class="d-flex flex-wrap">
                @foreach($fasilitas->chunk(4) as $chunk)
                    <div class="me-4">
                        @foreach($chunk as $fas)
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="id_fasilitas[]"
                                    value="{{ $fas->id }}"
                                    id="fas{{ $fas->id }}"
                                    {{ (collect(old('id_fasilitas'))->contains($fas->id)) ? 'checked' : '' }}
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

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.tempat-wisata.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
