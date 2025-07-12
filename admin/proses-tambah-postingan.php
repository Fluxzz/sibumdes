<?php
session_start();
include "../koneksi/koneksi.php";

// Fungsi untuk upload gambar
function uploadGambar($file)
{
    $targetDir = "../uploads/";
    $fileName = basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validasi tipe file
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $validExtensions)) {
        return ['error' => "Hanya file gambar (jpg, jpeg, png, gif) yang diperbolehkan."];
    }

    // Cek ukuran file max 5MB
    if ($file["size"] > 5 * 1024 * 1024) {
        return ['error' => "Ukuran file maksimal 5MB."];
    }

    // Rename file agar unik (timestamp + nama asli)
    $newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\.]/", "_", $fileName);
    $targetFile = $targetDir . $newFileName;

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ['success' => true, 'filename' => $newFileName];
    } else {
        return ['error' => "Gagal mengupload file gambar."];
    }
}

// Ambil data POST
$status = $_POST['status'];
$caption = mysqli_real_escape_string($db, $_POST['caption']);
$link_konten = $status === 'publish' ? mysqli_real_escape_string($db, $_POST['link_konten']) : '';
$tanggal_posting = $_POST['tanggal_posting']; // format datetime-local, seharusnya sesuai database datetime
$id_kategori = isset($_POST['id_kategori']) ? intval($_POST['id_kategori']) : 0;
$kategori_baru = trim($_POST['kategori_baru']);

// Validasi input wajib
if (empty($status) || empty($caption) || empty($tanggal_posting)) {
    die("Data tidak lengkap, harap lengkapi semua field wajib.");
}

// Jika ada kategori baru, simpan ke tb_kategori dan gunakan ID-nya
if (!empty($kategori_baru)) {
    $kategori_baru = mysqli_real_escape_string($db, $kategori_baru);

    // Cek dulu apakah kategori sudah ada
    $cekKategori = mysqli_query($db, "SELECT id_kategori FROM tb_kategori WHERE nama_kategori='$kategori_baru' LIMIT 1");
    if (mysqli_num_rows($cekKategori) > 0) {
        $row = mysqli_fetch_assoc($cekKategori);
        $id_kategori = $row['id_kategori'];
    } else {
        $insertKategori = mysqli_query($db, "INSERT INTO tb_kategori (nama_kategori) VALUES ('$kategori_baru')");
        if ($insertKategori) {
            $id_kategori = mysqli_insert_id($db);
        } else {
            die("Gagal menambahkan kategori baru.");
        }
    }
}

// Proses upload gambar
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $upload = uploadGambar($_FILES['gambar']);
    if (isset($upload['error'])) {
        die($upload['error']);
    }
    $nama_gambar = $upload['filename'];
} else {
    die("Gambar wajib diupload.");
}

// Insert ke tabel tb_postingan
$sql = "INSERT INTO tb_postingan (gambar, status, id_kategori, caption, link_konten, tanggal_posting)
        VALUES ('$nama_gambar', '$status', $id_kategori, '$caption', '$link_konten', '$tanggal_posting')";

if (mysqli_query($db, $sql)) {
    header("Location: ../data_postingan.php?msg=success");
    exit();
} else {
    die("Gagal menyimpan data postingan: " . mysqli_error($db));
}
