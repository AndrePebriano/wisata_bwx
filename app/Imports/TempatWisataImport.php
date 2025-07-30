<?php

namespace App\Imports;

use App\Models\Tempat_Wisata;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class TempatWisataImport implements ToCollection, WithHeadingRow, WithBatchInserts
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Buat atau update berdasarkan nama_tempat_wisata
            $tempat = Tempat_Wisata::updateOrCreate(
                ['nama_tempat_wisata' => $row['nama_tempat_wisata']], // kolom unik untuk pencocokan
                [
                    'lokasi'           => $row['lokasi'],
                    'deskripsi'        => $row['deskripsi'],
                    'rating_rata_rata' => $row['rating_rata_rata'],
                    'harga'            => $row['harga'],
                    'gambar'           => 'images/default.jpg',
                ]
            );

            // Ambil input kategori dan fasilitas berupa ID (contoh: "1,3")
            $kategoriIds = array_filter(array_map('intval', explode(',', $row['nama_kategori'] ?? '')));
            $fasilitasIds = array_filter(array_map('intval', explode(',', $row['nama_fasilitas'] ?? '')));

            // Sinkronisasi tabel pivot (replace semua relasi)
            if (!empty($kategoriIds)) {
                $tempat->kategoris()->sync($kategoriIds);
            }

            if (!empty($fasilitasIds)) {
                $tempat->fasilitas()->sync($fasilitasIds);
            }
        }
    }

    public function batchSize(): int
    {
        return 50;
    }
}
