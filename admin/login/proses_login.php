<?php
session_start();
include '../koneksi/koneksi.php'; 

// Cek apakah form dikirim dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dengan filter dan keamanan tambahan
    $username = trim($_POST['username_admin']);
    $password = trim($_POST['password']);

    // Validasi input sederhana (opsional, bisa dikembangkan lagi)
    if (empty($username) || empty($password)) {
        header("Location: ../login/?error=empty");
        exit();
    }

    // Menggunakan prepared statement untuk keamanan SQL Injection
    $stmt = $db->prepare("SELECT id_admin, username_admin, nama_admin, password FROM tb_admin WHERE username_admin = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Verifikasi password menggunakan password_verify
        if (password_verify($password, $data['password'])) {
            session_regenerate_id(true);
            $_SESSION['r3su'] = 'dmn';
            $_SESSION['id'] = $data['id_admin'];
            $_SESSION['username'] = $data['username_admin'];
            $_SESSION['nama'] = $data['nama_admin'];

            header('Location: ../');
            exit();
        } else {
            header("Location: ../login/?error=password");
            exit();
        }
    } else {
        header("Location: ../login/?error=username");
        exit();
    }
} else {
    // Jika halaman diakses tanpa POST, redirect ke login
    header("Location: ../login/");
    exit();
}
?>
