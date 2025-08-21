<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

// --- BAGIAN LOGIKA PHP ---

// 1. Ambil filter tahun & bulan dengan aman
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');
$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('m');

// 2. Ambil data statistik
$total_pengunjung = $db->query("SELECT SUM(jumlah_pengunjung) as total FROM tb_usaha_penjualan")->fetch_assoc()['total'] ?? 0;
$total_pendapatan = $db->query("SELECT SUM(total_pendapatan) as total FROM tb_usaha_penjualan")->fetch_assoc()['total'] ?? 0;
$total_pengeluaran = $db->query("SELECT SUM(pengeluaran) as total FROM tb_usaha_pengeluaran")->fetch_assoc()['total'] ?? 0;
$saldo_kas = $total_pendapatan - $total_pengeluaran;

// 3. Siapkan data untuk grafik
// Data Bulanan
$stmt_pb = $db->prepare("SELECT MONTH(tanggal) as bulan, SUM(jumlah_pengunjung) as total FROM tb_usaha_penjualan WHERE YEAR(tanggal) = ? GROUP BY MONTH(tanggal)");
$stmt_pb->bind_param("i", $tahun);
$stmt_pb->execute();
$pengunjung_bulanan_raw = $stmt_pb->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_pb->close();
$pengunjung_bulanan = array_fill(1, 12, 0);
foreach ($pengunjung_bulanan_raw as $data) { $pengunjung_bulanan[$data['bulan']] = (int)$data['total']; }
$pengunjung_bulanan = array_values($pengunjung_bulanan);

$stmt_pendapatan_b = $db->prepare("SELECT MONTH(tanggal) as bulan, SUM(total_pendapatan) as total FROM tb_usaha_penjualan WHERE YEAR(tanggal) = ? GROUP BY MONTH(tanggal)");
$stmt_pendapatan_b->bind_param("i", $tahun);
$stmt_pendapatan_b->execute();
$pendapatan_bulanan_raw = $stmt_pendapatan_b->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_pendapatan_b->close();
$pendapatan_bulanan = array_fill(1, 12, 0);
foreach ($pendapatan_bulanan_raw as $data) { $pendapatan_bulanan[$data['bulan']] = (float)$data['total']; }
$pendapatan_bulanan = array_values($pendapatan_bulanan);

// Data Harian
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
$stmt_ph = $db->prepare("SELECT DAY(tanggal) as hari, SUM(jumlah_pengunjung) as total FROM tb_usaha_penjualan WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ? GROUP BY DAY(tanggal)");
$stmt_ph->bind_param("ii", $tahun, $bulan);
$stmt_ph->execute();
$pengunjung_harian_raw = $stmt_ph->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_ph->close();
$pengunjung_harian = array_fill(1, $days_in_month, 0);
foreach ($pengunjung_harian_raw as $data) { $pengunjung_harian[$data['hari']] = (int)$data['total']; }
$pengunjung_harian = array_values($pengunjung_harian);

$stmt_pendapatan_h = $db->prepare("SELECT DAY(tanggal) as hari, SUM(total_pendapatan) as total FROM tb_usaha_penjualan WHERE YEAR(tanggal) = ? AND MONTH(tanggal) = ? GROUP BY DAY(tanggal)");
$stmt_pendapatan_h->bind_param("ii", $tahun, $bulan);
$stmt_pendapatan_h->execute();
$pendapatan_harian_raw = $stmt_pendapatan_h->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_pendapatan_h->close();
$pendapatan_harian = array_fill(1, $days_in_month, 0);
foreach ($pendapatan_harian_raw as $data) { $pendapatan_harian[$data['hari']] = (float)$data['total']; }
$pendapatan_harian = array_values($pendapatan_harian);

// Variabel lain untuk tampilan
$tahun_list_result = $db->query("SELECT DISTINCT YEAR(tanggal) as tahun FROM tb_usaha_penjualan ORDER BY tahun DESC");
$bulan_nama_lengkap = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$bulan_nama_singkat = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
$label_harian = range(1, $days_in_month);

