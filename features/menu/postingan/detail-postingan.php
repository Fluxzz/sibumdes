<?php
session_start();
// [PATH UPDATE] Menggunakan path baru
require_once '../../../koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ID Postingan tidak valid.'];
    header('Location: data-postingan.php');
    exit();
}
$id_postingan = (int)$_GET['id'];

// [KEAMANAN] Menggunakan prepared statement untuk mengambil data
$stmt = $db->prepare("
    SELECT p.*, k.nama_kategori 
    FROM tb_postingan p 
    LEFT JOIN tb_kategori k ON p.id_kategori = k.id_kategori 
    WHERE p.id_postingan = ?
");
$stmt->bind_param("i", $id_postingan);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Data Postingan tidak ditemukan.'];
    header('Location: data-postingan.php');
    exit();
}

// Variabel untuk template
$pageTitle = "Detail Postingan";
$activeMenu = "postingan"; 
?>

<?php
// [PATH UPDATE] Menggunakan path baru
include '../../../partials/header.php';
?>

<?php
// [PATH UPDATE] Menggunakan path baru
include '../../../partials/sidebar.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Detail Postingan</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php if(!empty($data['gambar'])): ?>
                            <img src="../../uploads/postingan/<?= htmlspecialchars($data['gambar']) ?>" class="img-fluid rounded" alt="Gambar Postingan">
                        <?php else: ?>
                             <p><em>Tidak ada gambar untuk postingan ini.</em></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-striped">
                            <tr>
                                <td width="30%"><strong>ID Postingan</strong></td>
                                <td>: <?= htmlspecialchars($data['id_postingan']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>: <?= htmlspecialchars($data['nama_kategori']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: <span class="badge <?= $data['status'] == 'publish' ? 'badge-success' : 'badge-warning' ?>"><?= htmlspecialchars(ucfirst($data['status'])) ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Posting</strong></td>
                                <td>: <?= !empty($data['tanggal_posting']) ? htmlspecialchars(date('d F Y, H:i', strtotime($data['tanggal_posting']))) . ' WIB' : '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Link Konten</strong></td>
                                <td>: <a href="<?= htmlspecialchars($data['link_konten']) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($data['link_konten']) ?></a></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Caption</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><?= nl2br(htmlspecialchars($data['caption'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-action">
                <a href="data-postingan.php" class="btn btn-danger">Kembali</a>
                <a href="edit_postingan.php?id=<?= $data['id_postingan'] ?>" class="btn btn-success">Edit</a>
            </div>
        </div>
    </div>
</div>

<?php
// [PATH UPDATE] Menggunakan path baru
include '../../../partials/footer.php';
?>