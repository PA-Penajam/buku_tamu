<?= $this->extend('layouts/metronic') ?>

<?php
$themeColor = $jenis_tamu === 'pengunjung' ? 'primary' : 'success';
$themeColorHex = $jenis_tamu === 'pengunjung' ? '#3699FF' : '#50CD89';
?>

<?= $this->section('styles') ?>
<style>
    /* Styling khusus Card Form agar menyatu dengan background */
    .form-card {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 10px 30px 0 rgba(0,0,0,0.15);
        background-color: #ffffff;
        overflow: hidden;
    }

    /* Stepper Styling Custom - Modern Glassmorphism */
    .stepper-pills {
        background: rgba(243, 246, 249, 0.4);
        padding: 1.5rem;
        border-radius: 1.25rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(228, 230, 239, 0.6);
    }

    .stepper-pills .stepper-item .stepper-icon {
        background-color: #f5f5f5 !important;
        color: #A1A5B7 !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid #E4E6EF !important;
        width: 45px !important;
        height: 45px !important;
    }
    
    .stepper-pills .stepper-item .stepper-icon .stepper-number {
        color: #A1A5B7 !important;
    }
    
    .stepper-pills .stepper-item .stepper-title {
        color: #7E8299;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* Saat aktif / current */
    .stepper-pills .stepper-item.current .stepper-icon {
        background-color: var(--bs-<?= $themeColor ?>) !important;
        color: #ffffff !important;
        border-color: var(--bs-<?= $themeColor ?>) !important;
        box-shadow: 0 0 15px 0 rgba(var(--bs-<?= $themeColor ?>-rgb), 0.35);
    }
    
    .stepper-pills .stepper-item.current .stepper-icon .stepper-number {
        color: #ffffff !important;
    }
    
    .stepper-pills .stepper-item.current .stepper-title {
        color: var(--bs-<?= $themeColor ?>) !important;
        font-weight: 800;
    }

    /* Saat selesai / completed — warna mengikuti tema */
    .stepper-pills .stepper-item.completed .stepper-icon {
        background-color: <?= $themeColorHex ?>1a !important;
        color: <?= $themeColorHex ?> !important;
        border-color: <?= $themeColorHex ?> !important;
    }
    
    .stepper-pills .stepper-item.completed .stepper-title {
        color: <?= $themeColorHex ?>;
    }

    /* Icon check untuk completed step */
    .stepper-pills .stepper-item.completed .stepper-check {
        color: <?= $themeColorHex ?> !important;
    }

    /* Garis Stepper */
    .stepper-pills .stepper-item .stepper-line {
        border-bottom: 2px solid #E4E6EF;
        margin: 0 1.5rem;
        flex-grow: 1;
        opacity: 0.5;
    }
    .stepper-item.completed .stepper-line {
        border-bottom-color: <?= $themeColorHex ?>;
        opacity: 1;
    }

    /* Kamera & Wajah Frame */
    .camera-box {
        position: relative;
        width: 100%;
        max-width: 520px;
        height: 380px;
        margin: 0 auto;
        background-color: #000000;
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        border: 4px solid #ffffff;
    }

    .camera-feed {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
        transition: filter 0.5s ease;
    }

    .camera-guide-svg {
        position: absolute;
        top: 45%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 5;
        transition: opacity 0.3s ease;
    }

    /* Flash Effect */
    .camera-flash {
        position: absolute;
        inset: 0;
        background: white;
        opacity: 0;
        z-index: 50;
        pointer-events: none;
    }
    @keyframes flash-anim {
        0% { opacity: 0; }
        10% { opacity: 1; }
        100% { opacity: 0; }
    }
    .do-flash { animation: flash-anim 0.4s ease-out; }

    /* Area Kontrol Kamera External (Dipisahkan dari camera-box) */
    .camera-controls-external {
        padding: 1rem 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* Countdown overlay */
    .countdown-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.7);
        z-index: 20;
    }
    .countdown-number {
        font-size: 8rem;
        font-weight: 800;
        color: var(--bs-<?= $themeColor ?>);
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    }

    /* Validasi Feedback */
    .is-valid { border-color: #50CD89 !important; }
    .is-invalid { border-color: #F1416C !important; }
    .valid-feedback { display: none; color: #50CD89; font-size: 0.85rem; margin-top: 4px; }
    .invalid-feedback { display: none; color: #F1416C; font-size: 0.85rem; margin-top: 4px; }
    .is-valid ~ .valid-feedback { display: block; }
    .is-invalid ~ .invalid-feedback { display: block; }

    .upload-fallback { display: none; }

    /* Ukuran font bantuan */
    .fs-7 { font-size: 0.85rem !important; }
</style>
<?= $this->endSection() ?>

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
        <div class="card form-card w-lg-750px">
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
                <form action="/tamu/store" method="post" class="form" id="kt_form_pendaftaran">
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

<?= $this->section('scripts') ?>
<?php if (isset($errors) && !empty($errors)): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
        text: "<?= addslashes(implode('\n', $errors)) ?>",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok, mengerti!",
        customClass: { confirmButton: "btn btn-danger" }
    });
});
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // === ELEMEN DOM ===
    const video = document.getElementById('kamera-video');
    const canvas = document.getElementById('kamera-canvas');
    const preview = document.getElementById('kamera-preview');
    const btnAmbil = document.getElementById('btn-ambil-foto');
    const btnUlangiWrapper = document.getElementById('btn-ulangi-wrapper');
    const btnUlangi = document.getElementById('btn-ulangi-foto');
    const inputBase64 = document.getElementById('foto_base64');
    const countdownOverlay = document.getElementById('countdownOverlay');
    const countdownNumber = document.getElementById('countdownNumber');
    const cameraGuide = document.querySelector('.camera-guide-svg');
    const cameraFlash = document.getElementById('cameraFlash');
    const shutterGroup = document.getElementById('camera-shutter-group');
    const uploadFallback = document.getElementById('uploadFallback');
    const fileUpload = document.getElementById('fileUpload');
    let stream = null;

    // === INIT KAMERA ===
    async function initCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                audio: false
            });
            video.srcObject = stream;

            // Sembunyikan loading dan tampilkan tombol setelah kamera siap
            video.onloadedmetadata = function() {
                document.getElementById('camera-loading').style.display = 'none';
                shutterGroup.style.display = 'block';
            };

        } catch (err) {
            console.error('Kamera gagal:', err);
            const loadingEl = document.getElementById('camera-loading');
            loadingEl.classList.add('text-warning');
            loadingEl.textContent = '⚠️ Kamera tidak dapat diakses. Coba izinkan akses kamera di browser Anda.';
            // Tampilkan upload fallback jika kamera gagal (tidak diberi izin / hardware rusak)
            uploadFallback.style.display = 'block';
            document.querySelector('.camera-box').style.display = 'none';
        }
    }
    initCamera();

    // === LOGIKA CAPTURE (LANGSUNG TANPA COUNTDOWN) ===
    btnAmbil.addEventListener('click', function () {
        if (!stream) {
            Swal.fire('Peringatan', 'Kamera belum aktif/diizinkan', 'warning');
            return;
        }
        btnAmbil.disabled = true;
        capturePhoto();
    });

    function capturePhoto() {
        // Flash Animation
        cameraFlash.classList.remove('do-flash');
        void cameraFlash.offsetWidth; // Trigger reflow
        cameraFlash.classList.add('do-flash');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        // Compress image to Base64
        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        inputBase64.value = dataUrl;

        // Tampilkan Preview Foto
        preview.src = dataUrl;
        preview.style.display = 'block';
        video.style.display = 'none';

        // Swap tombol di panel
        shutterGroup.style.display = 'none';
        btnUlangiWrapper.style.display = 'block';
        
        // Sembunyikan guide
        if(cameraGuide) cameraGuide.style.opacity = '0';

        // Langsung pindah ke step 2
        updateStepper(1, 'completed');
        updateStepper(2, 'current');
        showStep(2);
    }

    // Mengulang Pengambilan Foto
    btnUlangi.addEventListener('click', function () {
        inputBase64.value = '';
        preview.style.display = 'none';
        preview.src = '';
        video.style.display = 'block';

        shutterGroup.style.display = 'block';
        btnUlangiWrapper.style.display = 'none';
        if(cameraGuide) cameraGuide.style.opacity = '1';
    });

    // === FALLBACK: UPLOAD DARI FILE (JIKA KAMERA ERROR) ===
    fileUpload.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            const dataUrl = event.target.result;
            inputBase64.value = dataUrl;

            // Memaksa tampilkan preview file di kamera box
            document.querySelector('.camera-box').style.display = 'block';
            cameraGuide.style.display = 'none';
            btnAmbil.style.display = 'none';
            video.style.display = 'none';
            
            preview.src = dataUrl;
            preview.style.display = 'block';
            preview.style.transform = 'none'; // batalkan mirror untuk upload gambar

            uploadFallback.style.display = 'none';
            btnUlangiWrapper.style.display = 'block';

            // Langsung pindah ke step 2
            updateStepper(1, 'completed');
            updateStepper(2, 'current');
            showStep(2);
        };
        reader.readAsDataURL(file);
    });

    // === STEPPER & NAVIGASI TAB LOGIC ===
    const inputNama = document.getElementById('inputNama');
    const inputHp = document.getElementById('inputHp');
    const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,10}$/; // Format No HP Indo

    function updateStepper(stepNum, state) {
        const items = document.querySelectorAll('#kt_form_stepper .stepper-item');
        const index = stepNum - 1;

        items.forEach(function (item, i) {
            item.classList.remove('current', 'completed');
            const checkIcon = item.querySelector('.stepper-check');
            const numberSpan = item.querySelector('.stepper-number');

            if (i < index) {
                item.classList.add('completed');
                if(checkIcon) checkIcon.classList.remove('d-none');
                if(numberSpan) numberSpan.classList.add('d-none');
            } else if (i === index) {
                item.classList.add(state);
                if(state === 'completed') {
                    if(checkIcon) checkIcon.classList.remove('d-none');
                    if(numberSpan) numberSpan.classList.add('d-none');
                } else {
                    if(checkIcon) checkIcon.classList.add('d-none');
                    if(numberSpan) numberSpan.classList.remove('d-none');
                }
            } else {
                if(checkIcon) checkIcon.classList.add('d-none');
                if(numberSpan) numberSpan.classList.remove('d-none');
            }
        });
    }

    function showStep(stepNum) {
        document.querySelectorAll('.form-step').forEach(function (el) {
            el.style.display = 'none';
        });
        const target = document.querySelector('[data-form-step="' + stepNum + '"]');
        if (target) target.style.display = 'block';
    }

    // Navigasi mundur ke step tertentu
    window.goToStep = function (stepNum) {
        if (stepNum === 1) {
            // Pastikan countdown overlay tersembunyi saat kembali ke step 1
            countdownOverlay.style.display = 'none';
            if(cameraGuide) cameraGuide.style.opacity = '1';
            
            updateStepper(1, 'current');
            showStep(1);
        }
    };

    // Validasi form manual sebelum pindah step (Tombol Next di Step 2)
    const btnNextStep2 = document.getElementById('btn-next-step2');
    if (btnNextStep2) {
        btnNextStep2.addEventListener('click', function () {
            // Cek nama
            if (inputNama && inputNama.value.trim().length < 3) {
                inputNama.classList.add('is-invalid');
                inputNama.focus();
                return;
            }

            // Cek HP (jika diisi)
            if (inputHp && inputHp.value.trim() !== '' && !phoneRegex.test(inputHp.value.trim())) {
                inputHp.classList.add('is-invalid');
                inputHp.focus();
                return;
            }

            // Lolos, Lanjut ke Step 3
            updateStepper(2, 'completed');
            updateStepper(3, 'current');
            showStep(3);
        });
    }

    // Tombol Back di Step 3
    const btnBackStep3 = document.getElementById('btn-back-step3');
    if (btnBackStep3) {
        btnBackStep3.addEventListener('click', function () {
            updateStepper(2, 'current');
            showStep(2);
        });
    }

    // === VALIDASI INPUT INLINE ===
    if (inputNama) {
        inputNama.addEventListener('input', function () {
            if (this.value.trim().length >= 3) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (this.value.trim().length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }

    if (inputHp) {
        inputHp.addEventListener('input', function () {
            if (this.value.trim() === '') {
                this.classList.remove('is-valid', 'is-invalid');
            } else if (phoneRegex.test(this.value.trim())) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    }

    // Mencegah Form disubmit jika Step belum komplit
    document.getElementById('kt_form_pendaftaran').addEventListener('submit', function (e) {
        if (!inputBase64.value) {
            e.preventDefault();
            Swal.fire({
                text: 'Mohon penuhi langkah 1 (Ambil foto terlebih dahulu)',
                icon: 'warning',
                confirmButtonText: 'Baik',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            window.goToStep(1);
            return;
        }
        
        // Re-validasi ke step 2 jika langsung enter submit
        if (inputNama && inputNama.value.trim().length < 3) {
            e.preventDefault();
            updateStepper(2, 'current');
            showStep(2);
            inputNama.focus();
            return;
        }
    });
});
</script>
<?= $this->endSection() ?>
