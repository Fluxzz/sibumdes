<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

// Ambil ID dari parameter GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validasi ID
if ($id <= 0) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID Surat tidak valid.'];
    header('Location: ../surat/datasuratkeluar.php');
    exit();
}

// Ambil data dari database
$stmt = $db->prepare("SELECT * FROM tb_arsip_surat_keluar WHERE No = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Cek jika data tidak ditemukan
if (!$data) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Data Surat tidak ditemukan.'];
    header('Location: ../surat/datasuratkeluar.php');
    exit();
}

$pageTitle = "Edit Surat Keluar";
$activeMenu = "surat";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Edit Surat Keluar</div>
            </div>
            <div class="card-body">
                <form action="../proses/proses_editsuratkeluar.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="No" value="<?= (int)$data['No'] ?>">

                    <div class="form-group">
                        <label for="tanggal_keluar">Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control" value="<?= htmlspecialchars($data['tanggal_keluar']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nomor_surat">Nomor Surat</label>
                        <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="penerima">Penerima / Tujuan</label>
                        <input type="text" name="penerima" id="penerima" class="form-control" value="<?= htmlspecialchars($data['penerima']) ?>" required>
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
                        <?php if (!empty($data['file_surat'])): ?>
                            <p class="mt-2">File saat ini:
                                <a href="../../uploads/surat_keluar/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank">Lihat File</a>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="card-action mt-3">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="../surat/datasuratkeluar.php" class="btn btn-danger">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>
