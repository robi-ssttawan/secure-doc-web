<?= view('layout/header') ?>

<style>
    .dash-hero {
        background: #161b22;
        border: 1px solid #30363d;
        border-radius: 16px;
        padding: 36px 40px 32px;
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
    }

    /* grid background */
    .dash-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(56,139,253,0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(56,139,253,0.05) 1px, transparent 1px);
        background-size: 36px 36px;
        pointer-events: none;
        border-radius: 16px;
    }

    /* glow pojok kanan atas */
    .dash-hero::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 280px; height: 280px;
        background: radial-gradient(ellipse, rgba(56,139,253,0.10) 0%, transparent 70%);
        pointer-events: none;
    }

    .dash-hero-inner { position: relative; z-index: 1; }

    .dash-greeting {
        font-size: 26px;
        font-weight: 700;
        color: #e6edf3;
        margin-bottom: 10px;
        letter-spacing: 0.2px;
    }

    .dash-greeting span { color: #388bfd; }

    .dash-desc {
        font-size: 14px;
        color: #6e7681;
        line-height: 1.7;
        max-width: 720px;
        margin-bottom: 24px;
    }

    .dash-divider {
        height: 1px;
        background: #21262d;
        margin: 24px 0;
    }

    /* Identity bar */
    .identity-bar {
        background: #0d1117;
        border: 1px solid #30363d;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        font-family: 'Courier New', monospace;
        font-size: 13px;
    }

    .identity-icon {
        width: 36px; height: 36px;
        background: #1f3a5f;
        border: 1px solid rgba(56,139,253,0.25);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        color: #388bfd;
        font-size: 15px;
    }

    .identity-text { color: #8b949e; }
    .identity-text strong { color: #c9d1d9; font-weight: 600; }

    .identity-sep { color: #30363d; margin: 0 4px; }

    .role-badge {
        margin-left: auto;
        flex-shrink: 0;
        background: #1a2e1a;
        border: 1px solid rgba(63,185,80,0.25);
        color: #3fb950;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 4px 10px;
        border-radius: 5px;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }
</style>

<div class="container-fluid px-4 py-4">
    <div class="dash-hero shadow-sm">
        <div class="dash-hero-inner">

            <h1 class="dash-greeting">
                Selamat Datang, <span><?= session()->get('nama') ?></span>!
            </h1>

            <p class="dash-desc">
                Sistem Implementasi Algoritma Hybrid AES-256 dan Least Significant Bit (LSB)
                dengan Analisis Quality Metrics untuk Keamanan Pertukaran Dokumen Digital
                Dinas Kominfo.
            </p>

            <div class="dash-divider"></div>

            <div class="identity-bar">
                <div class="identity-icon">
                    <i class="fa-solid fa-id-badge"></i>
                </div>
                <div class="identity-text">
                    <strong>Identitas Logged In:</strong>
                    <span class="identity-sep">·</span>
                    ID: <strong><?= session()->get('id_user') ?></strong>
                    <span class="identity-sep">|</span>
                    Unit Jabatan: <strong><?= session()->get('nama_jabatan') ?></strong>
                    <span class="identity-sep">|</span>
                    Hak Akses: <strong><?= strtoupper(session()->get('role')) ?></strong>
                </div>
                <span class="role-badge"><?= strtoupper(session()->get('role')) ?></span>
            </div>

        </div>
    </div>
</div>

<?= view('layout/footer') ?>