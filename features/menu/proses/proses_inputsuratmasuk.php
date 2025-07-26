<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal_terima = $_POST['tanggal_terima'];
    $tanggal_surat = $_POST['tanggal_surat'];
    // ... (ambil semua data POST lainnya) ...

    $upload_dir = '../assets/uploads/surat_masuk/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $nama_file_surat = null;
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
        $nama_file_surat = "surat_masuk_" . uniqid() . ".pdf";
        if (!move_uploaded_file($_FILES['file_surat']['tmp_name'], $upload_dir . $nama_file_surat)) {
            $nama_file_surat = null;
        }
    }

    $nama_lampiran_foto = null;
    if (isset($_FILES['lampiran_foto']) && $_FILES['lampiran_foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['lampiran_foto']['name'], PATHINFO_EXTENSION);
        $nama_lampiran_foto = "lampiran_" . uniqid() . "." . $ext;
        if (!move_uploaded_file($_FILES['lampiran_foto']['tmp_name'], $upload_dir . $nama_lampiran_foto)) {
            $nama_lampiran_foto = null;
        }
    }
    
    // Manual ID calculation is removed. Assuming 'No' is AUTO_INCREMENT.
    $stmt = $db->prepare("INSERT INTO tb_arsip_surat_masuk (tanggal_terima, tanggal_surat, ..., file_surat, lampiran_foto) VALUES (?, ?, ..., ?, ?)");
    // $stmt->bind_param("sssssssssss", $tanggal_terima, ..., $nama_file_surat, $nama_lampiran_foto);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat masuk berhasil disimpan!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menyimpan data surat masuk.'];
    }
    $stmt->close();
    header('Location: ../datasuratmasuk.php');
    exit();
}
?>