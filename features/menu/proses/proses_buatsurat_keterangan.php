<?php
// WAJIB ADA: Untuk menampilkan pesan error jika ada masalah, sangat membantu saat development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// DISARANKAN: Gunakan require_once agar skrip berhenti jika file penting tidak ditemukan
require_once '../../../dompdf/autoload.inc.php';
require_once '../../../koneksi.php'; // Pastikan path ini sudah benar ke file koneksi Anda

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['nomor_surat']) && isset($_POST['nama']) && isset($_POST['jenis_keterangan_pendukung'])) {

    // DIKOREKSI: Path penyimpanan PDF dibuat di root folder agar lebih rapi (../ akan naik satu level dari 'proses')
    $pdf_dir = '../../../uploads/'; // Ini akan mengarah ke folder 'uploads' di direktori utama proyek Anda

    // PENAMBAHAN: Cek apakah folder uploads ada, jika tidak, coba buat
    if (!file_exists($pdf_dir) || !is_dir($pdf_dir)) {
        mkdir($pdf_dir, 0775, true); // Membuat folder jika belum ada
    }

    // Nama file PDF
    $nomor_surat_clean = preg_replace('/[^A-Za-z0-9\-]/', '_', $_POST['nomor_surat']); // Membersihkan karakter aneh dari nomor surat untuk nama file
    $pdf_filename = "Surat_Keterangan_" . $nomor_surat_clean . ".pdf";
    $pdf_path = $pdf_dir . $pdf_filename;

    // DIKOREKSI: Path ke kop surat disamakan dan dipastikan benar
    $path_kopsurat = '../../../assets/images/kopsurat.jpg'; // Pastikan ini adalah path yang benar dari file proses.php

    // PENAMBAHAN: Cek apakah file kop surat ada sebelum digunakan
    if (file_exists($path_kopsurat)) {
        $image_data = file_get_contents($path_kopsurat);
        $file_extension = pathinfo($path_kopsurat, PATHINFO_EXTENSION);
        $base64_image_kopsurat = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);
    } else {
        // Jika kop surat tidak ada, bisa dihentikan atau gunakan placeholder
        die("Error: File kop surat tidak ditemukan di: " . $path_kopsurat);
        // $base64_image_kopsurat = ''; // Atau biarkan kosong
    }


    // Ambil data dari form dengan aman
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_keterangan_pendukung = htmlspecialchars($_POST['jenis_keterangan_pendukung']);
    $keterangan_pendukung = htmlspecialchars($_POST['keterangan_pendukung']);
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $keterangan = nl2br(htmlspecialchars($_POST['keterangan'])); // nl2br agar baris baru di textarea tetap ada di PDF

    // Format tanggal ke format Indonesia
    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    $bulan_angka = (int) $date->format('n');
    $tanggal_format = $date->format('d') . ' ' . $bulan[$bulan_angka] . ' ' . $date->format('Y');

    // Konfigurasi DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // Buat konten HTML untuk PDF (Tidak ada perubahan di sini, sudah bagus)
    $html = "
    <!DOCTYPE html>
    <html lang='id'>
    <head>
        <style>
            /* CSS Anda sudah bagus, tidak perlu diubah */
            * { box-sizing: border-box; }
            body { font-family: 'Times New Roman', serif; line-height: 1.6; font-size: 14px; margin: 0.5cm; }
            .container { max-width: 100%; margin: 0 auto; }
            .kop-surat { text-align: center; margin-bottom: 20px; }
            .kop-surat img { width: 100%; height: auto; }
            .judul { text-align: center; font-weight: bold; text-decoration: underline; font-size: 16px; }
            .nomor { text-align: center; margin-bottom: 20px; font-size: 14px; }
            .content { margin: 20px 0; text-align: justify; }
            .data-section { margin-left: 40px; }
            .data-label { display: inline-block; width: 150px; }
            .penutup { margin-top: 20px; }
            .signature { margin-top: 40px; text-align: right; }
            .signature-content { display: inline-block; text-align: center; }
            .signature-space { height: 70px; }
            p { margin: 5px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='kop-surat'>
                <img src='$base64_image_kopsurat' alt='Kop Surat'>
            </div>
            
            <div class='judul'>SURAT KETERANGAN</div>
            <div class='nomor'>No. $nomor_surat</div>
            
            <div class='content'>
                <p>Yang bertanda tangan dibawah ini:</p>
                <div class='data-section'>
                    <p><span class='data-label'>Nama</span>: Sariyanto</p>
                    <p><span class='data-label'>Jabatan</span>: Direktur BUMDes Sumber Kamulyan</p>
                    <p><span class='data-label'>Alamat</span>: Desa Wunut, Kecamatan Tulung, Kabupaten Klaten</p>
                </div>
                
                <p>Menerangkan dengan sesungguhnya bahwa:</p>
                <div class='data-section'>
                    <p><span class='data-label'>Nama</span>: $nama</p>
                    <p><span class='data-label'>$jenis_keterangan_pendukung</span>: $keterangan_pendukung</p>
                </div>
                
                <div style='margin-top:20px;'>$keterangan</div>
                
                <div class='penutup'>
                    Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinya.
                </div>
            </div>
            
            <div class='signature'>
                <div class='signature-content'>
                    <p>Klaten, $tanggal_format</p>
                    <p><strong>Direktur BUMDes Sumber Kamulyan</strong></p>
                    <div class='signature-space'></div> 
                    <p><strong>Sariyanto</strong></p>
                </div>
            </div>
        </div>
    </body>
    </html>";

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Save the file locally
    file_put_contents($pdf_path, $dompdf->output());

    // Insert record into the database
    $query = "INSERT INTO tb_arsip_surat_keluar (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Pastikan variabel $db dari koneksi.php sudah tersedia
    if (isset($db)) {
        $stmt_insert = mysqli_prepare($db, $query);

        $perihal_text = "Surat Keterangan";
        $kode_text = "-";
        $keterangan_arsip = "Surat Keterangan an. " . $nama;

        mysqli_stmt_bind_param($stmt_insert, "sssssss", $tanggal, $nomor_surat, $nama, $perihal_text, $kode_text, $keterangan_arsip, $pdf_filename);

        if (mysqli_stmt_execute($stmt_insert)) {
            mysqli_stmt_close($stmt_insert);
            mysqli_close($db);

            // Tampilkan PDF di browser
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $pdf_filename . '"'); // 'inline' untuk menampilkan, 'attachment' untuk langsung download
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            readfile($pdf_path);
            exit();
        } else {
            echo "Error saat menyimpan ke database: " . mysqli_error($db);
        }
    } else {
        echo "Error: Koneksi database tidak ditemukan.";
    }
} else {
    echo "Data tidak lengkap! Pastikan semua field terisi.";
}
