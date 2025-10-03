<?php
session_start();
require_once '../../koneksi.php';

$pageTitle = "Tambah Admin Baru";
// $activeMenu = "kelola_admin"; 
?>

<?php include '../../partials/header.php'; ?>
<?php include '../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Tambah Admin Baru</div>
            </div>
            <div class="card-body">
                <!-- tambahkan enctype untuk upload file -->
                <form action="/features/admin/proses/proses-tambah-admin.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama_admin">Nama Lengkap</label>
                        <input type="text" name="nama_admin" id="nama_admin" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username_admin">Username</label>
                        <input type="text" name="username_admin" id="username_admin" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Foto Profil</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    </div>
                    <div class="card-action">
                        <button type="submit" name="submit" class="btn btn-success">Simpan Admin</button>
                        <a href="data-admin.php" class="btn btn-danger">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>
