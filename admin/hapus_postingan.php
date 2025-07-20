<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah ID postingan diberikan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: data-postingan.php?error=no_id");
    exit();
}

$id_postingan = intval($_GET['id']);

try {
    // Ambil data gambar untuk dihapus dari folder
    $query_get = "SELECT id_postingan, gambar, caption FROM tb_postingan WHERE id_postingan = ?";
    $stmt_get = mysqli_prepare($db, $query_get);
    mysqli_stmt_bind_param($stmt_get, "i", $id_postingan);
    mysqli_stmt_execute($stmt_get);
    $result_get = mysqli_stmt_get_result($stmt_get);

    if (mysqli_num_rows($result_get) == 0) {
        mysqli_stmt_close($stmt_get);
        header("Location: data-postingan.php?error=not_found");
        exit();
    }

    $data = mysqli_fetch_assoc($result_get);
    $gambar = $data['gambar'];
    $caption = $data['caption'];
    mysqli_stmt_close($stmt_get);

    // Matikan autocommit untuk transaksi
    mysqli_autocommit($db, FALSE);

    // Hapus data dari database
    $query_delete = "DELETE FROM tb_postingan WHERE id_postingan = ?";
    $stmt_delete = mysqli_prepare($db, $query_delete);
    mysqli_stmt_bind_param($stmt_delete, "i", $id_postingan);
    $result_delete = mysqli_stmt_execute($stmt_delete);

    if (!$result_delete) {
        mysqli_stmt_close($stmt_delete);
        throw new Exception("Gagal menghapus data dari database: " . mysqli_error($db));
    }

    $affected_rows = mysqli_stmt_affected_rows($stmt_delete);
    mysqli_stmt_close($stmt_delete);

    if ($affected_rows == 0) {
        throw new Exception("Data tidak ditemukan atau sudah terhapus");
    }

    // Hapus file gambar jika ada
    $path_file = "../uploads/" . $gambar;
    if (!empty($gambar) && file_exists($path_file)) {
        if (!unlink($path_file)) {
            error_log("Warning: Gagal menghapus file gambar: " . $gambar);
        }
    }

    // Commit transaksi
    mysqli_commit($db);
    mysqli_autocommit($db, TRUE);

    // Redirect dengan pesan sukses
    header("Location: data-postingan.php?success=delete&caption=" . urlencode(substr($caption, 0, 50)));
    exit();

} catch (Exception $e) {
    // Rollback jika error
    mysqli_rollback($db);
    mysqli_autocommit($db, TRUE);

    // Log error
    error_log("Error hapus postingan: " . $e->getMessage());

    // Redirect ke halaman data dengan pesan error
    header("Location: data-postingan.php?error=delete_failed&message=" . urlencode($e->getMessage()));
    exit();
}
?>
