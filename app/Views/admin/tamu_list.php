<?= $this->extend('layouts/main') ?>
<?= $this->section('navbar') ?>
<?= view('partials/navbar_admin') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Daftar Tamu</h2>
        <p class="text-muted mb-0">Total: <?= number_format(count($data)) ?> data</p>
    </div>
    <a href="/admin/laporan" class="btn btn-outline-success">
        <i class="bi bi-bar-chart me-2"></i>Lihat Laporan
    </a>
</div>

<div class="card border-0 shadow">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>No. HP</th>
                        <th>Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data tamu
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($data as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                                    </small>
                                    <div><?= date('H:i', strtotime($row['tanggal'])) ?></div>
                                </td>
                                <td><strong><?= esc($row['nama']) ?></strong></td>
                                <td><?= esc($row['instansi'] ?? '-') ?></td>
                                <td><?= esc($row['hp'] ?? '-') ?></td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 200px;" title="<?= esc($row['tujuan']) ?>">
                                        <?= esc($row['tujuan'] ?? '-') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if ($pager): ?>
    <div class="mt-4">
        <?= $pager->links() ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
