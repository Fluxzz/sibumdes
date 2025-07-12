<?php
session_start();
include "login/ceksession.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Postingan - Arsip Surat Desa Candirejo Borobudur</title>

    <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?php include("sidebarmenu.php"); ?>
            <?php include("header.php"); ?>

            <div class="right_col" role="main">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Tambah Postingan</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <form action="../proses-tambah-postingan.php" method="post" enctype="multipart/form-data" class="form-horizontal form-label-left">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Gambar *</label>
                                        <div class="col-md-9">
                                            <input type="file" name="gambar" accept="image/*" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Status *</label>
                                        <div class="col-md-9">
                                            <select name="status" id="status" class="form-control" onchange="toggleLinkInput()" required>
                                                <option value="draft">Draft</option>
                                                <option value="publish">Publish</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Kategori *</label>
                                        <div class="col-md-9">
                                            <select name="id_kategori" class="form-control">
                                                <?php
                                                $kategori = mysqli_query($db, "SELECT * FROM tb_kategori");
                                                while ($row = mysqli_fetch_assoc($kategori)) {
                                                    echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Kategori Baru</label>
                                        <div class="col-md-9">
                                            <input type="text" name="kategori_baru" placeholder="Opsional jika ingin tambah kategori baru" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Caption *</label>
                                        <div class="col-md-9">
                                            <textarea name="caption" rows="3" class="form-control" required></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Link Konten</label>
                                        <div class="col-md-9">
                                            <input type="text" name="link_konten" id="link_konten" placeholder="Isi jika status publish" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">Tanggal Posting *</label>
                                        <div class="col-md-9">
                                            <input type="datetime-local" name="tanggal_posting" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer>
                <div class="pull-right">Arsip Surat Desa Candirejo Borobudur</div>
                <div class="clearfix"></div>
            </footer>
        </div>
    </div>

    <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        function toggleLinkInput() {
            var status = document.getElementById("status").value;
            var linkInput = document.getElementById("link_konten");
            linkInput.disabled = (status !== "publish");
            if (status !== "publish") linkInput.value = "";
        }
        toggleLinkInput();
    </script>
</body>
</html>
