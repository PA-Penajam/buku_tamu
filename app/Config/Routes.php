<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -------------------------------------------------------------------------
// Routes Publik (Tidak perlu login)
// -------------------------------------------------------------------------

// Homepage
$routes->get('/', 'HomeController::index');

// Form Pendaftaran
$routes->get('/pengunjung', 'TamuController::pengunjung');
$routes->get('/tamu', 'TamuController::tamu');
$routes->post('/tamu/store', 'TamuController::store');

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
    $routes->get('tamu', 'AdminController::tamu');

    // Laporan
    $routes->get('laporan', 'AdminController::laporan');

    // API Chart Data
    $routes->get('chart', 'AdminController::chartData');
});
