<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: databagian.php');
    exit();
}
$id = (int)$_GET['id'];

$stmt = $db->prepare("SELECT * FROM tb_bagian WHERE id_bagian = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    header('Location: databagian.php');
    exit();
}

$pageTitle = "Edit Bagian";
$activeMenu = "bagian";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Edit Bagian</div>
            </div>
            <div class="card-body">
                <form action="../../../proses/edit_bagian.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_bagian" value="<?= $data['id_bagian'] ?>">
                    <div class="form-group">
                        <label>Nama Bagian</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama_bagian']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="username_admin_bagian">Username</label>
                        <input type="text" name="username_admin_bagian" id="username_admin_bagian" class="form-control" value="<?= htmlspecialchars($data['username_admin_bagian']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password_bagian">Password Baru</label>
                        <input type="password" name="password_bagian" id="password_bagian" class="form-control" placeholder="Isi hanya jika ingin mengubah password">
                    </div>
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir_bagian">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir_bagian" id="tanggal_lahir_bagian" class="form-control" value="<?= htmlspecialchars($data['tanggal_lahir_bagian']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2" required><?= htmlspecialchars($data['alamat']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="no_hp_bagian">No HP</label>
                        <input type="text" name="no_hp_bagian" id="no_hp_bagian" class="form-control" value="<?= htmlspecialchars($data['no_hp_bagian']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Ganti Foto</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                        <?php if(!empty($data['gambar'])): ?>
                            <p class="mt-2">Foto saat ini: <br><img src="../../../uploads/bagian/<?= htmlspecialchars($data['gambar']) ?>" alt="Foto" width="100" class="img-thumbnail mt-2"></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="databagian.php" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>