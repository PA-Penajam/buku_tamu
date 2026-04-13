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

    // === ERROR HANDLING ALERT ===
    // Use data-attribute to get error message if exist
    const formContainer = document.getElementById('kt_form_pendaftaran');
    if (formContainer && formContainer.dataset.error) {
        Swal.fire({
            text: formContainer.dataset.error,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, mengerti!",
            customClass: { confirmButton: "btn btn-danger" }
        });
    }

    // === INIT KAMERA ===
    async function initCamera() {
        if (!video) return; // If script loaded on page without video
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                audio: false
            });
            video.srcObject = stream;

            // Sembunyikan loading dan tampilkan tombol setelah kamera siap
            video.onloadedmetadata = function() {
                const cameraLoading = document.getElementById('camera-loading');
                if (cameraLoading) cameraLoading.style.display = 'none';
                if (shutterGroup) shutterGroup.style.display = 'block';
            };

        } catch (err) {
            console.error('Kamera gagal:', err);
            const loadingEl = document.getElementById('camera-loading');
            if (loadingEl) {
                loadingEl.classList.add('text-warning');
                loadingEl.textContent = '⚠️ Kamera tidak dapat diakses. Coba izinkan akses kamera di browser Anda.';
            }
            // Tampilkan upload fallback jika kamera gagal (tidak diberi izin / hardware rusak)
            if (uploadFallback) uploadFallback.style.display = 'block';
            const cameraBox = document.querySelector('.camera-box');
            if (cameraBox) cameraBox.style.display = 'none';
        }
    }
    
    if (video) {
        initCamera();
    }

    // === LOGIKA CAPTURE (LANGSUNG TANPA COUNTDOWN) ===
    if (btnAmbil) {
        btnAmbil.addEventListener('click', function () {
            if (!stream) {
                Swal.fire('Peringatan', 'Kamera belum aktif/diizinkan', 'warning');
                return;
            }
            btnAmbil.disabled = true;
            capturePhoto();
        });
    }

    function capturePhoto() {
        if (!cameraFlash || !canvas || !video || !preview || !inputBase64) return;
        
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
        if (shutterGroup) shutterGroup.style.display = 'none';
        if (btnUlangiWrapper) btnUlangiWrapper.style.display = 'block';
        
        // Sembunyikan guide
        if(cameraGuide) cameraGuide.style.opacity = '0';

        // Langsung pindah ke step 2
        updateStepper(1, 'completed');
        updateStepper(2, 'current');
        showStep(2);
    }

    // Mengulang Pengambilan Foto
    if (btnUlangi) {
        btnUlangi.addEventListener('click', function () {
            inputBase64.value = '';
            preview.style.display = 'none';
            preview.src = '';
            video.style.display = 'block';

            if (shutterGroup) shutterGroup.style.display = 'block';
            if (btnUlangiWrapper) btnUlangiWrapper.style.display = 'none';
            if (btnAmbil) btnAmbil.disabled = false;
            if(cameraGuide) cameraGuide.style.opacity = '1';
        });
    }

    // === FALLBACK: UPLOAD DARI FILE (JIKA KAMERA ERROR) ===
    if (fileUpload) {
        fileUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                const dataUrl = event.target.result;
                inputBase64.value = dataUrl;

                // Memaksa tampilkan preview file di kamera box
                const cameraBox = document.querySelector('.camera-box');
                if (cameraBox) cameraBox.style.display = 'block';
                if (cameraGuide) cameraGuide.style.display = 'none';
                if (btnAmbil) btnAmbil.style.display = 'none';
                if (video) video.style.display = 'none';
                
                if (preview) {
                    preview.src = dataUrl;
                    preview.style.display = 'block';
                    preview.style.transform = 'none'; // batalkan mirror untuk upload gambar
                }

                if (uploadFallback) uploadFallback.style.display = 'none';
                if (btnUlangiWrapper) btnUlangiWrapper.style.display = 'block';

                // Langsung pindah ke step 2
                updateStepper(1, 'completed');
                updateStepper(2, 'current');
                showStep(2);
            };
            reader.readAsDataURL(file);
        });
    }

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
            if (countdownOverlay) countdownOverlay.style.display = 'none';
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
    if (formContainer) {
        formContainer.addEventListener('submit', function (e) {
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
    }
});
