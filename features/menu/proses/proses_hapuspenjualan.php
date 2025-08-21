<?php
session_start();
include '../../../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data penjualan sebelum dihapus
    $sql = "SELECT total_pendapatan FROM tb_usaha_penjualan WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $pendapatan = $data['total_pendapatan'];

        // Hapus data penjualan
        $sql_delete = "DELETE FROM tb_usaha_penjualan WHERE id = ?";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);

        if ($stmt_delete->execute()) {
            // Rollback saldo
            $sql_saldo = "UPDATE tb_usaha_saldo SET saldo = saldo - ? ORDER BY id DESC LIMIT 1";
            $stmt_saldo = $db->prepare($sql_saldo);
            $stmt_saldo->bind_param("d", $pendapatan);
            $stmt_saldo->execute();

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil dihapus.'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Gagal menghapus data penjualan.'];
        }
    }
    header("Location: ../penjualan/rekap-usaha.php");
    exit();
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Parameter tidak lengkap.'];
    header("Location: ../penjualan/rekap-usaha.php");
    exit();
}
