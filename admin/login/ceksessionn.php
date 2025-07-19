<?php
session_start();

// Jika sudah login, arahkan ke dashboard sesuai rolenya
if (isset($_SESSION['r3su'])) {
    if ($_SESSION['r3su'] === 'dmn') {
        header('Location: ../login/login.php'); // arahkan ke dashboard admin
        exit();
    } elseif ($_SESSION['r3su'] === 'bgn') {
        header('Location: ../../bagian/'); // arahkan ke dashboard bagian (jika ada)
        exit();
    }
}
?>
