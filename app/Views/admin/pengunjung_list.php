<?= $this->extend('layouts/metronic') ?>

<?= $this->section('header') ?>
<?= view('partials/header_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl mt-5" id="kt_content_container">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <h2 class="mb-0">Daftar Pengunjung</h2>
            </div>
            <div class="card-toolbar gap-3">
                <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                    Tambah Pengunjung
                </button>
                <a href="/admin/laporan" class="btn btn-light-primary">
                    Lihat Laporan
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_pengunjung">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-50px">#</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Tujuan</th>
                            <th class="text-end min-w-100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<!-- Modal Form Pengunjung -->
<div class="modal fade" tabindex="-1" id="kt_modal_form">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal_title">Tambah Pengunjung</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fs-1">&times;</span>
                </div>
            </div>

            <form id="kt_form" onsubmit="submitForm(event)">
                <div class="modal-body">
                    <input type="hidden" id="input_id" name="id">
                    <input type="hidden" name="jenis_tamu" value="pengunjung">
                    
                    <div class="mb-5">
                        <label class="required form-label">Nama</label>
                        <input type="text" class="form-control form-control-solid" name="nama" id="input_nama" required placeholder="Nama Lengkap"/>
                    </div>
                    
                    <div class="mb-5">
                        <label class="required form-label">Alamat</label>
                        <input type="text" class="form-control form-control-solid" name="alamat" id="input_alamat" required placeholder="Alamat Lengkap"/>
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label">No. HP</label>
                        <input type="text" class="form-control form-control-solid" name="hp" id="input_hp" placeholder="08xxxxxxxxxx"/>
                    </div>

                    <div class="mb-5">
                        <label class="required form-label">Tujuan</label>
                        <textarea class="form-control form-control-solid" name="tujuan" id="input_tujuan" rows="3" required placeholder="Tujuan Kunjungan"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress d-none">
                            Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentModal;

    document.addEventListener("DOMContentLoaded", function () {
        currentModal = new bootstrap.Modal(document.getElementById('kt_modal_form'));
        
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
                        $('#kt_table_pengunjung').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/admin/pengunjung/dt",
                    "type": "POST"
                },
                "columns": [
                    { 
                        "data": "id",
                        "orderable": false,
                        "searchable": false,
                        "render": function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "tanggal" },
                    { "data": "nama" },
                    { "data": "alamat" },
                    { "data": "hp" },
                    { "data": "tujuan" },
                    { "data": "aksi", "orderable": false, "searchable": false, "className": "text-end" }
                ],
                "info": true,
                "order": [[1, "desc"]],
                "pageLength": 10,
                "paging": true,
                "searching": true,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "search": "Cari:",
                    "emptyTable": "Tidak ada data tersedia",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "processing": "Memproses data..."
                }
            });
        }
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer
            toast.onmouseleave = Swal.resumeTimer
        }
    });

    function openCreateModal() {
        document.getElementById('kt_form').reset();
        document.getElementById('input_id').value = '';
        document.getElementById('modal_title').innerText = 'Tambah Pengunjung';
        currentModal.show();
    }

    function openEditModal(data) {
        document.getElementById('kt_form').reset();
        document.getElementById('input_id').value = data.id;
        document.getElementById('input_nama').value = data.nama;
        document.getElementById('input_alamat').value = data.alamat;
        document.getElementById('input_hp').value = data.hp;
        document.getElementById('input_tujuan').value = data.tujuan;
        document.getElementById('modal_title').innerText = 'Edit Pengunjung';
        currentModal.show();
    }

    async function submitForm(e) {
        e.preventDefault();
        
        const form = document.getElementById('kt_form');
        const formData = new FormData(form);
        const id = document.getElementById('input_id').value;
        const btnSubmit = document.getElementById('btn_submit');
        
        const url = id ? `/admin/pengunjung/update/${id}` : `/admin/pengunjung/store`;

        // Loading state
        btnSubmit.setAttribute('data-kt-indicator', 'on');
        btnSubmit.disabled = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();

            if (result.status === 'success') {
                currentModal.hide();
                Toast.fire({ icon: 'success', title: result.message }).then(() => {
                    window.location.reload();
                });
            } else {
                let errorMsg = result.message || 'Terjadi kesalahan';
                if(result.errors) {
                    errorMsg = Object.values(result.errors).join('\n');
                }
                Swal.fire('Error', errorMsg, 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
        } finally {
            btnSubmit.removeAttribute('data-kt-indicator');
            btnSubmit.disabled = false;
        }
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/pengunjung/delete/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const res = await response.json();
                    if (res.status === 'success') {
                        Toast.fire({ icon: 'success', title: res.message }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
                }
            }
        });
    }
</script>
<?= $this->endSection() ?>
