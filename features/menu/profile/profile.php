<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

// Ambil data admin yang sedang login
$id_admin = $_SESSION['id'];
$stmt = $db->prepare("SELECT * FROM tb_admin WHERE id_admin = ?");
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

$pageTitle = "Profil Pengguna";
$activeMenu = "profile"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Profil Pengguna</div>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<div class='alert alert-{$_SESSION['message']['type']}'>{$_SESSION['message']['text']}</div>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="../../uploads/profile/<?= htmlspecialchars($data['gambar'] ?: 'default.jpg') ?>" alt="Foto Profil" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 class="mt-3"><?= htmlspecialchars($data['nama_admin']) ?></h4>
                        <p class="text-muted">@<?= htmlspecialchars($data['username_admin']) ?></p>
                        <a href="editprofile.php" class="btn btn-primary btn-round">Edit Profil</a>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-striped">
                            <tr>
                                <td width="30%"><strong>ID Admin</strong></td>
                                <td>: <?= htmlspecialchars($data['id_admin']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>: <?= htmlspecialchars($data['nama_admin']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Username</strong></td>
                                <td>: <?= htmlspecialchars($data['username_admin']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>