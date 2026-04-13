<?= $this->extend('layouts/metronic') ?>

<?= $this->section('styles') ?>
<style>
    /* Oval guide overlay untuk kamera */
    .camera-guide-oval {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -55%);
        width: 220px;
        height: 280px;
        border: 3px dashed rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        pointer-events: none;
        z-index: 5;
    }

    /* Countdown overlay */
    .countdown-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10;
    }
    .countdown-number {
        font-size: 72px;
        font-weight: 800;
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    /* Stepper styling */
    .stepper-item.current .stepper-icon {
        background-color: var(--kt-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?>, #<?= $jenis_tamu === 'pengunjung' ? '3699FF' : '50CD89' ?>);
        color: white;
    }
    .stepper-item.current .stepper-title {
        color: var(--kt-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?>, #<?= $jenis_tamu === 'pengunjung' ? '3699FF' : '50CD89' ?>);
    }
    .stepper-item.completed .stepper-icon {
        background-color: #50CD89;
        color: white;
    }
    .stepper-item.completed .stepper-check { display: inline; }
    .stepper-item.completed .stepper-number { display: none; }
    .stepper-item .stepper-check { display: none; }

    /* Validasi inline feedback */
    .is-valid { border-color: #50CD89 !important; }
    .is-invalid { border-color: #F1416C !important; }
    .valid-feedback { display: none; color: #50CD89; font-size: 0.85rem; margin-top: 4px; }
    .invalid-feedback { display: none; color: #F1416C; font-size: 0.85rem; margin-top: 4px; }
    .is-valid ~ .valid-feedback { display: block; }
    .is-invalid ~ .invalid-feedback { display: block; }

    /* Upload fallback button */
    .upload-fallback { display: none; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-root h-100" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10">
        <div class="card card-flush w-lg-650px py-5 shadow-sm">
            <div class="card-header bg-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?> py-5 rounded-top">
                <h3 class="card-title text-white fw-bolder mb-0">
                    <i class="ki-duotone <?= $jenis_tamu === 'pengunjung' ? 'ki-people' : 'ki-badge' ?> text-white fs-2x me-3">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                    Form Pendaftaran <?= ucfirst($jenis_tamu) ?>
                </h3>
            </div>

            <div class="card-body py-10">

                <!-- Stepper Indicator -->
                <div class="stepper stepper-pills d-flex justify-content-center mb-10" id="kt_form_stepper">
                    <div class="stepper-item current mx-2 my-2" data-kt-stepper-element="nav" data-step="1">
                        <div class="stepper-wrapper d-flex align-items-center">
                            <div class="stepper-icon w-30px h-30px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">1</span>
                            </div>
                            <div class="stepper-label ms-2">
                                <h3 class="stepper-title fs-6 fw-bold mb-0">Foto Wajah</h3>
                            </div>
                        </div>
                        <div class="stepper-line h-40px"></div>
                    </div>
                    <div class="stepper-item mx-2 my-2" data-kt-stepper-element="nav" data-step="2">
                        <div class="stepper-wrapper d-flex align-items-center">
                            <div class="stepper-icon w-30px h-30px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">2</span>
                            </div>
                            <div class="stepper-label ms-2">
                                <h3 class="stepper-title fs-6 fw-bold mb-0">Data Diri</h3>
                            </div>
                        </div>
                        <div class="stepper-line h-40px"></div>
                    </div>
                    <div class="stepper-item mx-2 my-2" data-kt-stepper-element="nav" data-step="3">
                        <div class="stepper-wrapper d-flex align-items-center">
                            <div class="stepper-icon w-30px h-30px rounded-circle bg-light d-flex align-items-center justify-content-center">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">3</span>
                            </div>
                            <div class="stepper-label ms-2">
                                <h3 class="stepper-title fs-6 fw-bold mb-0">Tujuan Kunjungan</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="/tamu/store" method="post" class="form" id="kt_form_pendaftaran">
                    <input type="hidden" name="jenis_tamu" value="<?= esc($jenis_tamu) ?>">

                    <!-- Step 1: Foto Wajah -->
                    <div class="form-step" data-form-step="1">
                        <div class="fv-row mb-8 text-center">
                            <label class="d-flex align-items-center justify-content-center fs-5 fw-semibold mb-2">
                                <span class="required">Foto Wajah</span>
                            </label>
                            <div id="camera-container" class="position-relative d-inline-block bg-light border border-gray-300 rounded" style="width: 100%; max-width: 400px; height: 300px; overflow: hidden; margin: 0 auto;">
                                <!-- Oval guide overlay -->
                                <div class="camera-guide-oval" id="cameraGuide"></div>

                                <!-- Video feed -->
                                <video id="kamera-video" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>

                                <!-- Snapshot preview -->
                                <img id="kamera-preview" style="display: none; width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);" />

                                <!-- Countdown overlay -->
                                <div class="countdown-overlay" id="countdownOverlay" style="display: none;">
                                    <span class="countdown-number" id="countdownNumber"></span>
                                </div>

                                <div class="position-absolute bottom-0 w-100 p-3" style="background: rgba(0,0,0,0.5);">
                                    <button type="button" id="btn-ambil-foto" class="btn btn-sm btn-primary w-100">
                                        <i class="ki-duotone ki-camera fs-2 me-2"></i>Ambil Foto
                                    </button>
                                    <button type="button" id="btn-ulangi-foto" class="btn btn-sm btn-warning w-100" style="display:none;">
                                        <i class="ki-duotone ki-arrows-circle fs-2 me-2"></i>Ulangi Foto
                                    </button>
                                </div>
                            </div>

                            <!-- Upload fallback (tampil jika kamera gagal) -->
                            <div class="upload-fallback mt-4" id="uploadFallback">
                                <label class="btn btn-light-primary btn-sm w-100" for="fileUpload">
                                    <i class="ki-duotone ki-upload fs-2 me-2"></i>Upload Foto sebagai alternatif
                                </label>
                                <input type="file" id="fileUpload" accept="image/*" capture="user" style="display: none;" />
                            </div>

                            <canvas id="kamera-canvas" style="display:none;"></canvas>
                            <input type="hidden" name="foto_base64" id="foto_base64" required>
                            <div class="text-muted fs-7 mt-2">Pastikan wajah terlihat jelas di dalam oval</div>
                        </div>
                    </div>

                    <!-- Step 2: Data Diri -->
                    <div class="form-step" data-form-step="2" style="display: none;">
                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Nama Lengkap</span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="nama" id="inputNama" value="<?= old('nama') ?>" placeholder="Masukkan nama lengkap Anda (min. 3 karakter)" required />
                            <div class="valid-feedback">Nama valid</div>
                            <div class="invalid-feedback">Nama minimal 3 karakter</div>
                        </div>

                        <?php if ($jenis_tamu === 'pengunjung'): ?>
                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Alamat</span>
                            </label>
                            <textarea class="form-control form-control-lg form-control-solid" name="alamat" rows="3" placeholder="Masukkan alamat lengkap Anda" required><?= old('alamat') ?></textarea>
                        </div>
                        <?php else: ?>
                        <div class="fv-row mb-8">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Instansi/Perusahaan</span>
                            </label>
                            <input type="text" class="form-control form-control-lg form-control-solid" name="instansi" value="<?= old('instansi') ?>" placeholder="Masukkan nama instansi atau perusahaan" required />
                        </div>
                        <?php endif; ?>

                        <div class="fv-row mb-8">
                            <label class="fs-5 fw-semibold mb-2">Nomor HP</label>
                            <input type="tel" class="form-control form-control-lg form-control-solid" name="hp" id="inputHp" value="<?= old('hp') ?>" placeholder="Contoh: 081234567890" />
                            <div class="valid-feedback">Nomor HP valid</div>
                            <div class="invalid-feedback">Format nomor HP tidak valid (contoh: 081234567890)</div>
                        </div>
                    </div>

                    <!-- Step 3: Tujuan Kunjungan -->
                    <div class="form-step" data-form-step="3" style="display: none;">
                        <div class="fv-row mb-10">
                            <label class="d-flex align-items-center fs-5 fw-semibold mb-2">
                                <span class="required">Tujuan Kunjungan</span>
                            </label>
                            <textarea class="form-control form-control-lg form-control-solid" name="tujuan" rows="6" placeholder="Jelaskan tujuan kunjungan Anda secara lengkap" required><?= old('tujuan') ?></textarea>
                        </div>

                        <div class="d-flex flex-stack mt-8">
                            <a href="/" class="btn btn-light btn-active-light-primary fw-bold px-6 py-3">
                                <i class="ki-duotone ki-arrow-left fs-2 me-2"><span class="path1"></span><span class="path2"></span></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-<?= $jenis_tamu === 'pengunjung' ? 'primary' : 'success' ?> fw-bold px-6 py-3">
                                <i class="ki-duotone ki-check fs-2 me-2"></i>
                                Simpan Data
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
    const btnUlangi = document.getElementById('btn-ulangi-foto');
    const inputBase64 = document.getElementById('foto_base64');
    const countdownOverlay = document.getElementById('countdownOverlay');
    const countdownNumber = document.getElementById('countdownNumber');
    const cameraGuide = document.getElementById('cameraGuide');
    const uploadFallback = document.getElementById('uploadFallback');
    const fileUpload = document.getElementById('fileUpload');
    let stream = null;

    // === KAMERA ===
    async function initCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                audio: false
            });
            video.srcObject = stream;
        } catch (err) {
            console.error('Kamera gagal:', err);
            // Tampilkan upload fallback jika kamera gagal
            uploadFallback.style.display = 'block';
            document.getElementById('camera-container').style.display = 'none';
        }
    }
    initCamera();

    // === COUNTDOWN SEBELUM CAPTURE ===
    btnAmbil.addEventListener('click', function () {
        if (!stream) {
            Swal.fire('Peringatan', 'Kamera belum aktif/diizinkan', 'warning');
            return;
        }
        btnAmbil.disabled = true;
        countdownOverlay.style.display = 'flex';
        cameraGuide.style.display = 'none';

        let count = 3;
        countdownNumber.textContent = count;

        const interval = setInterval(function () {
            count--;
            if (count > 0) {
                countdownNumber.textContent = count;
            } else {
                clearInterval(interval);
                countdownOverlay.style.display = 'none';
                cameraGuide.style.display = 'block';
                capturePhoto();
                btnAmbil.disabled = false;
            }
        }, 1000);
    });

    function capturePhoto() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
        inputBase64.value = dataUrl;

        preview.src = dataUrl;
        preview.style.display = 'block';
        video.style.display = 'none';

        btnAmbil.style.display = 'none';
        btnUlangi.style.display = 'block';

        // Update stepper: step 1 completed
        updateStepper(1, 'completed');
        updateStepper(2, 'current');
        showStep(2);
    }

    btnUlangi.addEventListener('click', function () {
        inputBase64.value = '';
        preview.style.display = 'none';
        preview.src = '';
        video.style.display = 'block';

        btnAmbil.style.display = 'block';
        btnUlangi.style.display = 'none';

        updateStepper(1, 'current');
        updateStepper(2, 'pending');
        showStep(1);
    });

    // === UPLOAD FALLBACK ===
    fileUpload.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            const dataUrl = event.target.result;
            inputBase64.value = dataUrl;

            preview.src = dataUrl;
            preview.style.display = 'block';
            preview.style.transform = 'none';

            uploadFallback.style.display = 'none';

            updateStepper(1, 'completed');
            updateStepper(2, 'current');
            showStep(2);
        };
        reader.readAsDataURL(file);
    });

    // === STEPPER LOGIC ===
    function updateStepper(stepNum, state) {
        const items = document.querySelectorAll('#kt_form_stepper .stepper-item');
        const index = stepNum - 1;

        items.forEach(function (item, i) {
            item.classList.remove('current', 'completed');
            if (i < index) {
                item.classList.add('completed');
            } else if (i === index) {
                item.classList.add(state);
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

    // === VALIDASI INLINE ===
    const inputNama = document.getElementById('inputNama');
    const inputHp = document.getElementById('inputHp');
    // Regex nomor HP Indonesia
    const phoneRegex = /^(\+62|62|0)8[1-9][0-9]{6,10}$/;

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

    // === FORM SUBMIT VALIDATION ===
    document.getElementById('kt_form_pendaftaran').addEventListener('submit', function (e) {
        if (!inputBase64.value) {
            e.preventDefault();
            Swal.fire({
                text: 'Mohon ambil foto wajah Anda terlebih dahulu.',
                icon: 'warning',
                confirmButtonText: 'Ok mengerti',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return;
        }

        // Validasi inline nama
        if (inputNama && inputNama.value.trim().length < 3) {
            e.preventDefault();
            updateStepper(1, 'completed');
            updateStepper(2, 'current');
            showStep(2);
            inputNama.classList.add('is-invalid');
            inputNama.focus();
            Swal.fire({ text: 'Nama minimal 3 karakter', icon: 'warning', confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-primary' } });
            return;
        }

        // Validasi inline HP jika diisi
        if (inputHp && inputHp.value.trim() !== '' && !phoneRegex.test(inputHp.value.trim())) {
            e.preventDefault();
            updateStepper(1, 'completed');
            updateStepper(2, 'current');
            showStep(2);
            inputHp.classList.add('is-invalid');
            inputHp.focus();
            Swal.fire({ text: 'Format nomor HP tidak valid', icon: 'warning', confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-primary' } });
            return;
        }
    });
});
</script>
<?= $this->endSection() ?>
