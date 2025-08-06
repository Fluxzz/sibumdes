<?php
session_start();
include '../../../koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Surat Masuk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Input Surat Masuk</h3>
    <form action="../proses/proses_inputsuratmasuk.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tanggal Terima</label>
            <input type="date" name="tanggal_terima" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Pengirim</label>
            <input type="text" name="pengirim" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Penerima Surat</label>
            <input type="text" name="penerima_surat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Disposisi</label>
            <textarea name="disposisi" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Perihal</label>
            <input type="text" name="perihal" class="form-control">
        </div>
        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control">
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label>File Surat (PDF)</label>
            <input type="file" name="file_surat" class="form-control-file" accept=".pdf">
        </div>
        <div class="form-group">
            <label>Lampiran Foto (jpg/png/jpeg)</label>
            <input type="file" name="lampiran_foto" class="form-control-file" accept="image/*">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
</body>
</html>
