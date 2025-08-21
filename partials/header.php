<?php
// Pastikan session sudah dimulai di file utama
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - SIBUMDES' : 'SIBUMDES WUNUT'; ?></title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    
    <link rel="icon" href="../../../assets/img/kaiadmin/favicon.ico" type="image/x-icon" />
    <script src="../../../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { "families": ["Public Sans:300,400,500,600,700"] },
            custom: { "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../../../assets/css/fonts.min.css'] },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../../assets/css/kaiadmin.min.css" />
</head>
<body>
    <div class="wrapper">