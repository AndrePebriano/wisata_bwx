<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Wisata Banyuwangi</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .hero {
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            transition: background-image 1s ease-in-out;
        }

        .btn-maroon {
            background-color: #821c34;
            color: white;
        }

        /* .btn-maroon:hover {
            background-color: #821c34;
        } */
        .tempat-wisata {
            background-color: #fceef0;
            padding: 60px 0;
        }

        .footer {
            background-color: #821c34;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .btn-outline-maroon {
            border: 1px solid #821c34;
            color: #821c34;
            background-color: transparent;
        }

        .btn-outline-maroon:hover {
            background-color: #821c34;
            color: #fff;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top" style="background-color: #821c34;">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center">
                <img src="{{ asset('images/logo putih.png') }}" height="40" class="me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-bold text-white" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        Edit Profil
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-white" href="{{ route('login') }}">
                                Login <i class="bi bi-person-fill"></i>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="container">
            <h1 class="display-4 fw-bold">REKOMENDASI TEMPAT WISATA DI BANYUWANGI</h1>
            <p class="lead">Temukan Keindahan Tersembunyi di Banyuwangi - Surga Wisata yang Menunggu untuk Dijelajahi!
            </p>
            <a href="#" class="btn btn-maroon mt-3">Jelajahi Sekarang</a>
        </div>
    </section>

    <!-- Filter Form -->
    <div class="container mt-5">
        <form class="bg-white p-4 shadow rounded border border-maroon" method="GET" action="{{ route('apps.home') }}">
            <div class="row g-3 align-items-end">
                <!-- Kategori -->
                <div class="col-md">
                    <label for="kategori" class="form-label fw-semibold text-maroon">Kategori</label>
                    <select class="form-select border-maroon text-dark" name="kategori[]" id="kategori" multiple>
                        @foreach ($kategoris as $item)
                            <option value="{{ $item->id }}"
                                {{ collect(request('kategori'))->contains($item->id) ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga -->
                <div class="col-md">
                    <label for="harga" class="form-label fw-semibold text-maroon">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" class="form-control border-maroon"
                        placeholder="Contoh: 10000" value="{{ request('harga') }}" min="1000" step="1000">
                </div>

                <!-- Fasilitas -->
                <div class="col-md">
                    <label for="fasilitas" class="form-label fw-semibold text-maroon">Fasilitas</label>
                    <select class="form-select border-maroon text-dark" name="fasilitas[]" id="fasilitas" multiple>
                        @foreach ($fasilitas as $item)
                            <option value="{{ $item->id }}"
                                {{ collect(request('fasilitas'))->contains($item->id) ? 'selected' : '' }}>
                                {{ $item->nama_fasilitas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Rating -->
                <div class="col-md">
                    <label for="rating" class="form-label fw-semibold text-maroon">Rating</label>
                    <select class="form-select border-maroon text-dark" name="rating" id="rating">
                        <option value="">Pilih Rating</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                {{ $i }} ke atas
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="container mt-4">
                <div class="row justify-content-center align-items-center g-3">
                    <!-- Tombol Rekomendasi -->
                    <div class="col-auto">
                        <button type="submit" name="rekomendasi" value="true"
                            class="btn btn-outline-maroon fw-bold px-4 py-2">
                            <i class="bi bi-stars"></i> Tampilkan Rekomendasi untuk Saya
                        </button>
                    </div>

                    <!-- Tombol Cari dan Reset -->
                    <div class="col-auto">
                        <button type="submit" class="btn btn-maroon fw-semibold px-4">Cari</button>
                        <a href="{{ route('apps.home') }}"
                            class="btn btn-outline-secondary fw-semibold px-4 ms-2">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Tempat Wisata -->
    <section class="tempat-wisata py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <h2 class="text-center fw-bold mb-5" style="color: #821c34;">Tempat Wisata di Banyuwangi</h2>
            <div class="row">
                @forelse ($tempatWisata as $wisata)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ asset($wisata->gambar) }}" class="card-img-top rounded-top"
                                alt="{{ $wisata->nama_tempat_wisata }}" style="height: 200px; object-fit: cover;">

                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-bold mb-2">{{ $wisata->nama_tempat_wisata }}</h5>

                                @php
                                    $skor = null;
                                    if (isset($isRekomendasi) && $isRekomendasi) {
                                        $item = collect($rekomendasi)->firstWhere('tempat.id', $wisata->id);
                                        $skor = $item['skor'] ?? null;
                                    }
                                @endphp

                                @if ($skor !== null)
                                    <div class="mb-2">
                                        <span class="badge bg-warning text-dark">Skor Rekomendasi:
                                            {{ number_format($skor, 3) }}</span>
                                    </div>
                                @endif

                                <p class="mb-1 text-muted small">
                                    <i class="bi bi-geo-alt-fill me-1"></i> {{ $wisata->lokasi }}
                                </p>

                                <div class="text-warning mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($wisata->rating_rata_rata))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - $wisata->rating_rata_rata < 1)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>

                                <p class="text-success fw-semibold mb-2">
                                    Rp {{ number_format($wisata->harga ?? 0, 0, ',', '.') }}
                                </p>

                                <p class="small text-muted mb-3 flex-grow-1">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($wisata->deskripsi), 70) }}
                                </p>

                                <a href="{{ route('wisata.detail', $wisata->id) }}"
                                    class="btn btn-maroon w-100 mt-auto">Lihat Detail</a>
                            </div>
                        </div>

                    </div>
                @empty
                    <p class="text-center">Belum ada data tempat wisata.</p>
                @endforelse
            </div>

            {{-- Tombol Muat Lebih Banyak --}}
            @if (!$showAll && $tempatWisata->count() >= 4)
                <div class="text-center mt-4">
                    <a href="{{ route('apps.home', array_merge(request()->all(), ['semua' => true])) }}"
                        class="btn btn-outline-maroon fw-semibold px-4">
                        Muat Lebih Banyak
                    </a>
                </div>
            @endif
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <p class="fst-italic px-4">
                "Wisata Banyuwangi adalah panduan wisata terbaik untuk menjelajahi keindahan alam dan budaya Banyuwangi.
                Temukan destinasi favorit, rencanakan perjalananmu, dan nikmati pengalaman tak terlupakan!"
            </p>
            <p>&copy; 2025 <strong>Wisata Banyuwangi</strong>. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <!-- Slideshow Script -->
    <script>
        const hero = document.getElementById('hero');
        const images = [
            '{{ asset('images/kawah_ijen.jpg') }}',
            '{{ asset('images/djawatan.jpg') }}',
            '{{ asset('images/teluk_hijau.jpg') }}',
            '{{ asset('images/jagir.jpg') }}',
            '{{ asset('images/pulau_merah.jpg') }}'
        ];

        let currentIndex = 0;

        function changeBackground() {
            currentIndex = (currentIndex + 1) % images.length;
            hero.style.backgroundImage = `url('${images[currentIndex]}')`;
        }

        hero.style.backgroundImage = `url('${images[0]}')`;
        setInterval(changeBackground, 5000);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (required by Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#fasilitas').select2({
                placeholder: 'Pilih Fasilitas',
                allowClear: true
            });
            $('#kategori').select2({
                placeholder: 'Pilih Kategori',
                allowClear: true
            });
        });
    </script>

</body>

</html>
