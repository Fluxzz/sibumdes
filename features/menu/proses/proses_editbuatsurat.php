<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nomor_surat_lama'])) {
    $nomor_surat_lama = trim($_POST['nomor_surat_lama']);
    $nomor_surat_baru = trim($_POST['nomor_surat']);
    $lampiran = trim($_POST['lampiran']);
    $perihal = trim($_POST['perihal']);
    $tanggal = trim($_POST['tanggal']);
    $kepada = trim($_POST['kepada']);
    $pembuka = trim($_POST['pembuka']);
    $isi = trim($_POST['isi']);
    $penutup = trim($_POST['penutup']);
    $penandatangan_surat = trim($_POST['penandatangan_surat']);

    $stmt_get = $db->prepare("SELECT kop_surat FROM buat_surat WHERE nomor_surat = ?");
    $stmt_get->bind_param("s", $nomor_surat_lama);
    $stmt_get->execute();
    $data_lama = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $file_update_sql = "";
    $params = [];
    $types = "";

    if (isset($_FILES['kop_surat']) && $_FILES['kop_surat']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['kop_surat'];
        $allowed = ['image/jpeg', 'image/png'];
        if (in_array($file['type'], $allowed) && $file['size'] <= 2000000) {
            $upload_dir = '../assets/images/kop_surat/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            if (!empty($data_lama['kop_surat']) && file_exists($upload_dir . $data_lama['kop_surat'])) {
                @unlink($upload_dir . $data_lama['kop_surat']);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = "kop_" . time() . "." . $ext;
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
                $file_update_sql = ", kop_surat = ?";
                $params[] = $new_filename;
                $types .= "s";
            }
        }
    }
    
    $query = "UPDATE buat_surat SET nomor_surat = ?, lampiran = ?, perihal = ?, tanggal = ?, kepada = ?, pembuka = ?, isi = ?, penutup = ?, penandatangan_surat = ? $file_update_sql WHERE nomor_surat = ?";
    $base_params = [$nomor_surat_baru, $lampiran, $perihal, $tanggal, $kepada, $pembuka, $isi, $penutup, $penandatangan_surat];
    $base_types = "sssssssss";

    $final_params = array_merge($base_params, $params);
    $final_params[] = $nomor_surat_lama;
    $final_types = $base_types . $types . "s";
    
    $stmt_update = $db->prepare($query);
    $stmt_update->bind_param($final_types, ...$final_params);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat berhasil diupdate.'];
        header('Location: ../datasurat.php');
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupdate data surat.'];
        header('Location: ../editsurat.php?nomor_surat=' . urlencode($nomor_surat_lama));
    }
    $stmt_update->close();
    exit();
}
?>