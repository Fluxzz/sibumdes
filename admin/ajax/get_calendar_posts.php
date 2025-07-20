<?php
header('Content-Type: application/json');

// Start session dan include koneksi
session_start();
include '../../koneksi/koneksi.php';

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // Cek apakah request POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Validasi input
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    
    if (!$start_date || !$end_date) {
        throw new Exception('Start date and end date are required');
    }
    
    // Validasi format tanggal
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || 
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
        throw new Exception('Invalid date format');
    }
    
    // Cek koneksi database
    if (!$db) {
        throw new Exception('Database connection failed');
    }
    
    // Query untuk mendapatkan postingan dalam rentang tanggal
    $sql = "SELECT 
                DATE(tanggal_posting) as date,
                COUNT(*) as post_count,
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'id', id_postingan,
                        'caption', SUBSTRING(caption, 1, 50),
                        'status', status
                    ) SEPARATOR '|||'
                ) as posts_data
            FROM tb_postingan 
            WHERE DATE(tanggal_posting) BETWEEN ? AND ?
            GROUP BY DATE(tanggal_posting)
            ORDER BY DATE(tanggal_posting)";
    
    $stmt = mysqli_prepare($db, $sql);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . mysqli_error($db));
    }
    
    mysqli_stmt_bind_param($stmt, 'ss', $start_date, $end_date);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Execute failed: ' . mysqli_stmt_error($stmt));
    }
    
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception('Get result failed: ' . mysqli_stmt_error($stmt));
    }
    
    $calendar_data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $posts = [];
        if ($row['posts_data']) {
            $posts_array = explode('|||', $row['posts_data']);
            foreach ($posts_array as $post_json) {
                $post = json_decode($post_json, true);
                if ($post) {
                    $posts[] = [
                        'id' => (int)$post['id'],
                        'caption' => htmlspecialchars($post['caption'] ?? '', ENT_QUOTES, 'UTF-8'),
                        'status' => htmlspecialchars($post['status'] ?? 'draft', ENT_QUOTES, 'UTF-8')
                    ];
                }
            }
        }
        
        $calendar_data[$row['date']] = $posts;
    }
    
    mysqli_stmt_close($stmt);
    
    echo json_encode($calendar_data, JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("Error in get_calendar_posts.php: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>