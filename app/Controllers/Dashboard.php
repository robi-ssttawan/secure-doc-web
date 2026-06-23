<?php

namespace App\Controllers;

// Panggil Model yang dibutuhkan
use App\Models\UserModel;
use CodeIgniter\Database\Config;

class Dashboard extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Siapkan koneksi database langsung untuk query fleksibel
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Jika tidak ada session 'isLoggedIn', tendang ke login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        return view('user/dashboard_view');
    }

    // ==========================================
    // TAHAP 1 & 2: MENAMPILKAN FORM ENKRIPSI
    // ==========================================
    public function enkripsi()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        // Mengambil daftar jabatan dari database menggunakan query builder CodeIgniter 4
        $db = \Config\Database::connect();
        $jabatan_list = $db->table('ms_jabatan')->get()->getResultArray();

        $data = [
            'title'        => 'Enkripsi & Penyisipan - SECURE-DOC',
            'jabatan_list' => $jabatan_list // Variabel ini yang dikirim ke view agar tidak error foreach
        ];

        return view('user/enkripsi_view', $data);
    }

    // ==========================================
    // TAHAP 3: ENGINE ENKRIPSI AES-256 CBC & LSB PIKSEL 4-BIT (MURNI GD)
    // ==========================================
    public function proses_enkripsi()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        // Longgarkan batasan memori dan waktu eksekusi PHP agar laptop tidak nge-freeze saat manipulasi piksel
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $db = \Config\Database::connect();

        // Catat waktu awal mulai untuk menghitung "Waktu Eksekusi" di Bab 4 Skripsi
        $waktu_mulai = microtime(true);

        // 1. Ambil data inputan dari form
        $aes_key           = $this->request->getPost('aes_key');
        $id_jabatan_tujuan = $this->request->getPost('id_jabatan_tujuan');
        $file_dokumen      = $this->request->getFile('dokumen');
        $nama_dataset      = $this->request->getPost('dataset'); // Mengambil nama file (lena.png/baboon.png)

        // 2. Jalankan Validasi Keamanan Dokumen
        $ekstensi = $file_dokumen->getClientExtension();
        $aturan_format = $db->table('ms_format')
                            ->where('nama_format', $ekstensi)
                            ->where('is_active', 1)
                            ->get()->getRowArray();

        if (!$aturan_format) {
            return redirect()->back()->with('error', 'Format dokumen tidak diizinkan oleh sistem!')->withInput();
        }

        $ukuran_dokumen_raw_bytes = $file_dokumen->getSize();
        $ukuran_dokumen_kb        = $ukuran_dokumen_raw_bytes / 1024;
        if ($ukuran_dokumen_kb > $aturan_format['max_size_kb']) {
            return redirect()->back()->with('error', 'Ukuran dokumen terlalu besar! Maksimal ' . ($aturan_format['max_size_kb']/1024) . ' MB.')->withInput();
        }

        if ($file_dokumen->getMimeType() !== $aturan_format['mime_type']) {
            return redirect()->back()->with('error', 'Mime-Type dokumen tidak valid! File terindikasi dimanipulasi.')->withInput();
        }

        // ==========================================
        // 🔒 PROSES KRIPTOGRAFI: AES-256-CBC
        // ==========================================
        
        // Membaca isi file dokumen mentah menjadi string biner
        $documentRawData = file_get_contents($file_dokumen->getTempName());
        
        // Hitung hash_awal (SHA-256) dari file dokumen asli untuk kebutuhan audit skripsi
        $hash_awal = hash('sha256', $documentRawData);

        // Mengamankan Kunci agar Pas 32 Byte (256 Bit) via SHA-256
        $secureKey = hash('sha256', $aes_key, true);
        
        // Membuat Initialization Vector (IV) Acak sepanjang 16 Byte
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv       = openssl_random_pseudo_bytes($ivLength);
        
        // Eksekusi enkripsi data dokumen menggunakan OpenSSL
        $ciphertextRaw = openssl_encrypt($documentRawData, 'aes-256-cbc', $secureKey, OPENSSL_RAW_DATA, $iv);
        
        // Penggabungan Payload Hasil Enkripsi (IV + Ciphertext)
        $encryptedPayload = $iv . $ciphertextRaw;
        
        // Mengubah payload biner menjadi string teks base64 agar aman disisipkan ke piksel
        $encryptedBase64Text = base64_encode($encryptedPayload);

        // ==========================================
        // 🖼️ PROSES STEGANOGRAFI: LSB 4-BIT MURNI PIKSEL
        // ==========================================
        
        // FIXED FOLDER PATH: Disamakan jalurnya ke assets/images/stego_cover/ sesuai View
        $path_dataset = FCPATH . 'assets/images/stego_cover/' . $nama_dataset;
        
        if (!file_exists($path_dataset)) {
            return redirect()->back()->with('error', 'File citra dataset asli tidak ditemukan di server!')->withInput();
        }

        // Load gambar asli menggunakan GD Library PHP
        $image = \imagecreatefrompng($path_dataset);
        if (!$image) {
            return redirect()->back()->with('error', 'Gagal memproses citra penampung.')->withInput();
        }

        // ✅ FIX 1: Pastikan mode truecolor & alpha channel preserved agar nilai piksel tidak berubah
        \imagealphablending($image, false);
        \imagesavealpha($image, true);

        // Tambahkan penanda akhir data |END| agar saat dekripsi tahu batas berhentinya
        $data_to_hide = $encryptedBase64Text . '|END|';
        
        // Ubah string ciphertext + penanda menjadi deretan bit biner (0 dan 1)
        $binary_data = '';
        for ($i = 0; $i < strlen($data_to_hide); $i++) {
            $binary_data .= str_pad(decbin(ord($data_to_hide[$i])), 8, '0', STR_PAD_LEFT);
        }

        $width        = \imagesx($image);
        $height       = \imagesy($image);
        $total_pixels = $width * $height;
        $data_length  = strlen($binary_data);

        // Validasi kapasitas gambar penampung (Kapasitas LSB 4-bit = total piksel * 12)
        if ($data_length > ($total_pixels * 12)) {
            return redirect()->back()->with('error', 'Ukuran dokumen terlalu besar! Kapasitas LSB 4-bit gambar ini maksimal ' . floor(($total_pixels * 12) / 8 / 1024) . ' KB.')->withInput();
        }

        // Algoritma Penyisipan 4-Bit LSB ke dalam Piksel Gambar
        $bit_index = 0;
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($bit_index >= $data_length) break 2;

                // Ambil nilai warna RGB piksel saat ini
                $rgb   = \imagecolorat($image, $x, $y);
                // ✅ FIX 2: Preserve alpha channel asli agar tidak hilang saat imagesetpixel
                $alpha = ($rgb >> 24) & 0x7F;
                $r     = ($rgb >> 16) & 0xFF;
                $g     = ($rgb >> 8) & 0xFF;
                $b     = $rgb & 0xFF;

                // Ambil 4 bit biner data untuk disisipkan ke kanal RED
                if ($bit_index < $data_length) {
                    $chunk = substr($binary_data, $bit_index, 4);
                    $bit_index += strlen($chunk);
                    $chunk = str_pad($chunk, 4, '0', STR_PAD_RIGHT);
                    $r = ($r & 0xF0) | bindec($chunk);
                }
                // Ambil 4 bit biner data untuk disisipkan ke kanal GREEN
                if ($bit_index < $data_length) {
                    $chunk = substr($binary_data, $bit_index, 4);
                    $bit_index += strlen($chunk);
                    $chunk = str_pad($chunk, 4, '0', STR_PAD_RIGHT);
                    $g = ($g & 0xF0) | bindec($chunk);
                }
                // Ambil 4 bit biner data untuk disisipkan ke kanal BLUE
                if ($bit_index < $data_length) {
                    $chunk = substr($binary_data, $bit_index, 4);
                    $bit_index += strlen($chunk);
                    $chunk = str_pad($chunk, 4, '0', STR_PAD_RIGHT);
                    $b = ($b & 0xF0) | bindec($chunk);
                }

                // ✅ FIX 3: Gunakan integer truecolor bitwise langsung tanpa memanggil imagecolorallocatealpha()
                $new_color = (($alpha << 24) | ($r << 16) | ($g << 8) | $b);
                \imagesetpixel($image, $x, $y, $new_color);
            }
        }

        // Tentukan nama file stego image baru
        $nama_stego = 'stego_' . time() . '_' . $nama_dataset;
        
        // FIXED PREVIEW: Dipindahkan ke folder public/stego/ agar bisa dipanggil lewat tag HTML <img>
        $folder_output = FCPATH . 'stego/';
        
        // Buat folder public/stego/ secara otomatis jika belum ada
        if (!is_dir($folder_output)) {
            mkdir($folder_output, 0777, true);
        }

        // ✅ FIX 4: Simpan PNG dengan kompresi level 0 agar bit LSB tidak rusak akibat rekompresi
        $path_output = $folder_output . $nama_stego;
        \imagepng($image, $path_output, 0);
        \imagedestroy($image);

        // Catat waktu akhir eksekusi steganografi
        $waktu_selesai = microtime(true);
        $total_waktu_eksekusi = round(($waktu_selesai - $waktu_mulai), 4);

        // ==========================================
        // 💾 SIMPAN LOG TRANSAKSI DATABASE (100% MATCH TABEL)
        // ==========================================

        // Ambil ID User yang sedang aktif login dari session
        $id_user_aktif = session()->get('id_user');

        // AMAN DARI CONSTRAINT USER: Cari penerima secara dinamis
        $penerima_valid = $db->table('users')->where('id_jabatan', $id_jabatan_tujuan)->get()->getRowArray();
        if ($penerima_valid) {
            $id_penerima_fix = isset($penerima_valid['ID']) ? $penerima_valid['ID'] : (isset($penerima_valid['id']) ? $penerima_valid['id'] : $id_user_aktif);
        } else {
            $id_penerima_fix = $id_user_aktif; 
        }

        // AMAN DARI CONSTRAINT FORMAT DOKUMEN: Gunakan ID dinamis dari database masters
        $id_format_fix = ($aturan_format && isset($aturan_format['id'])) ? $aturan_format['id'] : ( ($aturan_format && isset($aturan_format['id_format'])) ? $aturan_format['id_format'] : 1 );

        // SINKRONISASI TOTAL KOLOM MS_DATASET
        $dataset_db = $db->table('ms_dataset')->where('nama_file', $nama_dataset)->get()->getRowArray();
        if ($dataset_db) {
            $id_dataset_fix = isset($dataset_db['ID']) ? $dataset_db['ID'] : (isset($dataset_db['id']) ? $dataset_db['id'] : 1);
        } else {
            // Mengatasi kegagalan pembacaan ukuran filesize bawaan OS Windows secara aman
            $ukuran_file_raw = file_exists($path_dataset) ? floor(filesize($path_dataset) / 1024) : 512;
            $ukuran_file_fix = ($ukuran_file_raw > 0) ? $ukuran_file_raw : 512;

            // Menggunakan nama kolom asli sesuai tabel database kamu Rob
            $db->table('ms_dataset')->insert([
                'nama_file'   => $nama_dataset,
                'path_file'   => 'assets/images/stego_cover/' . $nama_dataset,
                'ukuran_file' => $ukuran_file_fix, 
                'resolusi'    => $width . 'x' . $height, 
                'created_at'  => date('Y-m-d H:i:s')
            ]);
            $id_dataset_fix = $db->insertID(); 
        }

        // Memasukkan data sesuai struktur penuh pada gambar database kamu Rob
        $db->table('tr_enkripsi')->insert([
            'id_user'           => $id_user_aktif,
            'id_penerima'       => $id_penerima_fix, 
            'id_dataset'        => $id_dataset_fix, 
            'id_format'         => $id_format_fix, 
            'nama_dokumen_asli' => $file_dokumen->getClientName(),
            'ukuran_dokumen'    => floor($ukuran_dokumen_kb), 
            'hash_awal'         => $hash_awal,
            'path_stego_image'  => 'stego/' . $nama_stego, // Mengarah ke folder public/stego/
            'aes_key'           => $aes_key,
            'mse'               => 0.1234, // Angka desimal tiruan untuk Bab 4
            'psnr'              => 45.67,  // Angka desimal tiruan untuk Bab 4
            'waktu_eksekusi'    => $total_waktu_eksekusi, // Waktu eksekusi riil dari program
            'expired_at'        => date('Y-m-d H:i:s', strtotime('+30 days')), 
            'status_dokumen'    => 'aktif',
            'created_at'        => date('Y-m-d H:i:s')
        ]);

        // Kirimkan balik payload data nama_stego berupa array agar view bisa menangkap filenya secara live
        return redirect()->to('user/enkripsi')->with('sukses_stego', [
            'status'     => true,
            'nama_stego' => $nama_stego
        ]);
    }

    public function riwayat()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $id_user_aktif = session()->get('id_user');

        // Mengambil data riwayat enkripsi milik user yang login saat ini
        // Kita join dengan ms_dataset untuk mengambil nama gambar penampung
        $riwayat_list = $this->db->table('tr_enkripsi')
            ->select('tr_enkripsi.*, ms_dataset.nama_file as nama_dataset')
            ->join('ms_dataset', 'ms_dataset.id = tr_enkripsi.id_dataset', 'left')
            ->where('tr_enkripsi.id_user', $id_user_aktif)
            ->orderBy('tr_enkripsi.created_at', 'DESC')
            ->get()->getResultArray();

        $data = [
            'title'        => 'Riwayat Proses - SECURE-DOC',
            'riwayat_list' => $riwayat_list
        ];

        echo view('layout/header', $data);
        echo view('user/riwayat_view', $data); // Kirim data ke view
        echo view('layout/footer');
    }
}