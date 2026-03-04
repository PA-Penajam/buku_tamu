<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Buku Tamu') ?></title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!-- Global Stylesheets Bundle(used by all pages) -->
    <link href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <?= $this->renderSection('styles') ?>
</head>
<?php 
    // Determine body class based on current route
    $is_admin = strpos(current_url(), '/admin') !== false;
    $body_class = $is_admin ? "header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" : "page-bg";
    $body_style = $is_admin ? "style=\"--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px\"" : "style=\"background-image: url('" . base_url('assets/media/auth/bg4.jpg') . "'); background-size: cover;\"";
?>
<body id="kt_body" class="<?= $body_class ?>" <?= $body_style ?>>
    <!-- Main Content -->
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <!-- Aside -->
            <?= $this->renderSection('aside') ?>
            
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!-- Header -->
                <?= $this->renderSection('header') ?>
                
                <!-- Content -->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="container-xxl mt-4">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="container-xxl mt-4">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Dynamic Content -->
                    <?= $this->renderSection('content') ?>
                </div>
                
                <!-- Footer -->
                <?= $this->renderSection('footer') ?>
            </div>
        </div>
    </div>

    <!-- Global Javascript Bundle(used by all pages) -->
    <script src="<?= base_url('assets/plugins/global/plugins.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/js/scripts.bundle.js') ?>"></script>
    
    <!-- Custom Scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
