<?php
session_start();
include '../koneksi/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Data Postingan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
  <h3 class="mb-4">Data Postingan</h3>

  <a href="tambah-postingan.php" class="btn btn-primary mb-3">+ Tambah Postingan</a>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Gambar</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>Caption</th>
        <th>Link Konten</th>
        <th>Tanggal Posting</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      $query = mysqli_query($db, "SELECT tb_postingan.*, tb_kategori.nama_kategori FROM tb_postingan
                                   JOIN tb_kategori ON tb_postingan.id_kategori = tb_kategori.id_kategori
                                   ORDER BY id_postingan DESC");
      while ($row = mysqli_fetch_assoc($query)) {
      ?>
      <tr>
        <td><?php echo $no++; ?></td>
        <td>
          <?php if (!empty($row['gambar']) && file_exists("../upload/" . $row['gambar'])) { ?>
            <img src="../upload/<?php echo htmlspecialchars($row['gambar']); ?>" width="100" alt="Gambar">
          <?php } else { ?>
            <span class="text-danger">Tidak Ada Gambar</span>
          <?php } ?>
        </td>
        <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
        <td><?php echo htmlspecialchars($row['status']); ?></td>
        <td><?php echo htmlspecialchars($row['caption']); ?></td>
        <td>
          <a href="<?php echo htmlspecialchars($row['link_konten']); ?>" target="_blank">
            <?php echo htmlspecialchars($row['link_konten']); ?>
          </a>
        </td>
        <td><?php echo htmlspecialchars(date('d M Y', strtotime($row['tanggal_posting']))); ?></td>
        <td>
          <a href="edit-postingan.php?id=<?php echo $row['id_postingan']; ?>" class="btn btn-warning btn-sm">Edit</a>
          <a href="hapus-postingan.php?id=<?php echo $row['id_postingan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
