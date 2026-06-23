<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'ADMIN PANEL - SECURE-DOC' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { min-height: 100vh; display: flex; background-color: #f4f6f9; }
        #sidebar-admin { width: 260px; min-height: 100vh; background: #1f2d3d; color: #fff; }
        #sidebar-admin .nav-link { color: #c2c7d0; padding: 12px 20px; font-weight: 500; border-radius: 4px; margin-bottom: 3px; }
        #sidebar-admin .nav-link:hover, #sidebar-admin .nav-link.active { background: #4f5962; color: #fff; }
        #sidebar-admin .nav-link.active { border-left: 5px solid #007bff; background: rgba(255,255,255,0.1); }
        #main-content { flex: 1; min-width: 0; padding: 25px; }
    </style>
</head>
<body></body>