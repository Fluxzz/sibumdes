<?php
session_start();
// Sesuaikan path ke file koneksi-mu
include '../../../koneksi.php';

// Cek jika ada parameter success dari operasi analisis
$show_success = false;
$success_message = "";
if (isset($_GET['status']) && $_GET['status'] == 'created') {
    $show_success = true;
    $success_message = "Analisis konten berhasil dibuat!";
} elseif (isset($_GET['status']) && $_GET['status'] == 'updated') {
    $show_success = true;
    $success_message = "Analisis konten berhasil diperbarui!";
} elseif (isset($_GET['status']) && $_GET['status'] == 'deleted') {
    $show_success = true;
    $success_message = "Analisis konten berhasil dihapus!";
}

// Variabel untuk menandai menu aktif di template
$pageTitle = "Analisis Konten";
$activeMenu = "analisis"; // Sesuaikan nama ini dengan ID menu di sidebar-mu

// Panggil header (yang berisi <head>, css, dll.)
// Ganti path ini jika berbeda
include '../../../partials/header.php';
include '../../../partials/sidebar.php';
?>

<div class="page-inner">

    <div class="page-header">
        <h4 class="page-title">Analisis Konten</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#"><i class="flaticon-home"></i></a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Konten</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Analisis Konten</a>
            </li>
        </ul>
    </div>

    <?php
    // Ambil statistik untuk KPI Cards (menggunakan mysqli_query)
    $total_konten_q = mysqli_query($db, "SELECT COUNT(id_postingan) as total FROM tb_postingan WHERE status = 'publish'");
    $total_konten = mysqli_fetch_assoc($total_konten_q)['total'] ?? 0;

    $sudah_dianalisis_q = mysqli_query($db, "
        SELECT COUNT(DISTINCT a.id_postingan) as total 
        FROM tb_analisis_konten a 
        JOIN tb_postingan p ON a.id_postingan = p.id_postingan 
        WHERE p.status = 'publish'
    ");
    $sudah_dianalisis = mysqli_fetch_assoc($sudah_dianalisis_q)['total'] ?? 0;

    $belum_dianalisis = $total_konten - $sudah_dianalisis;
    ?>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Konten Published</p>
                                <h4 class="card-title"><?= $total_konten ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Sudah Dianalisis</p>
                                <h4 class="card-title"><?= $sudah_dianalisis ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Menunggu Analisis</p>
                                <h4 class="card-title"><?= $belum_dianalisis ?></h4>
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
                        <h4 class="card-title">Daftar Konten untuk Analisis</h4>
                        <button class="btn btn-info btn-round ms-auto" onclick="refreshData()">
                            <i class="fa fa-sync-alt" id="refresh-icon"></i> Refresh Data
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($show_success): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($success_message) ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Konten</th>
                                    <th>Kategori</th>
                                    <th>Link</th>
                                    <th>Tanggal Posting</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                // Query untuk mengambil data tabel, disesuaikan dengan kode asli
                                $query = mysqli_query($db, "
                                    SELECT 
                                        tp.id_postingan, 
                                        tp.caption AS judul, -- Menggunakan alias untuk 'judul'
                                        tk.nama_kategori, 
                                        tp.link_konten, 
                                        tp.tanggal_posting, 
                                        ta.id_analisis
                                    FROM tb_postingan tp
                                    JOIN tb_kategori tk ON tp.id_kategori = tk.id_kategori
                                    LEFT JOIN tb_analisis_konten ta ON tp.id_postingan = ta.id_postingan
                                    WHERE tp.status = 'publish'
                                    ORDER BY tp.tanggal_posting DESC
                                ");

                                if (mysqli_num_rows($query) > 0):
                                    while ($row = mysqli_fetch_assoc($query)):
                                ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['judul']) ?></td>
                                            <td>
                                                <span class="badge badge-info"><?= htmlspecialchars($row['nama_kategori']) ?></span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($row['link_konten'])): ?>
                                                    <a href="<?= htmlspecialchars($row['link_konten']) ?>" target="_blank" class="btn btn-link btn-primary btn-sm p-0" title="Lihat Link">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d M Y, H:i', strtotime($row['tanggal_posting'])) ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <?php if (empty($row['id_analisis'])): ?>
                                                        <a href="create-analisis.php?id_postingan=<?= $row['id_postingan'] ?>" class="btn btn-link btn-success" title="Buat Analisis">
                                                            <i class="fa fa-plus-circle"></i> Buat
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="view-analisis.php?id_analisis=<?= $row['id_analisis'] ?>" class="btn btn-link btn-info" title="Lihat Analisis">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="edit_analisis.php?id_analisis=<?= $row['id_analisis'] ?>" class="btn btn-link btn-primary" title="Edit Analisis">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-link btn-danger" title="Hapus Analisis" onclick="confirmDeleteAnalisis(<?= $row['id_analisis'] ?>, '<?= htmlspecialchars(addslashes($row['judul'])) ?>')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada konten dengan status 'publish' yang tersedia.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAnalisisModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Konfirmasi</span>
                    <span class="fw-light"> Hapus Analisis</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data analisis untuk konten berikut?</p>
                <div class="alert alert-warning">
                    <strong>Judul Konten:</strong> <span id="deleteAnalisisTitle"></span>
                </div>
                <p class="text-danger"><small><i class="fa fa-exclamation-triangle"></i> Tindakan ini tidak dapat dibatalkan!</small></p>
            </div>
            <div class="modal-footer no-bd">
                <a href="#" id="confirmDeleteAnalisisBtn" class="btn btn-danger">Ya, Hapus</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<?php
// Panggil footer (yang berisi penutup tag html, js, dll.)
// Ganti path ini jika berbeda
include '../../../partials/footer.php';
?>

<script>
    // Fungsi untuk Refresh Data
    function refreshData() {
        var refreshIcon = $('#refresh-icon');
        refreshIcon.addClass('fa-spin'); // Tambahkan animasi putar

        // Reload halaman setelah 1 detik untuk memberi efek visual
        setTimeout(function() {
            location.reload();
        }, 1000);
    }

    // Fungsi untuk membuka modal konfirmasi hapus
    function confirmDeleteAnalisis(id_analisis, judul_konten) {
        // Isi judul konten di modal
        $('#deleteAnalisisTitle').text(judul_konten);
        // Set link tombol hapus di modal
        $('#confirmDeleteAnalisisBtn').attr('href', 'hapus-analisis.php?id=' + id_analisis);
        // Tampilkan modal
        $('#deleteAnalisisModal').modal('show');
    }

    // Fungsi untuk menyembunyikan notifikasi setelah beberapa detik
    $(document).ready(function() {
        // Hapus parameter 'status' dari URL agar notif tidak muncul lagi saat refresh
        if (window.history.replaceState) {
            const url = new URL(window.location.href);
            url.searchParams.delete('status');
            window.history.replaceState({
                path: url.href
            }, '', url.href);
        }

        // Sembunyikan alert setelah 5 detik
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 5000);
    });
</script>