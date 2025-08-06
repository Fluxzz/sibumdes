<?php
session_start();
require_once '../../../koneksi.php';

// Hitung total pendapatan
$pendapatan_query = $db->query("SELECT SUM(total_pendapatan) AS total FROM tb_usaha_penjualan");
$pendapatan = ($pendapatan_query && $pendapatan_query->num_rows > 0) ? $pendapatan_query->fetch_assoc()['total'] : 0;

// Hitung total pengeluaran
$pengeluaran_query = $db->query("SELECT SUM(pengeluaran) AS total FROM tb_usaha_pengeluaran");
$pengeluaran = ($pengeluaran_query && $pengeluaran_query->num_rows > 0) ? $pengeluaran_query->fetch_assoc()['total'] : 0;

// Hitung saldo saat ini
$saldo = $pendapatan - $pengeluaran;

// Ambil data untuk tabel
$penjualan_result = $db->query("SELECT id, tanggal, jumlah_pengunjung, total_pendapatan FROM tb_usaha_penjualan ORDER BY tanggal DESC");
$pengeluaran_result = $db->query("SELECT id, tanggal, keterangan, pengeluaran FROM tb_usaha_pengeluaran ORDER BY tanggal DESC");

$pageTitle = "Rekap Usaha";
$activeMenu = "usaha";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<!-- Panel Informasi Saldo -->
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <h5 class="mb-0"><strong>Saldo Terakhir:</strong> Rp <?= number_format($saldo, 0, ',', '.') ?></h5>
        </div>
    </div>
</div>

<div class="row">
    <!-- Form Input Penjualan -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Input Penjualan</h6>
            </div>
            <div class="card-body">
                <form action="../proses/proses_simpanpenjualan.php" method="POST">
                    <div class="form-group mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal_penjualan" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Jumlah Pengunjung</label>
                        <input type="number" name="jumlah_pengunjung" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Total Pendapatan (Rp)</label>
                        <input type="number" name="total_pendapatan" class="form-control" required>
                    </div>
                    <button type="submit" name="simpan_penjualan" class="btn btn-success w-100">Simpan Penjualan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Form Input Pengeluaran -->
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">Input Pengeluaran</h6>
            </div>
            <div class="card-body">
                <form action="../proses/proses_simpanpenjualan.php" method="POST">
                    <div class="form-group mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal_pengeluaran" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Jumlah Pengeluaran (Rp)</label>
                        <input type="number" name="pengeluaran" class="form-control" required>
                    </div>
                    <button type="submit" name="simpan_pengeluaran" class="btn btn-warning w-100">Simpan Pengeluaran</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Rekap Data Usaha -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <h6 class="mb-0">Rekap Data Usaha</h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="rekapTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="penjualan-tab" data-toggle="tab" href="#penjualan" role="tab">Data Penjualan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pengeluaran-tab" data-toggle="tab" href="#pengeluaran" role="tab">Data Pengeluaran</a>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="rekapTabsContent">
                    <!-- Tabel Penjualan -->
                    <div class="tab-pane fade show active" id="penjualan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah Pengunjung</th>
                                        <th>Total Pendapatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    while ($row = $penjualan_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                            <td><?= $row['jumlah_pengunjung'] ?></td>
                                            <td>Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                                            <td>
                                                <a href="edit_penjualan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                <a href="../proses/proses_hapuspenjualan.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel Pengeluaran -->
                    <div class="tab-pane fade" id="pengeluaran" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah Pengeluaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    while ($row = $pengeluaran_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                                            <td>Rp <?= number_format($row['pengeluaran'], 0, ',', '.') ?></td>
                                            <td>
                                                <a href="../proses/proses_hapuspenjualan.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>