<?php
session_start();
require_once '../../../koneksi.php';

// --- BAGIAN PROSES UPDATE (Method POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $id_postingan = (int)$_POST['id_postingan'];
    $id_kategori = $_POST['id_kategori'];
    $status = $_POST['status'];
    $caption = $_POST['caption'];
    $link_konten = !empty($_POST['link_konten']) ? $_POST['link_konten'] : null;
    $tanggal_posting = ($status == 'publish' && !empty($_POST['tanggal_posting'])) ? $_POST['tanggal_posting'] : null;

    $stmt_get = $db->prepare("SELECT gambar FROM tb_postingan WHERE id_postingan = ?");
    $stmt_get->bind_param("i", $id_postingan);
    $stmt_get->execute();
    $data_lama = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();
    $nama_file_gambar = $data_lama['gambar'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/postingan/';
        if (!empty($nama_file_gambar) && file_exists($upload_dir . $nama_file_gambar)) {
            @unlink($upload_dir . $nama_file_gambar);
        }
        $nama_file_gambar = 'post_' . uniqid() . '_' . basename($_FILES['gambar']['name']);
        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $nama_file_gambar)) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal mengupload gambar baru.'];
            header('Location: edit_postingan.php?id=' . $id_postingan);
            exit();
        }
    }
    
    $stmt_update = $db->prepare("UPDATE tb_postingan SET id_kategori=?, gambar=?, status=?, caption=?, link_konten=?, tanggal_posting=? WHERE id_postingan=?");
    $stmt_update->bind_param("isssssi", $id_kategori, $nama_file_gambar, $status, $caption, $link_konten, $tanggal_posting, $id_postingan);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Postingan berhasil diperbarui.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal memperbarui postingan.'];
    }
    $stmt_update->close();
    header('Location: data-postingan.php');
    exit();
}

// --- BAGIAN TAMPILAN (Method GET) ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: data-postingan.php');
    exit();
}
$id_postingan = (int)$_GET['id'];
$stmt = $db->prepare("SELECT * FROM tb_postingan WHERE id_postingan = ?");
$stmt->bind_param("i", $id_postingan);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    header('Location: data-postingan.php');
    exit();
}

$stmt_kategori = $db->prepare("SELECT id_kategori, nama_kategori FROM tb_kategori ORDER BY nama_kategori ASC");
$stmt_kategori->execute();
$result_kategori = $stmt_kategori->get_result();
$pageTitle = "Edit Postingan";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Edit Postingan</div>
            </div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_postingan" value="<?= $data['id_postingan'] ?>">
                    <div class="form-group">
                        <label for="id_kategori">Kategori</label>
                        <select name="id_kategori" id="id_kategori" required class="form-control">
                            <?php while($kategori = $result_kategori->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($kategori['id_kategori']) ?>" <?= $data['id_kategori'] == $kategori['id_kategori'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kategori['nama_kategori']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" required class="form-control">
                            <option value="draft" <?= $data['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="publish" <?= $data['status'] == 'publish' ? 'selected' : '' ?>>Publish</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="caption">Caption</label>
                        <textarea name="caption" id="caption" required class="form-control" rows="4"><?= htmlspecialchars($data['caption']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="link_konten">Link Konten</label>
                        <input type="url" name="link_konten" id="link_konten" class="form-control" value="<?= htmlspecialchars($data['link_konten']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_posting">Tanggal Posting</label>
                        <input type="datetime-local" name="tanggal_posting" id="tanggal_posting" class="form-control" value="<?= !empty($data['tanggal_posting']) ? htmlspecialchars(date('Y-m-d\TH:i', strtotime($data['tanggal_posting']))) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="gambar">Ganti Gambar</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                        <?php if(!empty($data['gambar'])): ?>
                            <p class="mt-2">Gambar saat ini: <br><img src="../../uploads/postingan/<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar" width="150" class="img-thumbnail mt-2"></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="submit" class="btn btn-success">Update Postingan</button>
                    <a href="data-postingan.php" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>