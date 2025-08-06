<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header('Location: ../../../login.php');
    exit();
}

// Koneksi ke database
require_once '../../../koneksi.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $No = (int)$_GET['id'];

    // Ambil nama file dari database
    $stmt_get = $db->prepare("SELECT file_surat, lampiran_foto FROM tb_arsip_surat_masuk WHERE No = ?");
    $stmt_get->bind_param("i", $No);
    $stmt_get->execute();
    $result = $stmt_get->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $stmt_get->close();

        // Hapus data dari database
        $stmt_delete = $db->prepare("DELETE FROM tb_arsip_surat_masuk WHERE No = ?");
        $stmt_delete->bind_param("i", $No);

        if ($stmt_delete->execute()) {
            $upload_dir = realpath(__DIR__ . '/../../uploads/surat_masuk') . DIRECTORY_SEPARATOR;

            // Hapus file_surat
            if (!empty($data['file_surat'])) {
                $file_path = $upload_dir . $data['file_surat'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }

            // Hapus lampiran_foto
            if (!empty($data['lampiran_foto'])) {
                $foto_path = $upload_dir . $data['lampiran_foto'];
                if (file_exists($foto_path)) {
                    unlink($foto_path);
                }
            }

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Data surat masuk berhasil dihapus.'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Gagal menghapus data surat masuk dari database.'
            ];
        }

        $stmt_delete->close();
    } else {
        // Data tidak ditemukan
        $_SESSION['message'] = [
            'type' => 'warning',
            'text' => 'Data surat tidak ditemukan.'
        ];
    }

    header('Location: ../surat/datasuratmasuk.php');
    exit();
} else {
    $_SESSION['message'] = [
        'type' => 'warning',
        'text' => 'ID surat tidak valid.'
    ];
    header('Location: ../surat/datasuratmasuk.php');
    exit();
}
?>
