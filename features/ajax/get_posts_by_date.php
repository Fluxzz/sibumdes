<?php
header('Content-Type: application/json');
session_start();
require_once '../../koneksi.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['date'])) {
        throw new Exception('Invalid request');
    }
    
    $date = trim($_POST['date']);
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        throw new Exception('Invalid date format');
    }
    
    if (!$db) {
        throw new Exception('Database connection failed');
    }
    
    $sql = "SELECT 
                p.id_postingan, -- <-- Ini penting untuk membuat link
                p.caption,
                p.status,
                p.link_konten,
                k.nama_kategori
            FROM tb_postingan p
            LEFT JOIN tb_kategori k ON p.id_kategori = k.id_kategori
            WHERE DATE(p.tanggal_posting) = ?
            ORDER BY p.tanggal_posting DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    echo json_encode($posts);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>