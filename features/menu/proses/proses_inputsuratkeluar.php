<?php
include '../../../auth/ceksession.php';
include '../../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $nomor_surat = $_POST['nomor_surat'];
    $penerima = $_POST['penerima'];
    $perihal = $_POST['perihal'];
    $kode = $_POST['kode'];
    $keterangan = $_POST['keterangan'];

    // Penanganan upload file
    $file_surat = '';
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === 0) {
        $nama_file = $_FILES['file_surat']['name'];
        $tmp_file = $_FILES['file_surat']['tmp_name'];
        $folder_tujuan = '../uploads/surat_keluar/';
        $file_surat = uniqid() . '_' . $nama_file;

        // Pindahkan file
        if (!move_uploaded_file($tmp_file, $folder_tujuan . $file_surat)) {
            echo "Gagal mengunggah file.";
            exit();
        }
    }

    // Query input ke tabel yang benar
    $query = "INSERT INTO tb_arsip_surat_keluar 
                (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'sssssss', $tanggal_keluar, $nomor_surat, $penerima, $perihal, $kode, $keterangan, $file_surat);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: ../surat/datasuratkeluar.php?status=sukses");
            exit();
        } else {
            echo "Gagal menyimpan data ke database.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Kesalahan query: " . mysqli_error($db);
    }
} else {
    echo "Metode tidak diperbolehkan.";
}
?>
