<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index'); // Jalur tampilkan halaman login
$routes->post('auth/login', 'Auth::login'); // Jalur proses datanya (Ini yang bikin 404 kalau lupa)
$routes->get('logout', 'Auth::logout');

//Halaman User
$routes->get('/dashboard', 'Dashboard::index');
$routes->get('enkripsi', 'Enkripsi::index');
$routes->get('dekripsi', 'Dekripsi::index');
$routes->get('dashboard/riwayat', 'Dashboard::riwayat');
$routes->get('dashboard/enkripsi', 'Dashboard::enkripsi');
$routes->get('user/enkripsi', 'Dashboard::enkripsi'); 
$routes->post('user/proses_enkripsi', 'Dashboard::proses_enkripsi');
$routes->get('user/dekripsi', 'Dekripsi::index');
$routes->post('dekripsi/proses', 'Dekripsi::proses');
// Hapus atau ganti baris yang lama dengan ini:
$routes->get('dekripsi/download_dokumen/(:any)', 'Dekripsi::download_dokumen/$1');
// Cari baris riwayat kamu, pastikan jalurnya seperti ini:
$routes->get('dashboard/riwayat', 'Dashboard::riwayat');

//Admin
// Grouping routes untuk Admin agar lebih rapi
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'Admin::users');
    $routes->get('dataset', 'Admin::dataset');
});