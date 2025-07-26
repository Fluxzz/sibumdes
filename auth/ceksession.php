<?php
// Mulai session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika session 'id' (atau session utama Anda) tidak ada
if (!isset($_SESSION['id'])) {
    // Hancurkan session dan paksa kembali ke login
    session_destroy();
    
    // [FIX] Ganti dengan path absolut ke file login Anda
    // Ganti '/nama_folder_proyek_anda/' jika perlu
    $login_path = '/login.php'; 
    
    header('Location: ' . $login_path);
    exit();
}
?>