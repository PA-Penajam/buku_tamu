<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TamuModel;

/**
 * Controller untuk dashboard admin
 * Menampilkan statistik, daftar tamu/pengunjung, dan laporan
 */
class AdminController extends Controller
{
    protected $tamuModel;

    public function __construct()
    {
        $this->tamuModel = new TamuModel();
    }

    /**
     * Dashboard admin dengan statistik ringkasan
     *
     * @return string
     */
    public function index()
    {
        $data = [
            'title'   => 'Dashboard Admin',
            'stats'   => $this->tamuModel->ringkasanDashboard(),
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Daftar pengunjung
     *
     * @return string
     */
    public function pengunjung()
    {
        $data = [
            'title' => 'Daftar Pengunjung'
        ];

        return view('admin/pengunjung_list', $data);
    }

    public function pengunjungDt()
    {
        $builder = $this->tamuModel->builder()->where('jenis_tamu', 'pengunjung');
        return \Hermawan\DataTables\DataTable::of($builder)
            ->add('aksi', function($row){
                $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                return '<button class="btn btn-sm btn-light-primary me-2" onclick="openEditModal('.$rowData.')">Edit</button>' .
                       '<button class="btn btn-sm btn-light-danger" onclick="deleteData('.$row->id.')">Hapus</button>';
            })
            ->format('tanggal', function($value){
                return '<div class="text-gray-800 fw-bold">'.date('d/m/Y', strtotime($value)).'</div>'.
                       '<div class="text-muted">'.date('H:i', strtotime($value)).'</div>';
            })
            ->format('nama', function($value){
                return '<div class="text-gray-800 fw-bold">'.esc($value).'</div>';
            })
            ->toJson(true);
    }

    /**
     * Daftar tamu
     *
     * @return string
     */
    public function tamu()
    {
        $data = [
            'title' => 'Daftar Tamu'
        ];

        return view('admin/tamu_list', $data);
    }

    public function tamuDt()
    {
        $builder = $this->tamuModel->builder()->where('jenis_tamu', 'tamu');
        return \Hermawan\DataTables\DataTable::of($builder)
            ->add('aksi', function($row){
                $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                return '<button class="btn btn-sm btn-light-primary me-2" onclick="openEditModal('.$rowData.')">Edit</button>' .
                       '<button class="btn btn-sm btn-light-danger" onclick="deleteData('.$row->id.')">Hapus</button>';
            })
            ->format('tanggal', function($value){
                return '<div class="text-gray-800 fw-bold">'.date('d/m/Y', strtotime($value)).'</div>'.
                       '<div class="text-muted">'.date('H:i', strtotime($value)).'</div>';
            })
            ->format('nama', function($value){
                return '<div class="text-gray-800 fw-bold">'.esc($value).'</div>';
            })
            ->toJson(true);
    }

    /**
     * Laporan bulanan dengan chart
     *
     * @return string
     */
    public function laporan()
    {
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        // Data untuk tabel
        $dataLaporan = $this->tamuModel
            ->filterBulanTahun($bulan, $tahun)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        // Data statistik bulanan untuk chart
        $statistik = $this->tamuModel->statistikBulanan($tahun);

        $data = [
            'title'       => 'Laporan Bulanan',
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'dataLaporan' => $dataLaporan,
            'statistik'   => $statistik,
        ];

        return view('admin/laporan', $data);
    }

    /**
     * API endpoint untuk data chart
     * Mengembalikan data dalam format JSON
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function chartData()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $statistik = $this->tamuModel->statistikBulanan($tahun);

        // Format data untuk Chart.js
        $labels = [];
        $pengunjungData = [];
        $tamuData = [];

        // Inisialisasi array untuk 12 bulan
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = $this->getNamaBulan($i);
            $pengunjungData[$i] = 0;
            $tamuData[$i] = 0;
        }

        // Isi data dari query
        foreach ($statistik as $row) {
            $bulan = (int) $row['bulan'];
            if ($row['jenis_tamu'] === 'pengunjung') {
                $pengunjungData[$bulan] = (int) $row['jumlah'];
            } else {
                $tamuData[$bulan] = (int) $row['jumlah'];
            }
        }

        return $this->response->setJSON([
            'labels'      => $labels,
            'pengunjung'  => array_values($pengunjungData),
            'tamu'        => array_values($tamuData),
        ]);
    }

    /**
     * Menyimpan data tamu/pengunjung baru via AJAX
     */
    public function storeTamu()
    {
        $jenisTamu = $this->request->getPost('jenis_tamu');
        
        $rules = [
            'jenis_tamu' => 'required|in_list[pengunjung,tamu]',
            'nama'       => 'required|max_length[255]',
            'hp'         => 'permit_empty|max_length[20]',
            'tujuan'     => 'required',
        ];

        if ($jenisTamu === 'pengunjung') {
            $rules['alamat'] = 'required';
        } else {
            $rules['instansi'] = 'required';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'jenis_tamu' => $jenisTamu,
            'tanggal'    => date('Y-m-d H:i:s'),
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat') ?? null,
            'instansi'   => $this->request->getPost('instansi') ?? null,
            'hp'         => $this->request->getPost('hp') ?? null,
            'tujuan'     => $this->request->getPost('tujuan'),
        ];

        if ($this->tamuModel->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    }

    /**
     * Update data tamu/pengunjung via AJAX
     */
    public function updateTamu($id)
    {
        $tamu = $this->tamuModel->find($id);
        if (!$tamu) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        $jenisTamu = $tamu['jenis_tamu'];
        
        $rules = [
            'nama'       => 'required|max_length[255]',
            'hp'         => 'permit_empty|max_length[20]',
            'tujuan'     => 'required',
        ];

        if ($jenisTamu === 'pengunjung') {
            $rules['alamat'] = 'required';
        } else {
            $rules['instansi'] = 'required';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'alamat'     => $this->request->getPost('alamat') ?? null,
            'instansi'   => $this->request->getPost('instansi') ?? null,
            'hp'         => $this->request->getPost('hp') ?? null,
            'tujuan'     => $this->request->getPost('tujuan'),
        ];

        if ($this->tamuModel->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update data']);
    }

    /**
     * Hapus data tamu/pengunjung via AJAX
     */
    public function deleteTamu($id)
    {
        if ($this->tamuModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal hapus data']);
    }

    /**
     * Export laporan dalam format Excel
     */
    public function exportExcel()
    {
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        $dataLaporan = $this->tamuModel
            ->filterBulanTahun($bulan, $tahun)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        $namaBulan = $this->getNamaBulan($bulan);

        // Load library PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'LAPORAN BUKU TAMU');
        $sheet->setCellValue('A2', 'Bulan: ' . $namaBulan . ' ' . $tahun);
        $sheet->setCellValue('A3', 'Dicetak: ' . date('d/m/Y H:i'));

        // Merge header
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');

        // Style header
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getFont()->setSize(10);

        // Header tabel
        $headerRow = 5;
        $headers = ['#', 'Tanggal', 'Jam', 'Jenis', 'Nama', 'Instansi / Alamat', 'Tujuan'];
        foreach ($headers as $col => $header) {
            $colLetter = chr(65 + $col);
            $sheet->setCellValue($colLetter . $headerRow, $header);
            $sheet->getStyle($colLetter . $headerRow)->getFont()->setBold(true);
            $sheet->getStyle($colLetter . $headerRow)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('2563EB');
            $sheet->getStyle($colLetter . $headerRow)->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($colLetter . $headerRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Data
        $rowNum = $headerRow + 1;
        $no = 1;
        foreach ($dataLaporan as $row) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, date('d/m/Y', strtotime($row['tanggal'])));
            $sheet->setCellValue('C' . $rowNum, date('H:i', strtotime($row['tanggal'])));
            $sheet->setCellValue('D' . $rowNum, ucfirst($row['jenis_tamu']));
            $sheet->setCellValue('E' . $rowNum, $row['nama']);
            $sheet->setCellValue('F' . $rowNum, $row['jenis_tamu'] === 'tamu' ? ($row['instansi'] ?? '-') : ($row['alamat'] ?? '-'));
            $sheet->setCellValue('G' . $rowNum, $row['tujuan'] ?? '-');

            // Alignment
            $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $rowNum++;
        }

        // Auto width kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download file
        $filename = 'laporan_buku_tamu_' . $namaBulan . '_' . $tahun . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: 0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Export laporan dalam format PDF
     */
    public function exportPdf()
    {
        $bulan = (int) ($this->request->getGet('bulan') ?? date('m'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        $dataLaporan = $this->tamuModel
            ->filterBulanTahun($bulan, $tahun)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        $namaBulan = $this->getNamaBulan($bulan);

        // Hitung statistik
        $totalPengunjung = count(array_filter($dataLaporan, fn($r) => $r['jenis_tamu'] === 'pengunjung'));
        $totalTamu = count(array_filter($dataLaporan, fn($r) => $r['jenis_tamu'] === 'tamu'));
        $total = count($dataLaporan);

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Tamu</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        h2 { text-align: center; font-size: 14px; font-weight: normal; margin-top: 0; }
        .meta { text-align: center; font-size: 10px; color: #666; margin-bottom: 20px; }
        .stats { display: flex; justify-content: center; gap: 30px; margin-bottom: 20px; }
        .stat-box { border: 1px solid #ddd; padding: 10px 20px; text-align: center; border-radius: 5px; }
        .stat-box .value { font-size: 24px; font-weight: bold; color: #2563EB; }
        .stat-box .label { font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #2563EB; color: white; padding: 8px 6px; text-align: center; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #ddd; vertical-align: top; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 9px; }
        .badge-pengunjung { background: #DBEAFE; color: #2563EB; }
        .badge-tamu { background: #DCFCE7; color: #16A34A; }
        .footer { text-align: center; font-size: 9px; color: #999; margin-top: 30px; }
    </style>
</head>
<body>
    <h1>LAPORAN BUKU TAMU</h1>
    <h2>' . $namaBulan . ' ' . $tahun . '</h2>
    <p class="meta">Dicetak pada: ' . date('d/m/Y H:i') . ' | Total: ' . $total . ' kunjungan</p>

    <div class="stats">
        <div class="stat-box">
            <div class="value">' . $totalPengunjung . '</div>
            <div class="label">Pengunjung</div>
        </div>
        <div class="stat-box">
            <div class="value">' . $totalTamu . '</div>
            <div class="label">Tamu</div>
        </div>
        <div class="stat-box">
            <div class="value">' . $total . '</div>
            <div class="label">Total Kunjungan</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Jenis</th>
                <th>Nama</th>
                <th>Instansi / Alamat</th>
                <th>Tujuan</th>
            </tr>
        </thead>
        <tbody>';

        $no = 1;
        foreach ($dataLaporan as $row) {
            $badgeClass = $row['jenis_tamu'] === 'pengunjung' ? 'badge-pengunjung' : 'badge-tamu';
            $instansi = $row['jenis_tamu'] === 'tamu' ? ($row['instansi'] ?? '-') : ($row['alamat'] ?? '-');
            $html .= '
            <tr>
                <td style="text-align:center">' . $no++ . '</td>
                <td style="text-align:center">' . date('d/m/Y', strtotime($row['tanggal'])) . '</td>
                <td style="text-align:center">' . date('H:i', strtotime($row['tanggal'])) . '</td>
                <td style="text-align:center"><span class="badge ' . $badgeClass . '">' . ucfirst($row['jenis_tamu']) . '</span></td>
                <td>' . htmlspecialchars($row['nama'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($instansi ?? '-', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($row['tujuan'] ?? '-', ENT_QUOTES, 'UTF-8') . '</td>
            </tr>';
        }

        $html .= '
        </tbody>
    </table>

    <p class="footer">Buku Tamu - Laporan Bulanan - Halaman 1</p>
</body>
</html>';

        // Generate PDF with Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'laporan_buku_tamu_' . $namaBulan . '_' . $tahun . '.pdf';
        $output = $dompdf->output();

        // Download PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($output));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        echo $output;
        exit;
    }

    /**
     * Helper untuk mendapatkan nama bulan
     *
     * @param int $bulan
     * @return string
     */
    private function getNamaBulan($bulan)
    {
        $bulanNama = [
            1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
            4  => 'April',   5  => 'Mei',      6  => 'Juni',
            7  => 'Juli',    8  => 'Agustus',  9  => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $bulanNama[$bulan] ?? '';
    }
}
