<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['id'];
    $nama = trim($_POST['nama_admin']);
    $username = trim($_POST['username_admin']);

    $stmt = $db->prepare("SELECT gambar FROM tb_admin WHERE id_admin = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $gambar_lama = $stmt->get_result()->fetch_assoc()['gambar'];
    $stmt->close();

    $nama_file_baru = $gambar_lama;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['gambar'];
        $allowed_types = ["image/jpeg", "image/jpg", "image/png"];
        $upload_dir = "../../uploads/profile/";

        if (in_array($file['type'], $allowed_types) && $file['size'] <= 2000000) {
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            if (!empty($gambar_lama) && file_exists($upload_dir . $gambar_lama)) {
                @unlink($upload_dir . $gambar_lama);
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $nama_file_baru = "admin_" . $id . "_" . time() . "." . $ext;
            
            if (!move_uploaded_file($file['tmp_name'], $upload_dir . $nama_file_baru)) {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupload gambar baru.'];
                header('Location: ../profile/profile.php');
                exit();
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'File tidak sesuai (JPG/PNG, maks 2MB).'];
            header('Location: ../profile/profile.php');
            exit();
        }
    }

    $stmt_update = $db->prepare("UPDATE tb_admin SET nama_admin = ?, username_admin = ?, gambar = ? WHERE id_admin = ?");
    $stmt_update->bind_param("sssi", $nama, $username, $nama_file_baru, $id);

    if ($stmt_update->execute()) {
        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Profil berhasil diperbarui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal memperbarui profil.'];
    }
    $stmt_update->close();
    
    header('Location: ../profile/profile.php');
    exit();
}
?>