<?php
header('Content-Type: application/json');
session_start();

// [FIX] Path ke koneksi.php diperbaiki
require_once '../../koneksi.php';

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', 0); // Jangan tampilkan error di output JSON

try {
    // Cek koneksi database
    if (!$db) {
        throw new Exception('Database connection failed');
    }

    // Validasi input
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if (!$start_date || !$end_date) {
        throw new Exception('Start date and end date are required');
    }

    // Query untuk mendapatkan postingan (kode Anda sudah bagus)
    $sql = "SELECT DATE(tanggal_posting) as date, COUNT(*) as post_count
            FROM tb_postingan 
            WHERE DATE(tanggal_posting) BETWEEN ? AND ?
            GROUP BY DATE(tanggal_posting)";

    $stmt = $db->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $calendar_data = [];
    while ($row = $result->fetch_assoc()) {
        $calendar_data[$row['date']] = [['count' => $row['post_count']]]; // Struktur disederhanakan
    }

    $stmt->close();

    echo json_encode($calendar_data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
