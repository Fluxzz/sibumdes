<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $No = (int)$_POST['No'];
    $tanggal_terima = $_POST['tanggal_masuk'];
    $tanggal_surat = $_POST['tanggalsurat_suratmasuk'];
    $nomor_surat = trim($_POST['nomor_suratmasuk']);
    $pengirim = trim($_POST['pengirim']);
    $penerima_surat = trim($_POST['penerima_surat']);
    $disposisi = trim($_POST['disposisi']);
    $perihal = trim($_POST['perihal']);
    $kode = trim($_POST['kode']);
    $keterangan = trim($_POST['keterangan']);

    $stmt_get = $db->prepare("SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No = ?");
    $stmt_get->bind_param("i", $No);
    $stmt_get->execute();
    $data_lama = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $upload_dir = '../assets/uploads/surat_masuk/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $kolom_update = [];
    $params = [];
    $types = "";

    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
        if (!empty($data_lama['file_surat']) && file_exists($upload_dir . $data_lama['file_surat'])) @unlink($upload_dir . $data_lama['file_surat']);
        $nama_file_surat = "surat_masuk_" . $No . "_" . time() . ".pdf";
        if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $upload_dir . $nama_file_surat)) {
            $kolom_update[] = "file_surat = ?";
            $params[] = $nama_file_surat;
            $types .= "s";
        }
    }
    if (isset($_FILES['lampiran_foto']) && $_FILES['lampiran_foto']['error'] === UPLOAD_ERR_OK) {
        if (!empty($data_lama['lampiran_foto']) && file_exists($upload_dir . $data_lama['lampiran_foto'])) @unlink($upload_dir . $data_lama['lampiran_foto']);
        $ext = pathinfo($_FILES['lampiran_foto']['name'], PATHINFO_EXTENSION);
        $nama_foto = "lampiran_" . $No . "_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['lampiran_foto']['tmp_name'], $upload_dir . $nama_foto)) {
            $kolom_update[] = "lampiran_foto = ?";
            $params[] = $nama_foto;
            $types .= "s";
        }
    }

    $query = "UPDATE tb_arsip_surat_masuk SET tanggal_terima = ?, tanggal_surat = ?, nomor_surat = ?, pengirim = ?, penerima_surat = ?, disposisi = ?, perihal = ?, kode = ?, keterangan = ?";
    $base_params = [$tanggal_terima, $tanggal_surat, $nomor_surat, $pengirim, $penerima_surat, $disposisi, $perihal, $kode, $keterangan];
    $base_types = "sssssssss";
    
    if (!empty($kolom_update)) { $query .= ", " . implode(", ", $kolom_update); }
    $query .= " WHERE No = ?";
    $final_params = array_merge($base_params, $params);
    $final_params[] = $No;
    $final_types = $base_types . $types . "i";

    $stmt_update = $db->prepare($query);
    $stmt_update->bind_param($final_types, ...$final_params);
    
    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat masuk berhasil diupdate.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupdate data surat masuk.'];
    }
    $stmt_update->close();
    header('Location: ../datasuratmasuk.php');
    exit();
}
?>