<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Buku Tamu') ?></title>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="<?= base_url('assets/plugins/global/plugins.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/style.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <?php if ($is_admin ?? false): ?>
    <link href="<?= base_url('assets/plugins/custom/datatables/datatables.bundle.css') ?>" rel="stylesheet" type="text/css" />
    <?php endif; ?>
    <?= $this->renderSection('styles') ?>
    <?php if (isset($css_files) && is_array($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
        <link href="<?= base_url($css) ?>" rel="stylesheet" type="text/css" />
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<?php
    // Determine if current page is admin
    $is_admin = strpos(current_url(), '/admin') !== false;
    $body_class = $is_admin ? "header-tablet-and-mobile-fixed aside-enabled" : "page-bg";
    $body_style = $is_admin ? "" : "style=\"background-image: url('" . base_url('assets/media/auth/bg4.jpg') . "'); background-size: cover;\"";
?>
<body id="kt_body" class="<?= $body_class ?>" <?= $body_style ?>>
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->

    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <?php if ($is_admin): ?>
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Aside-->
            <?= view('partials/sidebar_admin') ?>

            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <?= $this->renderSection('header') ?>
                <!--end::Header-->

                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <?= $this->renderSection('breadcrumb') ?>
                    <?= $this->renderSection('content') ?>
                </div>
                <!--end::Content-->

                <!--begin::Footer-->
                <?= view('partials/footer_admin') ?>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
        <?php else: ?>
        <!--begin::Content (Guest/Auth)-->
        <?= $this->renderSection('content') ?>
        <!--end::Content (Guest/Auth)-->
        <?php endif; ?>
    </div>
    <!--end::Root-->
    <!--end::Main-->

    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="<?= base_url('assets/plugins/global/plugins.bundle.js') ?>"></script>
    <script src="<?= base_url('assets/js/scripts.bundle.js') ?>"></script>
    <?php if ($is_admin): ?>
    <script src="<?= base_url('assets/plugins/custom/datatables/datatables.bundle.js') ?>"></script>
    <?php endif; ?>
    <!--end::Global Javascript Bundle-->

    <!-- Custom Scripts -->
    <?= $this->renderSection('scripts') ?>
    
    <?php if (isset($js_files) && is_array($js_files)): ?>
        <?php foreach ($js_files as $js): ?>
        <script src="<?= base_url($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                text: "<?= addslashes(session()->getFlashdata('success')) ?>",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, mengerti!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.error("<?= addslashes(session()->getFlashdata('error')) ?>");
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>
</body>
</html>
