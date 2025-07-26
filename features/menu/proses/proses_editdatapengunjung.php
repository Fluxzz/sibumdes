<?php
session_start();
if (!isset($_SESSION['id'])) { header('Location: ../login.php'); exit(); }
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $tanggal_kunjungan = $_POST['tanggal_kunjungan'];
    $pilihan_paket_wisata = $_POST['pilihan_paket_wisata'];
    $jenis_wisatawan = $_POST['jenis_wisatawan'];
    $nama = trim($_POST['nama']);
    $pax = (int)$_POST['pax'];

    $kota = ($_POST['jenis_wisatawan'] == 'Domestik') ? trim($_POST['kota']) : null;
    $negara = ($_POST['jenis_wisatawan'] == 'Mancanegara') ? trim($_POST['negara']) : null;
    $agen_wisata = !empty($_POST['agen_wisata']) ? trim($_POST['agen_wisata']) : null;
    $driver_agent_guide = !empty($_POST['driver_agent_guide']) ? trim($_POST['driver_agent_guide']) : null;
    $local_guide = !empty($_POST['local_guide']) ? trim($_POST['local_guide']) : null;
    
    $opsi_makan_tour = in_array($pilihan_paket_wisata, ['cycling_tour', 'dokar_tour', 'walking_tour']) ? $_POST['opsi_makan_tour'] : null;
    $jenis_makanan_paket = ($pilihan_paket_wisata == 'meal_only') ? $_POST['jenis_makanan_paket'] : null;
    $opsi_cooking_lesson = ($pilihan_paket_wisata == 'cooking_lesson') ? $_POST['opsi_cooking_lesson'] : null;

    $stmt_get = $db->prepare("SELECT foto FROM tb_data_pengunjung WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $foto_lama = $stmt_get->get_result()->fetch_assoc()['foto'];
    $stmt_get->close();

    $foto_baru = $foto_lama;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $upload_dir = '../assets/uploads/pengunjung/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        
        if (!empty($foto_lama) && file_exists($upload_dir . $foto_lama)) {
            @unlink($upload_dir . $foto_lama);
        }
        
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto_baru = 'pengunjung_' . $id . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $foto_baru);
    }

    $stmt_update = $db->prepare("UPDATE tb_data_pengunjung SET tanggal_kunjungan = ?, pilihan_paket_wisata = ?, opsi_makan_tour = ?, jenis_makanan_paket = ?, opsi_cooking_lesson = ?, jenis_wisatawan = ?, kota = ?, negara = ?, nama = ?, pax = ?, agen_wisata = ?, driver_agent_guide = ?, local_guide = ?, foto = ? WHERE id = ?");
    $stmt_update->bind_param("sssssssssissssi", $tanggal_kunjungan, $pilihan_paket_wisata, $opsi_makan_tour, $jenis_makanan_paket, $opsi_cooking_lesson, $jenis_wisatawan, $kota, $negara, $nama, $pax, $agen_wisata, $driver_agent_guide, $local_guide, $foto_baru, $id);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pengunjung berhasil diupdate!'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal mengupdate data.'];
    }
    $stmt_update->close();
    header('Location: ../datapengunjung.php');
    exit;
}
?>