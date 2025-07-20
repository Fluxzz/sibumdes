<!DOCTYPE html>
<?php
session_start();
include "../koneksi/koneksi.php";

// Ambil ID postingan dari parameter URL
$id_postingan = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id_postingan)) {
    header("Location: data-postingan.php");
    exit;
}

// Ambil data postingan yang akan diedit
$query_select = "SELECT * FROM tb_postingan WHERE id_postingan = '$id_postingan'";
$result_select = mysqli_query($db, $query_select);

if (!$result_select || mysqli_num_rows($result_select) == 0) {
    header("Location: data-postingan.php");
    exit;
}

$data_postingan = mysqli_fetch_assoc($result_select);

if (isset($_POST['submit'])) {
    // Escape semua input untuk keamanan
    $id_kategori = mysqli_real_escape_string($db, $_POST['id_kategori']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $caption = mysqli_real_escape_string($db, $_POST['caption']);
    $link_konten = mysqli_real_escape_string($db, $_POST['link_konten']);
    $tanggal_posting = isset($_POST['tanggal_posting']) ? mysqli_real_escape_string($db, $_POST['tanggal_posting']) : '';

    // Debug: Tampilkan nilai yang diterima (hapus setelah debugging)
    // echo "Status yang diterima: " . $status . "<br>";
    // echo "ID Postingan: " . $id_postingan . "<br>";

    // Validasi: link_konten dan tanggal_posting wajib ada jika status publish
    if ($status == 'publish') {
        if (empty($link_konten)) {
            $error_message = "Link konten wajib diisi untuk status publish!";
        }
        if (empty($tanggal_posting)) {
            $error_message = "Tanggal posting wajib diisi untuk status publish!";
        }
    }

    if (!isset($error_message)) {
        // Upload gambar baru jika ada
        $gambar = $data_postingan['gambar']; // Gunakan gambar lama sebagai default
        if (isset($_FILES['gambar']) && $_FILES['gambar']['name'] != '') {
            // Hapus gambar lama jika ada
            if (!empty($data_postingan['gambar']) && file_exists("uploads/" . $data_postingan['gambar'])) {
                unlink("uploads/" . $data_postingan['gambar']);
            }

            $gambar = uniqid() . '-' . basename($_FILES['gambar']['name']);
            $upload_dir = "uploads/";
            
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $gambar)) {
                $error_message = "Gagal mengupload gambar!";
            }
        }

        // Jika tidak ada error upload gambar, lanjutkan update
        if (!isset($error_message)) {
            // Set tanggal_posting ke NULL jika status bukan publish
            $tanggal_posting_value = ($status == 'publish' && !empty($tanggal_posting)) ? "'$tanggal_posting'" : "NULL";

            // Query update dengan prepared statement untuk keamanan
            $query = "UPDATE tb_postingan SET 
                      id_kategori = ?, 
                      gambar = ?, 
                      status = ?, 
                      caption = ?, 
                      link_konten = ?,
                      tanggal_posting = ?
                      WHERE id_postingan = ?";

            $stmt = mysqli_prepare($db, $query);
            
            if ($stmt) {
                // Bind parameters
                if ($status == 'publish' && !empty($tanggal_posting)) {
                    mysqli_stmt_bind_param($stmt, "issssss", $id_kategori, $gambar, $status, $caption, $link_konten, $tanggal_posting, $id_postingan);
                } else {
                    $null_date = null;
                    mysqli_stmt_bind_param($stmt, "issssss", $id_kategori, $gambar, $status, $caption, $link_konten, $null_date, $id_postingan);
                }
                
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    header("Location: data-postingan.php?success=edit");
                    exit;
                } else {
                    $error_message = "Query gagal: " . mysqli_error($db);
                }
                mysqli_stmt_close($stmt);
            } else {
                // Fallback ke query biasa jika prepared statement gagal
                $query_fallback = "UPDATE tb_postingan SET 
                          id_kategori = '$id_kategori', 
                          gambar = '$gambar', 
                          status = '$status', 
                          caption = '$caption', 
                          link_konten = '$link_konten',
                          tanggal_posting = $tanggal_posting_value
                          WHERE id_postingan = '$id_postingan'";

                if (mysqli_query($db, $query_fallback)) {
                    header("Location: data-postingan.php?success=edit");
                    exit;
                } else {
                    $error_message = "Query gagal: " . mysqli_error($db);
                }
            }
        }
    }
}

