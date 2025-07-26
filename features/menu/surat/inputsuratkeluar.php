<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

$pageTitle = "Tambah Surat Keluar";
$activeMenu = "surat"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Tambah Surat Keluar</div>
            </div>
            <div class="card-body">
                <form action="../../../proses/input_suratkeluar.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="tanggal_keluar">Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_surat">Nomor Surat</label>
                        <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" placeholder="Contoh: 005/BUMDES/VII/2025" required>
                    </div>
                    <div class="form-group">
                        <label for="penerima">Penerima / Tujuan</label>
                        <input type="text" name="penerima" id="penerima" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="perihal">Perihal</label>
                        <input type="text" name="perihal" id="perihal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Arsip</label>
                        <input type="text" name="kode" id="kode" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file_surat">File Surat (PDF)</label>
                        <input type="file" name="file_surat" id="file_surat" class="form-control" accept="application/pdf" required>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="datasuratkeluar.php" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>