<?php
session_start();
require_once '../../../koneksi.php';
require_once '../../../auth/ceksession.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data pengeluaran sebelum dihapus
    $sql = "SELECT pengeluaran FROM tb_usaha_pengeluaran WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $pengeluaran = $data['pengeluaran'];

        // Hapus data pengeluaran
        $sql_delete = "DELETE FROM tb_usaha_pengeluaran WHERE id = ?";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            // Rollback saldo
            $sql_saldo = "UPDATE tb_usaha_saldo SET saldo = saldo + ? ORDER BY id DESC LIMIT 1";
            $stmt_saldo = $db->prepare($sql_saldo);
            $stmt_saldo->bind_param("d", $pengeluaran);
            $stmt_saldo->execute();

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pengeluaran berhasil dihapus.'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus data pengeluaran.'];
        }
    }
    header("Location: ../penjualan/rekap-usaha.php");
    exit();
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Parameter tidak lengkap.'];
    header("Location: ../penjualan/rekap-usaha.php");
    exit();
}
