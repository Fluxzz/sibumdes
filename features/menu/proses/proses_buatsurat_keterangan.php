<?php
session_start();

include '../../../dompdf/autoload.inc.php';
include '../../../koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['nomor_surat']) && isset($_POST['nama']) && isset($_POST['jenis_keterangan_pendukung'])) {

    $pdf_dir = '../../uploads/surat/keterangan';
    $pdf_filename = "Surat_Keterangan_" . htmlspecialchars($_POST['nomor_surat']) . ".pdf";

    $pdf_path = $pdf_dir . $pdf_filename;
    $target_dir = "../images/";

    $file_extension = pathinfo('../../../assets/images/kopsurat.jpg', PATHINFO_EXTENSION);
    $target_file = "../../assets/images/kopsurat.jpg";
    $image_data = file_get_contents($target_file);
    $base64_image_kopsurat = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);

    // Ambil data dari form
    $nomor_surat = htmlspecialchars($_POST['nomor_surat']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_keterangan_pendukung = htmlspecialchars($_POST['jenis_keterangan_pendukung']);
    $keterangan_pendukung = htmlspecialchars($_POST['keterangan_pendukung']);
    $tanggal = htmlspecialchars($_POST['tanggal']);
    $keterangan = htmlspecialchars($_POST['keterangan']);
    // Pastikan variabel $tanggal_lahir juga diambil jika digunakan di konten surat
    // Ini penting jika Anda memiliki dua input dengan name="tanggal" seperti di form
    // Saya akan asumsikan 'tanggal' dari form itu untuk tanggal surat,
    // dan jika ada input lain untuk tanggal lahir, Anda perlu menyesuaikan nama 'name' di form.
    // Misal: $tanggal_lahir_individu = htmlspecialchars($_POST['tanggal_lahir']); 

    // Format tanggal
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

    // Format tanggal surat
    $date = DateTime::createFromFormat('Y-m-d', $tanggal);
    $bulan_angka = (int) $date->format('n');
    $tanggal_format = $date->format('d') . ' ' . $bulan[$bulan_angka] . ' ' . $date->format('Y');

    // Konfigurasi DomPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    // Buat konten HTML untuk PDF
    $html = "
    <!DOCTYPE html>
<html lang='id'>
<head>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            font-size: 14px;
            margin: 0.5cm;
            background-color: #ffffff;
            color: #000;
        }
        .container {
            max-width: 100%;
            margin: 0 auto;
            border: none;
            padding: 0;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .kop-surat img {
            width: 100%;
            height: auto;
            max-width: 800px;
        }
        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 18px;
        }
        .nomor {
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .content {
            margin: 20px 0;
            text-align: justify;
            line-height: 1.8;
        }
        
        /* --- Perubahan pada CSS untuk Keselarasan Teks --- */
        .data-section {
            margin: 20px 0;
            padding-left: 40px; /* Indentasi dari kiri */
            display: table; /* Menggunakan display table untuk keselarasan kolom */
            width: auto; /* Sesuaikan lebar */
        }
        .data-row {
            display: table-row; /* Setiap baris adalah baris tabel */
        }
        .data-label, .data-colon, .data-value {
            display: table-cell; /* Setiap bagian adalah sel tabel */
            padding-right: 5px; /* Spasi antar kolom */
            vertical-align: top; /* Penyelarasan vertikal */
        }
        .data-label {
            width: 120px; /* Tetapkan lebar tetap untuk label kiri */
            min-width: 120px; /* Pastikan lebar minimum */
            white-space: nowrap; /* Jangan biarkan label pindah baris */
        }
        .data-colon {
            width: 10px; /* Lebar untuk titik dua */
        }
        /* --- Akhir Perubahan CSS Keselarasan Teks --- */

        .isi-keterangan {
            text-align: justify;
            margin-top: 20px; /* Spasi setelah data-section */
        }
        .penutup {
            margin: 30px 0;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
            padding-right: 80px;
        }
        .signature-content {
            display: inline-block;
            text-align: center;
        }
        .signature-space {
            height: 80px; 
            margin: 10px 0;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='kop-surat'>
            <img src='$base64_image_kopsurat' alt='Kop Surat'>
        </div>
        
        <div class='judul'>
            SURAT KETERANGAN
        </div>
        
        <div class='nomor'>
            No. $nomor_surat
        </div>
        
        <div class='content'>
            <div class='pembuka'>
                Yang bertanda tangan dibawah ini,
            </div>
            
            <div class='data-section'>
                <div class='data-row'>
                    <span class='data-label'>Nama</span>
                    <span class='data-colon'>:</span>
                    <span class='data-value'>Sariyanto</span>
                </div>
                <div class='data-row'>
                    <span class='data-label'>Jabatan</span>
                    <span class='data-colon'>:</span>
                    <span class='data-value'>Direktur BUMDes Sumber Kamulyan </span>
                </div>
                <div class='data-row'>
                    <span class='data-label'>Alamat</span>
                    <span class='data-colon'>:</span>
                    <span class='data-value'>Desa Wunut, Kecamatan Tulung, Kabupaten Klaten</span>
                </div>
            </div>
            
            <div class='pembuka'>
                Menerangkan bahwa,
            </div>
            
            <div class='data-section'>
                <div class='data-row'>
                    <span class='data-label'>Nama</span>
                    <span class='data-colon'>:</span>
                    <span class='data-value'>$nama</span>
                </div>
                <div class='data-row'>
                    <span class='data-label'>$jenis_keterangan_pendukung</span>
                    <span class='data-colon'>:</span>
                    <span class='data-value'>$keterangan_pendukung</span>
                </div>
                </div>
            
            <div class='isi-keterangan'>
                $keterangan
            </div>
            
            <div class='penutup'>
                Demikian surat keterangan ini kami buat untuk digunakan sebagaimana mestinya.
            </div>
        </div>
        
        <div class='signature'>
            <div class='signature-content'>
                <p>Klaten, $tanggal_format</p>
                <p><strong>Pengurus</strong></p>
                <p><strong>Desa Wisata Wunut</strong></p>
                
                <div class='signature-space'></div> 
                
                <p><strong>Sariyanto</strong></p>
                <p><strong>Direktur</strong></p>
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
    $stmt_insert = mysqli_prepare($db, $query);

    $perihal_text = "Surat Keterangan";
    $kode_text = "-";
    $keterangan_arsip = "Dibuat dari fitur Buat surat keterangan";

    // Pastikan $pdf_filename adalah nama file saja, bukan path lengkap, jika kolom database hanya menyimpan nama file
    mysqli_stmt_bind_param($stmt_insert, "sssssss", $tanggal, $nomor_surat, $nama, $perihal_text, $kode_text, $keterangan_arsip, $pdf_filename);

    if (mysqli_stmt_execute($stmt_insert)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdf_filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');
        $dompdf->stream($pdf_filename, ["Attachment" => false]);
    } else {
        echo "Error: " . mysqli_error($db);
    }
    mysqli_stmt_close($stmt_insert);
} else {
    echo "Data tidak lengkap!";
}
