<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

$stmt = $db->prepare("SELECT * FROM tb_bagian ORDER BY id_bagian ASC");
$stmt->execute();
$result = $stmt->get_result();

$pageTitle = "Data Bagian";
$activeMenu = "bagian"; // Ganti atau sesuaikan dengan menu Anda
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Bagian</h4>
                    <a href="inputbagian.php" class="btn btn-primary btn-round ms-auto">
                        <i class="fa fa-plus"></i> Tambah Bagian
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<div class='alert alert-{$_SESSION['message']['type']}'>{$_SESSION['message']['text']}</div>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama Bagian</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>No HP</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($data = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['nama_bagian']) ?></td>
                                    <td><?= htmlspecialchars($data['username_admin_bagian']) ?></td>
                                    <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($data['no_hp_bagian']) ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="detail-bagian.php?id=<?= $data['id_bagian'] ?>" class="btn btn-link btn-info" title="Detail"><i class="fa fa-eye"></i></a>
                                            <a href="editbagian.php?id=<?= $data['id_bagian'] ?>" class="btn btn-link btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="../../../proses/hapus_bagian.php?id=<?= $data['id_bagian'] ?>" onclick="return confirm('Anda yakin?')" class="btn btn-link btn-danger" title="Hapus"><i class="fa fa-trash"></i></a>
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
include '../../../partials/footer.php'; 
?>