<?php
session_start();
require_once '../../../koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nama_admin = trim($_POST['nama_admin']);
    $username_admin = trim($_POST['username_admin']);
    $password_plain = $_POST['password'];

    // Validasi dasar
    if (empty($nama_admin) || empty($username_admin) || empty($password_plain)) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Semua kolom wajib diisi.'];
        header('Location: ../tambah-admin.php'); // Kembali ke form
        exit();
    }

    // [PENTING] Membuat hash yang aman dari password
    $password_hash = password_hash($password_plain, PASSWORD_BCRYPT);

    // Simpan ke database menggunakan prepared statement
    $stmt = $db->prepare("INSERT INTO tb_admin (nama_admin, username_admin, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama_admin, $username_admin, $password_hash);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Admin baru berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menambahkan admin baru. Username mungkin sudah ada.'];
    }
    $stmt->close();
    
    // Ganti data-admin.php jika nama filenya berbeda
    header('Location: ../data-admin.php'); 
    exit();
}
?>