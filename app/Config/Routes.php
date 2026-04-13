<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -------------------------------------------------------------------------
// Routes Publik (Tidak perlu login)
// -------------------------------------------------------------------------

// Homepage
$routes->get('/', 'TamuController::tamu');

// Form Pendaftaran
    // Route pengunjung telah dinonaktifkan (karena aplikasi ini sekarang murni khusus /tamu)
$routes->get('/tamu', 'TamuController::tamu');
$routes->post('/tamu/store', 'TamuController::store');

// Halaman Sukses Pendaftaran
$routes->get('/tamu/sukses', 'TamuController::sukses');

// -------------------------------------------------------------------------
// API Publik
// -------------------------------------------------------------------------

$routes->get('/api/stats/today', 'HomeController::apiStatsToday');

// -------------------------------------------------------------------------
// Routes Autentikasi
// -------------------------------------------------------------------------

$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');

// -------------------------------------------------------------------------
// Routes Admin (Dilindungi oleh AuthFilter)
// -------------------------------------------------------------------------

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('/', 'AdminController::index');

    // Daftar Pengunjung & Tamu
    $routes->get('pengunjung', 'AdminController::pengunjung');
    $routes->post('pengunjung/dt', 'AdminController::pengunjungDt');
    $routes->get('tamu', 'AdminController::tamu');
    $routes->post('tamu/dt', 'AdminController::tamuDt');
    
    // CRUD Tamu & Pengunjung (Admin)
    $routes->post('tamu/store', 'AdminController::storeTamu');
    $routes->post('tamu/update/(:num)', 'AdminController::updateTamu/$1');
    $routes->post('tamu/delete/(:num)', 'AdminController::deleteTamu/$1');
    
    $routes->post('pengunjung/store', 'AdminController::storeTamu');
    $routes->post('pengunjung/update/(:num)', 'AdminController::updateTamu/$1');
    $routes->post('pengunjung/delete/(:num)', 'AdminController::deleteTamu/$1');

    // Laporan
    $routes->get('laporan', 'AdminController::laporan');

    // Export Laporan
    $routes->get('laporan/export/excel', 'AdminController::exportExcel');
    $routes->get('laporan/export/pdf', 'AdminController::exportPdf');

    // API Chart Data
    $routes->get('chart', 'AdminController::chartData');

    // API Chart & Stats
    $routes->get('api/trend', 'AdminController::apiTrend');
    $routes->get('api/kunjungan-terakhir', 'AdminController::apiKunjunganTerakhir');
    $routes->post('api/bulk-delete', 'AdminController::bulkDelete');
});
