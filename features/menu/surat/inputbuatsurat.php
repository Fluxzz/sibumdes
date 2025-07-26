<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

$pageTitle = "Buat Surat";
$activeMenu = "surat"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pilih Jenis Surat yang Akan Dibuat</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="jenis_surat">Jenis Surat</label>
                    <select id="jenis_surat" class="form-control" onchange="showForm()">
                        <option value="">-- Pilih --</option>
                        <option value="undangan">Surat Undangan</option>
                        <option value="keterangan">Surat Keterangan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card" id="form_undangan" style="display:none;">
            <div class="card-header"><div class="card-title">Form Surat Undangan</div></div>
            <div class="card-body">
                <form action="../../../proses/proses_buatsurat_undangan.php" method="post" target="_blank">
                    <div class="form-group"><label>Nomor Surat</label><input type="text" name="nomor_surat" class="form-control" required></div>
                    <div class="form-group"><label>Tanggal Surat</label><input type="date" name="tanggal" class="form-control" required></div>
                    <div class="form-group"><label>Lampiran</label><input type="text" name="lampiran" class="form-control"></div>
                    <div class="form-group"><label>Perihal</label><input type="text" name="perihal" class="form-control" value="Undangan" required></div>
                    <div class="form-group"><label>Kepada Yth.</label><input type="text" name="kepada" class="form-control" required></div>
                    <div class="form-group"><label>di</label><input type="text" name="lokasi" class="form-control" placeholder="Lokasi penerima" required></div>
                    <hr>
                    <div class="form-group"><label>Hari/Tanggal Acara</label><input type="date" name="tanggal_acara" class="form-control" required></div>
                    <div class="form-group"><label>Waktu Acara</label><input type="text" name="waktu_acara" class="form-control" placeholder="Contoh: 09:00 WIB - Selesai" required></div>
                    <div class="form-group"><label>Tempat Acara</label><input type="text" name="tempat_acara" class="form-control" required></div>
                    <div class="form-group"><label>Keperluan/Acara</label><textarea name="keperluan" class="form-control" rows="2" required></textarea></div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-primary">Buat & Unduh PDF</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card" id="form_keterangan" style="display:none;">
            <div class="card-header"><div class="card-title">Form Surat Keterangan</div></div>
            <div class="card-body">
                <form action="../../../proses/proses_buatsurat_keterangan.php" method="post" target="_blank">
                    <div class="form-group"><label>Nomor Surat</label><input type="text" name="nomor_surat" class="form-control" required></div>
                    <div class="form-group"><label>Tanggal Surat</label><input type="date" name="tanggal" class="form-control" required></div>
                    <hr>
                    <div class="form-group"><label>Nama Lengkap (Pihak yang diterangkan)</label><input type="text" name="nama" class="form-control" required></div>
                    <div class="form-group"><label>Jenis Keterangan Pendukung (Contoh: NIK, Jabatan)</label><input type="text" name="jenis_keterangan_pendukung" class="form-control" required></div>
                    <div class="form-group"><label>Isi Keterangan Pendukung (Contoh: 33100... / Ketua Karang Taruna)</label><input type="text" name="keterangan_pendukung" class="form-control" required></div>
                    <div class="form-group"><label>Isi Surat (Paragraf utama surat keterangan)</label><textarea name="keterangan" class="form-control" rows="4" required></textarea></div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-primary">Buat & Unduh PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$pageJS = "
    function showForm() {
      var jenis = document.getElementById('jenis_surat').value;
      document.getElementById('form_undangan').style.display = (jenis === 'undangan') ? 'block' : 'none';
      document.getElementById('form_keterangan').style.display = (jenis === 'keterangan') ? 'block' : 'none';
    }
";
include '../../../partials/footer.php'; 
?>