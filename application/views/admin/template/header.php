<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — Admin FASE Les' : 'Admin FASE Les' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin.css'); ?>" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/Logo.png'); ?>">
</head>

<body id="page-top">

<div id="wrapper">

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar" id="adminSidebar">

        <a class="sidebar-brand" href="<?= site_url('admin'); ?>">
            <div class="sidebar-brand-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div class="sidebar-brand-text">FASE Les</div>
        </a>

        <hr class="sidebar-divider my-0">

        <ul class="nav flex-column mt-1">
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'dashboard' ? 'active' : '' ?>"
                   href="<?= site_url('admin'); ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Manajemen</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'admin' ? 'active' : '' ?>"
                   href="<?= site_url('admin/admin_list'); ?>">
                    <i class="bi bi-shield-fill-check"></i>
                    <span>Admin</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'peserta' ? 'active' : '' ?>"
                   href="<?= site_url('admin/peserta'); ?>">
                    <i class="bi bi-people-fill"></i>
                    <span>Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'pengajar' ? 'active' : '' ?>"
                   href="<?= site_url('admin/pengajar'); ?>">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Pengajar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'jadwal' ? 'active' : '' ?>"
                   href="<?= site_url('admin/jadwal'); ?>">
                    <i class="bi bi-calendar2-week-fill"></i>
                    <span>Jadwal</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'pembayaran' ? 'active' : '' ?>"
                   href="<?= site_url('admin/pembayaran'); ?>">
                    <i class="bi bi-credit-card-fill"></i>
                    <span>Pembayaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'paket' ? 'active' : '' ?>"
                   href="<?= site_url('admin/paket'); ?>">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>Paket</span>
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Laporan</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'rekap_bulanan' ? 'active' : '' ?>"
                   href="<?= site_url('admin/rekap_bulanan'); ?>">
                    <i class="bi bi-calendar-month"></i>
                    <span>Rekap Bulanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($active_menu ?? '') === 'rekap_tahunan' ? 'active' : '' ?>"
                   href="<?= site_url('admin/rekap_tahunan'); ?>">
                    <i class="bi bi-calendar3"></i>
                    <span>Rekap Tahunan</span>
                </a>
            </li>
        </ul>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="sidebar-toggler d-none d-md-flex justify-content-center mt-auto pb-3">
            <button id="sidebarToggle" title="Toggle Sidebar">
                <i class="bi bi-chevron-left" id="sidebarToggleIcon"></i>
            </button>
        </div>

    </div>
    <!-- ===== END SIDEBAR ===== -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
