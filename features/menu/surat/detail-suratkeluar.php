<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'ID Surat tidak valid.'];
    header('Location: datasuratkeluar.php');
    exit();
}
$id = (int)$_GET['id'];

$stmt = $db->prepare("SELECT * FROM tb_arsip_surat_keluar WHERE No = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Data Surat tidak ditemukan.'];
    header('Location: datasuratkeluar.php');
    exit();
}

$pageTitle = "Detail Surat Keluar";
$activeMenu = "surat"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><div class="card-title">Detail Surat Keluar: <?= htmlspecialchars($data['nomor_surat']) ?></div></div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr><td width="30%"><strong>Nomor Urut</strong></td><td>: <?= htmlspecialchars($data['No']) ?></td></tr>
                    <tr><td><strong>Tanggal Keluar</strong></td><td>: <?= htmlspecialchars(date('d F Y', strtotime($data['tanggal_keluar']))) ?></td></tr>
                    <tr><td><strong>Nomor Surat</strong></td><td>: <?= htmlspecialchars($data['nomor_surat']) ?></td></tr>
                    <tr><td><strong>Penerima</strong></td><td>: <?= htmlspecialchars($data['penerima']) ?></td></tr>
                    <tr><td><strong>Perihal</strong></td><td>: <?= htmlspecialchars($data['perihal']) ?></td></tr>
                    <tr><td><strong>Kode Arsip</strong></td><td>: <?= htmlspecialchars($data['kode']) ?></td></tr>
                    <tr><td><strong>Keterangan</strong></td><td>: <?= nl2br(htmlspecialchars($data['keterangan'])) ?></td></tr>
                    <tr>
                        <td><strong>File Surat</strong></td>
                        <td>: 
                            <?php if(!empty($data['file_surat'])): ?>
                                <a href="../uploads/surat/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Unduh File</a>
                                <a href="../uploads/surat/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-download"></i> Unduh File</a>
                            <?php else: ?> - <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-action">
                <a href="datasuratkeluar.php" class="btn btn-danger">Kembali</a>
                <a href="editsuratkeluar.php?id=<?= $data['No'] ?>" class="btn btn-success">Edit</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>