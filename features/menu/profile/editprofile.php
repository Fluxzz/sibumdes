<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

// Ambil data admin yang akan diedit
$id_admin = $_SESSION['id'];
$stmt = $db->prepare("SELECT * FROM tb_admin WHERE id_admin = ?");
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

$pageTitle = "Edit Profil";
$activeMenu = "profile";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Edit Profil</div>
            </div>
            <div class="card-body">
                <form action="../proses/proses_editprofile.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama_admin">Nama Lengkap</label>
                        <input type="text" name="nama_admin" id="nama_admin" class="form-control" value="<?= htmlspecialchars($data['nama_admin']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username_admin">Username</label>
                        <input type="text" name="username_admin" id="username_admin" class="form-control" value="<?= htmlspecialchars($data['username_admin']) ?>" required>
                    </div>
                    <hr>
                    <h4 class="mt-4">Ubah Password</h4>
                    <div class="form-group">
                       <label for="password_lama">Password Lama</label>
                       <input type="password" name="password_lama" id="password_lama" class="form-control" placeholder="Isi hanya jika ingin mengubah password">
                   </div>
                    <div class="form-group">
                       <label for="password_baru">Password Baru</label>
                       <input type="password" name="password_baru" id="password_baru" class="form-control">
                   </div>
                    <div class="form-group">
                       <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                       <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control">
                   </div>
                   <hr>
                    <div class="form-group">
                        <label for="gambar">Ganti Foto Profil</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                        <?php if(!empty($data['gambar'])): ?>
                            <p class="mt-2">Foto saat ini: <br><img src="../../uploads/profile/<?= htmlspecialchars($data['gambar']) ?>" alt="Foto" width="100" class="img-thumbnail mt-2"></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="submit" class="btn btn-success">Update Profil</button>
                    <a href="profile.php" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>