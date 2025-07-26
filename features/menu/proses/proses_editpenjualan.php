<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $produk = trim($_POST['produk']);
    $jumlah = (int)$_POST['jumlah'];
    $harga = (int)$_POST['harga'];
    
    $stmt = $db->prepare("UPDATE tb_data_penjualan_usaha SET produk = ?, jumlah = ?, harga = ? WHERE id = ?");
    $stmt->bind_param("siii", $produk, $jumlah, $harga, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil diedit.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengedit data penjualan.'];
    }
    $stmt->close();
    header('Location: ../datapenjualanusaha.php');
    exit();
}
?>