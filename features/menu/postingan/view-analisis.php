<?php
session_start();
// Sesuaikan path ke file koneksi-mu
include '../../../koneksi.php';

$id_analisis = isset($_GET['id_analisis']) ? (int)$_GET['id_analisis'] : 0;

if ($id_analisis == 0) {
    header("Location: analisis-konten.php");
    exit();
}

// Ambil semua data analisis dan data postingan terkait (SUDAH DIPERBAIKI)
$query = mysqli_query($db, "SELECT ta.*, tp.caption AS judul, tp.gambar, tp.link_konten, tp.tanggal_posting, tk.nama_kategori
                             FROM tb_analisis_konten ta
                             JOIN tb_postingan tp ON ta.id_postingan = tp.id_postingan
                             JOIN tb_kategori tk ON tp.id_kategori = tk.id_kategori
                             WHERE ta.id_analisis = $id_analisis");
$data_analisis = mysqli_fetch_assoc($query);

if (!$data_analisis) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Data analisis tidak ditemukan!'];
    header("Location: analisis-konten.php");
    exit();
}

// Siapkan data untuk Chart.js
$view_data = [
    (int)($data_analisis['view_home'] ?? 0),
    (int)($data_analisis['view_profile'] ?? 0),
    (int)($data_analisis['view_others'] ?? 0)
];
$view_labels = ['Home', 'Profile', 'Others'];

$interactions_data = [
    (int)($data_analisis['likes'] ?? 0),
    (int)($data_analisis['comments'] ?? 0),
    (int)($data_analisis['share'] ?? 0),
    (int)($data_analisis['saves'] ?? 0)
];
$interactions_labels = ['Likes', 'Comments', 'Share', 'Saves'];

// Palet warna Kai Admin (bisa disesuaikan)
$chart_colors_1 = ['#36A2EB', '#FFCE56', '#4BC0C0', '#FF6384'];
$chart_colors_2 = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'];

// Variabel untuk template
$pageTitle = "Detail Analisis Konten";
$activeMenu = "analisis";

