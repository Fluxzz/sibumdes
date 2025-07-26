<?php
session_start();
require_once '../koneksi.php'; // Path dari auth/ ke root

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username_admin'], $_POST['password'])) {
        $username = $_POST['username_admin'];
        $password_input = $_POST['password'];

        $stmt = $db->prepare("SELECT id_admin, username_admin, nama_admin, password FROM tb_admin WHERE username_admin = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();
            
            // Verifikasi password yang diinput dengan hash dari database
            if (password_verify($password_input, $user_data['password'])) {
                // Jika password cocok, login berhasil
                $_SESSION['id'] = $user_data['id_admin'];
                $_SESSION['username'] = $user_data['username_admin'];
                $_SESSION['nama'] = $user_data['nama_admin'];
                
                // Arahkan ke halaman utama/dashboard
                header('Location: ../features/dashboard.php');
                exit();
            }
        }
    }
}

// Jika username tidak ditemukan atau password salah
header('Location: ../login.php?error=1');
exit();
?>