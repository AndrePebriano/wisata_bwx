@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 50px; margin-left: 20px;">
        <h1 class="mb-4">Data Normalisasi {{ $tempat->nama_tempat_wisata }}</h1>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Detail Perhitungan Cosine Similarity</strong>
            </div>
            <div class="card-body">
                <h5>Menentukan Vektor</h5>
                <p>
                    Setelah dilakukan normalisasi, didapatkan vektor wisatawan (X’):<br>
                    <strong>X′ = [{{ implode(', ', $userVector) }}]</strong>
                </p>
                <p>
                    Untuk Wisata <strong>{{ $tempat->nama_tempat_wisata }}</strong>, vektor wisata (Y’) adalah:<br>
                    <strong>Y′ = [{{ implode(', ', $tempatVector) }}]</strong>
                </p>

                <hr>

                <h5>Hitung Dot Product (X’ ⋅ Y’)</h5>
                @php
                    $dotSteps = [];
                    $dotComponents = [];
                    $length = min(count($userVector), count($tempatVector)); // ambil panjang terpendek agar aman

                    for ($i = 0; $i < $length; $i++) {
                        $uVal = $userVector[$i];
                        $yVal = $tempatVector[$i];
                        $dotSteps[] = "({$uVal} × {$yVal})";
                        $dotComponents[] = round($uVal * $yVal, 4);
                    }
                @endphp
                <p>{!! implode(' + ', $dotSteps) !!}</p>
                <p>= {!! implode(' + ', $dotComponents) !!}</p>
                <p>= <strong>{{ $dot }}</strong></p>

                <hr>

                <h5>Hitung Panjang Vector X dan Y</h5>
                @php
                    $normXSteps = [];
                    $normYSteps = [];
                    foreach ($userVector as $v) {
                        $normXSteps[] = "({$v})²";
                    }
                    foreach ($tempatVector as $v) {
                        $normYSteps[] = "({$v})²";
                    }

                    $normXValues = array_map(fn($v) => pow($v, 2), $userVector);
                    $normYValues = array_map(fn($v) => pow($v, 2), $tempatVector);

                    $sumX = round(array_sum($normXValues), 4);
                    $sumY = round(array_sum($normYValues), 4);
                @endphp

                <p>|X| = √({!! implode(' + ', $normXSteps) !!})<br>
                    = √({!! implode(' + ', array_map(fn($n) => round($n, 4), $normXValues)) !!})<br>
                    = √({{ $sumX }}) = <strong>{{ $normUser }}</strong>
                </p>

                <p>|Y| = √({!! implode(' + ', $normYSteps) !!})<br>
                    = √({!! implode(' + ', array_map(fn($n) => round($n, 4), $normYValues)) !!})<br>
                    = √({{ $sumY }}) = <strong>{{ $normTempat }}</strong>
                </p>

                <hr>

                <h5>Hitung Cosine Similarity</h5>
                <p>= {{ $dot }} / ({{ $normUser }} × {{ $normTempat }})<br>
                    = {{ $dot }} / {{ round($normUser * $normTempat, 4) }}<br>
                    = <strong>{{ $similarity }}</strong>
                </p>
            </div>

            <div class="card-footer">
                <a href="{{ route('normalisasi.wisata') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
