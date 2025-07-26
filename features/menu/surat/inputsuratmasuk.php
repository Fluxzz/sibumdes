<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

$pageTitle = "Tambah Surat Masuk";
$activeMenu = "surat"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Tambah Surat Masuk</div>
            </div>
            <div class="card-body">
                <form action="../../../proses/input_suratmasuk.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="tanggal_terima">Tanggal Terima</label>
                        <input type="date" name="tanggal_terima" id="tanggal_terima" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_surat">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_surat">Nomor Surat</label>
                        <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" placeholder="Contoh: 123/ABC/IV/2025" required>
                    </div>
                    <div class="form-group">
                        <label for="pengirim">Pengirim</label>
                        <input type="text" name="pengirim" id="pengirim" class="form-control" placeholder="Nama instansi/perorangan" required>
                    </div>
                    <div class="form-group">
                        <label for="penerima_surat">Penerima</label>
                        <input type="text" name="penerima_surat" id="penerima_surat" class="form-control" placeholder="Nama penerima di BUMDES" required>
                    </div>
                    <div class="form-group">
                        <label for="disposisi">Disposisi</label>
                        <textarea name="disposisi" id="disposisi" class="form-control" rows="2" required></textarea>
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
                    <div class="form-group">
                        <label for="lampiran_foto">Lampiran Foto</label>
                        <input type="file" name="lampiran_foto" id="lampiran_foto" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="datasuratmasuk.php" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>