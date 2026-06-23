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
        border-color: #e11d48 !important; /* Aksen merah/rose tipis untuk fokus dekripsi */
        box-shadow: 0 0 8px rgba(225, 29, 72, 0.4) !important;
    }
    .cyber-input::placeholder {
        color: #475569 !important;
    }
    .cyber-input-group-text {
        background-color: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .cyber-info-box {
        background-color: #0b0f19 !important;
        border: 1px solid rgba(37, 99, 235, 0.15) !important;
    }
    .btn-cyber-danger {
        background-color: #dc2626;
        border: none;
        color: #ffffff;
        transition: all 0.2s ease;
    }
    .btn-cyber-danger:hover {
        background-color: #b91c1c;
        box-shadow: 0 0 15px rgba(220, 38, 38, 0.5);
        color: #ffffff;
    }
    .result-placeholder {
        background-color: #0b0f19;
        border: 2px dashed rgba(255, 255, 255, 0.1) !important;
    }
</style>

<div class="card cyber-card shadow-lg mb-5">
    <div class="card-header cyber-card-header py-3">
        <h5 class="mb-0 text-center fw-bold text-white">
            <i class="fa-solid fa-unlock-keyhole text-danger me-2" style="filter: drop-shadow(0 0 6px rgba(220, 38, 38, 0.4));"></i>
            Proses Ekstraksi & Dekripsi Dokumen
        </h5>
    </div>
    <div class="card-body p-4">
        
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger bg-danger-subtle border-danger text-danger-emphasis alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('dekripsi/proses') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row justify-content-center">
                <div class="col-md-6 mb-4">
                    <label class="form-label cyber-label fw-bold">Upload Stego-Image (.png hasil enkripsi)</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text cyber-input-group-text"><i class="fa-solid fa-file-image text-primary"></i></span>
                        <input type="file" class="form-control cyber-input" name="stego_image" id="stego_input" accept=".png" required onchange="previewStegoImage()">
                    </div>
                    <small style="color: #64748b;" class="d-block"><i class="fa-solid fa-info-circle me-1"></i> Wajib mengunggah file gambar berformat PNG hasil unduhan sistem.</small>
                    
                    <div id="stego_preview_container" class="text-center mt-3" style="display: none;">
                        <span style="color: #64748b;" class="small d-block mb-1 text-start"><i class="fa-solid fa-eye me-1"></i> Preview Citra Terpilih:</span>
                        <img id="stego_img_preview" src="" class="img-thumbnail bg-dark border-secondary shadow-sm" style="max-height: 180px;">
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label cyber-label fw-bold">Masukkan Kunci Cipher AES-256</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text cyber-input-group-text"><i class="fa-solid fa-key text-warning"></i></span>
                        <input type="password" class="form-control cyber-input" name="aes_key" placeholder="Masukkan Kunci Rahasia untuk Membuka Dokumen" required>
                    </div>
                    <div class="p-3 cyber-info-box rounded">
                        <small style="color: #cbd5e1;" class="fw-bold d-block mb-1"><i class="fa-solid fa-shield-halved text-primary me-1"></i> Aturan Ekstraksi:</small>
                        <small style="color: #94a3b8;" class="d-block text-justify">Sistem akan membaca susunan biner pada 4-bit LSB piksel gambar secara berurutan, mencari marker pembatas, dan mendekripsi payload biner menggunakan algoritma AES-256 CBC berdasarkan kunci yang Anda berikan.</small>
                    </div>
                </div>
            </div>

            <div class="text-center my-3">
                <button type="submit" class="btn btn-cyber-danger px-5 btn-lg shadow-sm fw-bold">
                    <i class="fa-solid fa-unlock fa-spin-reverse me-2"></i> MULAI PROSES EKSTRAKSI
                </button>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1);" class="my-4">

            <div class="text-center mt-4">
                <p class="fw-bold fs-5" style="color: #cbd5e1;">Hasil Ekstraksi Dokumen Rahasia</p>
                
                <?php if (session()->getFlashdata('sukses_dekripsi')) : 
                    $doc = session()->getFlashdata('sukses_dekripsi'); 
                ?>
                    <div class="alert alert-success bg-success-subtle border-success d-inline-block p-3 shadow-sm mx-auto animate__animated animate__fadeIn" style="width: 100%; max-width: 500px;">
                        <div class="d-flex align-items-center gap-3 text-start">
                            <div class="bg-success text-white p-3 rounded d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                <i class="fa-solid fa-file-arrow-down fa-xl"></i>
                            </div>
                            <div class="text-truncate" style="max-width: 380px;">
                                <h6 class="fw-bold text-success-emphasis mb-1">Dokumen Berhasil Ditemukan!</h6>
                                <p class="mb-0 small text-success-emphasis font-monospace text-truncate" title="<?= $doc['nama_dokumen'] ?>">Nama: <?= $doc['nama_dokumen'] ?></p>
                                <a href="<?= base_url('dekripsi/download_dokumen/' . $doc['file_temp']) ?>" class="btn btn-sm btn-success fw-bold mt-2 shadow-sm">
                                    <i class="fa-solid fa-download me-1"></i> Unduh Dokumen Asli
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="mx-auto border mb-3 rounded result-placeholder d-flex align-items-center justify-content-center" style="width: 100%; max-width: 450px; height: 120px;">
                        <div class="text-center">
                            <i class="fa-solid fa-file-circle-xmark text-secondary fa-2x mb-2 d-block" style="opacity: 0.5;"></i>
                            <span class="text-secondary small font-monospace" style="opacity: 0.6; font-style: italic;">Belum ada proses ekstraksi yang berjalan...</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<script>
function previewStegoImage() {
    const input = document.getElementById('stego_input');
    const container = document.getElementById('stego_preview_container');
    const imgPreview = document.getElementById('stego_img_preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgPreview.src = e.target.result;
            container.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
    }
}
</script>
<?= view('layout/footer') ?>