<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $no_ktp = trim($_POST['no_ktp']);
    $jabatan = trim($_POST['jabatan']);
    $periode = trim($_POST['periode']);
    $alamat = trim($_POST['alamat']);
    $no_telp = trim($_POST['no_telp']);

    $nama_file_ktp = null;
    $nama_pas_foto = null;
    
    function handle_upload($file_key) {
        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/uploads/pengurus/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
            $new_filename = $file_key . "_" . uniqid() . "." . $ext;
            if (move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $new_filename)) {
                return $new_filename;
            }
        }
        return null;
    }

    $nama_file_ktp = handle_upload('foto_ktp');
    $nama_pas_foto = handle_upload('pas_foto');

    $stmt = $db->prepare("INSERT INTO tb_data_pengurus (nama, no_ktp, jabatan, periode, alamat, no_telp, foto_ktp, pas_foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nama, $no_ktp, $jabatan, $periode, $alamat, $no_telp, $nama_file_ktp, $nama_pas_foto);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pengurus berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menambahkan data pengurus.'];
    }
    $stmt->close();
    header('Location: ../datapengurus.php');
    exit();
}
?>