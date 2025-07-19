<!DOCTYPE html>
<?php
session_start();
include "/sibumdes/koneksi/koneksi.php";
?>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Postingan - Arsip Surat Desa Candirejo Borobudur</title>

  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="../assets/build/css/custom.min.css" rel="stylesheet" />
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include("sidebarmenu.php"); ?>
      <?php include("header.php"); ?>


      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Data Postingan</h3>
            </div>
            <div class="title_right">
              <div class="pull-right">
                <a href="tambah-postingan.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah Postingan</a>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_content">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Status</th>
                        <th>Kategori</th>
                        <th>Caption</th>
                        <th>Link Konten</th>
                        <th>Tanggal Posting</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $query = mysqli_query($db, "
                        SELECT p.*, k.nama_kategori 
                        FROM tb_postingan p 
                        LEFT JOIN tb_kategori k ON p.id_kategori = k.id_kategori 
                        ORDER BY p.tanggal_posting DESC
                      ");
                      $no = 1;
                      while ($row = mysqli_fetch_assoc($query)) {
                        // Tampilkan gambar thumbnail kecil
                        $gambar = $row['gambar'] ? "<img src='../uploads/{$row['gambar']}' alt='gambar' style='width:80px; height:auto;'>" : "No Image";

                        // Status (buat huruf kapital awal)
                        $status = ucfirst($row['status']);

                        // Caption singkat (maks 50 karakter)
                        $caption = strlen($row['caption']) > 50 ? substr($row['caption'], 0, 50) . '...' : $row['caption'];

                        // Link konten (tampilkan hyperlink jika ada)
                        $link_konten = $row['link_konten'] ? "<a href='{$row['link_konten']}' target='_blank'>Link</a>" : "-";

                        // Format tanggal posting
                        $tgl_posting = date('d M Y, H:i', strtotime($row['tanggal_posting']));

                        echo "<tr>
                          <td>{$no}</td>
                          <td>{$gambar}</td>
                          <td>{$status}</td>
                          <td>{$row['nama_kategori']}</td>
                          <td>{$caption}</td>
                          <td>{$link_konten}</td>
                          <td>{$tgl_posting}</td>
                          <td>
                            <a href='edit_postingan.php?id={$row['id_postingan']}' class='btn btn-warning btn-xs'><i class='fa fa-pencil'></i></a> 
                            <a href='hapus_postingan.php?id={$row['id_postingan']}' onclick='return confirm(\"Yakin ingin menghapus postingan ini?\")' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>
                          </td>
                        </tr>";
                        $no++;
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <footer>
        <div class="pull-right">
          Arsip Surat Desa Candirejo Borobudur
        </div>
        <div class="clearfix"></div>
      </footer>
    </div>
  </div>

  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../assets/build/js/custom.min.js"></script>
</body>

</html>
