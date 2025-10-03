<?php
session_start();
require_once '../../../koneksi.php';

// pastikan ada id yang dikirim
if (isset($_GET['id'])) {
    $id_admin = intval($_GET['id']);

    // ambil data admin untuk cek apakah ada gambar
    $stmt = $db->prepare("SELECT gambar FROM tb_admin WHERE id_admin = ?");
    $stmt->bind_param("i", $id_admin);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if ($admin) {
        // hapus file gambar jika ada
        if (!empty($admin['gambar'])) {
            $file_path = "../uploads/profile/" . $admin['gambar'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // hapus data dari database
        $delete = $db->prepare("DELETE FROM tb_admin WHERE id_admin = ?");
        $delete->bind_param("i", $id_admin);
        if ($delete->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Admin berhasil dihapus.'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus admin.'];
        }
        $delete->close();
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Admin tidak ditemukan.'];
    }
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Parameter tidak valid.'];
}

header('Location: ../data-admin.php');
exit();
?>
