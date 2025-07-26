<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $No = (int)$_POST['No'];
    $tgl_keluar = $_POST['tanggal_keluar'];
    $kode = trim($_POST['kode']);
    $nomor_surat = trim($_POST['nomor_surat']);
    $penerima = trim($_POST['penerima']);
    $perihal = trim($_POST['perihal']);
    $keterangan = trim($_POST['keterangan']);

    $stmt_get = $db->prepare("SELECT file_surat FROM tb_arsip_surat_keluar WHERE No = ?");
    $stmt_get->bind_param("i", $No);
    $stmt_get->execute();
    $data_lama = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $file_update_sql = "";
    $params = [];
    $types = "";

    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/surat_keluar/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        if (!empty($data_lama['file_surat']) && file_exists($upload_dir . $data_lama['file_surat'])) {
            @unlink($upload_dir . $data_lama['file_surat']);
        }
        $new_filename = "surat_keluar_" . $No . "_" . time() . ".pdf";
        if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $upload_dir . $new_filename)) {
            $file_update_sql = ", file_surat = ?";
            $params[] = $new_filename;
            $types .= "s";
        }
    }
    
    $query = "UPDATE tb_arsip_surat_keluar SET tanggal_keluar = ?, kode = ?, nomor_surat = ?, penerima = ?, perihal = ?, keterangan = ? $file_update_sql WHERE No = ?";
    $base_params = [$tgl_keluar, $kode, $nomor_surat, $penerima, $perihal, $keterangan];
    $base_types = "ssssss";

    $final_params = array_merge($base_params, $params);
    $final_params[] = $No;
    $final_types = $base_types . $types . "i";

    $stmt_update = $db->prepare($query);
    $stmt_update->bind_param($final_types, ...$final_params);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data surat keluar berhasil diupdate.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupdate data surat keluar.'];
    }
    $stmt_update->close();
    
    header('Location: ../datasuratkeluar.php');
    exit();
}
?>