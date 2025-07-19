<?php
session_start();
include "../koneksi/koneksi.php";

if (isset($_POST['submit'])) {
    $id_kategori = $_POST['id_kategori'];
    $status = $_POST['status'];
    $caption = $_POST['caption'];
    $link_konten = $_POST['link_konten'];

    // Upload gambar
    $gambar = '';
    if ($_FILES['gambar']['name'] != '') {
        $gambar = uniqid() . '-' . basename($_FILES['gambar']['name']);
        $upload_dir = "../uploads/";
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    // Query simpan
    $query = "INSERT INTO tb_postingan (id_kategori, gambar, status, caption, link_konten, tanggal_posting) 
              VALUES ('$id_kategori', '$gambar', '$status', '$caption', '$link_konten', NOW())";

    if (!mysqli_query($db, $query)) {
        die("Query gagal: " . mysqli_error($db));
    }

    header("Location: data-postingan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Postingan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h2 class="mb-4">Tambah Postingan</h2>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="id_kategori" class="form-label">Kategori</label>
            <select name="id_kategori" id="id_kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="1">Informasi</option>
                <option value="2">Edukasi</option>
                <option value="3">Pengumuman</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Upload Gambar</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="draft">Draft</option>
                <option value="publish">Publish</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <textarea name="caption" id="caption" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="link_konten" class="form-label">Link Konten</label>
            <input type="url" name="link_konten" id="link_konten" class="form-control">
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
        <a href="data-postingan.php" class="btn btn-secondary">Kembali</a>
    </form>
</body>
</html>
