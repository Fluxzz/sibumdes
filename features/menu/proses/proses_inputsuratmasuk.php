<?php
session_start();
include '../../../koneksi.php';

if (isset($_POST['submit'])) {
    // Escape string
    $nomor_surat      = mysqli_real_escape_string($db, $_POST['nomor_surat']);
    $tanggal_terima   = $_POST['tanggal_terima'];
    $tanggal_surat    = $_POST['tanggal_surat'];
    $pengirim         = mysqli_real_escape_string($db, $_POST['pengirim']);
    $penerima_surat   = mysqli_real_escape_string($db, $_POST['penerima_surat']);
    $disposisi        = mysqli_real_escape_string($db, $_POST['disposisi']);
    $perihal          = mysqli_real_escape_string($db, $_POST['perihal']);
    $kode             = mysqli_real_escape_string($db, $_POST['kode']);
    $keterangan       = mysqli_real_escape_string($db, $_POST['keterangan']);

    // Handle file_surat
    $file_surat = $_FILES['file_surat']['name'];
    $tmp_surat = $_FILES['file_surat']['tmp_name'];
    $ext_surat = strtolower(pathinfo($file_surat, PATHINFO_EXTENSION));
    $new_file_surat = time() . "_surat." . $ext_surat;
    $path_surat = "../../uploads/surat_masuk/" . $new_file_surat;

    // Handle lampiran_foto
    $lampiran_foto = $_FILES['lampiran_foto']['name'];
    $tmp_foto = $_FILES['lampiran_foto']['tmp_name'];
    $ext_foto = strtolower(pathinfo($lampiran_foto, PATHINFO_EXTENSION));
    $new_lampiran_foto = time() . "_foto." . $ext_foto;
    $path_foto = "../../uploads/surat_masuk/" . $new_lampiran_foto;

    // Validasi file
    $allowed_surat = ['pdf'];
    $allowed_foto = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext_surat, $allowed_surat)) {
        echo "File surat harus berformat PDF.";
        exit();
    }

    if (!in_array($ext_foto, $allowed_foto)) {
        echo "Lampiran foto harus berformat JPG, JPEG, atau PNG.";
        exit();
    }

    // Pindahkan file jika valid
    $upload_surat = move_uploaded_file($tmp_surat, $path_surat);
    $upload_foto = move_uploaded_file($tmp_foto, $path_foto);

    if ($upload_surat && $upload_foto) {
        $query = "INSERT INTO tb_arsip_surat_masuk 
            (nomor_surat, tanggal_terima, tanggal_surat, pengirim, penerima_surat, disposisi, perihal, kode, keterangan, file_surat, lampiran_foto) 
            VALUES 
            ('$nomor_surat', '$tanggal_terima', '$tanggal_surat', '$pengirim', '$penerima_surat', '$disposisi', '$perihal', '$kode', '$keterangan', '$new_file_surat', '$new_lampiran_foto')";

        if (mysqli_query($db, $query)) {
            header("Location: ../surat/datasuratmasuk.php?status=sukses");
            exit();
        } else {
            echo "Gagal menyimpan data ke database: " . mysqli_error($db);
        }
    } else {
        echo "Gagal mengunggah file.";
    }
}
?>
