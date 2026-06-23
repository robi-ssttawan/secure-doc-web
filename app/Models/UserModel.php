<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'ID'; // DIUBAH: Menggunakan ID huruf besar sesuai rules database kita
    protected $allowedFields = ['id_jabatan', 'username', 'password', 'nama_lengkap', 'role', 'created_at']; // DISESUAIKAN: Ditambah id_jabatan

    /**
     * Fungsi khusus login untuk mengambil data user 
     * sekaligus men-JOIN nama jabatan dari tabel ms_jabatan
     */
    public function get_user_with_jabatan($username)
    {
        return $this->select('users.*, ms_jabatan.nama_jabatan')
                    ->join('ms_jabatan', 'ms_jabatan.ID = users.id_jabatan', 'left')
                    ->where('users.username', $username)
                    ->first();
    }
}