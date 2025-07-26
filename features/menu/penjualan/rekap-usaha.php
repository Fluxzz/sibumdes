<?php
session_start();
require_once '../../../koneksi.php';

// --- Ambil Data untuk Tabel ---
$penjualan_result = $db->query("SELECT id, tanggal, jumlah_pengunjung, total_pendapatan FROM tb_usaha_penjualan ORDER BY tanggal DESC");
$pengeluaran_result = $db->query("SELECT id, tanggal, keterangan, saldo_awal, pengeluaran, saldo_akhir FROM tb_usaha_pengeluaran ORDER BY tanggal DESC");

$pageTitle = "Rekap Usaha";
$activeMenu = "usaha"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><div class="card-title">Input Data Penjualan</div></div>
            <div class="card-body">
                <form action="../../../proses/simpan_penjualan.php" method="post">
                    <div class="form-group">
                        <label for="tanggal_penjualan">Tanggal</label>
                        <input type="date" name="tanggal_penjualan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_pengunjung">Jumlah Pengunjung</label>
                        <input type="number" name="jumlah_pengunjung" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="total_pendapatan">Total Pendapatan (Rp)</label>
                        <input type="number" name="total_pendapatan" class="form-control" required>
                    </div>
                    <div class="card-action">
                        <button type="submit" name="simpan_penjualan" class="btn btn-success">Simpan Penjualan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><div class="card-title">Input Data Pengeluaran Kas</div></div>
            <div class="card-body">
                <form action="../../../proses/simpan_pengeluaran.php" method="post">
                    <div class="form-group">
                        <label for="tanggal_pengeluaran">Tanggal</label>
                        <input type="date" name="tanggal_pengeluaran" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="pengeluaran">Jumlah Pengeluaran (Rp)</label>
                        <input type="number" name="pengeluaran" class="form-control" required>
                    </div>
                    <div class="card-action">
                        <button type="submit" name="simpan_pengeluaran" class="btn btn-warning">Simpan Pengeluaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Data Penjualan</h4></div>
            <div class="card-body">
                <?php
                // Notifikasi akan muncul di sini
                if (isset($_SESSION['message'])) {
                    echo "<div class='alert alert-{$_SESSION['message']['type']}'>{$_SESSION['message']['text']}</div>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jml Pengunjung</th>
                                <th>Total Pendapatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = $penjualan_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['tanggal']))) ?></td>
                                <td><?= htmlspecialchars($row['jumlah_pengunjung']) ?></td>
                                <td>Rp <?= htmlspecialchars(number_format($row['total_pendapatan'], 0, ',', '.')) ?></td>
                                <td>
                                    <a href="edit_penjualan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="../../../proses/hapus_penjualan.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</a>
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
<?php include '../../../partials/footer.php'; ?>