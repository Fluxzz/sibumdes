<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sibumdes';

$db = new mysqli($host, $user, $password, $database);

if ($db->connect_error) {
    die('Koneksi gagal: ' . $db->connect_error);
}
?>
