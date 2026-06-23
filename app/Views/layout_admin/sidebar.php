<div id="sidebar-admin" class="d-flex flex-column flex-shrink-0 p-3 shadow">
    <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="fa-solid fa-user-shield fa-2x me-2 text-warning"></i>
        <span class="fs-4 fw-bold">ADMIN PANEL</span>
    </div>
    <hr class="bg-secondary">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= url_is('admin/dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge-high me-2 text-danger"></i> Dashboard Analisis
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/users') ?>" class="nav-link <?= url_is('admin/users') ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear me-2 text-info"></i> Manajemen Users
            </a>
        </li>
        <li>
            <a href="<?= base_url('admin/dataset') ?>" class="nav-link <?= url_is('admin/dataset') ? 'active' : '' ?>">
                <i class="fa-solid fa-folder-tree me-2 text-success"></i> Master Citra Dataset
            </a>
        </li>
    </ul>
    <hr class="bg-secondary">
    <div class="dropdown">
        <div class="small font-monospace text-muted mb-2 text-center">Logged as: <?= session()->get('username') ?></div>
        <a href="<?= base_url('logout') ?>" class="btn btn-danger btn-sm w-100 fw-bold">
            <i class="fa-solid fa-right-from-bracket me-1"></i> LOGOUT SYSTEM
        </a>
    </div>
</div>
<div id="main-content"></div>