<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tgl_keluar = $_POST['tanggal_keluar'];
    $nomor_surat = trim($_POST['nomor_surat']);
    $penerima = trim($_POST['penerima']);
    $perihal = trim($_POST['perihal']);
    $kode = trim($_POST['kode']);
    $keterangan = trim($_POST['keterangan']);
    
    $nama_file = null;
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/surat_keluar/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $nama_file = "surat_keluar_" . uniqid() . ".pdf";
        if (!move_uploaded_file($_FILES['file_surat']['tmp_name'], $upload_dir . $nama_file)) {
            $nama_file = null; // Gagal upload
        }
    }
    
    // Manual ID calculation is removed. Assuming 'No' is AUTO_INCREMENT.
    $stmt = $db->prepare("INSERT INTO tb_arsip_surat_keluar (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $tgl_keluar, $nomor_surat, $penerima, $perihal, $kode, $keterangan, $nama_file);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat keluar berhasil disimpan!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menyimpan data surat keluar.'];
    }
    $stmt->close();
    header('Location: ../datasuratkeluar.php');
    exit();
}
?>