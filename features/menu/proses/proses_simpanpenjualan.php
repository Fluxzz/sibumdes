<?php
session_start();
require_once '../../koneksi.php';
require_once '../../auth/ceksession.php';

if (isset($_POST['simpan_penjualan'])) {
    $tanggal = $_POST['tanggal_penjualan'];
    $pengunjung = (int)$_POST['jumlah_pengunjung'];
    $pendapatan = (float)$_POST['total_pendapatan'];

    $stmt = $db->prepare("INSERT INTO tb_usaha_penjualan (tanggal, jumlah_pengunjung, total_pendapatan) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $tanggal, $pengunjung, $pendapatan);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil disimpan.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menyimpan data.'];
    }
    $stmt->close();
    header("Location: ../rekap-usaha.php");
    exit();
}
?>