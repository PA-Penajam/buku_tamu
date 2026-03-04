<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder untuk import data dari file JSON
 * Membaca data dari docs/frontdesk-merged.json
 */
class ImportJsonSeeder extends Seeder
{
    public function run()
    {
        $jsonPath = WRITEPATH . '../docs/frontdesk-merged.json';

        // Periksa apakah file JSON ada
        if (!file_exists($jsonPath)) {
            echo "File JSON tidak ditemukan: {$jsonPath}\n";
            return;
        }

        // Baca dan decode JSON
        $jsonContent = file_get_contents($jsonPath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error parsing JSON: " . json_last_error_msg() . "\n";
            return;
        }

        $tamuModel = new \App\Models\TamuModel();
        $inserted = 0;
        $skipped = 0;

        foreach ($data as $row) {
            // Siapkan data untuk insert
            $insertData = [
                'jenis_tamu' => $row['jenis_tamu'] ?? 'pengunjung',
                'tanggal'    => $row['tanggal'] ?? date('Y-m-d H:i:s'),
                'nama'       => $row['nama'] ?? '',
                'alamat'     => $row['alamat'] ?? null,
                'instansi'   => $row['instansi'] ?? null,
                'hp'         => $row['hp'] ?? null,
                'tujuan'     => $row['tujuan'] ?? null,
            ];

            // Insert data menggunakan model
            if ($tamuModel->insert($insertData)) {
                $inserted++;
            } else {
                $skipped++;
            }

            // Progress setiap 500 record
            if (($inserted + $skipped) % 500 === 0) {
                echo "Progress: {$inserted} inserted, {$skipped} skipped\n";
            }
        }

        echo "\n=== Import Selesai ===\n";
        echo "Total inserted: {$inserted}\n";
        echo "Total skipped: {$skipped}\n";
        echo "Total data: " . count($data) . "\n";
    }
}
