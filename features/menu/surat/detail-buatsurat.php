<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: databagian.php');
    exit();
}
$id_bagian = (int)$_GET['id'];

$stmt = $db->prepare("SELECT * FROM tb_bagian WHERE id_bagian = ?");
$stmt->bind_param("i", $id_bagian);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    header('Location: databagian.php');
    exit();
}

$pageTitle = "Detail Bagian";
$activeMenu = "bagian";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Detail Bagian: <?= htmlspecialchars($data['nama_lengkap']) ?></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <?php if(!empty($data['gambar'])): ?>
                            <img src="../../../uploads/bagian/<?= htmlspecialchars($data['gambar']) ?>" class="img-fluid rounded-circle" alt="Foto Profil" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <img src="../../../assets/img/default-avatar.png" class="img-fluid rounded-circle" alt="Foto Profil" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php endif; ?>
                        <h4 class="mt-3"><?= htmlspecialchars($data['nama_lengkap']) ?></h4>
                        <p class="text-muted"><?= htmlspecialchars($data['nama_bagian']) ?></p>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-striped">
                            <tr><td width="30%"><strong>ID Bagian</strong></td><td>: <?= htmlspecialchars($data['id_bagian']) ?></td></tr>
                            <tr><td><strong>Username</strong></td><td>: <?= htmlspecialchars($data['username_admin_bagian']) ?></td></tr>
                            <tr><td><strong>Tanggal Lahir</strong></td><td>: <?= htmlspecialchars(date('d F Y', strtotime($data['tanggal_lahir_bagian']))) ?></td></tr>
                            <tr><td><strong>Alamat</strong></td><td>: <?= htmlspecialchars($data['alamat']) ?></td></tr>
                            <tr><td><strong>No HP</strong></td><td>: <?= htmlspecialchars($data['no_hp_bagian']) ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-action">
                <a href="databagian.php" class="btn btn-danger">Kembali</a>
                <a href="editbagian.php?id=<?= $data['id_bagian'] ?>" class="btn btn-success">Edit</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>