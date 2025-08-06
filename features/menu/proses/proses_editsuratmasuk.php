<?php
session_start();
require_once '../../../koneksi.php';

// Validasi metode dan No
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['No'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ID tidak ditemukan.'];
    header('Location: ../surat/datasuratmasuk.php');
    exit();
}

$No = intval($_POST['No']);
$tanggal_terima = $_POST['tanggal_terima'] ?? '';
$tanggal_surat = $_POST['tanggal_surat'] ?? '';
$nomor_surat = $_POST['nomor_surat'] ?? '';
$pengirim = $_POST['pengirim'] ?? '';
$penerima_surat = $_POST['penerima_surat'] ?? '';
$perihal = $_POST['perihal'] ?? '';
$kode = $_POST['kode'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$disposisi = $_POST['disposisi'] ?? '';

// Ambil file lama
$stmt_old = $db->prepare("SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No = ?");
$stmt_old->bind_param("i", $No);
$stmt_old->execute();
$result = $stmt_old->get_result();
$data_lama = $result->fetch_assoc();
$stmt_old->close();

if (!$data_lama) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Data surat tidak ditemukan.'];
    header('Location: ../surat/datasuratmasuk.php');
    exit();
}

$upload_dir = '../../uploads/surat_masuk/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

$file_surat_final = $data_lama['file_surat'];
$lampiran_foto_final = $data_lama['lampiran_foto'];

// Upload file_surat baru
if (!empty($_FILES['file_surat']['name'])) {
    $ext = strtolower(pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION));
    $new_name = 'surat_' . time() . '.' . $ext;
    $target = $upload_dir . $new_name;
    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $target)) {
        if (!empty($file_surat_final) && file_exists($upload_dir . $file_surat_final)) {
            unlink($upload_dir . $file_surat_final);
        }
        $file_surat_final = $new_name;
    }
}

// Upload lampiran_foto baru
if (!empty($_FILES['lampiran_foto']['name'])) {
    $ext = strtolower(pathinfo($_FILES['lampiran_foto']['name'], PATHINFO_EXTENSION));
    $new_name = 'lampiran_' . time() . '.' . $ext;
    $target = $upload_dir . $new_name;
    if (move_uploaded_file($_FILES['lampiran_foto']['tmp_name'], $target)) {
        if (!empty($lampiran_foto_final) && file_exists($upload_dir . $lampiran_foto_final)) {
            unlink($upload_dir . $lampiran_foto_final);
        }
        $lampiran_foto_final = $new_name;
    }
}

// Update data
$stmt = $db->prepare("UPDATE tb_arsip_surat_masuk SET 
    tanggal_terima = ?, 
    tanggal_surat = ?, 
    nomor_surat = ?, 
    pengirim = ?, 
    penerima_surat = ?, 
    perihal = ?, 
    kode = ?, 
    keterangan = ?, 
    disposisi = ?, 
    file_surat = ?, 
    lampiran_foto = ? 
    WHERE No = ?");
    
$stmt->bind_param("sssssssssssi", 
    $tanggal_terima,
    $tanggal_surat,
    $nomor_surat,
    $pengirim,
    $penerima_surat,
    $perihal,
    $kode,
    $keterangan,
    $disposisi,
    $file_surat_final,
    $lampiran_foto_final,
    $No
);

if ($stmt->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat berhasil diperbarui.'];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal memperbarui data surat.'];
}
$stmt->close();
header('Location: ../surat/datasuratmasuk.php');
exit();
?>
