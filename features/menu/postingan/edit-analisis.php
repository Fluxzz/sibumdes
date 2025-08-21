<?php
session_start();
// Sesuaikan path ke file koneksi-mu
include '../../../koneksi.php';

$id_analisis = isset($_GET['id_analisis']) ? (int)$_GET['id_analisis'] : 0;

if ($id_analisis == 0) {
    header("Location: analisis-konten.php");
    exit();
}

// Ambil data analisis yang ada untuk di-edit (SUDAH DIPERBAIKI)
$query = mysqli_query($db, "SELECT ta.*, tp.caption AS judul, tk.nama_kategori, tp.link_konten, tp.tanggal_posting
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

// Proses form submission untuk UPDATE
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

    // Hitung ulang metrik turunan
    $interactions = $likes + $share + $saves + $comments;
    $profile_activity = $profile_visits + $follows;

    // Siapkan dan eksekusi query UPDATE menggunakan prepared statements
    $stmt = $db->prepare("UPDATE tb_analisis_konten SET
                             view = ?, interactions = ?, profile_activity = ?, account_reached = ?, accounts_engaged = ?,
                             likes = ?, share = ?, saves = ?, comments = ?, profile_visits = ?, follows = ?,
                             view_total = ?, view_home = ?, view_profile = ?, view_others = ?
                         WHERE id_analisis = ?");

    // 'view' diisi dengan 'view_total'
    $stmt->bind_param(
        "iiiiiiiiiiiiiiii",
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
        $view_others,
        $id_analisis
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Analisis konten berhasil diperbarui!'];
        header("Location: analisis-konten.php?status=updated");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Variabel untuk template
$pageTitle = "Edit Analisis Konten";
$activeMenu = "analisis";

// Panggil header dan sidebar
include '../../../partials/header.php';
include '../../../partials/sidebar.php';
?>

<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Edit Analisis Konten</h4>
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
                <a>Edit Data</a>
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
                                <p><?= htmlspecialchars($data_analisis['judul']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Kategori</strong></label>
                                <p><span class="badge badge-info"><?= htmlspecialchars($data_analisis['nama_kategori']); ?></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit Data Analisis</div>
                </div>
                <form action="edit-analisis.php?id_analisis=<?= $id_analisis; ?>" method="POST">
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
                                    <input type="number" class="form-control" id="view_total" name="view_total" required min="0" value="<?= htmlspecialchars($data_analisis['view_total'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="view_home">Views (Home)</label>
                                    <input type="number" class="form-control" id="view_home" name="view_home" min="0" value="<?= htmlspecialchars($data_analisis['view_home'] ?? 0) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="view_profile">Views (Profile)</label>
                                    <input type="number" class="form-control" id="view_profile" name="view_profile" min="0" value="<?= htmlspecialchars($data_analisis['view_profile'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="view_others">Views (Others)</label>
                                    <input type="number" class="form-control" id="view_others" name="view_others" min="0" value="<?= htmlspecialchars($data_analisis['view_others'] ?? 0) ?>">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="likes">Likes <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="likes" name="likes" required min="0" value="<?= htmlspecialchars($data_analisis['likes'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="comments">Comments <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="comments" name="comments" required min="0" value="<?= htmlspecialchars($data_analisis['comments'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="share">Share <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="share" name="share" required min="0" value="<?= htmlspecialchars($data_analisis['share'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="form-group">
                                    <label for="saves">Saves <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="saves" name="saves" required min="0" value="<?= htmlspecialchars($data_analisis['saves'] ?? 0) ?>">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="profile_visits">Profile Visits <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="profile_visits" name="profile_visits" required min="0" value="<?= htmlspecialchars($data_analisis['profile_visits'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="follows">Follows <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="follows" name="follows" required min="0" value="<?= htmlspecialchars($data_analisis['follows'] ?? 0) ?>">
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_reached">Account Reached <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="account_reached" name="account_reached" required min="0" value="<?= htmlspecialchars($data_analisis['account_reached'] ?? 0) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="accounts_engaged">Accounts Engaged <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="accounts_engaged" name="accounts_engaged" required min="0" value="<?= htmlspecialchars($data_analisis['accounts_engaged'] ?? 0) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i> Update Analisis
                        </button>
                        <a href="analisis-konten.php" class="btn btn-danger">
                            <i class="fa fa-times"></i> Batal
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