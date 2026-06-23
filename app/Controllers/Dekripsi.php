<?php

namespace App\Controllers;

class Dekripsi extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Dekripsi & Ekstraksi Dokumen - SECURE-DOC'
        ];

        return view('user/dekripsi_view', $data);
    }

    // =======================================================
    // 🔓 ENGINE EKSTRAKSI LSB MURNI WARNA & DEKRIPSI AES
    // =======================================================
    public function proses()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $aes_key     = $this->request->getPost('aes_key');
        $file_stego  = $this->request->getFile('stego_image');

        if (!$file_stego->isValid()) {
            return redirect()->back()->with('error', 'File gambar stego tidak valid!')->withInput();
        }

        $path_stego = $file_stego->getTempName();
        $image = @\imagecreatefrompng($path_stego);
        
        if (!$image) {
            return redirect()->back()->with('error', 'Format gambar harus PNG asli hasil download sistem!')->withInput();
        }

        $width  = \imagesx($image);
        $height = \imagesy($image);

        $binary_extracted = '';
        $extracted_text   = '';
        $marker           = '|END|';
        $marker_len       = strlen($marker);
        $found_end        = false;

        // 1. Jalankan Ekstraksi membaca indeks warna asli murni
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                
                // ✅ FIX: Baca komponen warna murni via indeks index asli
                $color_index = \imagecolorat($image, $x, $y);
                $rgba = \imagecolorsforindex($image, $color_index);
                
                $r = $rgba['red'];
                $g = $rgba['green'];
                $b = $rgba['blue'];

                $channels = [$r & 0x0F, $g & 0x0F, $b & 0x0F];

                foreach ($channels as $channel_bits) {
                    $binary_extracted .= str_pad(decbin($channel_bits), 4, '0', STR_PAD_LEFT);

                    while (strlen($binary_extracted) >= 8) {
                        $byte = substr($binary_extracted, 0, 8);
                        $binary_extracted = substr($binary_extracted, 8);
                        
                        $char = chr(bindec($byte));
                        $extracted_text .= $char;

                        if (strlen($extracted_text) >= $marker_len) {
                            if (substr($extracted_text, -$marker_len) === $marker) {
                                $found_end = true;
                                break 3;
                            }
                        }
                    }
                }
            }
        }

        \imagedestroy($image);

        if (!$found_end) {
            return redirect()->back()->with('error', 'Gagal Ekstraksi! Penanda data (|END|) tidak ditemukan.')->withInput();
        }

        // Potong penanda |END| untuk mendapatkan payload teks Base64 murni
        $encryptedBase64Text = substr($extracted_text, 0, -$marker_len);
        $encryptedBase64Text = trim($encryptedBase64Text);

        // 🔥 BLOK CHECK DATA DARI TEMEN LU:
        // Jika tanda '=' bocor ke tengah, kita potong stringnya tepat di tanda '=' pertama yang sah sebagai padding akhir
        $first_equal_pos = strpos($encryptedBase64Text, '=');
        if ($first_equal_pos !== false) {
            // Base64 padding bisa berupa '=' atau '==', ambil maksimal sampai 2 karakter '='
            $len_to_cut = (substr($encryptedBase64Text, $first_equal_pos, 2) === '==') ? 2 : 1;
            $encryptedBase64Text = substr($encryptedBase64Text, 0, $first_equal_pos + $len_to_cut);
        }

        // Dekode string Base64 kembali ke bentuk biner terenkripsi (IV + Ciphertext)
        $encryptedPayload = base64_decode($encryptedBase64Text, true);

        if ($encryptedPayload === false) {
            return redirect()->back()->with('error', 'Payload Base64 rusak atau struktur biner tidak valid setelah sinkronisasi warna!')->withInput();
        }

        // ==========================================
        // 🔑 PROSES KRIPTOGRAFI: DEKRIPSI AES-256-CBC
        // ==========================================
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        if (strlen($encryptedPayload) < $ivLength) {
            return redirect()->back()->with('error', 'Payload dekripsi cipher teks tidak lengkap.')->withInput();
        }
        
        $iv            = substr($encryptedPayload, 0, $ivLength);
        $ciphertextRaw = substr($encryptedPayload, $ivLength);

        // Kunci 32 Byte murni via SHA-256
        $secureKey = hash('sha256', $aes_key, true);

        // Jalankan fungsi dekripsi OpenSSL
        $decryptedDocumentData = openssl_decrypt($ciphertextRaw, 'aes-256-cbc', $secureKey, OPENSSL_RAW_DATA, $iv);

        if ($decryptedDocumentData === false) {
            return redirect()->back()->with('error', 'Gagal menguraikan dokumen! Struktur bita masih mengalami pergeseran skala piksel.')->withInput();
        }

        // 3. Validasi ke database untuk mengembalikan nama asli file
        $hash_akhir = hash('sha256', $decryptedDocumentData);
        $transaksi  = $this->db->table('tr_enkripsi')->where('hash_awal', $hash_akhir)->get()->getRowArray();
        $nama_dokumen_asli = $transaksi ? $transaksi['nama_dokumen_asli'] : 'dokumen_ekstraksi_' . time() . '.pdf';

        // 4. Simpan sementara file hasil dekripsi ke folder cache server agar aman diunduh
        $file_temp_name = 'temp_' . time() . '_' . $nama_dokumen_asli;
        $folder_temp    = WRITEPATH . 'cache/';
        
        if (!is_dir($folder_temp)) {
            mkdir($folder_temp, 0777, true);
        }

        file_put_contents($folder_temp . $file_temp_name, $decryptedDocumentData);

        session()->setFlashdata('sukses_dekripsi', [
            'nama_dokumen' => $nama_dokumen_asli,
            'file_temp'    => $file_temp_name
        ]);

        return redirect()->to('user/dekripsi');
    }

    public function download_dokumen($file_temp_name)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $path_file = WRITEPATH . 'cache/' . $file_temp_name;
        if (file_exists($path_file)) {
            $parts = explode('_', $file_temp_name);
            $nama_asli = str_replace('temp_' . $parts[1] . '_', '', $file_temp_name);
            return $this->response->download($path_file, null)->setFileName($nama_asli);
        } else {
            return redirect()->to('user/dekripsi')->with('error', 'File hasil dekripsi tidak ditemukan.');
        }
    }
}