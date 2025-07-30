<?php

namespace App\Services;

use App\Models\Tempat_Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Models\NormalisasiTempatWisata;
use App\Models\RekomendasiHistoris;

class RekomendasiService
{
    public function getRekomendasi(
        array $selectedKategori = [],
        array $selectedFasilitas = [],
        $harga = null,
        $rating = null
    ) {
        $allKategori = Kategori::all()->keyBy('id');
        $allFasilitas = Fasilitas::all()->keyBy('id');

        // 1) BANGUN VEKTOR PREFERENSI USER
        $userKategoriVector = $this->buildKategoriVector($selectedKategori, $allKategori);
        $userFasilitasVector = $this->buildFasilitasVector($selectedFasilitas, $allFasilitas);
        $userHarga = [$this->normalizeHarga($harga)];
        $userRating = [$this->normalizeRating($rating)];

        $userVector = array_merge(
            $userKategoriVector,
            $userFasilitasVector,
            $userHarga,
            $userRating
        );

        // 2) LOOP SEMUA TEMPAT, HITUNG COSINE SIMILARITY
        $tempatWisataList = Tempat_Wisata::with(['kategoris', 'fasilitas'])->get();
        $rekomendasi = [];

        foreach ($tempatWisataList as $tempat) {
            $kategoriIds = $tempat->kategoris->pluck('id')->toArray();
            $tempatKategoriVector = $this->buildKategoriVector($kategoriIds, $allKategori);

            $tempatFasilitasIds = $tempat->fasilitas->pluck('id')->toArray();
            $tempatFasilitasVector = $this->buildFasilitasVector($tempatFasilitasIds, $allFasilitas);

            $tempatHarga = [$this->normalizeHarga($tempat->harga)];
            $tempatRating = [$this->normalizeRating($tempat->rating_rata_rata)];

            $tempatVector = array_merge(
                $tempatKategoriVector,
                $tempatFasilitasVector,
                $tempatHarga,
                $tempatRating
            );

            $skor = $this->cosineSimilarity($userVector, $tempatVector);
            if ($skor >= 0.5) {
                $rekomendasi[] = [
                    'tempat' => $tempat,
                    'skor' => $skor,
                    'fasilitas_vector' => $tempatFasilitasVector,
                    'kategori_vector' => $tempatKategoriVector,
                ];
            }

            // simpan ke tabel normalisasi
            NormalisasiTempatWisata::updateOrCreate(
                ['tempat_wisata_id' => $tempat->id],
                [
                    'vektor_kategori'  => round(array_sum($tempatKategoriVector) / count($tempatKategoriVector), 4),
                    'vektor_fasilitas' => json_encode($tempatFasilitasVector),
                    'vektor_harga'     => $tempatHarga[0],
                    'vektor_rating'    => $tempatRating[0],
                ]
            );
        }

        // 3) URUTKAN SEMUA YANG LOLOS AMBANG BATAS
        usort($rekomendasi, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // 4) SIMPAN 5 TERATAS SAJA KE HISTORI
        $top5 = array_slice($rekomendasi, 0, 5);

        $batchData = [];
        $timestamp = now();

        foreach ($top5 as $item) {
            $tempat = $item['tempat'];

            $batchData[] = [
                'user_id' => null,
                'tempat_wisata_id' => $tempat->id,
                'vektor_kategori' => json_encode($item['kategori_vector']),
                'vektor_fasilitas' => json_encode($item['fasilitas_vector']),
                'vektor_harga' => $this->normalizeHarga($tempat->harga),
                'vektor_rating' => $this->normalizeRating($tempat->rating_rata_rata),
                'user_vector' => json_encode($userVector),
                'skor_similarity' => $item['skor'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        RekomendasiHistoris::insert($batchData);

        // 5) RETURN SEMUA YANG LOLOS ≥ 0.5
        return array_map(
            fn($item) => ['tempat' => $item['tempat'], 'skor' => $item['skor']],
            $rekomendasi
        );
    }

    private function buildKategoriVector(array $selectedIds, $allKategori): array
    {
        $vector = [];
        foreach ($allKategori as $kategori) {
            $vector[] = in_array($kategori->id, $selectedIds) ? 1 : 0;
        }
        return $vector;
    }

    private function buildFasilitasVector(array $selectedFasilitasIds, $allFasilitas): array
    {
        $vector = [];
        foreach ($allFasilitas as $fasilitas) {
            $vector[] = in_array($fasilitas->id, $selectedFasilitasIds) ? 1 : 0;
        }
        return $vector;
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        if (count($a) !== count($b)) return 0.0;

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < count($a); $i++) {
            $dotProduct += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        if ($normA <= 0 || $normB <= 0) return 0.0;

        $similarity = $dotProduct / (sqrt($normA) * sqrt($normB));
        return round(max(-1.0, min(1.0, $similarity)), 4);
    }

    private function normalizeHarga($h): float
    {
        if (!$h || $h <= 0) return 0.0;
        $min = 10000;
        $max = 50000;
        $v = ($h - $min) / ($max - $min);
        return round(max(min($v, 1), 0), 4);
    }

    private function normalizeRating($r): float
    {
        if (!$r || $r <= 0) return 0.0;
        $min = 4.5;
        $max = 4.7;
        $v = ($r - $min) / ($max - $min);
        return round(max(min($v, 1), 0), 4);
    }
}
