<?php
session_start();
include "../koneksi/koneksi.php";

// Pastikan pengguna sudah login
// if (!isset($_SESSION['r3su'])) {
//     header('Location: ../index.php');
//     exit();
// }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data_penjualan = null;

if ($id > 0) {
    // Ambil data penjualan berdasarkan ID
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

// Proses Update Penjualan
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Data Penjualan</title>
    <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?php include("sidebarmenu.php"); ?>
            <?php include("header.php"); ?>

            <div class="right_col" role="main">
                <div class="page-title">
                    <div class="title_left">
                        <h3>Edit Data Penjualan</h3>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Form Edit Penjualan</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form method="post" class="form-horizontal form-label-left">
                            <input type="hidden" name="id_penjualan" value="<?= htmlspecialchars($data_penjualan['id']) ?>">
                            
                            <div class="form-group">
                                <label class="control-label col-md-3">Tanggal</label>
                                <div class="col-md-6">
                                    <input type="date" name="tanggal_penjualan" class="form-control" value="<?= htmlspecialchars($data_penjualan['tanggal']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Jumlah Pengunjung</label>
                                <div class="col-md-6">
                                    <input type="number" name="jumlah_pengunjung" class="form-control" value="<?= htmlspecialchars($data_penjualan['jumlah_pengunjung']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Total Pendapatan (Rp)</label>
                                <div class="col-md-6">
                                    <input type="number" name="total_pendapatan" class="form-control" value="<?= htmlspecialchars($data_penjualan['total_pendapatan']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="submit" name="update_penjualan" class="btn btn-success">Update Penjualan</button>
                                    <a href="rekap-usaha.php" class="btn btn-default">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../assets/build/js/custom.min.js"></script>
</body>
</html>