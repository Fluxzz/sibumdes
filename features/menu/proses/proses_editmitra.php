<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $nama_pemilik = trim($_POST['nama_pemilik']);
    $nama_usaha = trim($_POST['nama_usaha']);
    $kategori_usaha = trim($_POST['kategori_usaha']);
    $alamat = trim($_POST['alamat']);
    $nomor_telp = trim($_POST['nomor_telp']);
    $legalitas_usaha = trim($_POST['legalitas_usaha']);

    $stmt_get = $db->prepare("SELECT bukti_legalitas, foto_kegiatan FROM tb_data_mitra WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $data_lama = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $kolom_update = [];
    $params = [];
    $types = "";

    // Handle upload bukti legalitas
    if (isset($_FILES['bukti_legalitas']) && $_FILES['bukti_legalitas']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/legalitas/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        if (!empty($data_lama['bukti_legalitas']) && file_exists($upload_dir . $data_lama['bukti_legalitas'])) {
            @unlink($upload_dir . $data_lama['bukti_legalitas']);
        }
        $nama_file_bukti = "legalitas_" . $id . "_" . time() . ".pdf";
        if (move_uploaded_file($_FILES['bukti_legalitas']['tmp_name'], $upload_dir . $nama_file_bukti)) {
            $kolom_update[] = "bukti_legalitas = ?";
            $params[] = $nama_file_bukti;
            $types .= "s";
        }
    }

    // Handle upload foto kegiatan (multiple)
    if (isset($_FILES['foto_kegiatan']) && !empty(array_filter($_FILES['foto_kegiatan']['name']))) {
        $upload_dir = '../assets/uploads/kegiatan/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        if (!empty($data_lama['foto_kegiatan'])) {
            $old_fotos = explode(',', $data_lama['foto_kegiatan']);
            foreach ($old_fotos as $old_foto) {
                if (file_exists($upload_dir . trim($old_foto))) @unlink($upload_dir . trim($old_foto));
            }
        }
        $foto_paths = [];
        foreach ($_FILES['foto_kegiatan']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['foto_kegiatan']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['foto_kegiatan']['name'][$key], PATHINFO_EXTENSION);
                $new_foto_name = "kegiatan_" . $id . "_" . $key . "_" . time() . "." . $ext;
                if (move_uploaded_file($tmp_name, $upload_dir . $new_foto_name)) {
                    $foto_paths[] = $new_foto_name;
                }
            }
        }
        if (!empty($foto_paths)) {
            $kolom_update[] = "foto_kegiatan = ?";
            $params[] = implode(',', $foto_paths);
            $types .= "s";
        }
    }
    
    $query = "UPDATE tb_data_mitra SET nama_pemilik = ?, nama_usaha = ?, kategori_usaha = ?, alamat = ?, nomor_telp = ?, legalitas_usaha = ?";
    $base_params = [$nama_pemilik, $nama_usaha, $kategori_usaha, $alamat, $nomor_telp, $legalitas_usaha];
    $base_types = "ssssss";
    
    if (!empty($kolom_update)) { $query .= ", " . implode(", ", $kolom_update); }
    $query .= " WHERE id = ?";

    $final_params = array_merge($base_params, $params);
    $final_params[] = $id;
    $final_types = $base_types . $types . "i";

    $stmt_update = $db->prepare($query);
    $stmt_update->bind_param($final_types, ...$final_params);
    
    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data mitra berhasil diupdate.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupdate data mitra.'];
    }
    $stmt_update->close();
    header('Location: ../datamitra.php');
    exit();
}
?>