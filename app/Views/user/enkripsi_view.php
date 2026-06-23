<?php $jabatan_list = $jabatan_list ?? []; ?>
<?= view('layout/header') ?>

<style>
    .cyber-card {
        background-color: #111622;
        border: 1px solid rgba(37, 99, 235, 0.2) !important;
        border-radius: 12px;
    }
    .cyber-card-header {
        background-color: rgba(15, 23, 42, 0.4) !important;
        border-bottom: 1px solid rgba(37, 99, 235, 0.15) !important;
    }
    .cyber-label {
        color: #94a3b8;
        font-size: 0.9rem;
        letter-spacing: 0.03em;
    }
    .cyber-input {
        background-color: #0b0f19 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    .cyber-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 8px rgba(59, 130, 246, 0.4) !important;
    }
    .cyber-input::placeholder {
        color: #475569 !important;
    }
    .cyber-input-group-text {
        background-color: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    /* Style untuk option di dalam select box agar teksnya tidak putih samar */
    .cyber-input option {
        background-color: #111622;
        color: #ffffff;
    }
    .btn-cyber-primary {
        background-color: #2563eb;
        border: none;
        color: #ffffff;
        transition: all 0.2s ease;
    }
    .btn-cyber-primary:hover {
        background-color: #1d4ed8;
        box-shadow: 0 0 15px rgba(37, 99, 235, 0.5);
        color: #ffffff;
    }
    .stego-placeholder {
        width: 220px; 
        height: 220px; 
        background-color: #0b0f19;
        border: 2px dashed rgba(255, 255, 255, 0.1) !important;
    }
</style>

<div class="card cyber-card shadow-lg mb-5">
    <div class="card-header cyber-card-header py-3">
        <h5 class="mb-0 text-center fw-bold text-white">
            <i class="fa-solid fa-shield-halved text-primary me-2" style="filter: drop-shadow(0 0 6px rgba(59, 130, 246, 0.4));"></i>
            Proses Enkripsi & Penyisipan Dokumen
        </h5>
    </div>
    <div class="card-body p-4">
        
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger bg-danger-subtle border-danger text-danger-emphasis alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('user/proses_enkripsi') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label cyber-label fw-bold">Pilih Dokumen Asli (.pdf / .docx)</label>
                    <div class="input-group">
                        <span class="input-group-text cyber-input-group-text"><i class="fa-solid fa-file-pdf text-danger"></i></span>
                        <input type="file" class="form-control cyber-input" name="dokumen" required>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label cyber-label fw-bold">Pilih Citra Penampung (Dataset)</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text cyber-input-group-text"><i class="fa-solid fa-image text-success"></i></span>
                        <select class="form-select cyber-input" name="dataset" id="dataset_select" required onchange="previewCoverImage()">
                            <option value="" selected disabled>-- Pilih Gambar Dataset --</option>
                            <option value="peppers.png">Peppers.png (Warna Gradasi)</option>
                            <option value="baboon.png">Baboon.png (Tekstur Padat)</option>
                            <option value="house.png">House.png (Standar Ekstensi)</option>
                        </select>
                    </div>
                    <div id="cover_preview_container" class="text-start mt-2" style="display: none;">
                        <span style="color: #64748b;" class="small d-block mb-1"><i class="fa-solid fa-eye me-1"></i> Preview Cover Asli:</span>
                        <img id="cover_img_preview" src="" class="img-thumbnail bg-dark border-secondary" style="max-height: 110px;">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label cyber-label fw-bold">Masukkan Kunci Cipher AES-256</label>
                    <div class="input-group">
                        <span class="input-group-text cyber-input-group-text"><i class="fa-solid fa-key text-warning"></i></span>
                        <input type="password" class="form-control cyber-input" name="aes_key" placeholder="Kunci Rahasia Enkripsi" required>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label cyber-label fw-bold">Kirim ke Jabatan / Unit Kerja Tujuan</label>
                    <div class="input-group">
                        <span class="input-group-text cyber-input-group-text"><i class="fa-solid fa-building text-info"></i></span>
                        <select name="id_jabatan_tujuan" class="form-select cyber-input" required>
                            <option value="" selected disabled>-- Pilih Jabatan Tujuan --</option>
                            <?php foreach ($jabatan_list as $j) : ?>
                                <?php if ($j['ID'] != session()->get('id_jabatan')) : ?>
                                    <option value="<?= $j['ID'] ?>"><?= $j['nama_jabatan'] ?> (<?= $j['kode_jabatan'] ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-center my-4">
                <button type="submit" class="btn btn-cyber-primary px-5 btn-lg shadow-sm fw-bold">
                    <i class="fa-solid fa-gear fa-spin me-2"></i> MULAI PROSES SINKRONISASI
                </button>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1);" class="my-4">

            <div class="text-center mt-4">
                <p class="fw-bold fs-5" style="color: #cbd5e1;">Hasil Stego-Image (Preview Realtime)</p>
                
                <?php if (session()->getFlashdata('sukses_stego')) : 
                    $res = session()->getFlashdata('sukses_stego'); 
                ?>
                    <div class="mx-auto mb-3" style="max-width: 250px;">
                        <img src="<?= base_url('stego/' . $res['nama_stego']) ?>" class="img-thumbnail bg-dark border-success shadow-sm animate__animated animate__fadeIn" alt="Hasil Steganografi">
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <div class="border-0 p-2 rounded bg-success text-white shadow-sm d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-cloud-arrow-down"></i>
                        </div>
                        <a href="<?= base_url('stego/' . $res['nama_stego']) ?>" download="<?= $res['nama_stego'] ?>" class="text-decoration-none text-success fw-bold fs-5">
                            Download Stego-Image Sekarang
                        </a>
                    </div>

                <?php else : ?>
                    <div class="mx-auto border mb-3 rounded stego-placeholder d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="fa-solid fa-images text-secondary fa-2x mb-2 d-block" style="opacity: 0.5;"></i>
                            <span class="small font-monospace text-secondary" style="opacity: 0.6; font-style: italic;">Menunggu Transaksi...</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-2" style="opacity: 0.4; cursor: not-allowed;">
                        <div class="border-0 p-2 rounded bg-secondary text-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                             <i class="fa-solid fa-download"></i>
                        </div>
                        <span class="text-decoration-none text-secondary fw-bold">Download Stego-Image</span>
                    </div>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<script>
function previewCoverImage() {
    const select = document.getElementById('dataset_select');
    const container = document.getElementById('cover_preview_container');
    const imgPreview = document.getElementById('cover_img_preview');
    
    if (select.value) {
        imgPreview.src = "<?= base_url('assets/images/stego_cover/') ?>/" + select.value;
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}
</script>

<?= view('layout/footer') ?>