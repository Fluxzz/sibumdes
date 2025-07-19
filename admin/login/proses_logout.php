<?php
session_start();

// Hapus semua data session
$_SESSION = [];
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
    <!-- Redirect ke halaman login setelah 1 detik -->
    <meta http-equiv="refresh" content="1;url=../login/index.php">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 100px;
            background-color: #f4f4f4;
        }
        h3 {
            color: #333;
        }
        h2 {
            color: #5cb85c;
        }
    </style>
</head>
<body>
    <h3>Anda telah keluar sistem</h3>
    <h2>Terima Kasih</h2>
</body>
</html>
