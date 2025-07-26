<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Ambil nama file foto yang akan dihapus
    $stmt_get = $db->prepare("SELECT foto FROM tb_data_pengunjung WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $data = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    // Hapus record dari database
    $stmt_delete = $db->prepare("DELETE FROM tb_data_pengunjung WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        // Jika berhasil, hapus file foto dari server
        $upload_dir = '../assets/uploads/pengunjung/';
        if (!empty($data['foto']) && file_exists($upload_dir . $data['foto'])) {
            @unlink($upload_dir . $data['foto']);
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pengunjung berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus data pengunjung.'];
    }
    $stmt_delete->close();

    header('Location: ../datapengunjung.php');
    exit();
}
?>