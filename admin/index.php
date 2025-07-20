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
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Arsip Surat Desa Candirejo Borobudur</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- bootstrap-progressbar -->
  <link href="../assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="../assets/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="../assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <!-- Calendar CSS -->
  <style>
    .calendar-container {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
    }

    .calendar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #f0f0f0;
    }

    .calendar-nav {
      background: none;
      border: none;
      font-size: 20px;
      color: #337ab7;
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 4px;
      transition: all 0.3s ease;
    }

    .calendar-nav:hover {
      background: #e3f2fd;
      color: #2196f3;
    }

    .calendar-title {
      font-size: 24px;
      font-weight: bold;
      color: #333;
      margin: 0;
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 1px;
      background: #e0e0e0;
      border-radius: 8px;
      overflow: hidden;
    }

    .calendar-day-header {
      background: #337ab7;
      color: white;
      padding: 12px;
      text-align: center;
      font-weight: bold;
      font-size: 14px;
    }

    .calendar-day {
      background: white;
      padding: 8px;
      min-height: 80px;
      position: relative;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 1px solid transparent;
    }

    .calendar-day:hover {
      background: #f8f9fa;
      border-color: #337ab7;
    }

    .calendar-day.other-month {
      background: #f5f5f5;
      color: #999;
    }

    .calendar-day.today {
      background: #e3f2fd;
      border-color: #2196f3;
      font-weight: bold;
    }

    .calendar-day.has-posts {
      background: #e8f5e8;
      border-color: #4caf50;
    }

    .calendar-day.has-posts:hover {
      background: #c8e6c9;
    }

    .day-number {
      font-weight: bold;
      margin-bottom: 4px;
    }

    .post-indicator {
      background: #4caf50;
      color: white;
      border-radius: 10px;
      padding: 2px 6px;
      font-size: 10px;
      position: absolute;
      top: 4px;
      right: 4px;
      min-width: 18px;
      text-align: center;
    }

    .post-preview {
      font-size: 11px;
      color: #666;
      margin-top: 2px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .loading-calendar {
      text-align: center;
      padding: 40px;
      color: #999;
    }

    .calendar-legend {
      display: flex;
      gap: 15px;
      margin-top: 15px;
      flex-wrap: wrap;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 12px;
    }

    .legend-color {
      width: 16px;
      height: 16px;
      border-radius: 4px;
      border: 1px solid #ddd;
    }

    .error-message {
      background: #f2dede;
      border: 1px solid #ebccd1;
      color: #a94442;
      padding: 15px;
      border-radius: 4px;
      margin: 10px 0;
    }

    @media (max-width: 768px) {
      .calendar-day {
        min-height: 60px;
        padding: 4px;
      }

      .calendar-title {
        font-size: 18px;
      }

      .calendar-day-header {
        padding: 8px 4px;
        font-size: 12px;
      }
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">

      <!-- Profile and Sidebarmenu -->
      <?php
      if (file_exists("sidebarmenu.php")) {
        include("sidebarmenu.php");
      } else {
        echo '<div class="error-message">File sidebarmenu.php tidak ditemukan!</div>';
      }
      ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php
      if (file_exists("header.php")) {
        include("header.php");
      } else {
        echo '<div class="error-message">File header.php tidak ditemukan!</div>';
      }
      ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="row">
          <div class="col-md-12">
            <div class="">
              <div class="x_content">
                <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <center>
                      <h1><b>Selamat Datang, <?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : 'Tamu'; ?></b></h1>
                    </center>
                    <br><br>
                  </div>
                </div>

                <!-- Statistics Row -->
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

                <!-- Calendar Section -->
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
                          <div class="legend-color" style="background: #e3f2fd; border-color: #2196f3;"></div>
                          <span>Hari Ini</span>
                        </div>
                        <div class="legend-item">
                          <div class="legend-color" style="background: #e8f5e8; border-color: #4caf50;"></div>
                          <span>Ada Postingan</span>
                        </div>
                        <div class="legend-item">
                          <div class="legend-color" style="background: #f5f5f5;"></div>
                          <span>Bulan Lain</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                  <div class="col-md-12">
                    <center>
                      <a href="inputbuatsurat.php" class="btn btn-primary btn-lg" style="margin-right: 10px;">
                        <i class="fa fa-pencil"></i> Buat Surat
                      </a>
                      <a href="tambah-postingan.php" class="btn btn-success btn-lg">
                        <i class="fa fa-plus"></i> Tambah Postingan
                      </a>
                    </center>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->

      <!-- footer content -->
      <footer>
        <div class="pull-right">
          VURIKO DEV STUDIO. All Rights Reserved.
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>

  <!-- Modal for Post Details -->
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

  <!-- jQuery -->
  <script src="../assets/vendors/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="../assets/vendors/fastclick/lib/fastclick.js"></script>
  <!-- NProgress -->
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <!-- Calendar Script -->
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
                  `<img src="uploads/${post.gambar}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;" alt="Post Image" onerror="this.style.display='none'; this.nextSibling.style.display='flex';">
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