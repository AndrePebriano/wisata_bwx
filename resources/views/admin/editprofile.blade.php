@extends('admin.layouts.app')

@section('content')
<div class="container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="px-3 py-1"></div>
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
    <form action="{{ route('admin.updateprofile') }}" method="POST" enctype="multipart/form-data">
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

        {{-- Foto Profil --}}
        <div class="mb-3">
            <label for="photo" class="form-label">Foto Profil</label>
            <input type="file" name="photo" id="photo" class="form-control">
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/photos/' . auth()->user()->photo) }}" width="100" class="mt-2 rounded">
            @endif
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
@endsection
