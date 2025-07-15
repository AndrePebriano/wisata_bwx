<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Auth | Wisata Bwx</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 1000px;
            height: 600px;
            display: flex;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .auth-left {
            background-color: #80142b;
            color: white;
            padding: 30px;
            width: 43%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .auth-left img {
            width: 250px;
            height: auto;
        }

        .auth-left p {
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .auth-right {
            background-color: #fff;
            padding: 40px;
            width: 60%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .btn-maroon {
            background-color: #821c34;
            color: white;
            text-decoration: none;
            padding: 8px 8px;
            border-radius: 5px;
            display: inline-block;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
            width: fit-content;
        }

        .btn-maroon:hover {
            background-color: #6a182c;
            color: white;
            text-decoration: none;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <img src="{{ asset('images/logo putih.png') }}" alt="Logo" />
            <p>Halo, Selamat Datang</p>
        </div>

        <div class="auth-right">
            <a href="/" class="btn btn-maroon mb-4">←</a>
            @yield('auth-content')
        </div>
    </div>
</body>
</html>
