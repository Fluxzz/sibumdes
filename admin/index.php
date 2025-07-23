<!DOCTYPE html>
<?php
session_start();

// Error handling untuk include files
if (!file_exists("login/ceksession.php")) {
    die("File ceksession.php tidak ditemukan!");
}
include "login/ceksession.php";

if (!file_exists('../koneksi/koneksi.php')) {
    die("File koneksi.php tidak ditemukan!");
}
include '../koneksi/koneksi.php';

// Cek koneksi database
if (!isset($db) || !$db) {
    die("Koneksi database gagal!");
}
?>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Arsip Surat Desa Candirejo Borobudur</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">

    <link rel="shortcut icon" href="../img/icon.ico">

    <link href="../assets/build/css/custom.min.css" rel="stylesheet">

    <style>
        /* General Body and Font Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5; /* Light neutral background */
            color: #333;
        }

        /* Override default Bootstrap components for a flatter, modern look */
        .panel {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Softer shadow */
        }

        .modal-content {
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #337ab7; /* Consistent with your login's button color */
            border-color: #337ab7;
        }

        .btn-primary:hover {
            background-color: #286090;
            border-color: #204d74;
        }

        .btn-success {
            background-color: #5cb85c;
            border-color: #5cb85c;
        }

        .btn-success:hover {
            background-color: #4cae4c;
            border-color: #449d44;
        }

        .btn-warning {
            background-color: #f0ad4e;
            border-color: #f0ad4e;
        }

        .btn-warning:hover {
            background-color: #ec971f;
            border-color: #d58512;
        }

        /* Main Content Area */
        .right_col {
            padding: 30px; /* More padding for spacious feel */
            min-height: calc(100vh - 100px); /* Adjust based on header/footer height */
            background-color: #f9fbfd; /* Slightly lighter content area */
            border-radius: 12px;
            margin: 20px; /* Margin from the sides */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* Welcome Message */
        h1 b {
            font-weight: 600;
            color: #2c3e50; /* Darker neutral for headings */
            margin-bottom: 25px; /* More space below heading */
        }

        /* Statistics Tiles */
        .tile-stats {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08); /* Better shadow for tiles */
            padding: 25px;
            margin-bottom: 20px;
            text-align: left; /* Align text left for better readability */
            display: flex; /* Use flexbox for layout */
            flex-direction: column;
            align-items: flex-start; /* Align items to start */
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            min-height: 150px; /* Ensure consistent height */
        }

        .tile-stats:hover {
            transform: translateY(-5px); /* Slight lift on hover */
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .tile-stats .icon {
            font-size: 3.5em; /* Larger icons */
            color: #bec5cc; /* Neutral icon color */
            margin-bottom: 10px; /* Space between icon and count */
            align-self: flex-end; /* Align icon to the right */
            position: absolute;
            top: 20px;
            right: 20px;
            opacity: 0.2; /* Subtle icon */
        }

        .tile-stats .count {
            font-size: 2.8em; /* Larger count */
            font-weight: 700;
            color: #34495e;
            z-index: 1; /* Ensure count is above icon */
        }

        .tile-stats h3 {
            font-size: 1.2em; /* Readable title */
            font-weight: 500;
            margin-top: 5px;
            color: #555;
            z-index: 1;
        }

        .tile-stats p {
            margin: 0;
            color: #777;
            font-size: 0.9em;
            z-index: 1;
        }

        .tile-stats .count.green {
            color: #26b99a; /* Specific color for published posts */
        }

        /* Calendar Styling Enhancements */
        .calendar-container {
            background: #ffffff;
            border-radius: 12px; /* Consistent border-radius */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            padding: 30px; /* More padding */
            margin-bottom: 30px;
        }

        .calendar-header {
            border-bottom: 1px solid #eee; /* Lighter border */
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .calendar-nav {
            color: #6c757d; /* Softer navigation arrows */
            padding: 10px 15px;
        }

        .calendar-nav:hover {
            background: #e9ecef; /* Subtle hover effect */
            color: #495057;
        }

        .calendar-title {
            font-size: 26px; /* Slightly larger title */
            font-weight: 600;
            color: #2c3e50;
        }

        .calendar-grid {
            border-radius: 10px;
            overflow: hidden; /* Ensure rounded corners for grid */
            border: 1px solid #e0e0e0; /* A subtle border around the grid */
        }

        .calendar-day-header {
            background: #f8f9fa; /* Lighter header background */
            color: #495057; /* Darker text */
            padding: 15px; /* More padding */
            font-weight: 600;
            font-size: 13px;
            border-right: 1px solid #e0e0e0; /* Separator lines */
            border-bottom: 1px solid #e0e0e0;
        }
        .calendar-day-header:last-child { border-right: none; }

        .calendar-day {
            background: #fff;
            padding: 10px;
            min-height: 100px; /* Taller cells for more content */
            border: 1px solid #e0e0e0; /* Lighter borders */
            border-top: none; /* Remove top border, handled by header/prev row */
            border-left: none; /* Remove left border */
            position: relative;
            transition: all 0.2s ease;
        }

        .calendar-day:nth-child(7n+1) { /* First day of week */
            border-left: 1px solid #e0e0e0;
        }

        .calendar-day:hover {
            background: #f5f8fa;
            border-color: #c9d8e5; /* Subtle hover border */
        }

        .calendar-day.other-month {
            background: #fbfbfc; /* Very light for other months */
            color: #b0b8c0; /* Lighter text for other months */
        }

        .calendar-day.today {
            background: #e6f7ff; /* Light blue for today */
            border-color: #91d5ff;
            box-shadow: 0 0 0 2px #91d5ff inset; /* Highlight today with an inner shadow */
        }

        .calendar-day.has-posts {
            background: #eaf8ea; /* Light green for days with posts */
            border-color: #8ce08c;
        }

        .calendar-day.has-posts:hover {
            background: #dff0d8;
        }

        .day-number {
            font-weight: 600;
            font-size: 1.1em;
            color: #34495e;
            margin-bottom: 8px;
            position: relative; /* For z-index to be above post-indicator if needed */
            z-index: 2;
        }

        .post-indicator {
            background: #28a745; /* Deeper green for indicator */
            color: white;
            border-radius: 50%; /* Circle shape */
            padding: 4px;
            font-size: 10px;
            position: absolute;
            top: 8px;
            right: 8px;
            min-width: 20px; /* Make sure it's a visible circle */
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            z-index: 3; /* Ensure it's on top */
        }

        .post-preview {
            font-size: 0.85em; /* Slightly smaller for preview */
            color: #6c757d;
            margin-top: 5px;
            line-height: 1.3;
            max-height: 40px; /* Limit height to show 2 lines */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Show max 2 lines */
            -webkit-box-orient: vertical;
        }

        .loading-calendar {
            color: #888;
            padding: 50px;
            font-size: 1.1em;
        }

        .calendar-legend {
            margin-top: 25px;
            justify-content: center; /* Center legend items */
        }

        .legend-item {
            font-size: 0.9em;
            color: #555;
        }

        .legend-color {
            width: 20px; /* Slightly larger color swatches */
            height: 20px;
            border-radius: 5px;
        }

        /* Action Buttons at the bottom */
        .action-buttons-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: center;
            gap: 20px; /* Space between buttons */
            flex-wrap: wrap; /* Allow buttons to wrap on smaller screens */
        }

        .action-buttons-container .btn-lg {
            padding: 15px 30px;
            font-size: 1.1em;
            min-width: 220px; /* Ensure buttons have a minimum width */
        }

        /* Modal Specific Styling */
        #postModal .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 20px;
        }

        #postModal .modal-title {
            font-weight: 600;
            color: #34495e;
            font-size: 1.5em;
        }

        #postModal .modal-body {
            padding: 25px;
        }

        #postModal .modal-footer {
            border-top: 1px solid #eee;
            padding: 15px 25px;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        #modal-posts-content .panel-default {
            border: 1px solid #e0e0e0;
            box-shadow: none; /* No shadow for inner panels */
            margin-bottom: 15px;
            transition: all 0.2s ease;
        }

        #modal-posts-content .panel-default:hover {
            border-color: #c9d8e5;
            background-color: #fcfdfe;
        }

        #modal-posts-content .media-left {
            padding-right: 15px;
            display: flex;
            align-items: center;
        }

        #modal-posts-content .media-body {
            flex-grow: 1;
        }

        #modal-posts-content .media-heading strong {
            color: #333;
            font-weight: 600;
        }

        #modal-posts-content .media-heading .label {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 500;
        }

        /* Error Message Styling */
        .error-message {
            background: #ffebee; /* Light red for errors */
            border: 1px solid #ef9a9a;
            color: #d32f2f;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            font-weight: 500;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .tile-stats {
                align-items: center;
                text-align: center;
            }
            .tile-stats .icon {
                position: static; /* Reset position */
                margin-bottom: 5px;
                opacity: 0.8;
                font-size: 2.5em;
            }
        }

        @media (max-width: 768px) {
            .right_col {
                padding: 15px;
                margin: 10px;
            }

            .tile-stats {
                padding: 20px;
            }

            .tile-stats .count {
                font-size: 2.2em;
            }

            .tile-stats h3 {
                font-size: 1.1em;
            }

            .calendar-day {
                min-height: 70px;
                padding: 6px;
            }

            .calendar-title {
                font-size: 22px;
            }

            .calendar-day-header {
                padding: 10px 5px;
                font-size: 11px;
            }

            .post-indicator {
                top: 5px;
                right: 5px;
                min-width: 18px;
                height: 18px;
                font-size: 9px;
            }

            .post-preview {
                font-size: 0.75em;
            }

            .action-buttons-container {
                flex-direction: column; /* Stack buttons vertically */
                gap: 15px;
            }

            .action-buttons-container .btn-lg {
                width: 100%; /* Full width buttons */
                min-width: unset;
            }
        }

        @media (max-width: 576px) {
            .tile-stats .icon {
                font-size: 2em;
            }
            .tile-stats .count {
                font-size: 1.8em;
            }
            .tile-stats h3 {
                font-size: 1em;
            }
            .calendar-nav {
                padding: 8px 10px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">

            <?php
            if (file_exists("sidebarmenu.php")) {
                include("sidebarmenu.php");
            } else {
                echo '<div class="error-message">File sidebarmenu.php tidak ditemukan!</div>';
            }
            ?>
            <?php
            if (file_exists("header.php")) {
                include("header.php");
            } else {
                echo '<div class="error-message">File header.php tidak ditemukan!</div>';
            }
            ?>
            <div class="right_col" role="main">
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel" style="border: none; box-shadow: none; background: transparent;"> <div class="x_content">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <center>
                                            <h1><b>Selamat Datang, <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Tamu'; ?></b></h1>
                                        </center>
                                        <br>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php
                                    try {
                                        $sql1 = "SELECT COUNT(*) as total FROM tb_arsip_surat_masuk";
                                        $query1 = mysqli_query($db, $sql1);
                                        if ($query1) {
                                            $result1 = mysqli_fetch_assoc($query1);
                                            $jumlah1 = $result1['total'];
                                        } else {
                                            $jumlah1 = 0;
                                            error_log("Error query surat masuk: " . mysqli_error($db));
                                        }
                                    } catch (Exception $e) {
                                        $jumlah1 = 0;
                                        error_log("Exception surat masuk: " . $e->getMessage());
                                    }
                                    ?>
                                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="tile-stats">
                                            <div class="icon"><i class="fa fa-inbox"></i></div>
                                            <div class="count"><?php echo number_format($jumlah1); ?></div>
                                            <h3>Surat Masuk</h3>
                                            <p>Telah diarsipkan</p>
                                        </div>
                                    </div>

                                    <?php
                                    try {
                                        $sql2 = "SELECT COUNT(*) as total FROM tb_arsip_surat_keluar";
                                        $query2 = mysqli_query($db, $sql2);
                                        if ($query2) {
                                            $result2 = mysqli_fetch_assoc($query2);
                                            $jumlah2 = $result2['total'];
                                        } else {
                                            $jumlah2 = 0;
                                            error_log("Error query surat keluar: " . mysqli_error($db));
                                        }
                                    } catch (Exception $e) {
                                        $jumlah2 = 0;
                                        error_log("Exception surat keluar: " . $e->getMessage());
                                    }
                                    ?>
                                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="tile-stats">
                                            <div class="icon"><i class="fa fa-send"></i></div>
                                            <div class="count"><?php echo number_format($jumlah2); ?></div>
                                            <h3>Surat Keluar</h3>
                                            <p>Telah diarsipkan</p>
                                        </div>
                                    </div>

                                    <?php
                                    try {
                                        // Statistics untuk postingan
                                        $sql_posts = "SELECT COUNT(*) as total FROM tb_postingan";
                                        $query_posts = mysqli_query($db, $sql_posts);
                                        if ($query_posts) {
                                            $result_posts = mysqli_fetch_assoc($query_posts);
                                            $jumlah_posts = $result_posts['total'];
                                        } else {
                                            $jumlah_posts = 0;
                                            error_log("Error query postingan: " . mysqli_error($db));
                                        }

                                        $sql_posts_published = "SELECT COUNT(*) as total FROM tb_postingan WHERE status='publish'";
                                        $query_posts_published = mysqli_query($db, $sql_posts_published);
                                        if ($query_posts_published) {
                                            $result_published = mysqli_fetch_assoc($query_posts_published);
                                            $jumlah_posts_published = $result_published['total'];
                                        } else {
                                            $jumlah_posts_published = 0;
                                            error_log("Error query postingan published: " . mysqli_error($db));
                                        }
                                    } catch (Exception $e) {
                                        $jumlah_posts = 0;
                                        $jumlah_posts_published = 0;
                                        error_log("Exception postingan: " . $e->getMessage());
                                    }
                                    ?>
                                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="tile-stats">
                                            <div class="icon"><i class="fa fa-newspaper-o"></i></div>
                                            <div class="count"><?php echo number_format($jumlah_posts); ?></div>
                                            <h3>Total Postingan</h3>
                                            <p>Telah dibuat</p>
                                        </div>
                                    </div>

                                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                        <div class="tile-stats">
                                            <div class="icon"><i class="fa fa-check-circle"></i></div>
                                            <div class="count green"><?php echo number_format($jumlah_posts_published); ?></div>
                                            <h3>Postingan Published</h3>
                                            <p>Sudah dipublikasikan</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="calendar-container">
                                            <div class="calendar-header">
                                                <button class="calendar-nav" onclick="changeMonth(-1)">
                                                    <i class="fa fa-chevron-left"></i>
                                                </button>
                                                <h2 class="calendar-title" id="calendar-title">Loading...</h2>
                                                <button class="calendar-nav" onclick="changeMonth(1)">
                                                    <i class="fa fa-chevron-right"></i>
                                                </button>
                                            </div>

                                            <div id="calendar-content">
                                                <div class="loading-calendar">
                                                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                                                    <p>Memuat kalender...</p>
                                                </div>
                                            </div>

                                            <div class="calendar-legend">
                                                <div class="legend-item">
                                                    <div class="legend-color" style="background: #e6f7ff; border-color: #91d5ff;"></div>
                                                    <span>Hari Ini</span>
                                                </div>
                                                <div class="legend-item">
                                                    <div class="legend-color" style="background: #eaf8ea; border-color: #8ce08c;"></div>
                                                    <span>Ada Postingan</span>
                                                </div>
                                                <div class="legend-item">
                                                    <div class="legend-color" style="background: #fbfbfc;"></div>
                                                    <span>Bulan Lain</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="action-buttons-container">
                                            <a href="inputbuatsurat.php" class="btn btn-primary btn-lg">
                                                <i class="fa fa-pencil"></i> Buat Surat
                                            </a>
                                            <a href="tambah-postingan.php" class="btn btn-success btn-lg">
                                                <i class="fa fa-plus"></i> Tambah Postingan
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer>
                <div class="pull-right">
                    VURIKO DEV STUDIO. All Rights Reserved.
                </div>
                <div class="clearfix"></div>
            </footer>
            </div>
    </div>

    <div class="modal fade" id="postModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Postingan pada <span id="modal-date"></span></h4>
                </div>
                <div class="modal-body" id="modal-posts-content">
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin"></i> Memuat data...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
    <script src="../assets/vendors/nprogress/nprogress.js"></script>
    <script src="../assets/build/js/custom.min.js"></script>

    <script>
        // Calendar Script - Improved Version
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        const today = new Date();

        // Indonesian month names
        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        // Indonesian day names
        const dayNames = ["Ming", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];

        function generateCalendar(month, year) {
            try {
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);

                let startDate = new Date(firstDay);
                startDate.setDate(startDate.getDate() - firstDay.getDay());

                // Update title
                document.getElementById('calendar-title').textContent =
                    monthNames[month] + ' ' + year;

                // Generate calendar HTML
                let calendarHTML = '<div class="calendar-grid">';

                // Day headers
                dayNames.forEach(day => {
                    calendarHTML += `<div class="calendar-day-header">${day}</div>`;
                });

                // Generate 6 weeks
                for (let week = 0; week < 6; week++) {
                    for (let day = 0; day < 7; day++) {
                        const currentDate = new Date(startDate);
                        currentDate.setDate(startDate.getDate() + (week * 7) + day);

                        const dayNum = currentDate.getDate();
                        const isCurrentMonth = currentDate.getMonth() === month;
                        const isToday = currentDate.toDateString() === today.toDateString();

                        let dayClass = 'calendar-day';
                        if (!isCurrentMonth) dayClass += ' other-month';
                        if (isToday) dayClass += ' today';

                        const dateStr = currentDate.toISOString().split('T')[0];

                        calendarHTML += `
                    <div class="${dayClass}" data-date="${dateStr}" onclick="showPostsForDate('${dateStr}')">
                        <div class="day-number">${dayNum}</div>
                        <div class="post-loading" data-date="${dateStr}">
                            <small><i class="fa fa-spinner fa-spin"></i></small>
                        </div>
                    </div>
                `;
                    }
                }

                calendarHTML += '</div>';

                document.getElementById('calendar-content').innerHTML = calendarHTML;

                // Load posts for visible dates after calendar is generated
                loadPostsForMonth(month, year);
            } catch (error) {
                console.error('Error generating calendar:', error);
                document.getElementById('calendar-content').innerHTML =
                    '<div class="alert alert-danger">Error loading calendar: ' + error.message + '</div>';
            }
        }

        function loadPostsForMonth(month, year) {
            try {
                const startDate = new Date(year, month, 1);
                const endDate = new Date(year, month + 1, 0);

                // Adjust for calendar view (show prev/next month days too)
                startDate.setDate(startDate.getDate() - startDate.getDay());
                endDate.setDate(endDate.getDate() + (6 - endDate.getDay()));

                // Check if jQuery is loaded
                if (typeof $ === 'undefined') {
                    console.error('jQuery not loaded');
                    $('.post-loading').html('<small class="text-danger">jQuery Error</small>');
                    return;
                }

                $.ajax({
                    url: 'ajax/get_calendar_posts.php',
                    type: 'POST',
                    data: {
                        start_date: startDate.toISOString().split('T')[0],
                        end_date: endDate.toISOString().split('T')[0]
                    },
                    dataType: 'json',
                    timeout: 15000, // 15 second timeout
                    success: function(response, textStatus, jqXHR) {
                        console.log('Calendar AJAX Success:', response);

                        // Clear loading indicators
                        $('.post-loading').remove();

                        // Check if response has error
                        if (response && response.error) {
                            console.error('Server Error:', response.message);
                            $('.calendar-day').append('<small class="text-danger">Server Error</small>');
                            return;
                        }

                        if (response && typeof response === 'object') {
                            // Add post indicators
                            Object.keys(response).forEach(date => {
                                const posts = response[date];
                                const dayElement = $(`.calendar-day[data-date="${date}"]`);

                                if (posts && Array.isArray(posts) && posts.length > 0) {
                                    dayElement.addClass('has-posts');
                                    dayElement.append(`<div class="post-indicator">${posts.length}</div>`);

                                    // Add first post preview
                                    if (posts[0] && posts[0].caption) {
                                        const preview = posts[0].caption.substring(0, 30) +
                                            (posts[0].caption.length > 30 ? '...' : '');
                                        dayElement.append(`<div class="post-preview">${preview}</div>`);
                                    }
                                }
                            });
                        } else {
                            console.warn('Invalid response format:', response);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Calendar AJAX Error Details:', {
                            status: jqXHR.status,
                            statusText: jqXHR.statusText,
                            textStatus: textStatus,
                            errorThrown: errorThrown,
                            responseText: jqXHR.responseText
                        });

                        $('.post-loading').html('<small class="text-danger">Load Error (' + jqXHR.status + ')</small>');

                        // Show user-friendly error
                        if (jqXHR.status === 404) {
                            console.error('File ajax/get_calendar_posts.php not found');
                        } else if (jqXHR.status === 500) {
                            console.error('Server error in get_calendar_posts.php');
                        }
                    }
                });
            } catch (error) {
                console.error('Error in loadPostsForMonth:', error);
                $('.post-loading').html('<small class="text-danger">JS Error</small>');
            }
        }

        function changeMonth(direction) {
            currentMonth += direction;

            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            } else if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }

            generateCalendar(currentMonth, currentYear);
        }

        function showPostsForDate(date) {
            try {
                const formattedDate = new Date(date + 'T00:00:00').toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                $('#modal-date').text(formattedDate);
                $('#modal-posts-content').html(
                    '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat data...</div>'
                );
                $('#postModal').modal('show');

                console.log('Loading posts for date:', date);

                $.ajax({
                    url: 'ajax/get_posts_by_date.php',
                    type: 'POST',
                    data: {
                        date: date
                    },
                    dataType: 'json',
                    timeout: 15000,
                    success: function(response, textStatus, jqXHR) {
                        console.log('Posts AJAX Success:', response);

                        let content = '';

                        // Check if response has error
                        if (response && response.error) {
                            console.error('Server Error:', response.message);
                            content = `
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i> ${response.message}
                            </div>
                        `;
                        } else if (!response || !Array.isArray(response) || response.length === 0) {
                            content = `
                            <div class="text-center" style="padding: 40px;">
                                <i class="fa fa-calendar-o fa-3x text-muted"></i>
                                <h4 class="text-muted">Tidak ada postingan</h4>
                                <p class="text-muted">Belum ada postingan pada tanggal ini</p>
                            </div>
                        `;
                        } else {
                            content = '<div class="row">';
                            response.forEach(function(post) {
                                const statusClass = post.status === 'publish' ? 'success' : 'default';
                                const imageHtml = post.gambar && post.gambar.trim() !== '' ?
                                    `<img src="uploads/${post.gambar}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;" alt="Post Image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                   <div class="text-center" style="width: 60px; height: 60px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; display: none; align-items: center; justify-content: center;"><i class="fa fa-image text-muted"></i></div>` :
                                    '<div class="text-center" style="width: 60px; height: 60px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-image text-muted"></i></div>';

                                content += `
                                <div class="col-md-12" style="margin-bottom: 15px;">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <div class="media">
                                                <div class="media-left">
                                                    ${imageHtml}
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-heading">
                                                        <strong>${post.nama_kategori || 'Tanpa Kategori'}</strong>
                                                        <span class="label label-${statusClass} pull-right">${post.status.toUpperCase()}</span>
                                                    </div>
                                                    <p style="margin: 5px 0;">${post.caption || 'Tidak ada caption'}</p>
                                                    ${post.link_konten && post.link_konten.trim() !== '' ? `<p><a href="${post.link_konten}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-external-link"></i> Lihat Link</a></p>` : ''}
                                                    <small class="text-muted">
                                                        <i class="fa fa-clock-o"></i> ${new Date(post.tanggal_posting).toLocaleString('id-ID')}
                                                    </small>
                                                    <div class="pull-right">
                                                        <a href="detail-postingan.php?id=${post.id_postingan}" class="btn btn-xs btn-primary">
                                                            <i class="fa fa-eye"></i> Detail
                                                        </a>
                                                        <a href="edit_postingan.php?id=${post.id_postingan}" class="btn btn-xs btn-warning">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            });
                            content += '</div>';
                        }

                        $('#modal-posts-content').html(content);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Posts AJAX Error Details:', {
                            status: jqXHR.status,
                            statusText: jqXHR.statusText,
                            textStatus: textStatus,
                            errorThrown: errorThrown,
                            responseText: jqXHR.responseText
                        });

                        let errorMessage = 'Gagal memuat data postingan';

                        if (jqXHR.status === 404) {
                            errorMessage = 'File ajax/get_posts_by_date.php tidak ditemukan';
                        } else if (jqXHR.status === 500) {
                            errorMessage = 'Terjadi kesalahan server';
                            if (jqXHR.responseText) {
                                try {
                                    const errorResponse = JSON.parse(jqXHR.responseText);
                                    if (errorResponse.message) {
                                        errorMessage += ': ' + errorResponse.message;
                                    }
                                } catch (e) {
                                    // If not JSON, show first 100 chars of response
                                    errorMessage += ': ' + jqXHR.responseText.substring(0, 100);
                                }
                            }
                        } else if (textStatus === 'timeout') {
                            errorMessage = 'Request timeout - server terlalu lama merespons';
                        }

                        $('#modal-posts-content').html(
                            `<div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i> ${errorMessage}
                            <br><small>Status: ${jqXHR.status} | Error: ${errorThrown}</small>
                        </div>`
                        );
                    }
                });
            } catch (error) {
                console.error('Error in showPostsForDate:', error);
                $('#modal-posts-content').html(
                    '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error JavaScript: ' + error.message + '</div>'
                );
            }
        }

        // Initialize calendar when document is ready
        $(document).ready(function() {
            try {
                console.log('Initializing calendar...');
                generateCalendar(currentMonth, currentYear);
            } catch (error) {
                console.error('Error initializing calendar:', error);
                $('#calendar-content').html(
                    '<div class="alert alert-danger">Error initializing calendar: ' + error.message + '</div>'
                );
            }
        });
    </script>
</body>

</html>