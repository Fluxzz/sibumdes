<?php
session_start();
include("../koneksi/koneksi.php");
// include("ceksessionn.php"); // Aktifkan jika ingin validasi session login
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Arsip Surat Desa Candirejo Borobudur</title>

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

                                    <form action="../admin/proses-tambah-postingan.php" method="post" enctype="multipart/form-data"
                                        id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gambar">Gambar <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="file" id="gambar" name="gambar" accept="image/*" required="required" class="form-control col-md-7 col-xs-12">
                                                <small class="text-muted">Format yang diizinkan: JPG, PNG, GIF. Maksimal 2MB</small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <select id="status" name="status" class="form-control" onchange="toggleLinkInput()" required="required">
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="draft">Draft</option>
                                                    <option value="publish">Publish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_kategori">Kategori <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <select id="id_kategori" name="id_kategori" class="form-control" required="required">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    <?php
                                                    $kategori = mysqli_query($db, "SELECT * FROM tb_kategori ORDER BY kategori ASC");
                                                    if ($kategori && mysqli_num_rows($kategori) > 0) {
                                                        while ($row = mysqli_fetch_assoc($kategori)) {
                                                            echo "<option value='{$row['kategori']}'>{$row['kategori']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="kategori_baru">Kategori Baru</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="text" id="kategori_baru" name="kategori_baru" maxlength="100"
                                                    placeholder="Opsional jika ingin tambah kategori baru" class="form-control col-md-7 col-xs-12">
                                                <small class="text-muted">Kosongkan jika menggunakan kategori yang sudah ada</small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="caption">Caption <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <textarea id="caption" name="caption" class="form-control" rows="4" required="required"
                                                    placeholder="Masukkan caption postingan"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group" id="link_group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link_konten">Link Konten</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="url" id="link_konten" name="link_konten" maxlength="255"
                                                    placeholder="Isi jika status publish (https://...)" class="form-control col-md-7 col-xs-12">
                                                <small class="text-muted">Wajib diisi jika status adalah "Publish"</small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_posting">Tanggal Posting <span class="required">*</span></label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <div class='input-group date' id='myDatepicker'>
                                                    <input type='datetime-local' id="tanggal_posting" name="tanggal_posting" required="required" class="form-control" />
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ln_solid"></div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                <button type="submit" class="btn btn-success">Submit</button>
                                                <button type="reset" class="btn btn-primary">Reset</button>
                                                <a href="daftar-postingan.php" class="btn btn-default">Kembali</a>
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
                    Arsip Surat Desa Candirejo Borobudur
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

    <script>
        function toggleLinkInput() {
            var status = document.getElementById("status").value;
            var linkInput = document.getElementById("link_konten");
            var linkGroup = document.getElementById("link_group");

            if (status === "publish") {
                linkInput.disabled = false;
                linkInput.required = true;
                linkInput.setAttribute('required', 'required');
                linkGroup.style.display = 'block';
            } else {
                linkInput.disabled = true;
                linkInput.required = false;
                linkInput.removeAttribute('required');
                linkInput.value = "";
                if (status === "draft") {
                    linkGroup.style.display = 'none';
                }
            }
        }

        // Initialize pada saat halaman dimuat
        $(document).ready(function() {
            toggleLinkInput();

            // Set tanggal default ke waktu sekarang
            var now = new Date();
            var year = now.getFullYear();
            var month = (now.getMonth() + 1).toString().padStart(2, '0');
            var day = now.getDate().toString().padStart(2, '0');
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');

            var datetime = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
            document.getElementById('tanggal_posting').value = datetime;

            // Initialize datetimepicker
            $('#myDatepicker').datetimepicker({
                format: 'YYYY-MM-DDTHH:mm',
                sideBySide: true
            });
        });

        // Validasi form sebelum submit
        document.getElementById('demo-form2').addEventListener('submit', function(e) {
            var status = document.getElementById('status').value;
            var linkKonten = document.getElementById('link_konten').value;

            if (status === 'publish' && linkKonten.trim() === '') {
                e.preventDefault();
                alert('Link konten wajib diisi jika status adalah Publish!');
                document.getElementById('link_konten').focus();
                return false;
            }
        });
    </script>
</body>

</html>