<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function dashboard()
    {
        // Data ini nanti bisa diambil dari database, sementara kita hardcode dulu
        $data = [
            'title' => 'Dashboard Admin'
        ];

        echo view('layout_admin/header', $data);
        echo view('layout_admin/sidebar');
        echo view('admin/dashboard_view');
        echo view('layout_admin/footer');
    }

    public function users()
    {
        echo view('layout_admin/header');
        echo view('layout_admin/sidebar');
        echo view('admin/users_view'); // Ini nanti dibuat setelah ini
        echo view('layout_admin/footer');
    }

    public function dataset()
    {
        echo view('layout_admin/header');
        echo view('layout_admin/sidebar');
        echo view('admin/dataset_view'); // Ini untuk manajemen gambar (Lena, Baboon, dll)
        echo view('layout_admin/footer');
    }
}