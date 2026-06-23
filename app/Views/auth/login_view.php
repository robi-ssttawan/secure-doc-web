<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SECURE-DOC</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0d1117;
            font-family: 'Segoe UI', system-ui, sans-serif;
            position: relative;
            overflow: hidden;
        }

        /* Grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(56,139,253,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56,139,253,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Glow atas */
        body::after {
            content: '';
            position: fixed;
            top: -80px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 350px;
            background: radial-gradient(ellipse, rgba(56,139,253,0.13) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Card ── */
        .card-login {
            position: relative;
            z-index: 1;
            width: 420px;
            background: #161b22;
            border: 1px solid #30363d !important;
            border-radius: 16px !important;
            padding: 40px 36px 36px;
        }

        /* ── Brand area ── */
        .shield-wrap {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1f3a5f 0%, #0d2137 100%);
            border: 1px solid rgba(56,139,253,0.25);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 700;
            color: #e6edf3;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .brand-title span { color: #388bfd; }

        .brand-sub {
            font-size: 12px;
            color: #6e7681;
            letter-spacing: 0.3px;
        }

        .enc-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }

        .badge-aes {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            background: #1f3a5f;
            color: #58a6ff;
            border: 1px solid rgba(56,139,253,0.25);
        }

        .badge-lsb {
            font-size: 10px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            background: #1a2e1a;
            color: #3fb950;
            border: 1px solid rgba(63,185,80,0.25);
        }

        /* ── Divider ── */
        .login-divider {
            height: 1px;
            background: #21262d;
            margin: 24px 0;
        }

        /* ── Alert ── */
        .alert-secure {
            background: #2d1b1b;
            border: 1px solid rgba(248,81,73,0.25) !important;
            border-left: 3px solid #f85149 !important;
            border-radius: 8px !important;
            padding: 10px 14px;
            font-size: 13px;
            color: #f8514990;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        /* ── Labels ── */
        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #8b949e;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }

        /* ── Input group ── */
        .input-group-text {
            background: #0d1117 !important;
            border: 1px solid #30363d !important;
            border-right: none !important;
            color: #484f58 !important;
            border-radius: 8px 0 0 8px !important;
        }

        .form-control {
            background: #0d1117 !important;
            border: 1px solid #30363d !important;
            border-left: none !important;
            color: #e6edf3 !important;
            font-size: 14px;
            padding: 11px 14px;
            border-radius: 0 8px 8px 0 !important;
        }

        .form-control::placeholder { color: #484f58 !important; }

        .form-control:focus {
            border-color: #388bfd !important;
            box-shadow: 0 0 0 3px rgba(56,139,253,0.12) !important;
            background: #0d1117 !important;
        }

        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #388bfd !important;
        }

        /* ── Button ── */
        .btn-login {
            width: 100%;
            background: #1f6feb;
            border: 1px solid rgba(56,139,253,0.5);
            border-radius: 8px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            padding: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
            transition: background 0.2s, border-color 0.2s;
            text-decoration: none;
        }

        .btn-login:hover {
            background: #388bfd;
            color: #fff;
        }

        /* ── Secure footer ── */
        .secure-footer {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #21262d;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        .secure-dot {
            width: 7px;
            height: 7px;
            background: #3fb950;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .secure-footer span {
            font-size: 11px;
            color: #484f58;
        }
    </style>
</head>
<body>

<div class="card-login">

    <!-- Brand -->
    <div class="text-center mb-0">
        <div class="shield-wrap">
            <i class="fa-solid fa-shield-halved fa-2x" style="color:#388bfd;"></i>
        </div>
        <div class="brand-title">SECURE<span>-</span>DOC</div>
        <div class="brand-sub">Sistem Manajemen Dokumen Terenkripsi</div>
        <div class="enc-badges">
            <span class="badge-aes">AES-256</span>
            <span class="badge-lsb">LSB Steganografi</span>
        </div>
    </div>

    <div class="login-divider"></div>

    <!-- Flash error -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert-secure" role="alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= base_url('auth/login') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="field-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                       required autocomplete="off" value="<?= old('username') ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="field-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                <input type="password" name="password" class="form-control"
                       placeholder="Masukkan password" required>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="fa-solid fa-right-to-bracket"></i> MASUK SISTEM
        </button>
    </form>

    <!-- Secure footer -->
    <div class="secure-footer">
        <span class="secure-dot"></span>
        <span>Koneksi aman &nbsp;·&nbsp; Sesi terenkripsi &nbsp;·&nbsp; SECURE-DOC v2.0</span>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>