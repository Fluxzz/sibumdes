<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt_get = $db->prepare("SELECT foto_ktp, pas_foto FROM tb_data_pengurus WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $data = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $stmt_delete = $db->prepare("DELETE FROM tb_data_pengurus WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        $upload_dir = '../assets/uploads/pengurus/';
        if (!empty($data['foto_ktp']) && file_exists($upload_dir . $data['foto_ktp'])) {
            @unlink($upload_dir . $data['foto_ktp']);
        }
        if (!empty($data['pas_foto']) && file_exists($upload_dir . $data['pas_foto'])) {
            @unlink($upload_dir . $data['pas_foto']);
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pengurus berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus data pengurus.'];
    }
    $stmt_delete->close();

    header('Location: ../datapengurus.php');
    exit();
}
?>