$pageTitle = "Visualisasi Usaha";
$activeMenu = "usaha"; 
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fas fa-filter"></i> Filter Data</div>
            </div>
            <div class="card-body">
                <form method="get" class="row">
                    <div class="col-md-5 form-group">
                        <label>Tahun:</label>
                        <select name="tahun" class="form-control">
                            <?php while($t = $tahun_list_result->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($t['tahun']) ?>" <?= $tahun == $t['tahun'] ? 'selected' : '' ?>><?= htmlspecialchars($t['tahun']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-5 form-group">
                        <label>Bulan:</label>
                        <select name="bulan" class="form-control">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= $bulan == $i ? 'selected' : '' ?>><?= $bulan_nama_lengkap[$i - 1] ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body "><div class="row align-items-center">
                <div class="col-icon"><div class="icon-big text-center icon-primary bubble-shadow-small"><i class="fas fa-users"></i></div></div>
                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers">
                    <p class="card-category">Total Pengunjung</p>
                    <h4 class="card-title"><?= number_format($total_pengunjung) ?></h4>
                </div></div>
            </div></div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body"><div class="row align-items-center">
                <div class="col-icon"><div class="icon-big text-center icon-success bubble-shadow-small"><i class="fas fa-dollar-sign"></i></div></div>
                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers">
                    <p class="card-category">Total Penjualan</p>
                    <h4 class="card-title">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h4>
                </div></div>
            </div></div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body"><div class="row align-items-center">
                <div class="col-icon"><div class="icon-big text-center icon-warning bubble-shadow-small"><i class="fas fa-shopping-cart"></i></div></div>
                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers">
                    <p class="card-category">Total Pengeluaran</p>
                    <h4 class="card-title">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></h4>
                </div></div>
            </div></div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body"><div class="row align-items-center">
                <div class="col-icon"><div class="icon-big text-center icon-info bubble-shadow-small"><i class="fas fa-wallet"></i></div></div>
                <div class="col col-stats ms-3 ms-sm-0"><div class="numbers">
                    <p class="card-category">Saldo Kas</p>
                    <h4 class="card-title">Rp <?= number_format($saldo_kas, 0, ',', '.') ?></h4>
                </div></div>
            </div></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Visualisasi Data</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-title">Pengunjung per Bulan (<?= $tahun ?>)</div>
                        <div class="chart-container"><canvas id="pengunjungBulananChart"></canvas></div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-title">Pengunjung Harian (<?= $bulan_nama_lengkap[$bulan - 1] . ' ' . $tahun ?>)</div>
                        <div class="chart-container"><canvas id="pengunjungHarianChart"></canvas></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card-title">Penjualan per Bulan (<?= $tahun ?>)</div>
                        <div class="chart-container"><canvas id="pendapatanBulananChart"></canvas></div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-title">Penjualan Harian (<?= $bulan_nama_lengkap[$bulan - 1] . ' ' . $tahun ?>)</div>
                        <div class="chart-container"><canvas id="pendapatanHarianChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Definisikan variabel JS untuk keempat grafik
$pageJS = "
    // Grafik 1: Pengunjung Bulanan
    new Chart(document.getElementById('pengunjungBulananChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: " . json_encode($bulan_nama_singkat) . ",
            datasets: [{
                label: 'Pengunjung',
                data: " . json_encode($pengunjung_bulanan) . ",
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
            }]
        }
    });

    // Grafik 2: Pengunjung Harian
    new Chart(document.getElementById('pengunjungHarianChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: " . json_encode($label_harian) . ",
            datasets: [{
                label: 'Pengunjung Harian',
                data: " . json_encode($pengunjung_harian) . ",
                borderColor: 'rgba(54, 162, 235, 1)',
                tension: 0.1
            }]
        }
    });

    // Grafik 3: Pendapatan Bulanan
    new Chart(document.getElementById('pendapatanBulananChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: " . json_encode($bulan_nama_singkat) . ",
            datasets: [{
                label: 'Penjualan (Rp)',
                data: " . json_encode($pendapatan_bulanan) . ",
                backgroundColor: 'rgba(28, 200, 138, 0.8)',
            }]
        }
    });

    // Grafik 4: Pendapatan Harian
    new Chart(document.getElementById('pendapatanHarianChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: " . json_encode($label_harian) . ",
            datasets: [{
                label: 'Penjualan Harian (Rp)',
                data: " . json_encode($pendapatan_harian) . ",
                borderColor: 'rgba(246, 194, 62, 1)',
                tension: 0.1
            }]
        }
    });
";
?>
<?php include '../../../partials/footer.php'; ?>