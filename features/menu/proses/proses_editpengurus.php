<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $nama = trim($_POST['nama']);
    $no_ktp = trim($_POST['no_ktp']);
    $jabatan = trim($_POST['jabatan']);
    $periode = trim($_POST['periode']);
    $alamat = trim($_POST['alamat']);
    $no_telp = trim($_POST['no_telp']);
    
    $stmt_get = $db->prepare("SELECT foto_ktp, pas_foto FROM tb_data_pengurus WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $data_lama = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $nama_file_ktp = $data_lama['foto_ktp'];
    $nama_pas_foto = $data_lama['pas_foto'];
    
    function handle_upload($file_key, $current_filename, $id_pengurus) {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/uploads/pengurus/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            if (!empty($current_filename) && file_exists($upload_dir . $current_filename)) {
                @unlink($upload_dir . $current_filename);
            }
            $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
            $new_filename = $file_key . "_" . $id_pengurus . "_" . time() . "." . $ext;
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $new_filename)) {
                return $new_filename;
            }
        }
        return $current_filename;
    }
    
    $nama_file_ktp = handle_upload('foto_ktp', $nama_file_ktp, $id);
    $nama_pas_foto = handle_upload('pas_foto', $nama_pas_foto, $id);

    $stmt_update = $db->prepare("UPDATE tb_data_pengurus SET nama = ?, no_ktp = ?, jabatan = ?, periode = ?, alamat = ?, no_telp = ?, foto_ktp = ?, pas_foto = ? WHERE id = ?");
    $stmt_update->bind_param("ssssssssi", $nama, $no_ktp, $jabatan, $periode, $alamat, $no_telp, $nama_file_ktp, $nama_pas_foto, $id);
    
    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pengurus berhasil diupdate.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupdate data pengurus.'];
    }
    $stmt_update->close();
    header('Location: ../datapengurus.php');
    exit();
}
?>