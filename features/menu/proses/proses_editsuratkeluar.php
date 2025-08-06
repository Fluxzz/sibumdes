<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Permintaan tidak valid.'];
    header('Location: ../surat/datasuratkeluar.php');
    exit();
}

$No            = intval($_POST['No']);
$tanggal       = $_POST['tanggal_keluar'];
$nomor_surat   = $_POST['nomor_surat'];
$penerima      = $_POST['penerima'];
$perihal       = $_POST['perihal'];
$kode          = $_POST['kode'];
$keterangan    = $_POST['keterangan'];
$fileBaru      = $_FILES['file_surat']['name'] ?? null;

// Ambil nama file lama
$queryLama = $db->prepare("SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = ?");
$queryLama->bind_param("i", $No);
$queryLama->execute();
$hasil = $queryLama->get_result();
$dataLama = $hasil->fetch_assoc();
$queryLama->close();

$fileLama = $dataLama['file_surat'];
$targetDir = '../../uploads/surat_keluar/';
$fileName = $fileLama; // default tetap file lama

if (!empty($fileBaru)) {
    // Hapus file lama jika ada
    if (!empty($fileLama) && file_exists($targetDir . $fileLama)) {
        unlink($targetDir . $fileLama);
    }

    // Upload file baru
    $ext = strtolower(pathinfo($fileBaru, PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'File harus berupa PDF.'];
        header("Location: ../surat/editsuratkeluar.php?id=$No");
        exit();
    }

    $fileName = uniqid() . '_' . basename($fileBaru);
    $targetFile = $targetDir . $fileName;

    if (!move_uploaded_file($_FILES['file_surat']['tmp_name'], $targetFile)) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal mengupload file surat.'];
        header("Location: ../surat/editsuratkeluar.php?id=$No");
        exit();
    }
}

// Update data ke database
$stmt = $db->prepare("UPDATE tb_arsip_surat_keluar 
    SET tanggal_keluar=?, nomor_surat=?, penerima=?, perihal=?, kode=?, keterangan=?, file_surat=? 
    WHERE No=?");

$stmt->bind_param("sssssssi", $tanggal, $nomor_surat, $penerima, $perihal, $kode, $keterangan, $fileName, $No);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat berhasil diperbarui.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal memperbarui data surat.'];
}
$stmt->close();

header('Location: ../surat/datasuratkeluar.php');
exit();
