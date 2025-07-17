<?php
session_start();
include '../koneksi/koneksi.php';

// Ambil data username dan password dari form
$username = mysqli_real_escape_string($db, $_POST['username_admin']);
$password = $_POST['password'];

// Query untuk mendapatkan data user berdasarkan username
$stmt = $db->prepare("SELECT * FROM tb_admin WHERE username_admin=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Login tanpa hash (karena password di database belum di-hash)
    if ($password === $data['password']) {
        // Jika username dan password cocok
        $_SESSION['r3su'] = 'dmn';
        $_SESSION['id'] = $data['id_admin'];
        $_SESSION['username'] = $data['username_admin'];
        $_SESSION['nama'] = $data['nama_admin'];

        // Arahkan ke halaman utama/dashboard
        header('Location: ../');
        exit();
    } else {
        // Password salah
        echo "<center>Username atau Password anda salah<br><br><h3>Silahkan Ulangi</h3></center>";
        echo "<meta http-equiv='refresh' content='2;url=../login/'>";
    }
} else {
    // Username tidak ditemukan
    echo "<center>Username atau Password anda salah<br><br><h3>Silahkan Ulangi</h3></center>";
    echo "<meta http-equiv='refresh' content='2;url=../login/'>";
}
?>
