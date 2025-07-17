<?php

namespace App\Services;

use App\Models\Tempat_Wisata;
use App\Models\Kategori;
use App\Models\Fasilitas;
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
        $allKategori = Kategori::pluck('id')->toArray();
        $allFasilitas = Fasilitas::pluck('id')->toArray();

        // Vektor preferensi user
        $userKategoriVector = $this->buildVector($allKategori, $selectedKategori);
        $userFasilitasVector = $this->buildVector($allFasilitas, $selectedFasilitas);
        $userHarga = [$this->normalizeHarga($harga)];
        $userRating = [$this->normalizeRating($rating)];

        $userVector = array_merge($userKategoriVector, $userFasilitasVector, $userHarga, $userRating);

        $tempatWisataList = Tempat_Wisata::with(['kategoris', 'fasilitas'])->get();

        $rekomendasi = [];

        foreach ($tempatWisataList as $tempat) {
            $tempatKategoriIDs = $tempat->kategoris->pluck('id')->toArray();
            $tempatFasilitasIDs = $tempat->fasilitas->pluck('id')->toArray();

            $tempatKategoriVector = $this->buildVector($allKategori, $tempatKategoriIDs);
            $tempatFasilitasVector = $this->buildVector($allFasilitas, $tempatFasilitasIDs);
            $tempatHarga = [$this->normalizeHarga($tempat->harga)];
            $tempatRating = [$this->normalizeRating($tempat->rating_rata_rata)];

            $tempatVector = array_merge($tempatKategoriVector, $tempatFasilitasVector, $tempatHarga, $tempatRating);

            $similarity = $this->cosineSimilarity($userVector, $tempatVector);

            // Hanya masukkan jika similarity > 0
            if ($similarity > 0) {
                $rekomendasi[] = [
                    'tempat' => $tempat,
                    'skor' => $similarity
                ];
            }
        }

        // Urutkan dari yang paling mirip
        usort($rekomendasi, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // Ambil 5 teratas
        $top5 = array_slice($rekomendasi, 0, 5);

        // Simpan histori ke DB
        $userId = Auth::id();

        if($userId){
            foreach ($top5 as $item) {
            RekomendasiHistoris::create([
                'user_id' => $userId,
                'tempat_wisata_id' => $item['tempat']->id,
                'vektor_kategori' => $this->buildVector($allKategori, $selectedKategori),
                'vektor_fasilitas' => $this->buildVector($allFasilitas, $selectedFasilitas),
                'vektor_harga' => $this->normalizeHarga($harga),
                'vektor_rating' => $this->normalizeRating($rating),
                'skor_similarity' => $item['skor'],
            ]);
        }
        }

        return $rekomendasi;
    }

    private function buildVector(array $allIds, array $selectedIds): array
    {
        return array_map(fn($id) => in_array($id, $selectedIds) ? 1 : 0, $allIds);
    }

    private function normalizeHarga($harga): float
    {
        // Harga maksimal dianggap 100.000 untuk normalisasi
        if (!$harga || $harga <= 0) return 0.0;
        $normalized = 1 - min($harga / 100000, 1); // makin murah makin besar
        return round($normalized, 3);
    }

    private function normalizeRating($rating): float
    {
        if (!$rating || $rating <= 0) return 0.0;
        return round(min($rating / 5, 1), 3); // rating 5 jadi 1.0
    }

    private function cosineSimilarity(array $vec1, array $vec2): float
    {
        $dotProduct = 0;
        $normA = 0;
        $normB = 0;

        for ($i = 0; $i < count($vec1); $i++) {
            $dotProduct += $vec1[$i] * $vec2[$i];
            $normA += $vec1[$i] ** 2;
            $normB += $vec2[$i] ** 2;
        }

        if ($normA == 0 || $normB == 0) {
            return 0.0;
        }

        return $dotProduct / (sqrt($normA) * sqrt($normB));
    }
}
