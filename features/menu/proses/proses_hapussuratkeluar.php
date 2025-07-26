<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if (isset($_GET['id'])) {
    $No = (int)$_GET['id'];

    $stmt_get = $db->prepare("SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = ?");
    $stmt_get->bind_param("i", $No);
    $stmt_get->execute();
    $data = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $stmt_delete = $db->prepare("DELETE FROM tb_arsip_surat_keluar WHERE No = ?");
    $stmt_delete->bind_param("i", $No);

    if ($stmt_delete->execute()) {
        $upload_dir = '../assets/uploads/surat_keluar/';
        if (!empty($data['file_surat']) && file_exists($upload_dir . $data['file_surat'])) {
            @unlink($upload_dir . $data['file_surat']);
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat keluar berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus data surat keluar.'];
    }
    $stmt_delete->close();

    header('Location: ../datasuratkeluar.php');
    exit();
}
?>