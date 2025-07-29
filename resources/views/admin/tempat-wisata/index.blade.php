@extends('admin.layouts.app')

@section('content')
<div class="container-fluid" style="padding: 50px; margin-left: 20px;">
    <h1 class="mb-4">Data Tempat Wisata</h1>

    <a href="{{ route('admin.tempat-wisata.create') }}" class="btn btn-primary mb-3">Tambah Tempat Wisata</a>
    <button type="button" class="btn btn-success mb-3 me-2" data-toggle="modal" data-target="#importModal">
        Import Excel
    </button>
    <a href="{{ asset('template/template-wisata.xlsx') }}" class="btn btn-info mb-3 me-2">
        Download Template Excel
    </a>
    <a href="{{ route('normalisasi.wisata') }}" class="btn btn-secondary mb-3">Hasil Perhitungan Normalisasi</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Tempat Wisata</th>
                <th>Lokasi</th>
                <th>Deskripsi</th>
                <th>Rating</th>
                <th>Harga</th>
                <th>Kategori</th>
                <th>Fasilitas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tempatWisatas as $index => $wisata)
            <tr>
                <td>{{ $index + 1 }}</td>

                <!-- Tampilkan gambar -->
                <td>
                    @if($wisata->gambar)
                    <img src="{{ asset($wisata->gambar) }}" width="100">
                    @else
                    <span class="text-muted">Tidak ada</span>
                    @endif
                </td>

                <td>{{ $wisata->nama_tempat_wisata }}</td>
                <td>{{ $wisata->lokasi }}</td>
                <td>{{ $wisata->deskripsi }}</td>
                <td>{{ $wisata->rating_rata_rata }}</td>
                <td>Rp {{ number_format($wisata->harga, 0, ',', '.') }}</td>

                <td>
                    {{ $wisata->kategoris->count() ? $wisata->kategoris->pluck('nama_kategori')->implode(', ') : '-' }}
                </td>

                <td>
                    {{ $wisata->fasilitas->count() ? $wisata->fasilitas->pluck('nama_fasilitas')->implode(', ') : '-' }}
                </td>

                <td>
                    <a href="{{ route('admin.tempat-wisata.edit', $wisata->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.tempat-wisata.destroy', $wisata->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data tempat wisata</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.tempat-wisata.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Tempat Wisata dari Excel</h5>
                        <button type="button" class="&times;" data-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih file Excel</label>
                            <input type="file" class="form-control" name="file" required accept=".xlsx,.xls">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Import</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection