<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Kategori;
use App\Models\NormalisasiTempatWisata;
use App\Models\RekomendasiHistoris;
use App\Models\Tempat_Wisata;
use Illuminate\Http\Request;

class NormalisasiTempatWisataController extends Controller
{
    public function index()
    {
        $data = NormalisasiTempatWisata::with('tempat')->latest()->get(); // pastikan relasi 'tempat' sudah disiapkan
        return view('admin.tempat-wisata.normalisasi', compact('data'));
    }

    public function showDetail($id)
    {
        $tempat = Tempat_Wisata::with(['kategoris', 'fasilitas'])->findOrFail($id);
        $historis = RekomendasiHistoris::where('tempat_wisata_id', $id)->latest()->firstOrFail();

        $userVector = json_decode($historis->user_vector, true);
        $tempatVector = array_merge(
            [$historis->vektor_kategori],
            is_string($historis->vektor_fasilitas)
                ? json_decode($historis->vektor_fasilitas, true)
                : $historis->vektor_fasilitas,
            [$historis->vektor_harga],
            [$historis->vektor_rating]
        );


        $dot = $this->dotProduct($userVector, $tempatVector);
        $normUser = $this->vectorLength($userVector);
        $normTempat = $this->vectorLength($tempatVector);
        $similarity = $normUser && $normTempat ? round($dot / ($normUser * $normTempat), 4) : 0;

        return view('admin.tempat-wisata.detail', compact(
            'tempat',
            'userVector',
            'tempatVector',
            'dot',
            'normUser',
            'normTempat',
            'similarity'
        ));
    }

    // Tambahkan bantuan fungsi ini (atau taruh di service):

    private function buildVector($kategoriIds, $fasilitasIds, $harga, $rating, $kategoriBobot, $allFasilitas)
    {
        $vektorKategori = $this->normalizeKategori($kategoriIds, $kategoriBobot);
        $vektorFasilitas = [];
        foreach ($allFasilitas as $fasilitas) {
            $vektorFasilitas[] = in_array($fasilitas->id, $fasilitasIds) ? 1 : 0;
        }
        $vektorHarga = [$this->normalizeHarga($harga)];
        $vektorRating = [$this->normalizeRating($rating)];

        return array_merge($vektorKategori, $vektorFasilitas, $vektorHarga, $vektorRating);
    }

    private function dotProduct($a, $b)
    {
        if (!is_array($a) || !is_array($b)) return 0.0;
        if (count($a) !== count($b)) return 0.0;

        return round(array_sum(array_map(fn($x, $y) => $x * $y, $a, $b)), 4);
    }


    private function vectorLength($v)
    {
        if (!is_array($v)) return 0.0; // Tangani jika bukan array
        return round(sqrt(array_sum(array_map(fn($x) => $x ** 2, $v))), 4);
    }


    private function normalizeKategori(array $selectedIds, array $bobot): array
    {
        if (empty($selectedIds)) return [0.0];
        $scores = [];
        foreach ($selectedIds as $id) {
            if (isset($bobot[$id])) {
                $nilai = $bobot[$id];
                if ($nilai >= 1 && $nilai <= 6) {
                    $scores[] = round(($nilai - 1) / 5, 4);
                }
            }
        }
        return [round(count($scores) ? array_sum($scores) / count($scores) : 0.0, 4)];
    }

    private function normalizeHarga($h)
    {
        if (!$h || $h <= 0) return 0.0;
        return round(min(max(($h - 10000) / 40000, 0), 1), 4);
    }

    private function normalizeRating($r)
    {
        if (!$r || $r <= 0) return 0.0;
        return round(min(max(($r - 4.5) / 0.2, 0), 1), 4);
    }
}
