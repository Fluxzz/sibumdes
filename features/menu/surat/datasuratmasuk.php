<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

// --- Ambil Data Surat Masuk ---
$stmt = $db->prepare("SELECT No, nomor_surat, tanggal_terima, pengirim, perihal FROM tb_arsip_surat_masuk ORDER BY No DESC");
$stmt->execute();
$result = $stmt->get_result();

$pageTitle = "Arsip Surat Masuk";
$activeMenu = "surat";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Arsip Surat Masuk</h4>
                    <a href="inputsuratmasuk.php" class="btn btn-primary btn-round ms-auto">
                        <i class="fa fa-plus"></i>
                        Tambah Surat Masuk
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
                                <th>No Urut</th>
                                <th>No Surat</th>
                                <th>Tanggal Terima</th>
                                <th>Pengirim</th>
                                <th>Perihal</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($data = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['No']) ?></td>
                                    <td><?= htmlspecialchars($data['nomor_surat']) ?></td>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($data['tanggal_terima']))) ?></td>
                                    <td><?= htmlspecialchars($data['pengirim']) ?></td>
                                    <td><?= htmlspecialchars($data['perihal']) ?></td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="detail-suratmasuk.php?id=<?= $data['No'] ?>" class="btn btn-link btn-info" title="Detail"><i class="fa fa-eye"></i></a>
                                            <a href="editsuratmasuk.php?id=<?= $data['No'] ?>" class="btn btn-link btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                            <a href="../proses/proses_hapussuratmasuk.php?id=<?= $data['No'] ?>" onclick="return confirm('Anda yakin ingin menghapus surat ini?')" class="btn btn-link btn-danger" title="Hapus"><i class="fa fa-trash"></i></a>

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