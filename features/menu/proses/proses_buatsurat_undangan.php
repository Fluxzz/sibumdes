<?php
session_start();

require '../../../dompdf/autoload.inc.php';
include '../../../koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['nomor_surat']) && isset($_POST['tanggal']) && isset($_POST['kepada'])) {

    $pdf_dir = '../../uploads/surat/undangan'; 
    $pdf_filename = "Surat_Undangan_" . htmlspecialchars($_POST['nomor_surat']) . ".pdf";
    $pdf_path = $pdf_dir . $pdf_filename;
    $target_file_kop = "../../../assets/images/kopsurat.jpg";    
    
    if (file_exists($target_file_kop)) {
        $file_extension = pathinfo($target_file_kop, PATHINFO_EXTENSION);
        $image_data = file_get_contents($target_file_kop);
        $base64_image_kopsurat = 'data:image/' . $file_extension . ';base64,' . base64_encode($image_data);
    } else {
        $base64_image_kopsurat = '';
        error_log("Error: File kop surat tidak ditemukan di " . $target_file_kop);
    }

    $nomor_surat = htmlspecialchars($_POST['nomor_surat']);
    $tanggal_input_form = htmlspecialchars($_POST['tanggal']);
    $lampiran = isset($_POST['lampiran']) && $_POST['lampiran'] !== '' ? htmlspecialchars($_POST['lampiran']) : '-';
    $perihal = isset($_POST['perihal']) && $_POST['perihal'] !== '' ? htmlspecialchars($_POST['perihal']) : 'Undangan';
    $kepada = htmlspecialchars($_POST['kepada']);
    $lokasi_penerima = htmlspecialchars($_POST['lokasi']);
    $tanggal_acara_input = htmlspecialchars($_POST['tanggal_acara']);
    $waktu_acara = htmlspecialchars($_POST['waktu_acara']);
    $tempat_acara = htmlspecialchars($_POST['tempat_acara']);
    $keperluan = htmlspecialchars($_POST['keperluan']);

    $bulan_indonesia = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $date_surat = DateTime::createFromFormat('Y-m-d', $tanggal_input_form);
    $tanggal_surat_formatted = $date_surat ? $date_surat->format('d') . ' ' . $bulan_indonesia[(int)$date_surat->format('n')] . ' ' . $date_surat->format('Y') : '-';

    $date_acara = DateTime::createFromFormat('Y-m-d', $tanggal_acara_input);
    $tanggal_acara_formatted = $date_acara ? $date_acara->format('d') . ' ' . $bulan_indonesia[(int)$date_acara->format('n')] . ' ' . $date_acara->format('Y') : '-';

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);    
    $dompdf = new Dompdf($options);

    $html = "
    <!DOCTYPE html>
<html lang='id'>
<head>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 14px;
            line-height: 1.5; 
            margin: 0.5cm;
            color: #000;
        }
        .container {
            width: 100%;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 15px;
        }
        .kop-surat img {
            width: 100%;
            height: auto;
            max-width: 800px;
        }

        /* Tanggal di Kanan atas, tidak di dalam header-info-wrapper lagi */
        .tanggal-kanan {
            text-align: right;
            padding-right: 20px;
            margin-top: 10px; /* Jarak dari kop surat */
            margin-bottom: 20px; /* Memberi jarak ke blok header-info-wrapper */
        }

        /* Wrapper untuk Nomor, Lampiran, Perihal */
        .header-info-wrapper {
            width: 100%;
            display: table; /* Menggunakan display table untuk wrapper utama */
            table-layout: fixed;
            margin-bottom: 20px; /* Jarak ke blok Kepada Yth */
            padding-left: 20px; /* Padding untuk konten kiri */
            padding-right: 20px; /* Padding untuk konten kanan (bukan tanggal) */
        }
        .header-info-row {
            display: table-row;
        }
        .header-info-left {
            display: table-cell;
            vertical-align: top;
            width: 60%; /* Lebar untuk kolom kiri (Nomor, Lampiran, Perihal) */
            /* margin-top: 15px; */ /* Dihapus dari sini karena sudah dipindahkan */
        }
        .header-info-right {
            display: table-cell;
            vertical-align: top;
            width: 40%; /* Lebar untuk kolom kanan, jika ada konten lain selain tanggal */
            /* Kosongkan saja jika tidak ada konten di sini */
        }

        /* Tabel internal untuk Nomor, Lampiran, Perihal agar selaras */
        .info-table {
            display: table;
            width: auto; /* Agar tabel menyesuaikan lebarnya */
            /* margin-top: 15px; */ /* Tambahkan ini jika perlu di-enter lagi */
        }
        .info-item {
            display: table-row;
        }
        .info-label, .info-colon, .info-value {
            display: table-cell;
            vertical-align: top;
            padding-right: 5px; /* Jarak antar kolom */
            padding-bottom: 3px; /* Sedikit jarak antar baris */
        }
        .info-label {
            width: 75px; /* Lebar tetap untuk label (Nomor, Lampiran, Perihal) */
            white-space: nowrap; /* Mencegah label pindah baris */
        }
        .info-colon {
            width: 10px; /* Lebar untuk titik dua */
        }
        .perihal-text {
            font-weight: bold;
        }

        .kepada-yth {
            margin-top: 25px;
            margin-bottom: 25px;
            padding-left: 20px;
        }
        .kepada-yth p {
            margin: 3px 0;
        }

        .content-body {
            margin-top: 15px;
            padding-left: 20px;    
            padding-right: 20px;    
            text-align: justify;
        }
        .content-body p {
            margin-bottom: 8px;
            text-indent: 0.5in;
        }

        .data-acara-section {
            display: table;    
            margin-left: 20px;
            margin-top: 10px;
            margin-bottom: 10px;
            width: auto;
        }
        .data-acara-row {
            display: table-row;
        }
        .data-acara-label, .data-acara-colon, .data-acara-value {
            display: table-cell;
            padding-right: 5px;
            vertical-align: top;
        }
        .data-acara-label {
            width: 100px;
            white-space: nowrap;
        }
        .data-acara-colon {
            width: 10px;
        }

        .signature {
            margin-top: 30px;
            text-align: right;
            padding-right: 80px;    
        }
        .signature-content {
            display: inline-block;
            text-align: center;
        }
        .signature-content p {
            margin: 0;
            line-height: 1.3;
        }
        .signature-space {
            height: 60px;
            margin: 5px 0;
        }
        
        p { 
            margin: 0 0 10px;
        }

    </style>
