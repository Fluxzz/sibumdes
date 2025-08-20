<?php
session_start();
// Sesuaikan path ke file koneksi-mu
include '../../../koneksi.php';

// 1. Ambil dan validasi ID dari URL
// Kita menggunakan 'id' karena ini yang dikirim dari JavaScript di halaman daftar
$id_analisis = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_analisis > 0) {
    // 2. Gunakan prepared statement untuk keamanan
    $stmt = $db->prepare("DELETE FROM tb_analisis_konten WHERE id_analisis = ?");
    
    // Periksa apakah prepare berhasil
    if ($stmt) {
        // Bind parameter ke placeholder '?'
        $stmt->bind_param("i", $id_analisis);

        // 3. Eksekusi query
        if ($stmt->execute()) {
            // Periksa apakah ada baris yang terhapus
            if ($stmt->affected_rows > 0) {
                // Set session message untuk notifikasi sukses
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Data analisis berhasil dihapus!'];
            } else {
                // Jika ID tidak ditemukan di database
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus: Data analisis tidak ditemukan.'];
            }
        } else {
            // Jika eksekusi query gagal
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Terjadi kesalahan saat menghapus data.'];
        }
        // Tutup statement
        $stmt->close();

    } else {
        // Jika prepare statement gagal
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Terjadi kesalahan pada server.'];
    }

} else {
    // Jika ID tidak valid atau tidak ada
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID analisis tidak valid.'];
}

// 4. Redirect kembali ke halaman daftar
header("Location: analisis-konten.php");
exit(); // Wajib ada setelah header redirect

?>