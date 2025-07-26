<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }

// [PATH] Sesuaikan path ke autoload DomPDF dan koneksi
require_once '../vendor/dompdf/autoload.inc.php';
require_once '../koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // [PATH] Tentukan direktori upload dan path untuk kop surat
    $upload_dir = '../assets/uploads/surat_keluar/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Pastikan file kop surat ada
    $kop_surat_path = "../assets/images/kop_surat/kopsurat.jpg"; // Pastikan nama file dan path ini benar
    if (!file_exists($kop_surat_path)) {
        die("Error: File kop surat tidak ditemukan di " . $kop_surat_path);
    }
    $base64_image_kopsurat = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($kop_surat_path));

    // Ambil semua data POST dengan htmlspecialchars
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']);
    // ... (ambil semua data POST lainnya seperti di kode asli Anda) ...

    // Proses pembuatan HTML untuk PDF (kode HTML asli Anda sudah bagus)
    $html = "
    <!DOCTYPE html>
    <html>
    <head>...</head>
    <body>
        <div class='kop-surat'>
            <img src='$base64_image_kopsurat' alt='Kop Surat'>
        </div>
        ... (sisa konten HTML Anda) ...
    </body>
    </html>";

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // [PATH] Buat nama file PDF yang valid
    $pdf_filename = "Undangan_" . str_replace(['/', '\\'], '-', $nomor_surat) . ".pdf";
    $pdf_path = $upload_dir . $pdf_filename;
    
    // Simpan file ke server terlebih dahulu
    file_put_contents($pdf_path, $dompdf->output());

    // [KEAMANAN] Kode insert Anda sudah menggunakan prepared statement, jadi aman!
    $query_insert = "INSERT INTO tb_arsip_surat_keluar (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $db->prepare($query_insert);
    
    // ... (bind parameter Anda dari kode asli) ...
    // mysqli_stmt_bind_param($stmt_insert, "sssssss", $tanggal_input_form, $nomor_surat, ..., $pdf_filename); // Pastikan $pdf_filename, bukan $pdf_path
    
    if ($stmt_insert->execute()) {
        // Jika berhasil disimpan di DB, kirim file ke browser untuk di-download/dilihat
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $pdf_filename . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        readfile($pdf_path);
        exit();
    } else {
        echo "Error: Gagal menyimpan data arsip surat ke database.";
    }
    $stmt_insert->close();
}
?>