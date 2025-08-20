<?php
session_start();
// Sesuaikan path ke file koneksi-mu
include '../../../koneksi.php';

$id_postingan = isset($_GET['id_postingan']) ? (int)$_GET['id_postingan'] : 0;

if ($id_postingan == 0) {
    // Jika tidak ada ID, kembali ke halaman utama analisis
    header("Location: analisis-konten.php");
    exit();
}

// Ambil data postingan untuk ditampilkan di form (SUDAH DIPERBAIKI)
$post_query = mysqli_query($db, "SELECT tp.caption AS judul, tk.nama_kategori, tp.link_konten, tp.tanggal_posting
                                 FROM tb_postingan tp
                                 JOIN tb_kategori tk ON tp.id_kategori = tk.id_kategori
                                 WHERE tp.id_postingan = $id_postingan");
$post_data = mysqli_fetch_assoc($post_query);

if (!$post_data) {
    // Jika data postingan tidak ditemukan
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Postingan tidak ditemukan!'];
    header("Location: analisis-konten.php");
    exit();
}

// Cek apakah analisis untuk postingan ini sudah ada
$check_analisis = mysqli_query($db, "SELECT id_analisis FROM tb_analisis_konten WHERE id_postingan = $id_postingan");
if (mysqli_num_rows($check_analisis) > 0) {
    // Jika sudah ada, redirect ke halaman edit
    $existing_analisis = mysqli_fetch_assoc($check_analisis);
    header("Location: edit_analisis.php?id_analisis=" . $existing_analisis['id_analisis']);
    exit();
}

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil semua data dari form
    $view_total = (int)$_POST['view_total'];
    $view_home = (int)$_POST['view_home'];
    $view_profile = (int)$_POST['view_profile'];
    $view_others = (int)$_POST['view_others'];
    $likes = (int)$_POST['likes'];
    $share = (int)$_POST['share'];
    $saves = (int)$_POST['saves'];
    $comments = (int)$_POST['comments'];
    $profile_visits = (int)$_POST['profile_visits'];
    $follows = (int)$_POST['follows'];
    $accounts_engaged = (int)$_POST['accounts_engaged'];
    $account_reached = (int)$_POST['account_reached'];

    // Hitung metrik turunan
    $interactions = $likes + $share + $saves + $comments;
    $profile_activity = $profile_visits + $follows;

    // Siapkan dan eksekusi query INSERT
    $stmt = $db->prepare("INSERT INTO tb_analisis_konten (
                             id_postingan, view, interactions, profile_activity, account_reached, accounts_engaged,
                             likes, share, saves, comments, profile_visits, follows,
                             view_total, view_home, view_profile, view_others
                         ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // 'view' diisi dengan 'view_total'
    $stmt->bind_param(
        "iiiiiiiiiiiiiiii",
        $id_postingan,
        $view_total,
        $interactions,
        $profile_activity,
        $account_reached,
        $accounts_engaged,
        $likes,
        $share,
        $saves,
        $comments,
        $profile_visits,
        $follows,
        $view_total,
        $view_home,
        $view_profile,
        $view_others
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Analisis konten berhasil dibuat!'];
        header("Location: analisis-konten.php?status=created");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Variabel untuk template
$pageTitle = "Buat Analisis Konten";
$activeMenu = "analisis";

// Panggil header
include '../../../partials/header.php';

// Panggil sidebar
include '../../../partials/sidebar.php';
?>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Buat Analisis Konten</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#"><i class="flaticon-home"></i></a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="analisis-konten.php">Analisis Konten</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a>Buat Data</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Detail Konten</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Judul Konten</strong></label>
                                <p><?= htmlspecialchars($post_data['judul']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Kategori</strong></label>
                                <p><span class="badge badge-info"><?= htmlspecialchars($post_data['nama_kategori']); ?></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Tanggal Posting</strong></label>
                                <p><?= date('d M Y, H:i', strtotime($post_data['tanggal_posting'])); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Link Konten</strong></label>
                                <p><a href="<?= htmlspecialchars($post_data['link_konten']); ?>" target="_blank">Lihat Tautan</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Input Data Analisis</div>
                </div>
                <form action="create-analisis.php?id_postingan=<?= $id_postingan; ?>" method="POST">
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="view_total">Total Views <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="view_total" name="view_total" required min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="view_home">Views (Home)</label>
                                    <input type="number" class="form-control" id="view_home" name="view_home" min="0" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="view_profile">Views (Profile)</label>
                                    <input type="number" class="form-control" id="view_profile" name="view_profile" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="view_others">Views (Others)</label>
                                    <input type="number" class="form-control" id="view_others" name="view_others" min="0" value="0">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="likes">Likes <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="likes" name="likes" required min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="comments">Comments <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="comments" name="comments" required min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="share">Share <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="share" name="share" required min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="saves">Saves <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="saves" name="saves" required min="0" value="0">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile_visits">Profile Visits <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="profile_visits" name="profile_visits" required min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="follows">Follows <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="follows" name="follows" required min="0" value="0">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_reached">Account Reached <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="account_reached" name="account_reached" required min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="accounts_engaged">Accounts Engaged <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="accounts_engaged" name="accounts_engaged" required min="0" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Submit Analisis
                        </button>
                        <a href="analisis-konten.php" class="btn btn-danger">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Panggil footer
include '../../../partials/footer.php';
?>