<?php
session_start();
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $caption = mysqli_real_escape_string($db, $_POST['caption']);
    $link_konten = isset($_POST['link_konten']) ? $_POST['link_konten'] : "";
    $tanggal_posting = $_POST['tanggal_posting'];

    if (!empty($_POST['kategori_baru'])) {
        $kategori_baru = mysqli_real_escape_string($db, $_POST['kategori_baru']);
        mysqli_query($db, "INSERT INTO tb_kategori (nama_kategori) VALUES ('$kategori_baru')");
        $id_kategori = mysqli_insert_id($db);
    } else {
        $id_kategori = $_POST['id_kategori'];
    }

    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $folder = "../uploads/postingan/";

    if (!is_dir($folder)) mkdir($folder, 0755, true);

    $ext = pathinfo($gambar, PATHINFO_EXTENSION);
    $gambar_baru = 'post_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    move_uploaded_file($tmp_name, $folder . $gambar_baru);

    $query = mysqli_query($db, "INSERT INTO tb_postingan (caption, gambar, status, tanggal_posting, id_kategori, link_konten) VALUES ('$caption', '$gambar_baru', '$status', '$tanggal_posting', '$id_kategori', '$link_konten')");

    if ($query) {
        header("Location: data-postingan.php?msg=sukses");
        exit();
    } else {
        header("Location: tambah-postingan.php?msg=gagal");
        exit();
    }
} else {
    header("Location: tambah-postingan.php");
    exit();
}
?>
