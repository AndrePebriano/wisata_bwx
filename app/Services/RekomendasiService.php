<?php

namespace App\Services;

use App\Models\Tempat_Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
use App\Models\NormalisasiTempatWisata;
use App\Models\RekomendasiHistoris;
use Illuminate\Support\Facades\Auth;

class RekomendasiService
{
    public function getRekomendasi(
        array $selectedKategori = [],
        array $selectedFasilitas = [],
        $harga = null,
        $rating = null
    ) {
        $kategoriBobot = $this->getKategoriBobot();
        $allFasilitas = Fasilitas::all()->keyBy('id');

        // 1) BANGUN VEKTOR PREFERENSI USER
        $userKategoriScore = $this->normalizeKategori($selectedKategori, $kategoriBobot);
        $userFasilitasVector = $this->buildFasilitasVector($selectedFasilitas, $allFasilitas);
        $userHarga = [$this->normalizeHarga($harga)];
        $userRating = [$this->normalizeRating($rating)];

        $userVector = array_merge(
            $userKategoriScore,
            $userFasilitasVector,
            $userHarga,
            $userRating
        );

        // 2) LOOP SEMUA TEMPAT, HITUNG COSINE SIMILARITY
        $tempatWisataList = Tempat_Wisata::with(['kategoris', 'fasilitas'])->get();
        $rekomendasi = [];

        foreach ($tempatWisataList as $tempat) {
            $kategoriIds = $tempat->kategoris->pluck('id')->toArray();
            $tempatKategoriScore = $this->normalizeKategori($kategoriIds, $kategoriBobot);

            $tempatFasilitasIds = $tempat->fasilitas->pluck('id')->toArray();
            $tempatFasilitasVector = $this->buildFasilitasVector($tempatFasilitasIds, $allFasilitas);

            $tempatHarga = [$this->normalizeHarga($tempat->harga)];
            $tempatRating = [$this->normalizeRating($tempat->rating_rata_rata)];

            $tempatVector = array_merge(
                $tempatKategoriScore,
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
                ];
            }

            // simpan ke tabel normalisasi (jika belum ada)
            NormalisasiTempatWisata::updateOrCreate(
                ['tempat_wisata_id' => $tempat->id],
                [
                    'vektor_kategori'  => $tempatKategoriScore[0],
                    'vektor_fasilitas' => $tempatFasilitasVector,
                    'vektor_harga'     => $tempatHarga[0],
                    'vektor_rating'    => $tempatRating[0],
                ]
            );
        }

        // 3) URUTKAN SEMUA YANG LOLOS AMBANG BATAS
        usort($rekomendasi, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // 4) SIMPAN 5 TERATAS SAJA KE HISTORI
        $top5 = array_slice($rekomendasi, 0, 5);

        if ($userId = Auth::id()) {
            $batchData = [];
            $timestamp = now();

            foreach ($top5 as $item) {
                $tempat = $item['tempat'];

                $batchData[] = [
                    'user_id' => $userId,
                    'tempat_wisata_id' => $tempat->id,
                    'vektor_kategori' => $this->normalizeKategori(
                        $tempat->kategoris->pluck('id')->toArray(),
                        $this->getKategoriBobot()
                    )[0],
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
        }

        // 5) RETURN SEMUA YANG LOLOS ≥ 0.5
        return array_map(
            fn($item) => ['tempat' => $item['tempat'], 'skor' => $item['skor']],
            $rekomendasi
        );
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

    private function normalizeFasilitas($jumlah): float
    {
        if ($jumlah <= 4 || $jumlah >= 10) return 0.0;
        return round(($jumlah - 4) / (10 - $jumlah), 4);
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

    private function getKategoriBobot(): array
    {
        return Kategori::pluck('nilai', 'id')->toArray();
    }

    private function calculateFacilityScore(array $facilityVector): float
    {
        return round(array_sum($facilityVector) / count($facilityVector), 4);
    }

    private function dotProduct(array $a, array $b): float
    {
        $dot = 0.0;
        for ($i = 0; $i < count($a); $i++) {
            $dot += $a[$i] * $b[$i];
        }
        return round($dot, 4);
    }
}
