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
        header('Location: ../tambah-admin.php');
        exit();
    }

    // Cek apakah username sudah ada
    $cek = $db->prepare("SELECT id_admin FROM tb_admin WHERE username_admin = ?");
    $cek->bind_param("s", $username_admin);
    $cek->execute();
    $cek->store_result();
    if ($cek->num_rows > 0) {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Username sudah digunakan, silakan pilih yang lain.'];
        $cek->close();
        header('Location: ../tambah-admin.php');
        exit();
    }
    $cek->close();

    // Hash password
    $password_hash = password_hash($password_plain, PASSWORD_BCRYPT);

    // Proses upload gambar
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/profile/"; 
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $filename = "admin_" . time() . "." . $ext;
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $gambar = $filename;
        }
    }

    // Simpan ke database
    $stmt = $db->prepare("INSERT INTO tb_admin (nama_admin, username_admin, password, gambar) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_admin, $username_admin, $password_hash, $gambar);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Admin baru berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menambahkan admin baru.'];
    }
    $stmt->close();
    
    header('Location: ../data-admin.php');
    exit();
}
?>