// Panggil header dan sidebar
include '../../../partials/header.php';
include '../../../partials/sidebar.php';
?>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Detail Analisis</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#"><i class="flaticon-home"></i></a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item">
                <a href="analisis-konten.php">Analisis Konten</a>
            </li>
            <li class="separator"><i class="flaticon-right-arrow"></i></li>
            <li class="nav-item">
                <a>Detail Data</a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Konten</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <?php
                    // Sesuaikan path ke folder uploads jika berbeda
                    $image_path = "../../uploads/postingan/" . htmlspecialchars($data_analisis['gambar']);
                    ?>
                    <?php if (!empty($data_analisis['gambar']) && file_exists($image_path)): ?>
                        <img src="<?= $image_path; ?>" alt="Gambar Konten" class="img-fluid rounded">
                    <?php else: ?>
                        <div class="img-fluid rounded d-flex align-items-center justify-content-center" style="height: 200px; background-color: #f8f9fa;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <h3><?= htmlspecialchars($data_analisis['judul']); ?></h3>
                    <div class="d-flex align-items-center mb-2">
                        <strong>Kategori:</strong>
                        <span class="badge badge-info ms-2"><?= htmlspecialchars($data_analisis['nama_kategori']); ?></span>
                    </div>
                    <p><strong>Tanggal Posting:</strong> <?= date('d M Y, H:i', strtotime($data_analisis['tanggal_posting'])); ?></p>
                    <p><strong>Link Konten:</strong>
                        <?php if (!empty($data_analisis['link_konten'])): ?>
                            <a href="<?= htmlspecialchars($data_analisis['link_konten']); ?>" target="_blank">Lihat Tautan</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </p>
                    <hr>
                    <p class="text-muted">Analisis Terakhir Diperbarui: <?= date('d M Y, H:i', strtotime($data_analisis['updated_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary bg-primary-gradient">
                <div class="card-body">
                    <h4 class="mb-1 fw-bold text-white">Overview Metrik</h4>
                    <div class="d-flex justify-content-between mt-4">
                        <p class="text-white mb-0">Total View</p>
                        <p class="text-white fw-bold mb-0"><?= number_format($data_analisis['view']); ?></p>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <p class="text-white mb-0">Interactions</p>
                        <p class="text-white fw-bold mb-0"><?= number_format($data_analisis['interactions']); ?></p>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <p class="text-white mb-0">Profile Activity</p>
                        <p class="text-white fw-bold mb-0"><?= number_format($data_analisis['profile_activity']); ?></p>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <p class="text-white mb-0">Account Reached</p>
                        <p class="text-white fw-bold mb-0"><?= number_format($data_analisis['account_reached']); ?></p>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <p class="text-white mb-0">Accounts Engaged</p>
                        <p class="text-white fw-bold mb-0"><?= number_format($data_analisis['accounts_engaged']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Distribusi View</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="viewChart"></canvas>
                    </div>
                    <div id="viewLegend" class="chart-legend mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Komposisi Interaksi</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="interactionsChart"></canvas>
                    </div>
                    <div id="interactionsLegend" class="chart-legend mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-center">
                    <a href="edit-analisis.php?id_analisis=<?= $id_analisis; ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Edit Analisis</a>
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteAnalisis(<?= $id_analisis ?>, '<?= htmlspecialchars(addslashes($data_analisis['judul'])) ?>')"><i class="fa fa-trash"></i> Hapus Analisis</button>
                    <a href="analisis-konten.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAnalisisModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title"><span class="fw-mediumbold">Konfirmasi</span><span class="fw-light"> Hapus</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data analisis untuk konten berikut?</p>
                <div class="alert alert-warning"><strong>Judul Konten:</strong> <span id="deleteAnalisisTitle"></span></div>
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
// Panggil footer
include '../../../partials/footer.php';
?>

<style>
    /* CSS untuk custom legend chart */
    .chart-legend ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }

    .chart-legend li {
        display: flex;
        align-items: center;
        font-size: 12px;
    }

    .chart-legend .color-box {
        width: 12px;
        height: 12px;
        margin-right: 8px;
        border-radius: 2px;
    }
</style>

<script>
    // Fungsi untuk membuka modal konfirmasi hapus
    function confirmDeleteAnalisis(id_analisis, judul_konten) {
        $('#deleteAnalisisTitle').text(judul_konten);
        $('#confirmDeleteAnalisisBtn').attr('href', 'hapus-analisis.php?id=' + id_analisis);
        $('#deleteAnalisisModal').modal('show');
    }

    // Fungsi untuk membuat legend HTML custom
    function generateLegend(chart, elementId) {
        const legendContainer = document.getElementById(elementId);
        if (!legendContainer) return;

        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
        if (total === 0) {
            legendContainer.innerHTML = '<p class="text-muted text-center">Tidak ada data untuk ditampilkan.</p>';
            return;
        }

        let legendHtml = '<ul>';
        chart.data.labels.forEach((label, index) => {
            const value = chart.data.datasets[0].data[index];
            const color = chart.data.datasets[0].backgroundColor[index];
            const percentage = ((value / total) * 100).toFixed(1);
            if (value > 0) {
                legendHtml += `
                    <li>
                        <span class="color-box" style="background-color:${color}"></span>
                        ${label}: ${value.toLocaleString('id-ID')} (${percentage}%)
                    </li>`;
            }
        });
        legendHtml += '</ul>';
        legendContainer.innerHTML = legendHtml;
    }

    // Inisialisasi Chart setelah halaman siap
    $(document).ready(function() {
        // Data dari PHP
        const viewData = <?php echo json_encode($view_data); ?>;
        const viewLabels = <?php echo json_encode($view_labels); ?>;
        const interactionsData = <?php echo json_encode($interactions_data); ?>;
        const interactionsLabels = <?php echo json_encode($interactions_labels); ?>;

        // Opsi dasar untuk kedua chart
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        let total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                        let value = data.datasets[0].data[tooltipItem.index];
                        let label = data.labels[tooltipItem.index] || '';
                        let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return `${label}: ${value.toLocaleString('id-ID')} (${percentage}%)`;
                    }
                }
            }
        };

        // Inisialisasi View Chart
        const viewCtx = document.getElementById('viewChart');
        if (viewCtx && viewData.reduce((a, b) => a + b, 0) > 0) {
            const viewChart = new Chart(viewCtx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: viewData,
                        backgroundColor: <?php echo json_encode($chart_colors_1); ?>
                    }],
                    labels: viewLabels
                },
                options: chartOptions
            });
            generateLegend(viewChart, 'viewLegend');
        } else if (viewCtx) {
            document.getElementById('viewLegend').innerHTML = '<p class="text-muted text-center">Tidak ada data View.</p>';
        }

        // Inisialisasi Interactions Chart
        const interactionsCtx = document.getElementById('interactionsChart');
        if (interactionsCtx && interactionsData.reduce((a, b) => a + b, 0) > 0) {
            const interactionsChart = new Chart(interactionsCtx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: interactionsData,
                        backgroundColor: <?php echo json_encode($chart_colors_2); ?>
                    }],
                    labels: interactionsLabels
                },
                options: chartOptions
            });
            generateLegend(interactionsChart, 'interactionsLegend');
        } else if (interactionsCtx) {
            document.getElementById('interactionsLegend').innerHTML = '<p class="text-muted text-center">Tidak ada data Interaksi.</p>';
        }
    });
</script>