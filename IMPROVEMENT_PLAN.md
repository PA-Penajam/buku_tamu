# Rencana Perbaikan Proyek Buku Tamu (Prioritas Tinggi ke Rendah)

## 🎯 RINGKASAN HASIL TEMUAN

**Proyek**: Aplikasi Buku Tamu (Guestbook) CodeIgniter 4
**Status**: Fungsional tapi perlu peningkatan signifikan
**Fokus**: Security, Performance, Code Quality, Testing

---

## 📋 DAFTAR PERBAIKAN BERDASARKAN PRIORITAS

### 🔴 **HIGH PRIORITY** (Segera Dikerjakan - Minggu Depan)

#### 1. **Security Hardening**
**File**: `app/Controllers/TamuController.php`, `app/Models/TamuModel.php`

**Masalah**: Input validation minim, risiko XSS dan injection
**Perbaikan**:
```php
// Di TamuController::store()
// Tambahkan sanitasi input
$rules = [
    'nama' => 'required|max_length[255]|alpha_numeric_spaces',
    'tujuan' => 'required|max_length[500]|alpha_numeric_punct',
    'foto_base64' => 'required|regex_match[/^data:image\/(jpeg|png|gif);base64,]/'],
];
```

**Security Checklist**:
- [ ] Gunakan `form_validation` dengan rules ketat
- [ ] Sanitize semua output dengan `esc()`
- [ ] Validasi tipe file upload (hanya jpg, png, gif)
- [ ] Batasi ukuran file upload (max 2MB)
- [ ] Hindari direct file_put_contents dengan user input
- [ ] Gunakan prepared statements (sudah menggunakan ORM)

#### 2. **Database Indexing**
**File**: Perlu ditambahkan migration atau SQL manual

**Masalah**: Query tanpa index pada kolom `tanggal`
**Perbaikan**:
```php
// Buat migration baru
public function up()
{
    $this->forge->addKey('tanggal');
    $this->forge->addKey('jenis_tamu');
    $this->forge->addKey('created_at');
    $this->forge->createTable('tamu');
}
```

**Perintah SQL**:
```sql
CREATE INDEX idx_tanggal ON tamu(tanggal);
CREATE INDEX idx_jenis_tamu ON tamu(jenis_tamu);
CREATE INDEX idx_created_at ON tamu(created_at);
```

#### 3. **Password Hashing**
**File**: `app/Controllers/AuthController.php`

**Masalah**: Password tidak di-hash (asumsi)
**Perbaikan**:
```php
use Config\Services;

// Saat login
$hashedPassword = Services::passwords()->hash($password);
if (Services::passwords()->verify($password, $storedHash)) {
    // Berhasil login
}
```

---
n
### 🟡 **MEDIUM PRIORITY** (Sudah Minggu Depan)

#### 4. **Code Refactoring - Repository Pattern**
**File**: `app/Repositories/TamuRepository.php` (dibuat baru)

**Masalah**: Business logic campur dengan controller
**Perbaikan**:
```php
<?php
namespace App\Repositories;

use App\Models\TamuModel;

class TamuRepository {
    protected $model;
    
    public function __construct() {
        $this->model = new TamuModel();
    }
    
    public function saveGuest(array $data): bool {
        // Validasi bisnis logic
        return $this->model->insert($data);
    }
    
    public function getRecentGuests(int $limit = 5): array {
        return $this->model->orderBy('tanggal', 'DESC')
                          ->limit($limit)
                          ->findAll();
    }
}
```

**Controller baru**:
```php
// app/Controllers/TamuController.php
public function __construct() {
    $this->tamuRepository = new \App\Repositories\TamuRepository();
}
```

#### 5. **Error Handling Enhancement**
**File**: `app/Controllers/TamuController.php`

**Perbaikan**:
```php
public function store() {
    try {
        // Validasi dan proses
        if (!$this->validate($rules)) {
            return $this->validationResponse();
        }
        
        $result = $this->tamuRepository->saveGuest($data);
        if (!$result) {
            throw new \Exception('Gagal menyimpan data');
        }
        
        return redirect()->to('/tamu/sukses')->with('success', 'Data berhasil disimpan');
        
    } catch (\Exception $e) {
        log_message('error', 'TamuController error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan sistem');
    }
}
```

#### 6. **Response Standardization**
**File**: `app/Helpers/response_helper.php` (dibuat baru)

```php
if (!function_exists('response_json')) {
    function response_json($data, int $status = 200, string $message = '') {
        return \Config\Services::response()->setJSON([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ])->setStatusCode($status);
    }
}
```

---

### 🟢 **LOW PRIORITY** (Sudah Memuaskan/Melanjutkan)

#### 7. **Performance Optimization**
**Status**: Sudah cukup baik
**Perbaikan opsional**:
- Implementasi caching untuk statistik dashboard
- Gunakan query builder daripada query manual
- Lazy loading untuk gambar

#### 8. **Testing**
**File**: `tests/Feature/TamuTest.php` (tambah)

```php
<?php
namespace Tests\Feature;

use CodeIgniter\Test\CIDatabaseTestTrait;
use CodeIgniter\Test\CIFilterTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class TamuTest extends CIUnitTestCase {
    use CIDatabaseTestTrait;
    
    public function testStoreGuestSuccess() {
        $data = [
            'jenis_tamu' => 'tamu',
            'nama' => 'Test User',
            'tujuan' => 'Kunjungan'
        ];
        $result = $this->client->post('/tamu/store', $data);
        $this->assertEquals(200, $result->getStatusCode());
    }
}
```

#### 9. **UX/UI Enhancement**
**Status**: Sudah responsive (memakai framework)
**Perbaikan opsional**:
- Loading state pada form submit
- Toast notification untuk success/error
- Loading skeleton untuk data statistik

---

## 📊 JADWAL IMPLEMENTASI

| Minggu | Prioritas | Tugas | Owner | Status |
|--------|-----------|-------|-------|--------|
| Minggu 1 | HIGH | Security hardening | Tim Dev | Belum |
| Minggu 1 | HIGH | Database indexing | Tim Dev | Belum |
| Minggu 1 | HIGH | Password hashing | Tim Dev | Belum |
| Minggu 2 | MEDIUM | Repository pattern | Tim Dev | Belum |
| Minggu 2 | MEDIUM | Error handling | Tim Dev | Belum |
| Minggu 3 | LOW | Testing | Tim QA | Belum |
| Minggu 3 | LOW | UX enhancement | Tim Design | Belum |

---

## ⚠️ RISKO YANG DITEKINI

1. **Security Risk**: Tanpa hardening, aplikasi rentan terhadap serangan
2. **Performance Issue**: Query lambat saat data bertambah banyak
3. **Technical Debt**: Code yang tercampur sulit dipelihari

---

## ✅ VERIFICATION CHECKLIST

**Sebelum deploy ke production**:
- [ ] Semua input field memiliki validation
- [ ] Semua output di-escape
- [ ] Semua query sudah di-index
- [ ] Semua password di-hash
- [ ] Unit tests sudah 80%+ coverage
- [ ] Manual testing dilakukan
- [ ] Error logging aktif

---

**Catatan**: Rencana ini mengikuti prinsip **D.R.Y**, **K.I.S.S**, dan **Y.A.G.N.I**. Fokus pada security terlebih dahulu karena data pengguna terlibat.