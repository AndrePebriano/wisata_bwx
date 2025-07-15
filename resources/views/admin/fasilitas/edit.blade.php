@extends('admin.layouts.app')

@section('content')
<div class="container-fluid" style="padding: 50px; margin-left: 20px;">
    <h1 class="mb-4">Edit Fasilitas</h1>

    <form action="{{ route('admin.fasilitas.update', $fasilitas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama_fasilitas">Nama Fasilitas</label>
            <input type="text" name="nama_fasilitas" class="form-control" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
            @error('nama_fasilitas')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="nilai">Nilai</label>
            <input type="number" name="nilai" class="form-control" value="{{ old('nilai', $fasilitas->nilai) }}" required>
            @error('nilai')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.fasilitas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
