<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if (isset($_POST['simpan_penjualan'])) {
    $tanggal = $_POST['tanggal_penjualan'];
    $pengunjung = (int)$_POST['jumlah_pengunjung'];
    $pendapatan = (float)$_POST['total_pendapatan'];

    // 1. Simpan data penjualan ke tabel tb_usaha_penjualan
    $stmt = $db->prepare("INSERT INTO tb_usaha_penjualan (tanggal, jumlah_pengunjung, total_pendapatan) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $tanggal, $pengunjung, $pendapatan);

    if ($stmt->execute()) {
        // 2. Ambil saldo terakhir
        $cekSaldo = $db->query("SELECT saldo FROM tb_usaha_saldo ORDER BY id DESC LIMIT 1");

        if ($cekSaldo->num_rows > 0) {
            $dataSaldo = $cekSaldo->fetch_assoc();
            $saldoBaru = $dataSaldo['saldo'] + $pendapatan;

            // 3. Update saldo terbaru
            $stmt2 = $db->prepare("INSERT INTO tb_usaha_saldo (saldo, tanggal_update) VALUES (?, ?)");
            $stmt2->bind_param("ds", $saldoBaru, $tanggal);
            $stmt2->execute();
            $stmt2->close();
        } else {
            // 4. Jika belum ada data saldo, buat saldo awal
            $stmt2 = $db->prepare("INSERT INTO tb_usaha_saldo (saldo, tanggal_update) VALUES (?, ?)");
            $stmt2->bind_param("ds", $pendapatan, $tanggal);
            $stmt2->execute();
            $stmt2->close();
        }

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan dan saldo berhasil disimpan.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menyimpan data penjualan.'];
    }

    $stmt->close();
    header("Location: ../penjualan/rekap-usaha.php");
    exit();
}
?>
