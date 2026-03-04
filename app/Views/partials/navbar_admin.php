<nav class="navbar navbar-expand-lg navbar-dark bg-gradient-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/admin">
            <i class="bi bi-journal-bookmark me-2"></i>Buku Tamu Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= (current_url() === base_url('admin')) ? 'active' : '' ?>" href="/admin">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'admin/pengunjung') !== false) ? 'active' : '' ?>" href="/admin/pengunjung">
                        <i class="bi bi-people me-1"></i> Pengunjung
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'admin/tamu') !== false) ? 'active' : '' ?>" href="/admin/tamu">
                        <i class="bi bi-person-badge me-1"></i> Tamu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (strpos(current_url(), 'admin/laporan') !== false) ? 'active' : '' ?>" href="/admin/laporan">
                        <i class="bi bi-bar-chart me-1"></i> Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Situs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="/logout">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
