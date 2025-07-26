<?php
session_start();
require_once '../../koneksi.php';
require_once '../../auth/ceksession.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $stmt = $db->prepare("DELETE FROM tb_usaha_penjualan WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus data.'];
    }
    $stmt->close();
    header("Location: ../rekap-usaha.php");
    exit();
}
?>