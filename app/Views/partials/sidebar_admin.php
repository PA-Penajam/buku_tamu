<?php
// Helper untuk cek active menu
function isActiveMenu(string $path): string {
    return strpos(current_url(), $path) !== false ? 'here show' : '';
}
?>
<!--begin::Aside-->
<div id="kt_aside" class="aside py-9" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '280px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid ps-5 pe-3 mb-7" id="kt_aside_menu">
        <!--begin::Aside Menu-->
        <div class="w-100 hover-scroll-y d-flex pe-2" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_footer, #kt_header" data-kt-scroll-wrappers="#kt_aside, #kt_aside_menu, #kt_aside_menu_wrapper" data-kt-scroll-offset="102">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention menu-active-bg fw-semibold my-auto" id="#kt_aside_menu" data-kt-menu="true">

                <!--begin::Menu item - Dashboard-->
                <div class="menu-item <?= isActiveMenu(base_url('admin')) && strpos(current_url(), '/admin/tamu') === false && strpos(current_url(), '/admin/pengunjung') === false && strpos(current_url(), '/admin/laporan') === false ? 'here show' : '' ?>">
                    <a class="menu-link" href="<?= base_url('admin') ?>">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                <!--begin::Menu item - Tamu-->
                <div class="menu-item <?= strpos(current_url(), '/admin/tamu') !== false ? 'here show' : '' ?>">
                    <a class="menu-link" href="<?= base_url('admin/tamu') ?>">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-people fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title">Tamu</span>
                    </a>
                </div>

                <?php if (env('TAMU_ONLY') != true): ?>
                <!--begin::Menu item - Pengunjung-->
                <div class="menu-item <?= strpos(current_url(), '/admin/pengunjung') !== false ? 'here show' : '' ?>">
                    <a class="menu-link" href="<?= base_url('admin/pengunjung') ?>">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Pengunjung</span>
                    </a>
                </div>
                <?php endif; ?>

                <!--begin::Menu item - Laporan-->
                <div class="menu-item <?= strpos(current_url(), '/admin/laporan') !== false ? 'here show' : '' ?>">
                    <a class="menu-link" href="<?= base_url('admin/laporan') ?>">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-chart fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Laporan</span>
                    </a>
                </div>

            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->

    <!--begin::Footer-->
    <div class="aside-footer flex-column-auto px-9" id="kt_aside_footer">
        <!--begin::User panel-->
        <div class="d-flex flex-stack">
            <!--begin::Wrapper-->
            <div class="d-flex align-items-center">
                <!--begin::Avatar-->
                <div class="symbol symbol-circle symbol-40px">
                    <span class="symbol-label bg-primary text-white fw-bold fs-6">
                        <i class="ki-duotone ki-user text-white fs-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </span>
                </div>
                <!--end::Avatar-->
                <!--begin::User info-->
                <div class="ms-2">
                    <!--begin::Name-->
                    <span class="text-gray-800 text-hover-primary fs-6 fw-bold lh-1">Admin</span>
                    <!--end::Name-->
                    <!--begin::Major-->
                    <span class="text-muted fw-semibold d-block fs-7 lh-1">Administrator</span>
                    <!--end::Major-->
                </div>
                <!--end::User info-->
            </div>
            <!--end::Wrapper-->
            <!--begin::User menu-->
            <div class="ms-1">
                <div class="btn btn-sm btn-icon btn-active-color-primary position-relative me-n2" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="top-end">
                    <i class="ki-duotone ki-setting-2 fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-200px" data-kt-menu="true">
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="<?= base_url('logout') ?>" class="menu-link px-5 text-danger">
                            <i class="ki-duotone ki-exit-left fs-3 me-2 text-danger">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Keluar
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
            </div>
            <!--end::User menu-->
        </div>
        <!--end::User panel-->
    </div>
    <!--end::Footer-->
</div>
<!--end::Aside-->
