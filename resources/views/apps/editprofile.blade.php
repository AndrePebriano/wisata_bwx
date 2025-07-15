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
                                    <a class="dropdown-item border-maroon" href="{{ route('profile.edit') }}">Edit Profil
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

    <section class="py-5">
        <div class="container">
            {{-- Header dan Breadcrumb --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="px-3 py-1">
                </div>

                <div>
                    <a href="{{ url('/apps/home') }}" class="text-decoration-none text-dark">Home</a> /
                    <strong>Edit Profile</strong>
                </div>
            </div>

            {{-- Judul --}}
            <h3 class="mb-4">Edit Profil</h3>

            {{-- Notifikasi sukses --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Validasi error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('apps.updateprofile') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', auth()->user()->name) }}">
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', auth()->user()->email) }}">
                </div>

                <hr>

                <h5 class="mt-4">Ubah Password</h5>

                {{-- Password Lama --}}
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <input type="password" name="current_password" id="current_password" class="form-control"
                        placeholder="Masukkan password lama jika ingin mengubah password">
                </div>

                {{-- Password Baru --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>

        </div>
    </section>



    <!-- Footer -->

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