</head>
<body>
    <div class='container'>
        <div class='kop-surat'>
            <img src='$base64_image_kopsurat' alt='Kop Surat'>
        </div>

        <div class='tanggal-kanan'>
            <p>Klaten, $tanggal_surat_formatted</p>
        </div>

        <div class='header-info-wrapper'>
            <div class='header-info-row'>
                <div class='header-info-left'>
                    <div class='info-table'>
                        <div class='info-item'>
                            <span class='info-label'>Nomor</span>
                            <span class='info-colon'>:</span>
                            <span class='info-value'>$nomor_surat</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Lampiran</span>
                            <span class='info-colon'>:</span>
                            <span class='info-value'>$lampiran</span>
                        </div>
                        <div class='info-item'>
                            <span class='info-label'>Perihal</span>
                            <span class='info-colon'>:</span>
                            <span class='info-value'><span class='perihal-text'>$perihal</span></span>
                        </div>
                    </div>
                </div>
                <div class='header-info-right'>
                    </div>
            </div>
        </div>
        
        <div class='kepada-yth'>
            <p><i>Kepada Yth.</i></p>
            <p><b>Bapak/Ibu $kepada</b></p>
            <p><b>di $lokasi_penerima</b></p>
        </div>

        <div class='content-body'>
            <p>Puji syukur kepada Allah SWT atas limpahan rahmat-Nya. Sholawat dan salam selalu tercurah kepada junjungan kita, Nabi Muhammad SAW.</p>
            <p>Dengan ini mengharapkan kepada Bapak/Ibu/Saudara untuk hadir pada:</p>

            <div class='data-acara-section'>
                <div class='data-acara-row'>
                    <span class='data-acara-label'>Hari/Tanggal</span>
                    <span class='data-acara-colon'>:</span>
                    <span class='data-acara-value'>$tanggal_acara_formatted</span>
                </div>
                <div class='data-acara-row'>
                    <span class='data-acara-label'>Waktu</span>
                    <span class='data-acara-colon'>:</span>
                    <span class='data-acara-value'>$waktu_acara</span>
                </div>
                <div class='data-acara-row'>
                    <span class='data-acara-label'>Tempat</span>
                    <span class='data-acara-colon'>:</span>
                    <span class='data-acara-value'>$tempat_acara</span>
                </div>
                <div class='data-acara-row'>
                    <span class='data-acara-label'>Acara</span>
                    <span class='data-acara-colon'>:</span>
                    <span class='data-acara-value'>$keperluan</span>
                </div>
            </div>

            <p>Demikian undangan ini kami sampaikan, atas perhatian dan kehadirannya kami ucapkan terima kasih.</p>
        </div>

        <div class='signature'>
            <div class='signature-content'>
                <p>Klaten, $tanggal_surat_formatted</p>
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

    file_put_contents($pdf_path, $dompdf->output());

    $query_insert = "INSERT INTO tb_arsip_surat_keluar (tanggal_keluar, nomor_surat, penerima, perihal, kode, keterangan, file_surat) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($db, $query_insert);
    $kode_default = "-";
    $keterangan_default = "Dibuat dari fitur Buat surat";
    mysqli_stmt_bind_param($stmt_insert, "sssssss", $tanggal_input_form, $nomor_surat, $kepada, $perihal, $kode_default, $keterangan_default, $pdf_filename);    
    
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
?>