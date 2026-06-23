<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return session()->get('role') == 'admin' ? redirect()->to('admin/dashboard') : redirect()->to('dashboard');
        }
        return view('auth/login_view');
    }

    public function login()
    {
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        // reCAPTCHA dibuang total agar tidak mempersulit proses login saat lokal development

        // 1. Cek Database (Tetap join tabel ms_jabatan sesuai 8 tabel baru kamu)
        $userModel = new UserModel();
        $user = $userModel->get_user_with_jabatan($username);

        if ($user && password_verify($password, $user['password'])) {
            
            // 2. Set Session (Data bawaan kamu tetap dipertahankan secara utuh)
            session()->set([
                'isLoggedIn'   => true,
                'id_user'      => $user['ID'], // ID user dari DB
                'username'     => $user['username'],
                'nama'         => $user['nama_lengkap'],
                'role'         => $user['role'],
                'id_jabatan'   => $user['id_jabatan'], // ID jabatan untuk foreign key transaksi
                'nama_jabatan' => $user['nama_jabatan'] ?? 'Tidak Ada Jabatan' // Nama jabatan untuk tampilan/report
            ]);

            // 3. Jalur Redirect (Sesuai rute asli kamu, dijamin tidak 404)
            return ($user['role'] == 'admin') ? redirect()->to('admin/dashboard') : redirect()->to('dashboard');
        } else {
            return redirect()->back()->with('error', 'Username atau Password salah!')->withInput();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}