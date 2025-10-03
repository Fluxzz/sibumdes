<?php
// Ambil data admin yang sedang login
if (isset($db) && isset($_SESSION['id'])) {
    $stmt_user = $db->prepare("SELECT nama_admin, username_admin, gambar FROM tb_admin WHERE id_admin = ?");
    $stmt_user->bind_param("i", $_SESSION['id']);
    $stmt_user->execute();
    $user_data = $stmt_user->get_result()->fetch_assoc();
    $stmt_user->close();
}
?>

<style>
    .sidebar .user {
        padding: 25px 10px;
        display: flex;
        align-items: center;
        gap: 40px;
        flex-direction: column;
        text-align: center;
    }

    .sidebar .user .avatar-sm img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #fff;
        margin-left: -5px;
    }

    .sidebar .user .info span {
        display: block;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar .user .info .user-level {
        display: block;
        font-size: 12px;
        color: #a0a0a0;
    }

    /* === FIX saat sidebar collapse (minimize) === */
    .sidebar.sidebar-minimize .user {
        padding: 10px 5px;
    }

    .sidebar.sidebar-minimize .user .avatar-sm img {
        width: 1px;
        height: 1px;
    }

    .sidebar.sidebar-minimize .user .info {
        display: none;
        /* sembunyikan teks biar rapi */

    }
</style>

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="/features/dashboard.php" class="logo text-white fw-bold">
                <i class="fas fa-landmark me-2"></i> SIBUMDES - Wunut
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">

            <!-- User Profile -->
            <div class="user">
                <div class="avatar-sm">
                    <img src="/features/uploads/profile/<?php echo !empty($user_data['gambar']) ? htmlspecialchars($user_data['gambar']) : 'default.jpg'; ?>"
                        alt="Profile" class="avatar-img rounded-circle" />
                </div>
                <div class="info">
                    <span>
                        <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'User'; ?>
                    </span>
                </div>
            </div>

            <!-- Menu -->
            <ul class="nav nav-secondary">

                <li class="nav-item <?php echo ($activeMenu ?? '') == 'dashboard' ? 'active' : ''; ?>">
                    <a href="/features/dashboard.php">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Features</h4>
                </li>

                <!-- Manajemen Surat -->
                <li class="nav-item <?php echo ($activeMenu ?? '') == 'surat' ? 'active' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#menu-surat" aria-expanded="<?php echo ($activeMenu ?? '') == 'surat' ? 'true' : 'false'; ?>">
                        <i class="fas fa-envelope"></i>
                        <p>Manajemen Surat</p><span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo ($activeMenu ?? '') == 'surat' ? 'show' : ''; ?>" id="menu-surat">
                        <ul class="nav nav-collapse">
                            <li><a href="/features/menu/surat/inputbuatsurat.php"><span class="sub-item">Buat Surat</span></a></li>
                            <li><a href="/features/menu/surat/datasuratmasuk.php"><span class="sub-item">Arsip Surat Masuk</span></a></li>
                            <li><a href="/features/menu/surat/datasuratkeluar.php"><span class="sub-item">Arsip Surat Keluar</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Manajemen Postingan -->
                <li class="nav-item <?php echo ($activeMenu ?? '') == 'postingan' ? 'active' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#menu-postingan" aria-expanded="<?php echo ($activeMenu ?? '') == 'postingan' ? 'true' : 'false'; ?>">
                        <i class="fas fa-newspaper"></i>
                        <p>Manajemen Postingan</p><span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo ($activeMenu ?? '') == 'postingan' ? 'show' : ''; ?>" id="menu-postingan">
                        <ul class="nav nav-collapse">
                            <li><a href="/features/menu/postingan/tambah-postingan.php"><span class="sub-item">Tambah Postingan</span></a></li>
                            <li><a href="/features/menu/postingan/data-postingan.php"><span class="sub-item">Data Postingan</span></a></li>
                            <li><a href="/features/menu/postingan/analisis-konten.php"><span class="sub-item">Analisis Konten</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Pengelolaan Usaha -->
                <li class="nav-item <?php echo ($activeMenu ?? '') == 'usaha' ? 'active' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#menu-usaha" aria-expanded="<?php echo ($activeMenu ?? '') == 'usaha' ? 'true' : 'false'; ?>">
                        <i class="fas fa-chart-line"></i>
                        <p>Pengelolaan Usaha</p><span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo ($activeMenu ?? '') == 'usaha' ? 'show' : ''; ?>" id="menu-usaha">
                        <ul class="nav nav-collapse">
                            <li><a href="/features/menu/penjualan/rekap-usaha.php"><span class="sub-item">Rekap Usaha</span></a></li>
                            <li><a href="/features/menu/penjualan/visualisasi-usaha.php"><span class="sub-item">Visualisasi Usaha</span></a></li>
                        </ul>
                    </div>
                </li>
            </ul>

        </div>
    </div>
</div>
<!-- End Sidebar -->

<!-- Main Panel -->
<div class="main-panel">
    <div class="main-header">
        <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
                <a href="/features/dashboard.php" class="logo">
                    <img src="/assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                    <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
                </div>
                <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
            </div>
        </div>

        <!-- Topbar User -->
        <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
                <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                    <li class="nav-item topbar-user dropdown hidden-caret">
                        <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                            <div class="avatar-sm">
                                <img src="/features/uploads/profile/<?php echo !empty($user_data['gambar']) ? htmlspecialchars($user_data['gambar']) : 'default.jpg'; ?>"
                                    alt="..." class="avatar-img rounded-circle" />
                            </div>
                            <span class="profile-username">
                                <span class="op-7">Hi,</span>
                                <span class="fw-bold"><?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'User'; ?></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated fadeIn">
                            <div class="dropdown-user-scroll scrollbar-outer">
                                <li>
                                    <div class="user-box">
                                        <div class="avatar-lg"><img src="/features/uploads/profile/<?php echo !empty($user_data['gambar']) ? htmlspecialchars($user_data['gambar']) : 'default.jpg'; ?>" alt="image profile" class="avatar-img rounded" /></div>
                                        <div class="u-text">
                                            <h4><?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'User Name'; ?></h4>
                                            <p class="text-muted"><?php echo isset($user_data['username_admin']) ? htmlspecialchars($user_data['username_admin']) : 'username'; ?></p>
                                            <a href="/features/menu/profile/profile.php" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/auth/proses_logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout</a>
                                    <a class="dropdown-item" href="/features/admin/data-admin.php">Kelola User</a>
                                </li>
                            </div>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Content Wrapper -->
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3"><?php echo isset($pageTitle) ? $pageTitle : 'Halaman'; ?></h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home"><a href="/features/dashboard.php"><i class="icon-home"></i></a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a><?php echo isset($activeMenu) ? ucfirst($activeMenu) : 'Page'; ?></a></li>
                </ul>
            </div>