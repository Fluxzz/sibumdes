<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID Surat tidak valid.'];
    header('Location: datasuratmasuk.php');
    exit();
}

$id = (int)$_GET['id'];

// Ambil data surat berdasarkan No
$stmt = $db->prepare("SELECT * FROM tb_arsip_surat_masuk WHERE No = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Data Surat tidak ditemukan.'];
    header('Location: datasuratmasuk.php');
    exit();
}

$pageTitle = "Detail Surat Masuk";
$activeMenu = "surat";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Detail Surat Masuk: <?= htmlspecialchars($data['nomor_surat']) ?></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-striped">
                            <tr><td width="30%"><strong>Nomor Urut</strong></td><td>: <?= htmlspecialchars($data['No']) ?></td></tr>
                            <tr><td><strong>Tanggal Terima</strong></td><td>: <?= date('d F Y', strtotime($data['tanggal_terima'])) ?></td></tr>
                            <tr><td><strong>Tanggal Surat</strong></td><td>: <?= date('d F Y', strtotime($data['tanggal_surat'])) ?></td></tr>
                            <tr><td><strong>Nomor Surat</strong></td><td>: <?= htmlspecialchars($data['nomor_surat']) ?></td></tr>
                            <tr><td><strong>Pengirim</strong></td><td>: <?= htmlspecialchars($data['pengirim']) ?></td></tr>
                            <tr><td><strong>Penerima</strong></td><td>: <?= htmlspecialchars($data['penerima_surat']) ?></td></tr>
                            <tr><td><strong>Perihal</strong></td><td>: <?= htmlspecialchars($data['perihal']) ?></td></tr>
                            <tr><td><strong>Disposisi</strong></td><td>: <?= nl2br(htmlspecialchars($data['disposisi'])) ?></td></tr>
                            <tr><td><strong>Kode Arsip</strong></td><td>: <?= htmlspecialchars($data['kode']) ?></td></tr>
                            <tr><td><strong>Keterangan</strong></td><td>: <?= nl2br(htmlspecialchars($data['keterangan'])) ?></td></tr>
                            <tr>
                                <td><strong>File Surat</strong></td>
                                <td>:
                                    <?php if (!empty($data['file_surat'])): ?>
                                        <a href="../../uploads/surat_masuk/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Unduh File
                                        </a>
                                    <?php else: ?> - <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <strong>Lampiran Foto:</strong><br>
                        <?php if (!empty($data['lampiran_foto'])): ?>
                            <a href="../../uploads/surat_masuk/<?= htmlspecialchars($data['lampiran_foto']) ?>" target="_blank">
                                <img src="../../uploads/surat_masuk/<?= htmlspecialchars($data['lampiran_foto']) ?>" alt="Lampiran" class="img-fluid rounded mt-2">
                            </a>
                        <?php else: ?>
                            <p class="mt-2"><em>Tidak ada lampiran foto.</em></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-action">
                <a href="datasuratmasuk.php" class="btn btn-danger">Kembali</a>
                <a href="editsuratmasuk.php?id=<?= $data['No'] ?>" class="btn btn-success">Edit</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>