// Siapkan nilai default untuk field
$current_link_konten = htmlspecialchars($data_postingan['link_konten']);
$current_caption = htmlspecialchars($data_postingan['caption']);
$current_tanggal_posting = $data_postingan['tanggal_posting'] ? date('Y-m-d\TH:i', strtotime($data_postingan['tanggal_posting'])) : '';
?>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Postingan</title>

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
            <?php include("sidebarmenu.php"); ?>
            <!-- /Profile and Sidebarmenu -->

            <!-- top navigation -->
            <?php include("header.php"); ?>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Edit Postingan</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br />

                                    <?php if (isset($error_message)): ?>
                                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <?php echo $error_message; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Debug info (hapus setelah debugging) -->
                                    <?php if (isset($_POST['submit'])): ?>
                                        <div class="alert alert-info">
                                            <strong>Debug Info:</strong><br>
                                            Status yang diterima: <?php echo isset($_POST['status']) ? $_POST['status'] : 'TIDAK ADA'; ?><br>
                                            ID Postingan: <?php echo $id_postingan; ?><br>
                                            Status saat ini di database: <?php echo $data_postingan['status']; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_kategori">Kategori <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <select id="id_kategori" name="id_kategori" class="form-control" required="required">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    <?php
                                                    // Query untuk mengambil data kategori dari database
                                                    $query_kategori = "SELECT * FROM tb_kategori ORDER BY nama_kategori";
                                                    $result_kategori = mysqli_query($db, $query_kategori);

                                                    if ($result_kategori && mysqli_num_rows($result_kategori) > 0) {
                                                        while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                                            $selected = ($row_kategori['id_kategori'] == $data_postingan['id_kategori']) ? 'selected' : '';
                                                            echo '<option value="' . $row_kategori['id_kategori'] . '" ' . $selected . '>' . htmlspecialchars($row_kategori['nama_kategori']) . '</option>';
                                                        }
                                                    } else {
                                                        // Fallback jika tabel kategori belum ada
                                                        $selected1 = ($data_postingan['id_kategori'] == '1') ? 'selected' : '';
                                                        $selected2 = ($data_postingan['id_kategori'] == '2') ? 'selected' : '';
                                                        $selected3 = ($data_postingan['id_kategori'] == '3') ? 'selected' : '';
                                                        echo '<option value="1" ' . $selected1 . '>Informasi</option>';
                                                        echo '<option value="2" ' . $selected2 . '>Edukasi</option>';
                                                        echo '<option value="3" ' . $selected3 . '>Pengumuman</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gambar">Upload Gambar</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="file" id="gambar" name="gambar" accept="image/*" class="form-control col-md-7 col-xs-12">
                                                <small class="form-text text-muted">Format yang didukung: JPG, JPEG, PNG, GIF. Maksimal 5MB</small>
                                                <?php if (!empty($data_postingan['gambar'])): ?>
                                                    <div id="current-image" style="margin-top: 10px;">
                                                        <p><small class="text-muted">Gambar saat ini:</small></p>
                                                        <img src="uploads/<?php echo htmlspecialchars($data_postingan['gambar']); ?>" alt="Current Image" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <select id="status" name="status" class="form-control" required="required" onchange="togglePublishFields()">
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="draft" <?php echo ($data_postingan['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                                                    <option value="publish" <?php echo ($data_postingan['status'] == 'publish') ? 'selected' : ''; ?>>Publish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Field Tanggal Posting - Muncul hanya jika status publish -->
                                        <div class="form-group" id="tanggal-posting-group" style="<?php echo ($data_postingan['status'] == 'publish') ? 'display:block;' : 'display:none;'; ?>">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_posting">
                                                Tanggal Posting <span class="required">*</span>
                                            </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="datetime-local" id="tanggal_posting" name="tanggal_posting" value="<?php echo $current_tanggal_posting; ?>" class="form-control col-md-7 col-xs-12">
                                                <small class="form-text text-muted">Pilih tanggal dan waktu posting (wajib untuk status publish)</small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="caption">Caption <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <textarea id="caption" name="caption" class="form-control" rows="5" required="required" placeholder="Tulis caption postingan..."><?php echo $current_caption; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link_konten">
                                                Link Konten <span id="link_required" style="<?php echo ($data_postingan['status'] == 'publish') ? 'display:inline;' : 'display:none;'; ?>" class="required">*</span>
                                            </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="url" id="link_konten" name="link_konten" value="<?php echo $current_link_konten; ?>" placeholder="https://example.com" class="form-control col-md-7 col-xs-12">
                                                <small class="form-text text-muted" id="link_help_text">
                                                    <?php if ($data_postingan['status'] == 'publish'): ?>
                                                        URL postingan yang akan dipublikasikan (wajib untuk status publish)
                                                    <?php else: ?>
                                                        Link tambahan terkait postingan (opsional untuk draft, wajib untuk publish)
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                <button type="submit" name="submit" class="btn btn-success">
                                                    <i class="fa fa-save"></i> Update Postingan
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

    <!-- JavaScript untuk preview gambar dan validasi -->
    <script>
        // Fungsi untuk menampilkan/menyembunyikan validasi link_konten dan tanggal_posting
        function togglePublishFields() {
            var status = document.getElementById('status').value;
            var linkKonten = document.getElementById('link_konten');
            var linkRequired = document.getElementById('link_required');
            var linkHelpText = document.getElementById('link_help_text');
            var tanggalPostingGroup = document.getElementById('tanggal-posting-group');
            var tanggalPosting = document.getElementById('tanggal_posting');

            console.log('Status dipilih:', status); // Debug log

            if (status === 'publish') {
                // Show dan set required untuk publish
                linkKonten.setAttribute('required', 'required');
                linkRequired.style.display = 'inline';
                linkHelpText.innerHTML = 'URL postingan yang akan dipublikasikan (wajib untuk status publish)';
                
                // Show tanggal posting group dan set required
                tanggalPostingGroup.style.display = 'block';
                tanggalPosting.setAttribute('required', 'required');
                
                // Set default tanggal jika kosong (sekarang + 1 jam)
                if (!tanggalPosting.value) {
                    var now = new Date();
                    now.setHours(now.getHours() + 1);
                    var year = now.getFullYear();
                    var month = String(now.getMonth() + 1).padStart(2, '0');
                    var day = String(now.getDate()).padStart(2, '0');
                    var hours = String(now.getHours()).padStart(2, '0');
                    var minutes = String(now.getMinutes()).padStart(2, '0');
                    tanggalPosting.value = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
                }
            } else {
                // Hide dan remove required untuk draft
                linkKonten.removeAttribute('required');
                linkRequired.style.display = 'none';
                linkHelpText.innerHTML = 'Link tambahan terkait postingan (opsional untuk draft, wajib untuk publish)';
                
                // Hide tanggal posting group dan remove required
                tanggalPostingGroup.style.display = 'none';
                tanggalPosting.removeAttribute('required');
            }
        }

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
                    // Hide current image
                    const currentImage = document.getElementById('current-image');
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }

                    // Remove existing preview if any
                    const existingPreview = document.getElementById('image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    // Create new preview
                    const preview = document.createElement('div');
                    preview.id = 'image-preview';
                    preview.className = 'col-md-9 col-sm-9 col-xs-12 col-md-offset-3';
                    preview.innerHTML = '<div style="margin-top: 10px;"><p><small class="text-muted">Preview gambar baru:</small></p><img src="' + e.target.result + '" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;"></div>';

                    // Insert preview after the file input
                    const fileInputGroup = document.getElementById('gambar').closest('.form-group');
                    fileInputGroup.parentNode.insertBefore(preview, fileInputGroup.nextSibling);
                };
                reader.readAsDataURL(file);
            } else {
                // Show current image back if file input is cleared
                const currentImage = document.getElementById('current-image');
                if (currentImage) {
                    currentImage.style.display = 'block';
                }

                // Remove preview
                const existingPreview = document.getElementById('image-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }
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

            counter.textContent = currentLength + '/' + maxLength + ' karakter';

            if (currentLength > maxLength) {
                counter.className = 'form-text text-danger';
                this.value = this.value.substring(0, maxLength);
            } else {
                counter.className = 'form-text text-muted';
            }
        });

        // Trigger counter on page load
        document.getElementById('caption').dispatchEvent(new Event('input'));

        // Form validation
        document.getElementById('demo-form2').addEventListener('submit', function(e) {
            const kategori = document.getElementById('id_kategori').value;
            const status = document.getElementById('status').value;
            const caption = document.getElementById('caption').value.trim();
            const linkKonten = document.getElementById('link_konten').value.trim();
            const tanggalPosting = document.getElementById('tanggal_posting').value;

            console.log('Form submit - Status:', status); // Debug log

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

            // Validasi khusus untuk status publish
            if (status === 'publish') {
                if (!linkKonten) {
                    e.preventDefault();
                    alert('Link konten wajib diisi untuk status publish!');
                    return false;
                }

                if (!tanggalPosting) {
                    e.preventDefault();
                    alert('Tanggal posting wajib diisi untuk status publish!');
                    return false;
                }

                // Validasi tanggal tidak boleh masa lalu
                const selectedDate = new Date(tanggalPosting);
                const now = new Date();
                if (selectedDate < now) {
                    if (!confirm('Tanggal posting yang dipilih sudah lewat. Apakah Anda yakin ingin melanjutkan?')) {
                        e.preventDefault();
                        return false;
                    }
                }

                // Validasi format URL
                try {
                    new URL(linkKonten);
                } catch (error) {
                    e.preventDefault();
                    alert('Format link konten tidak valid!');
                    return false;
                }
            }

            // Validasi format URL link_konten jika diisi
            if (linkKonten) {
                try {
                    new URL(linkKonten);
                } catch (error) {
                    e.preventDefault();
                    alert('Format link konten tidak valid!');
                    return false;
                }
            }

            // Konfirmasi sebelum submit
            if (!confirm('Apakah Anda yakin ingin mengupdate postingan ini?')) {
                e.preventDefault();
                return false;
            }
        });

        // Reset form
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin mereset form? Semua perubahan akan hilang.')) {
                e.preventDefault();
                return false;
            }

            // Reset preview dan current image
            const preview = document.getElementById('image-preview');
            if (preview) {
                preview.remove();
            }

            const currentImage = document.getElementById('current-image');
            if (currentImage) {
                currentImage.style.display = 'block';
            }

            // Reset publish fields
            setTimeout(function() {
                togglePublishFields();
            }, 100);
        });

        // Initialize publish fields on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePublishFields();
        });
    </script>
</body>

</html>