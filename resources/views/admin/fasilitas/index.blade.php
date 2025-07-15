@extends('admin.layouts.app')

@section('content')
<div class="container-fluid" style="padding: 50px; margin-left: 20px;">
    <h1 class="mb-4">Daftar fasilitas</h1>
    <a href="{{ route('admin.fasilitas.create') }}" class="btn btn-primary mb-3">Tambah Fasilitas</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Fasilitas</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fasilitas as $fasilitas)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $fasilitas->nama_fasilitas }}</td>
                <td>{{ $fasilitas->nilai }}</td>
                <td>
                    <a href="{{ route('admin.fasilitas.edit', $fasilitas->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.fasilitas.destroy', $fasilitas->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus fasilitas ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
