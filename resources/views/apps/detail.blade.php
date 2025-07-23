<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Wisata Banyuwangi</title>
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

        .border-maroon {
            background-color: #821c34;
            color: white;
            border: 2px solid #821c34;
        }

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


    <!-- Detail Tempat Wisata -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="px-3 py-1 rounded fw-bold border-maroon"> Detail Tempat Wisata
                </div>

                <div>
                    <a href="{{ url('/apps/home') }}" class="text-decoration-none text-dark">Home</a> /
                    <strong>Detail</strong>
                </div>
            </div>

            <h2 class="fw-bold" style="color: maroon; font-size: 3rem;">
                {{ $wisata->nama_tempat_wisata }}
            </h2>

            @if (session('success'))
                <div id="success-alert" class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <p class="text-muted">
                <i class="bi bi-geo-alt-fill text-danger"></i> {{ $wisata->lokasi }}
            </p>

            <div class="text-center">
                <img src="{{ asset($wisata->gambar) }}" alt="{{ $wisata->nama_tempat_wisata }}"
                    class="img-fluid mb-4 rounded shadow w-100"
                    style="max-width: 100%; height: auto; object-fit: cover;">
            </div>


            <p>{{ $wisata->deskripsi }}</p>

            <h5 class="fw-bold mt-4">Fasilitas :</h5>
            <p>
                {{ implode(', ', $wisata->fasilitas->pluck('nama_fasilitas')->toArray()) }}
            </p>

            <h5 class="fw-bold mt-3">Rating :</h5>
            <div class="text-warning mb-2 fs-4">
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

            <h5 class="fw-bold">Harga Tiket :</h5>
            <p class="fs-5 fw-semibold text-success">
                Rp {{ number_format($wisata->harga ?? 0, 0, ',', '.') }}
            </p>

            @auth
                <h5 class="fw-bold mt-5">Beri Ulasan:</h5>
                <form action="{{ route('review.wisata', $wisata->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Rating:</label>
                        <div id="star-rating" class="text-warning fs-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star" data-value="{{ $i }}"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating" required>
                    </div>

                    <div class="mb-3">
                        <label for="ulasan" class="form-label">Ulasan:</label>
                        <textarea name="ulasan" id="ulasan" rows="3" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn border-maroon">Kirim</button>
                </form>
            @else
                <p><a href="{{ route('login') }}">Login</a> untuk memberikan ulasan.</p>
            @endauth

            <h5 class="fw-bold mt-5">Ulasan Pengunjung:</h5>
            @forelse ($wisata->reviews as $review)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $review->user->name ?? 'Pengunjung' }}</strong>
                        <span class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </span>
                    </div>
                    <p class="mb-0">{{ $review->ulasan }}</p>
                    <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                </div>
            @empty
                <p>Belum ada ulasan.</p>
            @endforelse

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
    <script>
        // Hapus alert setelah 3 detik (3000ms)
        setTimeout(function() {
            const alertBox = document.getElementById('success-alert');
            if (alertBox) {
                alertBox.remove();
            }
        }, 3000);
    </script>

    <script>
        const stars = document.querySelectorAll('#star-rating i');
        const ratingInput = document.getElementById('rating');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-value');
                ratingInput.value = rating;

                // Update UI: fill bintang sampai yang diklik
                stars.forEach(s => {
                    if (s.getAttribute('data-value') <= rating) {
                        s.classList.remove('bi-star');
                        s.classList.add('bi-star-fill');
                    } else {
                        s.classList.remove('bi-star-fill');
                        s.classList.add('bi-star');
                    }
                });
            });
        });
    </script>

</body>

</html>
