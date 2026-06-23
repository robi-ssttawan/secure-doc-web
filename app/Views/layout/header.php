<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SECURE-DOC - USER' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #0b0f19;
            /* Efek Grid Halus sesuai tema Login */
            background-image: 
                linear-gradient(rgba(18, 24, 38, 0.5) 1px, transparent 1px),
                linear-gradient(90deg, rgba(18, 24, 38, 0.5) 1px, transparent 1px);
            background-size: 24px 24px;
            color: #e2e8f0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .custom-navbar {
            background-color: #111622 !important;
            border-bottom: 1px solid rgba(37, 99, 235, 0.2);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .navbar-brand {
            font-size: 1.25rem;
            letter-spacing: 0.05em;
            color: #ffffff !important;
        }

        .navbar-brand i {
            color: #3b82f6 !important;
            filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.5));
        }

        /* Nav Link Styling */
        .custom-navbar .nav-link {
            color: #94a3b8 !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .custom-navbar .nav-link i {
            font-size: 0.95rem;
        }

        .custom-navbar .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.03);
        }

        /* Nav Link Active State (Aksen Biru Neon) */
        .custom-navbar .nav-link.active-nav {
            color: #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.1);
            font-weight: 600 !important;
        }

        /* User Info Pill */
        .user-badge {
            background-color: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            color: #cbd5e1;
            font-size: 0.85rem;
        }

        /* Logout Button Modernization */
        .btn-logout {
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #ef4444;
            background-color: rgba(239, 68, 68, 0.05);
            font-size: 0.85rem;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.3);
        }

        /* Toggler Button */
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg custom-navbar shadow-lg">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= base_url('dashboard') ?>">
            <i class="fa-solid fa-shield-halved me-2"></i> SECURE-DOC
        </a>
        <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1 mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= url_is('dashboard') ? 'active-nav' : '' ?>" href="<?= base_url('dashboard') ?>">
                        <i class="fa-solid fa-house me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (url_is('enkripsi') || url_is('dashboard/enkripsi') || url_is('user/enkripsi')) ? 'active-nav' : '' ?>" href="<?= base_url('user/enkripsi') ?>">
                        <i class="fa-solid fa-lock me-2"></i> Enkripsi & LSB
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url_is('dekripsi') ? 'active-nav' : '' ?>" href="<?= base_url('dekripsi') ?>">
                        <i class="fa-solid fa-key me-2"></i> Dekripsi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= url_is('dashboard/riwayat') ? 'active-nav' : '' ?>" href="<?= base_url('dashboard/riwayat') ?>">
                        <i class="fa-solid fa-history me-2"></i> Riwayat
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0 border-top border-secondary pt-3 pt-lg-0 border-lg-0">
                <div class="user-badge font-monospace d-flex align-items-center">
                    <i class="fa-solid fa-user-circle me-2 text-primary"></i> <?= session()->get('nama_jabatan') ?>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn btn-logout fw-bold d-flex align-items-center gap-1">
                    <i class="fa-solid fa-power-off"></i> Keluar
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container my-4">