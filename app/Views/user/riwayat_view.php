<div class="container-fluid mt-4">

    <style>
        .cyber-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px;
        }
        .cyber-card-header {
            background-color: #0f172a !important; /* Header Card Gelap Premium */
            color: #ffffff !important;
            border-bottom: 2px solid #3b82f6 !important;
        }
        .cyber-table {
            background-color: #ffffff !important;
            color: #1e293b !important; /* Teks Utama Hitam Pekat/Gelap - PASTI TERBACA */
        }
        .cyber-table thead th {
            background-color: #1e293b !important; /* Header Tabel Gelap */
            color: #ffffff !important; /* Teks Header Putih */
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            vertical-align: middle;
        }
        .cyber-table tbody tr:hover {
            background-color: #f8fafc !important; /* Hover abu-abu sangat terang */
        }
        .cyber-code {
            background-color: #f1f5f9 !important;
            color: #b45309 !important;
            border: 1px solid #cbd5e1;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
        .metric-danger {
            color: #dc2626 !important; /* Merah tegas untuk MSE */
            font-weight: bold;
        }
        .metric-success {
            color: #16a34a !important; /* Hijau tegas untuk PSNR */
            font-weight: bold;
        }
        .metric-info {
            color: #2563eb !important; /* Biru tegas untuk Waktu */
            font-weight: bold;
        }
    </style>

    <div class="card cyber-card shadow-sm mb-4">
        <div class="card-header cyber-card-header py-3">
            <h5 class="m-0 font-weight-bold text-white">
                <i class="fa-solid fa-list-check text-blue me-2"></i> Riwayat Enkripsi & Penyisipan Dokumen
            </h5>
        </div>
        <div class="card-body bg-white">
            
            <?php if (empty($riwayat_list)) : ?>
                <div class="alert alert-light text-center py-4 border">
                    <i class="fa-solid fa-folder-open text-muted d-block fa-2x mb-2"></i>
                    <span class="text-secondary">Belum ada riwayat proses enkripsi yang dilakukan.</span>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-bordered cyber-table table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal Proses</th>
                                <th>Nama Dokumen Asli</th>
                                <th>Ukuran</th>
                                <th>Cover Image (Dataset)</th>
                                <th>Kunci AES</th>
                                <th>MSE</th>
                                <th>PSNR</th>
                                <th>Waktu Eksekusi</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($riwayat_list as $row) : ?>
                                <tr>
                                    <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>
                                    <td class="text-center"><small class="text-muted"><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></small></td>
                                    <td>
                                        <span class="text-truncate d-inline-block fw-semibold text-dark" style="max-width: 180px;" title="<?= htmlspecialchars($row['nama_dokumen_asli']); ?>">
                                            📄 <?= htmlspecialchars($row['nama_dokumen_asli']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center text-dark"><?= $row['ukuran_dokumen']; ?> KB</td>
                                    <td><small class="text-dark">🖼️ <?= htmlspecialchars($row['nama_dataset'] ?? 'Default'); ?></small></td>
                                    <td class="text-center"><code class="cyber-code"><?= htmlspecialchars($row['aes_key']); ?></code></td>
                                    <td class="text-center metric-danger"><?= $row['mse']; ?></td>
                                    <td class="text-center metric-success"><?= $row['psnr']; ?> dB</td>
                                    <td class="text-center metric-info"><?= $row['waktu_eksekusi']; ?> s</td>
                                    <td class="text-center">
                                        <a href="<?= base_url($row['path_stego_image']); ?>" download class="btn btn-sm btn-primary btn-block fw-bold shadow-sm" title="Download Gambar Stego">
                                            <i class="fa-solid fa-download me-1"></i> Unduh
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert mt-3 py-3 border-0 rounded" style="font-size: 13px; background-color: #f8fafc; border-left: 4px solid #f59e0b !important;">
                    <span class="text-dark"><i class="fa-solid fa-circle-info text-warning me-1"></i> <strong>📌 Catatan Skripsi:</strong> Nilai MSE, PSNR, dan Waktu Eksekusi di atas disimpan otomatis saat kamu menekan tombol enkripsi baru dan langsung singgah di tabel <code>tr_enkripsi</code> untuk bahan grafik lampiran Bab 4.</span>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>