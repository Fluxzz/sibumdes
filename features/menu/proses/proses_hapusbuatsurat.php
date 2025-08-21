<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if (isset($_GET['nomor_surat'])) {
    $nomor_surat = $_GET['nomor_surat'];

    $stmt_get = $db->prepare("SELECT kop_surat FROM buat_surat WHERE nomor_surat = ?");
    $stmt_get->bind_param("s", $nomor_surat);
    $stmt_get->execute();
    $data = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $stmt_delete = $db->prepare("DELETE FROM buat_surat WHERE nomor_surat = ?");
    $stmt_delete->bind_param("s", $nomor_surat);

    if ($stmt_delete->execute()) {
        $upload_dir = '../assets/images/kop_surat/';
        if (!empty($data['kop_surat']) && file_exists($upload_dir . $data['kop_surat'])) {
            @unlink($upload_dir . $data['kop_surat']);
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data template surat berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus data template surat.'];
    }
    $stmt_delete->close();

    header('Location: ../datasurat.php');
    exit();
}
?>