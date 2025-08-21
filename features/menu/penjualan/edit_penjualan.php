<?php
session_start();
include "../../../koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data_penjualan = null;

if ($id > 0) {
    $stmt = mysqli_prepare($db, "SELECT id, tanggal, jumlah_pengunjung, total_pendapatan FROM tb_usaha_penjualan WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data_penjualan = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$data_penjualan) {
        echo "<script>alert('Data penjualan tidak ditemukan!'); window.location.href='rekap-usaha.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID penjualan tidak valid!'); window.location.href='rekap-usaha.php';</script>";
    exit();
}

if (isset($_POST['update_penjualan'])) {
    $tanggal = $_POST['tanggal_penjualan'];
    $pengunjung = $_POST['jumlah_pengunjung'];
    $pendapatan = $_POST['total_pendapatan'];
    $id_update = $_POST['id_penjualan'];

    $stmt = mysqli_prepare($db, "UPDATE tb_usaha_penjualan SET tanggal = ?, jumlah_pengunjung = ?, total_pendapatan = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "sidi", $tanggal, $pengunjung, $pendapatan, $id_update);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data penjualan berhasil diperbarui!'); window.location.href='rekap-usaha.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data penjualan: " . mysqli_error($db) . "');</script>";
    }
    mysqli_stmt_close($stmt);
    exit();
}
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Penjualan /</span> Edit</h4>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Form Edit Data Penjualan</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="id_penjualan" value="<?= htmlspecialchars($data_penjualan['id']) ?>">

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_penjualan" class="form-control" value="<?= htmlspecialchars($data_penjualan['tanggal']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Pengunjung</label>
                        <input type="number" name="jumlah_pengunjung" class="form-control" value="<?= htmlspecialchars($data_penjualan['jumlah_pengunjung']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Pendapatan (Rp)</label>
                        <input type="number" name="total_pendapatan" class="form-control" value="<?= htmlspecialchars($data_penjualan['total_pendapatan']) ?>" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="update_penjualan" class="btn btn-primary">Update Penjualan</button>
                        <a href="rekap-usaha.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>