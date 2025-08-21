<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pemilik = trim($_POST['nama_pemilik']);
    $nama_usaha = trim($_POST['nama_usaha']);
    $kategori_usaha = trim($_POST['kategori_usaha']);
    $alamat = trim($_POST['alamat']);
    $nomor_telp = trim($_POST['nomor_telp']);
    $legalitas_usaha = trim($_POST['legalitas_usaha']);

    $kolom_sql = ['nama_pemilik', 'nama_usaha', 'kategori_usaha', 'alamat', 'nomor_telp', 'legalitas_usaha'];
    $params = [$nama_pemilik, $nama_usaha, $kategori_usaha, $alamat, $nomor_telp, $legalitas_usaha];
    $types = "ssssss";

    if (isset($_FILES['bukti_legalitas']) && $_FILES['bukti_legalitas']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/legalitas/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $nama_file_bukti = 'legalitas_' . uniqid() . '.pdf';
        if (move_uploaded_file($_FILES['bukti_legalitas']['tmp_name'], $upload_dir . $nama_file_bukti)) {
            $kolom_sql[] = 'bukti_legalitas';
            $params[] = $nama_file_bukti;
            $types .= "s";
        }
    }
    // ... (Logika untuk upload foto_kegiatan jika ada) ...

    $placeholders = implode(', ', array_fill(0, count($params), '?'));
    $query = sprintf("INSERT INTO tb_data_mitra (%s) VALUES (%s)", implode(', ', $kolom_sql), $placeholders);
    
    $stmt = $db->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data mitra berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menambahkan data mitra.'];
    }
    $stmt->close();
    header('Location: ../datamitra.php');
    exit();
}
?>