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
                <div class="dropdown">
                    <button type="button" class="btn btn-light-danger btn-active-light-danger dropdown-toggle" data-bs-toggle="dropdown" id="btnBulkActions" disabled>
                        <i class="ki-duotone ki-trash fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Hapus yang Dipilih
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="dropdown-item text-danger" id="btnBulkDelete">
                                <i class="ki-duotone ki-trash fs-4 me-2"></i>Hapus Terpilih
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="<?= base_url('admin/laporan') ?>" class="btn btn-light-success">
                    <i class="ki-duotone ki-chart fs-3"><span class="path1"></span><span class="path2"></span></i>
                    Lihat Laporan
                </a>
                <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                    <i class="ki-duotone ki-plus fs-3"><span class="path1"></span><span class="path2"></span></i>
                    Tambah Pengunjung
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_pengunjung">
                <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-50px"><input type="checkbox" class="form-check-input" id="selectAll" /></th>
                        <th>Foto</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Tujuan</th>
                        <th class="text-end min-w-100px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold"></tbody>
                <tfoot>
                    <tr class="text-start text-muted fw-bold fs-7">
                        <th></th>
                        <th>Foto</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Tujuan</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Drawer Detail -->
<div id="kt_drawer_detail" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'350px', 'md': '425px'}" data-kt-drawer-direction="end">
    <div class="card rounded-0 w-100">
        <div class="card-header pe-5" id="kt_drawer_header">
            <h3 class="card-title fw-bold">Detail Pengunjung</h3>
            <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_close" data-kt-drawer-close="kt_drawer_detail">
                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
            </div>
        </div>
        <div class="card-body pt-5" id="kt_drawer_body"></div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="kt_modal_form">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal_title">Tambah Pengunjung</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
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
                    <div class="mb-0">
                        <label class="required form-label">Tujuan</label>
                        <textarea class="form-control form-control-solid" name="tujuan" id="input_tujuan" rows="3" required placeholder="Tujuan Kunjungan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress d-none">Mohon tunggu...<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selectedIds = [];
    var table;
    var drawerObject;

    var drawerEl = document.getElementById('kt_drawer_detail');
    if (drawerEl && typeof KTOffcanvas !== 'undefined') {
        drawerObject = new KTOffcanvas(drawerEl);
    }

    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        table = $('#kt_table_pengunjung').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('admin/pengunjung/dt') ?>',
                type: 'POST'
            },
            columns: [
                {
                    data: 'id', orderable: false, searchable: false,
                    render: function (data, type, row) {
                        return '<input type="checkbox" class="form-check-input row-checkbox" value="' + row.id + '" />';
                    }
                },
                {
                    data: 'foto', orderable: false, searchable: false,
                    render: function (data) {
                        if (data) {
                            return '<div class="symbol symbol-50px"><img src="<?= base_url('uploads/tamu/') ?>' + data + '" alt="foto" style="object-fit:cover"/></div>';
                        }
                        return '<div class="symbol symbol-50px"><div class="symbol-label fs-3 bg-light-primary text-primary"><i class="ki-duotone ki-user fs-2"><span class="path1"></span><span class="path2"></span></i></div></div>';
                    }
                },
                { data: 'tanggal' },
                { data: 'nama' },
                { data: 'alamat' },
                { data: 'hp' },
                { data: 'tujuan' },
                { data: 'aksi', orderable: false, searchable: false, className: 'text-end' }
            ],
            info: true,
            order: [[2, 'desc']],
            pageLength: 10,
            paging: true,
            searching: true,
            language: {
                lengthMenu: 'Tampilkan _MENU_',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                search: 'Cari:',
                emptyTable: 'Tidak ada data tersedia',
                zeroRecords: 'Tidak ada data yang cocok',
                processing: 'Memproses data...'
            },
            initComplete: function () {
                var api = this.api();
                [1, 2, 3, 4, 5, 6].forEach(function (colIdx) {
                    var cell = api.column(colIdx).footer();
                    var input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control form-control-sm form-control-solid';
                    input.placeholder = 'Cari...';
                    cell.textContent = '';
                    cell.appendChild(input);
                    $(input).on('keyup change clear', function () {
                        if (api.column(colIdx).search() !== this.value) {
                            api.column(colIdx).search(this.value).draw();
                        }
                    });
                });
            },
            createdRow: function (row, data) {
                $(row).css('cursor', 'pointer');
                $(row).on('click', function (e) {
                    if ($(e.target).closest('.row-checkbox, .btn, a').length) return;
                    openDetailDrawer(data);
                });
            }
        });
    }

    $('#selectAll').on('change', function () {
        var isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
        updateSelectedIds();
    });

    $(document).on('change', '.row-checkbox', function () { updateSelectedIds(); });

    function updateSelectedIds() {
        selectedIds = [];
        $('.row-checkbox:checked').each(function () { selectedIds.push(parseInt($(this).val())); });
        var btnBulk = document.getElementById('btnBulkActions');
        if (btnBulk) btnBulk.disabled = selectedIds.length === 0;
    }

    document.getElementById('btnBulkDelete').addEventListener('click', function (e) {
        e.preventDefault();
        if (selectedIds.length === 0) return;
        Swal.fire({
            title: 'Hapus ' + selectedIds.length + ' data?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                var formData = new FormData();
                formData.append('ids', JSON.stringify(selectedIds));
                fetch('<?= base_url('admin/api/bulk-delete') ?>', {
                    method: 'POST', body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(function (r) { return r.json(); }).then(function (res) {
                    if (res.status === 'success') {
                        Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 2000 });
                        if (table) table.ajax.reload();
                        selectedIds = []; $('#selectAll').prop('checked', false);
                    } else { Swal.fire('Error', res.message, 'error'); }
                });
            }
        });
    });

    function openDetailDrawer(data) {
        var body = document.getElementById('kt_drawer_body');
        body.textContent = '';

        var headerDiv = document.createElement('div');
        headerDiv.className = 'text-center mb-8';
        var fotoDiv = document.createElement('div');
        fotoDiv.className = 'symbol symbol-100px symbol-circle mx-auto mb-4';
        var fotoImg = document.createElement('img');
        fotoImg.src = data.foto ? '<?= base_url('uploads/tamu/') ?>' + data.foto : '<?= base_url('assets/media/avatars/blank.png') ?>';
        fotoImg.alt = 'foto'; fotoImg.className = 'symbol-label'; fotoImg.style.objectFit = 'cover';
        fotoImg.onerror = function () { this.src = '<?= base_url('assets/media/avatars/blank.png') ?>'; };
        fotoDiv.appendChild(fotoImg);
        var namaEl = document.createElement('h3');
        namaEl.className = 'fw-bolder text-gray-900 fs-4';
        namaEl.textContent = data.nama;
        var badge = document.createElement('span');
        badge.className = 'badge badge-light-primary fs-7'; badge.textContent = 'Pengunjung'; badge.style.marginLeft = '8px';
        headerDiv.appendChild(fotoDiv); headerDiv.appendChild(namaEl); headerDiv.appendChild(badge);
        body.appendChild(headerDiv);

        var info = [
            { label: 'Alamat', value: data.alamat },
            { label: 'No. HP', value: data.hp },
            { label: 'Tujuan', value: data.tujuan },
            { label: 'Tanggal', value: data.tanggal }
        ];
        info.forEach(function (item) {
            var row = document.createElement('div'); row.className = 'mb-4';
            var label = document.createElement('div'); label.className = 'text-muted fw-semibold fs-7 mb-1'; label.textContent = item.label;
            var value = document.createElement('div'); value.className = 'fw-bold text-gray-800 fs-6'; value.textContent = item.value || '-';
            row.appendChild(label); row.appendChild(value); body.appendChild(row);
        });

        var actions = document.createElement('div');
        actions.className = 'd-flex flex-stack mt-8 pt-5 border-top';
        var editBtn = document.createElement('button');
        editBtn.className = 'btn btn-primary btn-sm fw-bold'; editBtn.textContent = 'Edit';
        editBtn.addEventListener('click', function () { openEditModal(data); if (drawerObject) drawerObject.hide(); });
        var deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn btn-light-danger btn-sm fw-bold'; deleteBtn.textContent = 'Hapus';
        deleteBtn.addEventListener('click', function () { deleteData(data.id); if (drawerObject) drawerObject.hide(); });
        actions.appendChild(editBtn); actions.appendChild(deleteBtn); body.appendChild(actions);

        if (drawerObject) drawerObject.show();
        else if (typeof bootstrap !== 'undefined') new bootstrap.Offcanvas(drawerEl).show();
    }

    var currentModal = new bootstrap.Modal(document.getElementById('kt_modal_form'));

    window.openCreateModal = function () {
        document.getElementById('kt_form').reset();
        document.getElementById('input_id').value = '';
        document.getElementById('modal_title').textContent = 'Tambah Pengunjung';
        currentModal.show();
    };

    window.openEditModal = function (data) {
        document.getElementById('kt_form').reset();
        document.getElementById('input_id').value = data.id;
        document.getElementById('input_nama').value = data.nama;
        document.getElementById('input_alamat').value = data.alamat;
        document.getElementById('input_hp').value = data.hp;
        document.getElementById('input_tujuan').value = data.tujuan;
        document.getElementById('modal_title').textContent = 'Edit Pengunjung';
        currentModal.show();
    };

    window.submitForm = async function (e) {
        e.preventDefault();
        var form = document.getElementById('kt_form');
        var formData = new FormData(form);
        var id = document.getElementById('input_id').value;
        var btnSubmit = document.getElementById('btn_submit');
        var url = id ? '<?= base_url('admin/pengunjung/update/') ?>' + id : '<?= base_url('admin/pengunjung/store') ?>';
        btnSubmit.setAttribute('data-kt-indicator', 'on'); btnSubmit.disabled = true;
        try {
            var response = await fetch(url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            var result = await response.json();
            if (result.status === 'success') {
                currentModal.hide();
                Swal.fire({ icon: 'success', title: result.message, showConfirmButton: false, timer: 2000 })
                    .then(function () { if (table) table.ajax.reload(); });
            } else {
                var errorMsg = result.message || 'Terjadi kesalahan';
                if (result.errors) errorMsg = Object.values(result.errors).join('\n');
                Swal.fire('Error', errorMsg, 'error');
            }
        } catch (error) { Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error'); }
        finally { btnSubmit.removeAttribute('data-kt-indicator'); btnSubmit.disabled = false; }
    };

    window.deleteData = function (id) {
        Swal.fire({
            title: 'Apakah Anda yakin?', text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then(async function (result) {
            if (result.isConfirmed) {
                try {
                    var response = await fetch('<?= base_url('admin/pengunjung/delete/') ?>' + id, {
                        method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    var res = await response.json();
                    if (res.status === 'success') {
                        Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 2000 })
                            .then(function () { if (table) table.ajax.reload(); });
                    } else { Swal.fire('Error', res.message, 'error'); }
                } catch (error) { Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error'); }
            }
        });
    };
});
</script>
<?= $this->endSection() ?>
