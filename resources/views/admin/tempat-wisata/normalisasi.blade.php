@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 50px; margin-left: 20px;">
        <h1 class="mb-4">Data Normalisasi Tempat Wisata</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Tempat</th>
                    <th>Kategori (v)</th>
                    <th>Fasilitas Vector</th>
                    <th>Harga (v)</th>
                    <th>Rating (v)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->tempat->nama_tempat_wisata ?? '-' }}</td>
                        <td>{{ $item->vektor_kategori }}</td>
                        <td>
                            @php
                                $fasilitas = is_string($item->vektor_fasilitas)
                                    ? json_decode($item->vektor_fasilitas, true)
                                    : $item->vektor_fasilitas;
                                $fasilitasText = is_array($fasilitas) ? implode(', ', $fasilitas) : '-';
                            @endphp
                            {{ $fasilitasText }}
                        </td>
                        <td>{{ $item->vektor_harga }}</td>
                        <td>{{ $item->vektor_rating }}</td>
                        <td>
                            <a href="{{ route('normalisasi.detail', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <!-- Di bagian sebelum </body> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
