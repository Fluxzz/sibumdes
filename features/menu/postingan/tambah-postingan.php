<?php
session_start();
// [FIX] Tambahkan dua baris ini untuk koneksi dan autentikasi
require_once '../../../koneksi.php';

// --- BAGIAN PROSES (jika form disubmit) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $id_kategori = $_POST['id_kategori'];
    $status = $_POST['status'];
    $caption = $_POST['caption'];
    $link_konten = !empty($_POST['link_konten']) ? $_POST['link_konten'] : null;
    $tanggal_posting = ($status == 'publish' && !empty($_POST['tanggal_posting'])) ? $_POST['tanggal_posting'] : date('Y-m-d H:i:s');

    $nama_file_gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        // Ganti path ini ke direktori upload postingan Anda
        $upload_dir = '../../uploads/postingan/'; 
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $nama_file_gambar = 'post_' . uniqid() . '_' . basename($_FILES['gambar']['name']);
        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_dir . $nama_file_gambar)) {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal mengupload gambar.'];
            header('Location: tambah-postingan.php');
            exit();
        }
    }
    
    $stmt = $db->prepare("INSERT INTO tb_postingan (id_kategori, gambar, status, caption, link_konten, tanggal_posting) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id_kategori, $nama_file_gambar, $status, $caption, $link_konten, $tanggal_posting);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Postingan baru berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menambahkan postingan.'];
    }
    $stmt->close();
    
    header('Location: data-postingan.php');
    exit();
}

// --- BAGIAN TAMPILAN (untuk menampilkan form) ---
$stmt_kategori = $db->prepare("SELECT id_kategori, nama_kategori FROM tb_kategori ORDER BY nama_kategori ASC");
$stmt_kategori->execute();
$result_kategori = $stmt_kategori->get_result();
$pageTitle = "Tambah Postingan Baru";
?>

<?php include '../../../partials/header.php'; ?>
<?php include '../../../partials/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Tambah Postingan</div>
            </div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="id_kategori">Kategori</label>
                        <select name="id_kategori" id="id_kategori" required class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            <?php while($kategori = $result_kategori->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($kategori['id_kategori']) ?>"><?= htmlspecialchars($kategori['nama_kategori']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" required class="form-control">
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="caption">Caption</label>
                        <textarea name="caption" id="caption" required class="form-control" rows="4" placeholder="Masukkan isi caption..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="link_konten">Link Konten (Opsional)</label>
                        <input type="url" name="link_konten" id="link_konten" class="form-control" placeholder="https://contoh.com">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_posting">Tanggal Posting (jika publish)</label>
                        <input type="datetime-local" name="tanggal_posting" id="tanggal_posting" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="gambar">Upload Gambar</label>
                        <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="card-action">
                    <button type="submit" name="submit" class="btn btn-success">Simpan Postingan</button>
                    <a href="data-postingan.php" class="btn btn-danger">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../../partials/footer.php'; ?>