<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

// Simpan Penjualan
if (isset($_POST['simpan_penjualan'])) {
    $tanggal = $_POST['tanggal_penjualan']; // dari form penjualan
    $jumlah_pengunjung = (int)$_POST['jumlah_pengunjung'];
    $total_pendapatan = (float)$_POST['total_pendapatan'];

    // Simpan ke tabel penjualan
    $stmt = $db->prepare("INSERT INTO tb_usaha_penjualan (tanggal, jumlah_pengunjung, total_pendapatan) 
                          VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $tanggal, $jumlah_pengunjung, $total_pendapatan);
    $stmt->execute();

    // Ambil saldo terakhir
    $result = $db->query("SELECT saldo FROM tb_usaha_saldo ORDER BY id DESC LIMIT 1");
    $row = $result->fetch_assoc();
    $saldo_sebelumnya = $row ? $row['saldo'] : 0;

    // Update saldo baru
    $saldo_baru = $saldo_sebelumnya + $total_pendapatan;
    $stmt2 = $db->prepare("INSERT INTO tb_usaha_saldo (saldo, tanggal_update) VALUES (?, ?)");
    $stmt2->bind_param("ds", $saldo_baru, $tanggal);
    $stmt2->execute();

    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil disimpan.'];
    header("Location: ../penjualan/rekap-usaha.php");
    exit();
}

// Simpan Pengeluaran
if (isset($_POST['simpan_pengeluaran'])) {
    $tanggal = $_POST['tanggal_pengeluaran']; // dari form pengeluaran
    $keterangan = $_POST['keterangan'];
    $jumlah_pengeluaran = (float)$_POST['pengeluaran'];

    // Ambil saldo terakhir
    $result = $db->query("SELECT saldo FROM tb_usaha_saldo ORDER BY id DESC LIMIT 1");
    $row = $result->fetch_assoc();
    $saldo_sebelumnya = $row ? $row['saldo'] : 0;

    // Hitung saldo akhir setelah pengeluaran
    $saldo_akhir = $saldo_sebelumnya - $jumlah_pengeluaran;

    // Simpan ke tabel pengeluaran
    $stmt = $db->prepare("INSERT INTO tb_usaha_pengeluaran (tanggal, keterangan, saldo_awal, pengeluaran, saldo_akhir) 
                          VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddd", $tanggal, $keterangan, $saldo_sebelumnya, $jumlah_pengeluaran, $saldo_akhir);
    $stmt->execute();

    // Update saldo usaha
    $stmt2 = $db->prepare("INSERT INTO tb_usaha_saldo (saldo, tanggal_update) VALUES (?, ?)");
    $stmt2->bind_param("ds", $saldo_akhir, $tanggal);
    $stmt2->execute();

    $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pengeluaran berhasil disimpan.'];
    header("Location: ../penjualan/rekap-usaha.php");
    exit();
}
?>
