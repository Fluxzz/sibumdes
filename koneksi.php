<?php
// Konfigurasi Database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "sibumdes";

// Membuat Koneksi Database
$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek Koneksi
if ($db->connect_error) {
    die("Koneksi ke database gagal: " . $db->connect_error);
}
$db->set_charset("utf8mb4");
?>