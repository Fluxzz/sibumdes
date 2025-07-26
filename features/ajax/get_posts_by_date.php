<?php
header('Content-Type: application/json');

// Start session dan include koneksi
session_start();
include '../../koneksi/koneksi.php';

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', 0); // Jangan tampilkan error di output JSON

try {
    // Cek apakah request POST dan ada parameter date
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['date'])) {
        throw new Exception('Invalid request');
    }
    
    // Validasi dan sanitasi input date
    $date = trim($_POST['date']);
    if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        throw new Exception('Invalid date format');
    }
    
    // Cek koneksi database
    if (!$db) {
        throw new Exception('Database connection failed');
    }
    
    // Query untuk mendapatkan postingan berdasarkan tanggal
    $sql = "SELECT 
                p.id_postingan,
                p.caption,
                p.gambar,
                p.link_konten,
                p.status,
                p.tanggal_posting,
                k.nama_kategori
            FROM tb_postingan p
            LEFT JOIN tb_kategori k ON p.id_kategori = k.id_kategori
            WHERE DATE(p.tanggal_posting) = ?
            ORDER BY p.tanggal_posting DESC";
    
    // Prepare statement untuk keamanan
    $stmt = mysqli_prepare($db, $sql);
    if (!$stmt) {
        throw new Exception('Prepare statement failed: ' . mysqli_error($db));
    }
    
    mysqli_stmt_bind_param($stmt, 's', $date);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Execute statement failed: ' . mysqli_stmt_error($stmt));
    }
    
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception('Get result failed: ' . mysqli_stmt_error($stmt));
    }
    
    $posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Sanitasi output
        $posts[] = [
            'id_postingan' => (int)$row['id_postingan'],
            'caption' => htmlspecialchars($row['caption'] ?? '', ENT_QUOTES, 'UTF-8'),
            'gambar' => htmlspecialchars($row['gambar'] ?? '', ENT_QUOTES, 'UTF-8'),
            'link_konten' => htmlspecialchars($row['link_konten'] ?? '', ENT_QUOTES, 'UTF-8'),
            'status' => htmlspecialchars($row['status'] ?? 'draft', ENT_QUOTES, 'UTF-8'),
            'tanggal_posting' => $row['tanggal_posting'],
            'nama_kategori' => htmlspecialchars($row['nama_kategori'] ?? 'Tanpa Kategori', ENT_QUOTES, 'UTF-8')
        ];
    }
    
    mysqli_stmt_close($stmt);
    
    // Return JSON response
    echo json_encode($posts, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Log error untuk debugging
    error_log("Error in get_posts_by_date.php: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>