<!DOCTYPE html>
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
        $upload_dir = "uploads/";
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar);
    }

    // Query simpan sesuai struktur database yang ada
    // Hanya field: id_postingan, id_kategori, gambar, status, caption, link_konten, tanggal_posting
    $query = "INSERT INTO tb_postingan (id_kategori, gambar, status, caption, link_konten, tanggal_posting) 
              VALUES ('$id_kategori', '$gambar', '$status', '$caption', '$link_konten', NOW())";

    if (!mysqli_query($db, $query)) {
        die("Query gagal: " . mysqli_error($db));
    }

    header("Location: data-postingan.php");
    exit;
}
?>
<html lang="id">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Tambah Postingan</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="../assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="../assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Switchery -->
  <link href="../assets/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-datetimepicker -->
  <link href="../assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
  <!-- starrr -->
  <link href="../assets/vendors/starrr/dist/starrr.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <!-- Profile and Sidebarmenu -->
      <?php
      include("sidebarmenu.php");
      ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php
      include("header.php");
      ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Tambah Postingan</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  
                  <form action="" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate
                    class="form-horizontal form-label-left">
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_kategori">Kategori <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="id_kategori" name="id_kategori" class="form-control" required="required">
                          <option value="">-- Pilih Kategori --</option>
                          <?php
                          // Query untuk mengambil data kategori dari database
                          $query_kategori = "SELECT * FROM tb_kategori ORDER BY nama_kategori";
                          $result_kategori = mysqli_query($db, $query_kategori);
                          
                          if ($result_kategori && mysqli_num_rows($result_kategori) > 0) {
                              while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                  echo '<option value="' . $row_kategori['id_kategori'] . '">' . htmlspecialchars($row_kategori['nama_kategori']) . '</option>';
                              }
                          } else {
                              // Fallback jika tabel kategori belum ada
                              echo '<option value="1">Informasi</option>';
                              echo '<option value="2">Edukasi</option>';
                              echo '<option value="3">Pengumuman</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gambar">Upload Gambar
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="file" id="gambar" name="gambar" accept="image/*" class="form-control col-md-7 col-xs-12">
                        <small class="form-text text-muted">Format yang didukung: JPG, JPEG, PNG, GIF. Maksimal 5MB</small>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <select id="status" name="status" class="form-control" required="required">
                          <option value="">-- Pilih Status --</option>
                          <option value="draft">Draft</option>
                          <option value="publish">Publish</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="caption">Caption <span
                          class="required">*</span>
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <textarea id="caption" name="caption" class="form-control" rows="5" required="required"
                          placeholder="Tulis caption postingan..."></textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link_konten">Link Konten
                      </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="url" id="link_konten" name="link_konten" 
                          placeholder="https://example.com" class="form-control col-md-7 col-xs-12">
                        <small class="form-text text-muted">Link tambahan terkait postingan (opsional)</small>
                      </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" name="submit" class="btn btn-success">
                          <i class="fa fa-save"></i> Simpan Postingan
                        </button>
                        <button type="reset" class="btn btn-primary">
                          <i class="fa fa-refresh"></i> Reset
                        </button>
                        <a href="data-postingan.php" class="btn btn-default">
                          <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                      </div>
                    </div>
                  </form>

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
          Sistem Manajemen Postingan
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
  <!-- bootstrap-progressbar -->
  <script src="../assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="../assets/vendors/iCheck/icheck.min.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../assets/vendors/moment/min/moment.min.js"></script>
  <script src="../assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-datetimepicker -->
  <script src="../assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <!-- JavaScript untuk preview gambar -->
  <script>
    // Preview gambar sebelum upload
    document.getElementById('gambar').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Validasi ukuran file (5MB)
        if (file.size > 5 * 1024 * 1024) {
          alert('Ukuran file terlalu besar! Maksimal 5MB');
          this.value = '';
          return;
        }
        
        // Validasi format file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
          alert('Format file tidak didukung! Gunakan JPG, JPEG, PNG, atau GIF');
          this.value = '';
          return;
        }
        
        // Preview gambar
        const reader = new FileReader();
        reader.onload = function(e) {
          // Remove existing preview if any
          const existingPreview = document.getElementById('image-preview');
          if (existingPreview) {
            existingPreview.remove();
          }
          
          // Create new preview
          const preview = document.createElement('div');
          preview.id = 'image-preview';
          preview.className = 'col-md-9 col-sm-9 col-xs-12 col-md-offset-3';
          preview.innerHTML = `
            <div style="margin-top: 10px;">
              <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
              <p><small class="text-muted">Preview gambar</small></p>
            </div>
          `;
          
          // Insert preview after the file input
          const fileInputGroup = document.getElementById('gambar').closest('.form-group');
          fileInputGroup.parentNode.insertBefore(preview, fileInputGroup.nextSibling);
        };
        reader.readAsDataURL(file);
      }
    });

    // Character counter untuk caption
    document.getElementById('caption').addEventListener('input', function() {
      const maxLength = 500; // Batasi caption
      const currentLength = this.value.length;
      
      // Remove existing counter if any
      let counter = document.getElementById('caption-counter');
      if (!counter) {
        counter = document.createElement('small');
        counter.id = 'caption-counter';
        counter.className = 'form-text text-muted';
        this.parentNode.appendChild(counter);
      }
      
      counter.textContent = `${currentLength}/${maxLength} karakter`;
      
      if (currentLength > maxLength) {
        counter.className = 'form-text text-danger';
        this.value = this.value.substring(0, maxLength);
      } else {
        counter.className = 'form-text text-muted';
      }
    });

    // Form validation
    document.getElementById('demo-form2').addEventListener('submit', function(e) {
      const kategori = document.getElementById('id_kategori').value;
      const status = document.getElementById('status').value;
      const caption = document.getElementById('caption').value.trim();
      
      if (!kategori || !status || !caption) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi!');
        return false;
      }
      
      if (caption.length < 10) {
        e.preventDefault();
        alert('Caption minimal 10 karakter!');
        return false;
      }
      
      // Konfirmasi sebelum submit
      if (!confirm('Apakah Anda yakin ingin menyimpan postingan ini?')) {
        e.preventDefault();
        return false;
      }
    });
  </script>
</body>

</html>