<?php
session_start();
include "../koneksi/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: ../data_postingan.php");
    exit();
}

$id_postingan = intval($_GET['id']);
$query = mysqli_query($db, "
    SELECT * FROM tb_postingan WHERE id_postingan = $id_postingan
");

if (mysqli_num_rows($query) === 0) {
    echo "Data tidak ditemukan.";
    exit();
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Postingan</title>
    <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="nav-md">

<div class="container">
    <h3>Edit Postingan</h3>
    <form action="proses_edit_postingan.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="id_postingan" value="<?= $data['id_postingan'] ?>">

        <div class="form-group">
            <label>Gambar Lama:</label><br>
            <?php if (!empty($data['gambar'])): ?>
                <img src="../uploads/<?= $data['gambar'] ?>" style="width:100px;">
            <?php else: ?>
                Tidak ada gambar
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Ganti Gambar (Opsional)</label>
            <input type="file" name="gambar" class="form-control">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="draft" <?= $data['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="publish" <?= $data['status'] === 'publish' ? 'selected' : '' ?>>Publish</option>
            </select>
        </div>

        <div class="form-group">
            <label>Caption</label>
            <textarea name="caption" class="form-control"><?= htmlspecialchars($data['caption']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Link Konten</label>
            <input type="text" name="link_konten" value="<?= $data['link_konten'] ?>" class="form-control">
        </div>

        <div class="form-group">
            <label>Tanggal Posting</label>
            <input type="datetime-local" name="tanggal_posting" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal_posting'])) ?>" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>
