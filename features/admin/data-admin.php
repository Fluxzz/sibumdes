<?php
session_start();
require_once '../../koneksi.php';


// --- Ambil Semua Data Admin ---
// Kita tidak mengambil kolom password untuk keamanan
$stmt = $db->prepare("SELECT id_admin, nama_admin, username_admin, gambar FROM tb_admin ORDER BY id_admin DESC");
$stmt->execute();
$result = $stmt->get_result();

$pageTitle = "Manajemen Admin";
$activeMenu = "kelola_admin"; // Sesuaikan dengan menu Anda
?>

<?php include '../../partials/header.php'; ?>
<?php include '../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Pengguna Admin</h4>
                    <a href="tambah-admin.php" class="btn btn-primary btn-round ms-auto">
                        <i class="fa fa-plus"></i>
                        Tambah Admin
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php
                // Menampilkan notifikasi flash message
                if (isset($_SESSION['message'])) {
                    echo "<div class='alert alert-{$_SESSION['message']['type']}'>{$_SESSION['message']['text']}</div>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($data = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['id_admin']) ?></td>
                                    <td>
                                        <?php if(!empty($data['gambar'])): ?>
                                            <img src="../uploads/profile/<?= htmlspecialchars($data['gambar']) ?>" alt="Foto Profil" width="50" class="rounded-circle">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($data['nama_admin']) ?></td>
                                    <td><?= htmlspecialchars($data['username_admin']) ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="../menu/profile/editprofile.php?id=<?= $data['id_admin'] ?>" class="btn btn-link btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="/features/admin/proses/proses-hapus-admin.php?id=<?= $data['id_admin'] ?>" onclick="return confirm('Anda yakin ingin menghapus admin ini?')" class="btn btn-link btn-danger" title="Hapus"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$stmt->close();
include '../../partials/footer.php'; 
?>