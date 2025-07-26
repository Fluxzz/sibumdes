<?php
session_start();
// [PATH UPDATE] Menggunakan path baru
require_once '../../../koneksi.php';

// Cek apakah ID ada dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID Postingan tidak valid.'];
    // [PATH UPDATE] Path redirect disesuaikan
    header('Location: data-postingan.php');
    exit();
}
$id_postingan = (int)$_GET['id'];

// 1. Ambil nama file gambar terlebih dahulu untuk dihapus dari server
$stmt_get = $db->prepare("SELECT gambar FROM tb_postingan WHERE id_postingan = ?");
$stmt_get->bind_param("i", $id_postingan);
$stmt_get->execute();
$data = $stmt_get->get_result()->fetch_assoc();
$stmt_get->close();

// 2. Hapus record dari database dengan aman
$stmt_delete = $db->prepare("DELETE FROM tb_postingan WHERE id_postingan = ?");
$stmt_delete->bind_param("i", $id_postingan);

if ($stmt_delete->execute()) {
    // 3. Jika record berhasil dihapus, hapus file gambar fisiknya
    if (!empty($data['gambar'])) {
        // [PATH UPDATE] Path untuk unlink disesuaikan
        $path_file = '../uploads/postingan/' . $data['gambar'];
        if (file_exists($path_file)) {
            @unlink($path_file);
        }
    }
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Postingan berhasil dihapus.'];
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus postingan.'];
}
$stmt_delete->close();

// 4. Redirect kembali ke halaman data postingan
// [PATH UPDATE] Path redirect disesuaikan
header('Location: data-postingan.php');
exit();
?>