<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID tidak valid.'];
    header('Location: ../surat/datasuratkeluar.php');
    exit();
}
$id = (int)$_GET['id'];

// Ambil data file untuk dihapus
$stmt = $db->prepare("SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Data tidak ditemukan.'];
    header('Location: datasuratkeluar.php');
    exit();
}

// Coba hapus file jika ada
$filePath = '../../../uploads/surat_keluar/' . $data['file_surat'];
if (!empty($data['file_surat']) && is_file($filePath)) {
    if (!unlink($filePath)) {
        // Gagal menghapus file (opsional: log error)
        $_SESSION['message'] = ['type' => 'warning', 'text' => 'File tidak dapat dihapus, tetapi data tetap dihapus.'];
    }
}

// Hapus data dari database
$stmtDelete = $db->prepare("DELETE FROM tb_arsip_surat_keluar WHERE No = ?");
$stmtDelete->bind_param("i", $id);
if ($stmtDelete->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data berhasil dihapus.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus data.'];
}
$stmtDelete->close();

header('Location: ../surat/datasuratkeluar.php');
exit();
