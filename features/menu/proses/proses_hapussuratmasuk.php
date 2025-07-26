<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if (isset($_GET['id'])) {
    $No = (int)$_GET['id'];

    $stmt_get = $db->prepare("SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No = ?");
    $stmt_get->bind_param("i", $No);
    $stmt_get->execute();
    $data = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $stmt_delete = $db->prepare("DELETE FROM tb_arsip_surat_masuk WHERE No = ?");
    $stmt_delete->bind_param("i", $No);

    if ($stmt_delete->execute()) {
        $upload_dir = '../assets/uploads/surat_masuk/';
        if (!empty($data['file_surat']) && file_exists($upload_dir . $data['file_surat'])) {
            @unlink($upload_dir . $data['file_surat']);
        }
        if (!empty($data['lampiran_foto']) && file_exists($upload_dir . $data['lampiran_foto'])) {
            @unlink($upload_dir . $data['lampiran_foto']);
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat masuk berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus data surat masuk.'];
    }
    $stmt_delete->close();

    header('Location: ../datasuratmasuk.php');
    exit();
}
?>