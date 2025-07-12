<?php
session_start();
include "../koneksi/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: ../data_postingan.php");
    exit();
}

$id_postingan = intval($_GET['id']);

// Ambil nama file gambar sebelum dihapus
$getData = mysqli_query($db, "SELECT gambar FROM tb_postingan WHERE id_postingan = $id_postingan");
if (mysqli_num_rows($getData) > 0) {
    $data = mysqli_fetch_assoc($getData);
    $gambar = $data['gambar'];

    // Hapus file gambar jika ada
    if (!empty($gambar) && file_exists("../uploads/$gambar")) {
        unlink("../uploads/$gambar");
    }

    // Hapus dari database
    mysqli_query($db, "DELETE FROM tb_postingan WHERE id_postingan = $id_postingan");

    header("Location: ../data_postingan.php?msg=hapus-sukses");
    exit();
} else {
    header("Location: ../data_postingan.php?msg=data-tidak-ditemukan");
    exit();
}
?>
