<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

// Validasi ID dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID Surat tidak valid.'];
    header('Location: datasuratmasuk.php');
    exit();
}
$id = (int)$_GET['id'];

// Ambil data surat yang akan diedit
$stmt = $db->prepare("SELECT * FROM tb_arsip_surat_masuk WHERE No = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Jika data tidak ditemukan, redirect
if (!$data) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Data Surat tidak ditemukan.'];
    header('Location: datasuratmasuk.php');
    exit();
}

$pageTitle = "Edit Surat Masuk";
$activeMenu = "surat"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Edit Surat Masuk</div>
            </div>
            <div class="card-body">
                <form action="../../../proses/edit_suratmasuk.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="No" value="<?= $data['No'] ?>">
                    <div class="form-group">
                        <label for="tanggal_terima">Tanggal Terima</label>
                        <input type="date" name="tanggal_terima" id="tanggal_terima" class="form-control" value="<?= htmlspecialchars($data['tanggal_terima']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_surat">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" value="<?= htmlspecialchars($data['tanggal_surat']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_surat">Nomor Surat</label>
                        <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="pengirim">Pengirim</label>
                        <input type="text" name="pengirim" id="pengirim" class="form-control" value="<?= htmlspecialchars($data['pengirim']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="penerima_surat">Penerima</label>
                        <input type="text" name="penerima_surat" id="penerima_surat" class="form-control" value="<?= htmlspecialchars($data['penerima_surat']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="disposisi">Disposisi</label>
                        <textarea name="disposisi" id="disposisi" class="form-control" rows="2" required><?= htmlspecialchars($data['disposisi']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="perihal">Perihal</label>
                        <input type="text" name="perihal" id="perihal" class="form-control" value="<?= htmlspecialchars($data['perihal']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="kode">Kode Arsip</label>
                        <input type="text" name="kode" id="kode" class="form-control" value="<?= htmlspecialchars($data['kode']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="2"><?= htmlspecialchars($data['keterangan']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file_surat">Ganti File Surat (PDF)</label>
                        <input type="file" name="file_surat" id="file_surat" class="form-control" accept="application/pdf">
                        <?php if(!empty($data['file_surat'])): ?>
                            <p class="mt-2">File saat ini: <a href="../../../uploads/surat_masuk/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank">Lihat File</a></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="lampiran_foto">Ganti Lampiran Foto</label>
                        <input type="file" name="lampiran_foto" id="lampiran_foto" class="form-control" accept="image/*">
                         <?php if(!empty($data['lampiran_foto'])): ?>
                            <p class="mt-2">Foto saat ini: <br><img src="../../../uploads/surat_masuk/<?= htmlspecialchars($data['lampiran_foto']) ?>" alt="Lampiran" width="150" class="img-thumbnail mt-2"></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="datasuratmasuk.php" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>