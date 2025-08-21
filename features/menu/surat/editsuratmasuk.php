<?php
session_start();
require_once '../../../koneksi.php';

if (!isset($_POST['No'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ID tidak ditemukan.'];
    header('Location: ../surat/datasuratmasuk.php');
    exit();
}

$No = (int)$_POST['No'];
$asal_surat = $_POST['asal_surat'];
$tanggal_surat = $_POST['tanggal_surat'];
$tanggal_diterima = $_POST['tanggal_diterima'];
$nomor_surat = $_POST['nomor_surat'];
$perihal = $_POST['perihal'];
$keterangan = $_POST['keterangan'];

// Ambil data lama
$stmt_old = $db->prepare("SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No = ?");
$stmt_old->bind_param("i", $No);
$stmt_old->execute();
$data_lama = $stmt_old->get_result()->fetch_assoc();
$stmt_old->close();

$upload_dir = '../../uploads/surat_masuk/';
$file_surat_baru = $_FILES['file_surat']['name'];
$lampiran_foto_baru = $_FILES['lampiran_foto']['name'];

$file_surat_final = $data_lama['file_surat'];
$lampiran_foto_final = $data_lama['lampiran_foto'];

// === Upload File Surat (PDF) ===
if (!empty($file_surat_baru)) {
    $ext = pathinfo($file_surat_baru, PATHINFO_EXTENSION);
    $new_name = 'surat_' . time() . '.' . $ext;
    $target_file = $upload_dir . $new_name;

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $target_file)) {
        // Hapus file lama
        if (!empty($file_surat_final) && file_exists($upload_dir . $file_surat_final)) {
            @unlink($upload_dir . $file_surat_final);
        }
        $file_surat_final = $new_name;
    }
}

// === Upload Lampiran Foto ===
if (!empty($lampiran_foto_baru)) {
    $ext = pathinfo($lampiran_foto_baru, PATHINFO_EXTENSION);
    $new_name = 'lampiran_' . time() . '.' . $ext;
    $target_file = $upload_dir . $new_name;

    if (move_uploaded_file($_FILES['lampiran_foto']['tmp_name'], $target_file)) {
        // Hapus lampiran lama
        if (!empty($lampiran_foto_final) && file_exists($upload_dir . $lampiran_foto_final)) {
            @unlink($upload_dir . $lampiran_foto_final);
        }
        $lampiran_foto_final = $new_name;
    }
}

// === Update ke Database ===
$stmt_update = $db->prepare("UPDATE tb_arsip_surat_masuk SET 
    asal_surat = ?, 
    tanggal_surat = ?, 
    tanggal_diterima = ?, 
    nomor_surat = ?, 
    perihal = ?, 
    keterangan = ?, 
    file_surat = ?, 
    lampiran_foto = ? 
    WHERE No = ?");

$stmt_update->bind_param("ssssssssi", 
    $asal_surat, 
    $tanggal_surat, 
    $tanggal_diterima, 
    $nomor_surat, 
    $perihal, 
    $keterangan, 
    $file_surat_final, 
    $lampiran_foto_final, 
    $No
);

if ($stmt_update->execute()) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat masuk berhasil diperbarui.'];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal memperbarui data surat masuk.'];
}

$stmt_update->close();
header('Location: ../surat/datasuratmasuk.php');
exit();
?>
