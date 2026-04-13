<?= $this->extend('layouts/metronic') ?>

<?php
$themeColor = $jenis_tamu === 'pengunjung' ? 'primary' : 'success';
$themeColorHex = $jenis_tamu === 'pengunjung' ? '#3699FF' : '#50CD89';
?>

<!-- Style moved to form_tamu.css -->

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10">
        
        <!-- Judul Atas -->
        <div class="text-center mb-10">
            <h1 class="text-white fw-bolder fs-2qx mb-3" style="text-shadow: 0 2px 8px rgba(0,0,0,0.35);">
                <i class="ki-duotone <?= $jenis_tamu === 'pengunjung' ? 'ki-people' : 'ki-badge' ?> text-white fs-1 me-2">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                </i>
                Form Pendaftaran <?= ucfirst($jenis_tamu) ?>
            </h1>
            <p class="text-white opacity-90 fs-5" style="text-shadow: 0 1px 4px rgba(0,0,0,0.3);">Silakan lengkapi langkah-langkah di bawah ini untuk mendapatkan tiket antrian.</p>
        </div>

        <!-- Card Modern -->
        <div class="card form-card w-lg-750px theme-<?= $themeColor ?>">
            <div class="card-body p-8 p-lg-12">

                <!-- Stepper Indicator -->
                <div class="stepper stepper-pills d-flex flex-column flex-sm-row justify-content-between mb-15" id="kt_form_stepper">
                    <!-- Step 1 -->
                    <div class="stepper-item current d-flex align-items-center flex-column flex-sm-row" data-kt-stepper-element="nav" data-step="1">
                        <div class="stepper-wrapper d-flex align-items-center">
                            <div class="stepper-icon rounded-circle d-flex align-items-center justify-content-center fw-bolder fs-4">
                                <span class="stepper-number">1</span>
                                <i class="fas fa-check fs-3 stepper-check d-none"></i>
                            </div>
                            <div class="stepper-label ms-3 mt-2 mt-sm-0">
                                <h3 class="stepper-title fs-6 mb-0">Foto Wajah</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stepper-line d-none d-sm-block"></div>

                    <!-- Step 2 -->
                    <div class="stepper-item d-flex align-items-center flex-column flex-sm-row" data-kt-stepper-element="nav" data-step="2">
                        <div class="stepper-wrapper d-flex align-items-center">
                            <div class="stepper-icon rounded-circle d-flex align-items-center justify-content-center fw-bolder fs-4">
                                <span class="stepper-number">2</span>
                                <i class="fas fa-check fs-3 stepper-check d-none"></i>
                            </div>
                            <div class="stepper-label ms-3 mt-2 mt-sm-0">
                                <h3 class="stepper-title fs-6 mb-0">Data Diri</h3>
                            </div>
                        </div>
                    </div>

                    <div class="stepper-line d-none d-sm-block"></div>

                    <!-- Step 3 -->
                    <div class="stepper-item d-flex align-items-center flex-column flex-sm-row" data-kt-stepper-element="nav" data-step="3">
                        <div class="stepper-wrapper d-flex align-items-center">
                            <div class="stepper-icon rounded-circle d-flex align-items-center justify-content-center fw-bolder fs-4">
                                <span class="stepper-number">3</span>
                                <i class="fas fa-check fs-3 stepper-check d-none"></i>
                            </div>
                            <div class="stepper-label ms-3 mt-2 mt-sm-0">
                                <h3 class="stepper-title fs-6 mb-0">Tujuan</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Stepper -->

                <!-- Form -->
                <?php $errorSession = session()->getFlashdata('errors'); ?>
                <form action="/tamu/store" method="post" class="form" id="kt_form_pendaftaran" data-error="<?= isset($errors) ? esc(implode('\n', $errors)) : '' ?>">
                    <input type="hidden" name="jenis_tamu" value="<?= esc($jenis_tamu) ?>">

                    <!-- ===================== STEP 1: FOTO WAJAH ===================== -->
                    <div class="form-step" data-form-step="1">
                        <div class="mb-4 text-center">
                            <h2 class="fw-bolder text-gray-900">Pindai Wajah Anda</h2>
                            <div class="text-muted fw-semibold fs-6">Pastikan wajah Anda berada tepat di dalam bingkai oval batas.</div>
                        </div>
                        
                        <!-- Area Kamera (tanpa tombol kontrol di dalam) -->
                        <div class="camera-box mb-4">
                            <!-- Flash Overlay -->
                            <div class="camera-flash" id="cameraFlash"></div>

                            <!-- SVG Guide Premium (Lebih Tipis & Elegan) -->
                            <div class="camera-guide-svg">
                                <svg width="240" height="300" viewBox="0 0 240 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <ellipse cx="120" cy="150" rx="100" ry="135" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-dasharray="10 10"/>
                                </svg>
                            </div>

                            <!-- Video feed -->
                            <video id="kamera-video" class="camera-feed" autoplay playsinline></video>

                            <!-- Snapshot preview -->
                            <img id="kamera-preview" class="camera-feed" style="display: none;" />

                            <!-- Countdown overlay -->
                            <div class="countdown-overlay" id="countdownOverlay" style="display: none;">
                                <span class="countdown-number" id="countdownNumber"></span>
                            </div>
                        </div>

                        <!-- Area Kontrol Kamera (DIPISAHKAN dari camera-box) -->
                        <div class="camera-controls-external">
                            <!-- Loading Indikator Kamera -->
                            <div id="camera-loading" class="text-center mb-3">
                                <div class="spinner-border text-<?= $themeColor ?> spinner-border-sm me-2" role="status"></div>
                                <span class="opacity-75 fw-semibold">Menginisialisasi Kamera...</span>
                            </div>

                            <!-- Area Tombol Utama -->
                            <div id="camera-shutter-group" style="display: none;" class="text-center">
                                <button type="button" id="btn-ambil-foto" class="btn btn-<?= $themeColor ?> btn-lg rounded-pill fw-bold px-8">
                                    <i class="fas fa-camera me-2"></i> Ambil Foto
                                </button>
                            </div>

                            <!-- Tombol Ulangi (setelah pengambilan foto) -->
                            <div id="btn-ulangi-wrapper" style="display:none;" class="text-center">
                                <button type="button" id="btn-ulangi-foto" class="btn btn-outline btn-outline-dashed btn-outline-<?= $themeColor ?> rounded-pill px-6 fw-semibold">
                                    <i class="fas fa-sync-alt"></i> Ambil Ulang Foto
                                </button>
                            </div>
                        </div>

                        <!-- Upload fallback (tampil jika kamera gagal) -->
                        <div class="upload-fallback mt-6" id="uploadFallback">
                            <div class="alert alert-warning bg-light-warning border-warning d-flex align-items-center p-5 mb-5">
                                <i class="ki-duotone ki-information fs-2hx text-warning me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-warning">Kamera Tidak Tersedia</h4>
                                    <span>Pilih file foto dari galeri gawai Anda sebagai alternatif.</span>
                                </div>
                            </div>
                            <label class="btn btn-outline btn-outline-dashed btn-outline-<?= $themeColor ?> btn-active-light-<?= $themeColor ?> w-100 p-8" for="fileUpload">
                                <i class="ki-duotone ki-file-up fs-3x mb-3 text-<?= $themeColor ?>"><span class="path1"></span><span class="path2"></span></i><br>
                                <span class="fs-4 fw-bold">Ketuk untuk Mengunggah Foto</span>
                            </label>
                            <input type="file" id="fileUpload" accept="image/*" capture="user" style="display: none;" />
                        </div>

                        <canvas id="kamera-canvas" style="display:none;"></canvas>
                        <input type="hidden" name="foto_base64" id="foto_base64" required>
                    </div>

                    <!-- ===================== STEP 2: DATA DIRI ===================== -->
                    <div class="form-step" data-form-step="2" style="display: none;">
                        <div class="mb-6">
                            <h2 class="fw-bolder text-gray-900">Informasi Pribadi</h2>
                            <div class="text-muted fw-semibold fs-6">Silakan masukkan data diri Anda yang valid.</div>
                        </div>

                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Nama Lengkap</span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="nama" id="inputNama" value="<?= old('nama') ?>" placeholder="Masukkan nama (min. 3 karakter)" required />
                            <div class="valid-feedback">Nama valid <i class="fas fa-check text-success ms-1"></i></div>
                            <div class="invalid-feedback">Nama terlalu pendek</div>
                        </div>

                        <?php if ($jenis_tamu === 'pengunjung'): ?>
                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Alamat Domisili</span>
                            </label>
                            <textarea class="form-control form-control-lg form-control-solid" name="alamat" rows="3" placeholder="Contoh: Jl. Sudirman No. 12" required><?= old('alamat') ?></textarea>
                        </div>
                        <?php else: ?>
                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Instansi / Perusahaan Asal</span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="instansi" value="<?= old('instansi') ?>" placeholder="Contoh: PT. Maju Jaya" required />
                        </div>
                        <?php endif; ?>

                        <div class="fv-row mb-10">
                            <label class="fs-5 fw-semibold mb-2">Nomor HP</label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text"><i class="ki-duotone ki-phone fs-2"></i></span>
                                <input type="tel" class="form-control form-control-lg form-control-solid" name="hp" id="inputHp" value="<?= old('hp') ?>" placeholder="Contoh: 081234567890" />
                            </div>
                            <div class="valid-feedback pt-2">Nomor HP valid</div>
                            <div class="invalid-feedback pt-2">Format nomor HP tidak valid</div>
                        </div>

                        <!-- Panel Navigasi -->
                        <div class="d-flex flex-stack pt-10 border-top mt-10">
                            <button type="button" class="btn btn-light btn-active-light-<?= $themeColor ?> rounded-pill fw-bold px-8 py-3" onclick="goToStep(1)">
                                <i class="ki-duotone ki-arrow-left fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Kembali
                            </button>
                            <button type="button" class="btn btn-<?= $themeColor ?> rounded-pill fw-bold px-8 py-3" id="btn-next-step2">
                                Selanjutnya <i class="ki-duotone ki-arrow-right fs-2 ms-2"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>
                    </div>

                    <!-- ===================== STEP 3: TUJUAN ===================== -->
                    <div class="form-step" data-form-step="3" style="display: none;">
                        <div class="mb-6">
                            <h2 class="fw-bolder text-gray-900">Maksud Kedatangan</h2>
                            <div class="text-muted fw-semibold fs-6">Mohon deskripsikan secara rinci keperluan atau layanan yang Anda butuhkan.</div>
                        </div>

                        <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-3">
                                <span class="required">Keperluan / Keterangan</span>
                            </label>
                            <textarea class="form-control form-control-lg form-control-solid border-gray-300" name="tujuan" rows="6" placeholder="Beri tau kami keperluan Anda..." required><?= old('tujuan') ?></textarea>
                        </div>

                        <!-- Panel Navigasi -->
                        <div class="d-flex flex-stack pt-10 border-top mt-10">
                            <button type="button" class="btn btn-light btn-active-light-<?= $themeColor ?> rounded-pill fw-bold px-8 py-3" id="btn-back-step3">
                                <i class="ki-duotone ki-arrow-left fs-2 me-2"><span class="path1"></span><span class="path2"></span></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-<?= $themeColor ?> btn-lg rounded-pill fw-bolder px-10 py-3 shadow-sm">
                                <i class="ki-duotone ki-check fs-2 me-2"></i> Selesai & Simpan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Scripts moved to form_tamu.js -->
