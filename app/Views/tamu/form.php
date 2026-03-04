<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow">
            <div class="card-header bg-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?> text-white">
                <h4 class="mb-0">
                    <i class="bi bi-<?= $jenis_tamu === 'pengunjung' ? 'people' : 'person-badge' ?> me-2"></i>
                    Form Pendaftaran <?= ucfirst($jenis_tamu) ?>
                </h4>
            </div>
            <div class="card-body p-4">
                <!-- Error Messages -->
                <?php if (isset($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/tamu/store" method="post">
                    <!-- Hidden field untuk jenis tamu -->
                    <input type="hidden" name="jenis_tamu" value="<?= esc($jenis_tamu) ?>">

                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               value="<?= old('nama') ?>" required>
                    </div>

                    <!-- Alamat (untuk pengunjung) / Instansi (untuk tamu) -->
                    <?php if ($jenis_tamu === 'pengunjung'): ?>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= old('alamat') ?></textarea>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label for="instansi" class="form-label">Instansi/Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="instansi" name="instansi"
                                   value="<?= old('instansi') ?>" required>
                        </div>
                    <?php endif; ?>

                    <!-- No. HP -->
                    <div class="mb-3">
                        <label for="hp" class="form-label">Nomor HP</label>
                        <input type="tel" class="form-control" id="hp" name="hp"
                               value="<?= old('hp') ?>" placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- Tujuan -->
                    <div class="mb-4">
                        <label for="tujuan" class="form-label">Tujuan Kunjungan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="tujuan" name="tujuan" rows="3" required><?= old('tujuan') ?></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?> btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Simpan Data
                        </button>
                        <a href="/" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
