<?php
session_start();
require_once '../../../koneksi.php';

// --- Ambil Data Penjualan ---
$stmt = $db->prepare("SELECT * FROM tb_data_penjualan_usaha ORDER BY id DESC");
$stmt->execute();
$penjualan_result = $stmt->get_result();

$pageTitle = "Data Penjualan Usaha";
$activeMenu = "usaha"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Penjualan Usaha</h4>
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
                                <th>ID</th>
                                <th>Produk</th>
                                <th>Paket Wisata</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $penjualan_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><?= htmlspecialchars($row['produk']) ?></td>
                                <td><?= htmlspecialchars($row['paket_wisata'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($row['jumlah']) ?></td>
                                <td>Rp <?= htmlspecialchars(number_format($row['harga'], 0, ',', '.')) ?></td>
                                <td>Rp <?= htmlspecialchars(number_format($row['total'], 0, ',', '.')) ?></td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="detail-penjualan.php?id=<?= $row['id'] ?>" class="btn btn-link btn-info" title="Detail"><i class="fa fa-eye"></i></a>
                                        <a href="edit-penjualan.php?id=<?= $row['id'] ?>" class="btn btn-link btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="../../../proses/hapus_penjualan.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin?')" class="btn btn-link btn-danger" title="Hapus"><i class="fa fa-trash"></i></a>
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