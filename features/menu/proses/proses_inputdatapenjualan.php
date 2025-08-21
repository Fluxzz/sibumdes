<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_produk = trim($_POST['produk']);
    $pilihan_paket_wisata = ($jenis_produk == 'Paket Wisata') ? trim($_POST['pilihan_paket_wisata']) : null;
    $jumlah = (int)$_POST['jumlah'];
    $harga = (int)$_POST['harga'];

    $stmt = $db->prepare("INSERT INTO tb_data_penjualan_usaha (produk, paket_wisata, jumlah, harga) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $jenis_produk, $pilihan_paket_wisata, $jumlah, $harga);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil disimpan!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menyimpan data penjualan.'];
    }
    $stmt->close();
    header('Location: ../datapenjualanusaha.php');
    exit();
}
?>