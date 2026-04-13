document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('kt_content_container');
    if (!container) return;

    var urlDt = container.dataset.urlDt;
    var urlBulkDelete = container.dataset.urlBulkDelete;
    var urlUpdate = container.dataset.urlUpdate;
    var urlStore = container.dataset.urlStore;
    var urlDelete = container.dataset.urlDelete;
    var urlUploads = container.dataset.urlUploads;
    var urlBlank = container.dataset.urlBlank;

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
                url: urlDt,
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
                            return '<div class="symbol symbol-50px"><img src="' + urlUploads + data + '" alt="foto" style="object-fit:cover"/></div>';
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

    var btnBulkDelete = document.getElementById('btnBulkDelete');
    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', function (e) {
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
                    fetch(urlBulkDelete, {
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
    }

    function openDetailDrawer(data) {
        var body = document.getElementById('kt_drawer_body');
        body.textContent = '';

        var headerDiv = document.createElement('div');
        headerDiv.className = 'text-center mb-8';
        var fotoDiv = document.createElement('div');
        fotoDiv.className = 'symbol symbol-100px symbol-circle mx-auto mb-4';
        var fotoImg = document.createElement('img');
        fotoImg.src = data.foto ? urlUploads + data.foto : urlBlank;
        fotoImg.alt = 'foto'; fotoImg.className = 'symbol-label'; fotoImg.style.objectFit = 'cover';
        fotoImg.onerror = function () { this.src = urlBlank; };
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

    var modalEl = document.getElementById('kt_modal_form');
    var currentModal = modalEl ? new bootstrap.Modal(modalEl) : null;

    window.openCreateModal = function () {
        document.getElementById('kt_form').reset();
        document.getElementById('input_id').value = '';
        document.getElementById('modal_title').textContent = 'Tambah Pengunjung';
        if (currentModal) currentModal.show();
    };

    window.openEditModal = function (data) {
        document.getElementById('kt_form').reset();
        document.getElementById('input_id').value = data.id;
        document.getElementById('input_nama').value = data.nama;
        document.getElementById('input_alamat').value = data.alamat;
        document.getElementById('input_hp').value = data.hp;
        document.getElementById('input_tujuan').value = data.tujuan;
        document.getElementById('modal_title').textContent = 'Edit Pengunjung';
        if (currentModal) currentModal.show();
    };

    window.submitForm = async function (e) {
        e.preventDefault();
        var form = document.getElementById('kt_form');
        var formData = new FormData(form);
        var id = document.getElementById('input_id').value;
        var btnSubmit = document.getElementById('btn_submit');
        var url = id ? urlUpdate + id : urlStore;
        btnSubmit.setAttribute('data-kt-indicator', 'on'); btnSubmit.disabled = true;
        try {
            var response = await fetch(url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            var result = await response.json();
            if (result.status === 'success') {
                if (currentModal) currentModal.hide();
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
                    var response = await fetch(urlDelete + id, {
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
