<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt_get = $db->prepare("SELECT bukti_legalitas, foto_kegiatan FROM tb_data_mitra WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $data = $stmt_get->get_result()->fetch_assoc();
    $stmt_get->close();

    $stmt_delete = $db->prepare("DELETE FROM tb_data_mitra WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        $upload_dir_legalitas = '../assets/uploads/legalitas/';
        $upload_dir_kegiatan = '../assets/uploads/kegiatan/';
        
        if (!empty($data['bukti_legalitas']) && file_exists($upload_dir_legalitas . $data['bukti_legalitas'])) {
            @unlink($upload_dir_legalitas . $data['bukti_legalitas']);
        }
        if (!empty($data['foto_kegiatan'])) {
            $old_fotos = explode(',', $data['foto_kegiatan']);
            foreach ($old_fotos as $old_foto) {
                if (file_exists($upload_dir_kegiatan . trim($old_foto))) @unlink($upload_dir_kegiatan . trim($old_foto));
            }
        }
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data mitra berhasil dihapus.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus data mitra.'];
    }
    $stmt_delete->close();

    header('Location: ../datamitra.php');
    exit();
}
?>