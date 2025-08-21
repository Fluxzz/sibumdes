<?php
session_start();
require_once '../../../koneksi.php';


// --- Logika PHP ---

// Ambil statistik postingan
$total_posts = $db->query("SELECT COUNT(*) AS total FROM tb_postingan")->fetch_assoc()['total'] ?? 0;
$published_posts = $db->query("SELECT COUNT(*) AS published FROM tb_postingan WHERE status='publish'")->fetch_assoc()['published'] ?? 0;
$draft_posts = $db->query("SELECT COUNT(*) AS draft FROM tb_postingan WHERE status='draft'")->fetch_assoc()['draft'] ?? 0;
$today_posts = $db->query("SELECT COUNT(*) AS today FROM tb_postingan WHERE DATE(tanggal_posting) = CURDATE()")->fetch_assoc()['today'] ?? 0;

// Ambil semua postingan untuk tabel
$stmt = $db->prepare("
    SELECT p.id_postingan, p.gambar, p.status, p.caption, k.nama_kategori
    FROM tb_postingan p
    LEFT JOIN tb_kategori k ON p.id_kategori = k.id_kategori
    ORDER BY p.id_postingan DESC
");
$stmt->execute();
$posts_result = $stmt->get_result();

// Variabel untuk template
$pageTitle = "Manajemen Postingan";
$activeMenu = "postingan"; // Untuk menandai menu aktif di sidebar
?>

<?php
// Memanggil header.php di awal
include '../../../partials/header.php';
?>

<?php
// Memanggil sidebar.php yang sudah berisi navbar dan pembuka konten
include '../../../partials/sidebar.php';
?>

<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon"><div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-newspaper"></i></div></div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Konten</p>
                            <h4 class="card-title"><?= $total_posts ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon"><div class="icon-big text-center icon-success bubble-shadow-small"><i class="fas fa-check-circle"></i></div></div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Published</p>
                            <h4 class="card-title"><?= $published_posts ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon"><div class="icon-big text-center icon-warning bubble-shadow-small"><i class="fas fa-edit"></i></div></div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Draft</p>
                            <h4 class="card-title"><?= $draft_posts ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Daftar Postingan</h4>
                    <a href="tambah-postingan.php" class="btn btn-primary btn-round ms-auto">
                        <i class="fa fa-plus"></i> Tambah Postingan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['message'])) {
                    $type = $_SESSION['message']['type'];
                    $text = $_SESSION['message']['text'];
                    echo "<div class='alert alert-{$type}'>{$text}</div>";
                    unset($_SESSION['message']);
                }
                ?>
                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Caption</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $posts_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_postingan']) ?></td>
                                <td>
                                    <?php if (!empty($row['gambar'])): ?>
                                        <img src="../../uploads/postingan/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar" width="100" class="img-thumbnail">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                <td>
                                    <span class="badge <?= $row['status'] == 'publish' ? 'badge-success' : 'badge-warning' ?>">
                                        <?= htmlspecialchars(ucfirst($row['status'])) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars(substr($row['caption'], 0, 100)) ?>...</td>
                                <td>
                                    <div class="form-button-action">
                                        <a href="detail-postingan.php?id=<?= $row['id_postingan'] ?>" class="btn btn-link btn-info" title="Detail"><i class="fa fa-eye"></i></a>
                                        <a href="edit_postingan.php?id=<?= $row['id_postingan'] ?>" class="btn btn-link btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="hapus_postingan.php?id=<?= $row['id_postingan'] ?>" class="btn btn-link btn-danger" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus postingan ini?')"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../../../partials/footer.php';
?>