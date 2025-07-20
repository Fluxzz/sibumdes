<!DOCTYPE html>
<?php
session_start();
include '../koneksi/koneksi.php';

// Cek apakah ada parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: data-postingan.php?error=no_id");
    exit();
}

$id_postingan = intval($_GET['id']);

// Query untuk mendapatkan data postingan dan nama kategori
$query = mysqli_query($db, "SELECT p.*, k.nama_kategori 
                            FROM tb_postingan p 
                            LEFT JOIN tb_kategori k ON p.id_kategori = k.id_kategori 
                            WHERE p.id_postingan = '$id_postingan'");

$data_postingan = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data_postingan) {
    echo "<h3>Data tidak ditemukan!</h3>";
    exit();
}
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Detail Data Postingan</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
  
  <style>
    .badge-status {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .status-draft { 
        background-color: #6c757d; 
        color: white; 
    }
    .status-publish { 
        background-color: #28a745; 
        color: white; 
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">

      <!-- Profile and Sidebarmenu -->
      <?php include("sidebarmenu.php"); ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php include("header.php"); ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Detail Data Postingan</h3>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Detail Postingan <small>Detail Data Postingan</small></h2>
                  <div class="clearfix"></div>
                </div>
                
                <div class="x_content">
                  <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                    <div class="profile_img">
                      <div id="crop-avatar">
                        <!-- Current post image -->
                        <?php if (!empty($data_postingan['gambar']) && file_exists("uploads/" . $data_postingan['gambar'])): ?>
                        <img class="img-responsive avatar-view"
                          src="uploads/<?php echo htmlspecialchars($data_postingan['gambar']); ?>" alt="Post Image">
                        <?php else: ?>
                        <img class="img-responsive avatar-view" src="../img/default-image.png" alt="Default Image">
                        <?php endif; ?>
                      </div>
                    </div>
                    <h3 align="center">
                      <span class="badge-status <?php echo ($data_postingan['status'] ?? '') == 'publish' ? 'status-publish' : 'status-draft'; ?>">
                        <?php echo strtoupper($data_postingan['status'] ?? 'DRAFT'); ?>
                      </span>
                    </h3>
                    <br />
                  </div>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="profile_title">
                      <div class="col-md-6">
                        <h2>Detail Postingan</h2>
                      </div>
                    </div>
                    <div class="x_content">
                      <table class="table table-striped">
                        <tbody>
                          <tr>
                            <td width="50%">ID Postingan</td>
                            <td><?php echo htmlspecialchars($data_postingan['id_postingan']); ?></td>
                          </tr>
                          <tr>
                            <td>Kategori</td>
                            <td><?php echo htmlspecialchars($data_postingan['nama_kategori'] ?? 'Tidak diketahui'); ?></td>
                          </tr>
                          <tr>
                            <td>Status</td>
                            <td>
                              <span class="badge-status <?php echo ($data_postingan['status'] ?? '') == 'publish' ? 'status-publish' : 'status-draft'; ?>">
                                <?php echo strtoupper($data_postingan['status'] ?? 'DRAFT'); ?>
                              </span>
                            </td>
                          </tr>
                          <tr>
                            <td>Tanggal Posting</td>
                            <td><?php echo htmlspecialchars($data_postingan['tanggal_posting'] ?? '-'); ?></td>
                          </tr>
                          <tr>
                            <td>Link Konten</td>
                            <td>
                              <?php if (!empty($data_postingan['link_konten'])): ?>
                                <a href="<?php echo htmlspecialchars($data_postingan['link_konten']); ?>" target="_blank">
                                  <?php echo htmlspecialchars($data_postingan['link_konten']); ?>
                                </a>
                              <?php else: ?>
                                <em>Tidak ada</em>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <tr>
                            <td>Caption</td>
                            <td><?php echo nl2br(htmlspecialchars($data_postingan['caption'] ?? '-')); ?></td>
                          </tr>
                          <tr>
                            <td>Nama File Gambar</td>
                            <td><?php echo htmlspecialchars($data_postingan['gambar'] ?? '-'); ?></td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="text-right">
                        <a href="data-postingan.php" class="btn btn-success">
                          <span class="glyphicon glyphicon-arrow-left"></span> Kembali
                        </a>
                        <a href="edit_postingan.php?id=<?php echo $data_postingan['id_postingan']; ?>" class="btn btn-warning">
                          <span class="glyphicon glyphicon-edit"></span> Edit
                        </a>
                        <a href="hapus_postingan.php?id=<?php echo $data_postingan['id_postingan']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Yakin ingin menghapus postingan ini?')">
                          <span class="glyphicon glyphicon-trash"></span> Hapus
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-right">

        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>

  <!-- jQuery -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>
</body>

</html>