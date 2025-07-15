@extends('admin.layouts.app')

@section('content')
<div class="container-fluid" style="padding: 50px; margin-left: 20px;">
   
      <div class="container-fluid">
    <h1 class="mb-4">Tambah Fasilitas</h1>

    <form action="{{ route('admin.fasilitas.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama_fasilitas">Nama Fasilitas</label>
            <input type="text" name="nama_fasilitas" class="form-control" value="{{ old('nama_fasilitas') }}" required>
            @error('nama_fasilitas')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="nilai">Nilai</label>
            <input type="number" name="nilai" class="form-control" value="{{ old('nilai') }}" required>
            @error('nilai')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.fasilitas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

</div>
@endsection
