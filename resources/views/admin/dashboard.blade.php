@extends('admin.layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid" style="padding: 50px; margin-left: 20px;">
        <h1 class="mb-4">Dashboard</h1>

        {{-- Statistik Utama --}}
        <div class="row g-3 mb-5">

            {{-- Tempat Wisata --}}
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-start border-4 border-primary">
                    <div class="card-body d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-map-marker-alt" style="font-size: 22px;"></i>
                            </div>
                            <div class="fs-6 ms-3">
                                <span class="text-muted">Tempat Wisata:</span>
                                <span class="fw-bold fs-5">{{ $totalWisata }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.tempat-wisata.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-start border-4 border-success">
                    <div class="card-body d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-tags-fill" style="font-size: 22px;"></i>
                            </div>
                            <div class="fs-6 ms-3">
                                <span class="text-muted">Kategori:</span>
                                <span class="fw-bold fs-5">{{ $totalKategori }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-outline-success">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>

            {{-- Fasilitas --}}
            <div class="col-md-3 col-sm-6">
                <div class="card shadow-sm border-start border-4 border-warning">
                    <div class="card-body d-flex align-items-center justify-content-between py-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning text-white rounded-circle d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px;">
                                <i class="fas fa-concierge-bell" style="font-size: 22px;"></i>
                            </div>
                            <div class="fs-6 ms-3">
                                <span class="text-muted">Fasilitas:</span>
                                <span class="fw-bold fs-5">{{ $totalFasilitas }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.fasilitas.index') }}" class="btn btn-sm btn-outline-warning">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Daftar Tempat Wisata Terbaru --}}
        <div class="card shadow border-0">
            <div class="card-header bg-white fw-bold fs-5">
                <i class="bi bi-clock text-primary me-2"></i>Tempat Wisata Terbaru
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Tempat</th>
                            <th>Alamat</th>
                            <th>Kategori</th>
                            <th>Fasilitas</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wisataTerbaru as $index => $wisata)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $wisata->nama }}</td>
                                <td>{{ $wisata->alamat }}</td>
                                <td>{{ $wisata->kategori->nama ?? '-' }}</td>
                                <td>{{ $wisata->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data tempat wisata baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
