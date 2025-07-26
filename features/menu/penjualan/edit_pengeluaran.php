<?php
session_start();
include "../koneksi/koneksi.php";

// Pastikan pengguna sudah login
// if (!isset($_SESSION['r3su'])) {
//     header('Location: ../index.php');
//     exit();
// }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data_pengeluaran = null;

if ($id > 0) {
    // Ambil data pengeluaran berdasarkan ID
    $stmt = mysqli_prepare($db, "SELECT id, tanggal, keterangan, saldo_awal, pengeluaran, saldo_akhir FROM tb_usaha_pengeluaran WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data_pengeluaran = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$data_pengeluaran) {
        echo "<script>alert('Data pengeluaran tidak ditemukan!'); window.location.href='rekap-usaha.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID pengeluaran tidak valid!'); window.location.href='rekap-usaha.php';</script>";
    exit();
}

// Proses Update Pengeluaran
if (isset($_POST['update_pengeluaran'])) {
    $tanggal = $_POST['tanggal_pengeluaran'];
    $keterangan = $_POST['keterangan'];
    $saldo_awal = $_POST['saldo_awal'];
    $pengeluaran = $_POST['pengeluaran'];
    $saldo_akhir = $_POST['saldo_akhir'];
    $id_update = $_POST['id_pengeluaran'];

    $stmt = mysqli_prepare($db, "UPDATE tb_usaha_pengeluaran SET tanggal = ?, keterangan = ?, saldo_awal = ?, pengeluaran = ?, saldo_akhir = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssiddi", $tanggal, $keterangan, $saldo_awal, $pengeluaran, $saldo_akhir, $id_update);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Data pengeluaran berhasil diperbarui!'); window.location.href='rekap-usaha.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data pengeluaran: " . mysqli_error($db) . "');</script>";
    }
    mysqli_stmt_close($stmt);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit Data Pengeluaran Kas</title>
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
                        <h3>Edit Data Pengeluaran Kas</h3>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="x_panel">
                    <div class="x_title">
                        <h2>Form Edit Pengeluaran Kas</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form method="post" class="form-horizontal form-label-left">
                            <input type="hidden" name="id_pengeluaran" value="<?= htmlspecialchars($data_pengeluaran['id']) ?>">
                            
                            <div class="form-group">
                                <label class="control-label col-md-3">Tanggal</label>
                                <div class="col-md-6">
                                    <input type="date" name="tanggal_pengeluaran" class="form-control" value="<?= htmlspecialchars($data_pengeluaran['tanggal']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Keterangan</label>
                                <div class="col-md-6">
                                    <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($data_pengeluaran['keterangan']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Saldo Awal</label>
                                <div class="col-md-6">
                                    <input type="number" name="saldo_awal" class="form-control" value="<?= htmlspecialchars($data_pengeluaran['saldo_awal']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Pengeluaran</label>
                                <div class="col-md-6">
                                    <input type="number" name="pengeluaran" class="form-control" value="<?= htmlspecialchars($data_pengeluaran['pengeluaran']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">Saldo Akhir</label>
                                <div class="col-md-6">
                                    <input type="number" name="saldo_akhir" class="form-control" value="<?= htmlspecialchars($data_pengeluaran['saldo_akhir']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">
                                    <button type="submit" name="update_pengeluaran" class="btn btn-success">Update Pengeluaran</button>
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