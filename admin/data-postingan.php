<!DOCTYPE html>
<?php
session_start();
include '../koneksi/koneksi.php';



// Cek jika ada parameter refresh atau update
$show_success = false;
if (isset($_GET['success']) && $_GET['success'] == 'edit') {
  $show_success = true;
} elseif (isset($_GET['updated']) && $_GET['updated'] == 'success') {
  $show_success = true;
}
?>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Data Postingan</title>

  <!-- Bootstrap -->
  <link href="../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../assets/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- Datatables -->
  <link href="../assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="../img/icon.ico">

  <!-- Custom Theme Style -->
  <link href="../assets/build/css/custom.min.css" rel="stylesheet">

  <style>
    .status-badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: bold;
      text-transform: uppercase;
      transition: all 0.3s ease;
    }

    .status-publish {
      background-color: #28a745;
      color: white;
    }

    .status-draft {
      background-color: #6c757d;
      color: white;
    }

    .img-thumbnail-table {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    .caption-preview {
      max-width: 200px;
      word-wrap: break-word;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .link-preview {
      max-width: 150px;
      word-wrap: break-word;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .table-actions {
      white-space: nowrap;
    }

    /* Loading overlay */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      z-index: 9999;
    }

    .loading-spinner {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 24px;
    }

    /* Row animation */
    .table tbody tr {
      transition: background-color 0.3s ease;
    }

    .table tbody tr.updated {
      background-color: #d4edda !important;
      animation: highlightFade 3s ease-in-out;
    }

    @keyframes highlightFade {
      0% {
        background-color: #d4edda;
      }

      100% {
        background-color: transparent;
      }
    }
  </style>
</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <!-- Profile and Sidebarmenu -->
      <?php
      include("sidebarmenu.php");
      ?>
      <!-- /Profile and Sidebarmenu -->

      <!-- top navigation -->
      <?php
      include("header.php");
      ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Data Postingan</h3>
            </div>

            <div class="title_right">
              <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Cari postingan..." id="search-input">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div class="clearfix"></div>

          <!-- Alert Success -->
          <?php if ($show_success) { ?>
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <i class="fa fa-check-circle"></i> <strong>Berhasil!</strong> Status postingan berhasil diperbarui.
            </div>
          <?php } ?>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2><i class="fa fa-list"></i> Daftar Postingan</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-wrench"></i>
                      </a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#" onclick="exportData('excel')">Export Excel</a></li>
                        <li><a href="#" onclick="exportData('pdf')">Export PDF</a></li>
                        <li><a href="#" onclick="refreshData()">Refresh Data</a></li>
                      </ul>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="card-box table-responsive">
                        <!-- Tombol Aksi -->
                        <div class="mb-3">
                          <a href="tambah-postingan.php" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Tambah Postingan
                          </a>
                          <button type="button" class="btn btn-info" onclick="refreshData()" title="Refresh Data">
                            <i class="fa fa-refresh" id="refresh-icon"></i> Refresh
                          </button>
                          <div class="btn-group pull-right">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fa fa-filter"></i> Filter Status <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                              <li><a href="#" onclick="filterStatus('all')">Semua Status</a></li>
                              <li><a href="#" onclick="filterStatus('publish')">Publish</a></li>
                              <li><a href="#" onclick="filterStatus('draft')">Draft</a></li>
                            </ul>
                          </div>
                        </div>

                        <!-- Statistik -->
                        <div class="row tile_count mb-3" id="statistics-section">
                          <?php
                          $total_posts = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_postingan"));
                          $published_posts = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_postingan WHERE status='publish'"));
                          $draft_posts = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_postingan WHERE status='draft'"));
                          ?>
                          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-files-o"></i> Total Postingan</span>
                            <div class="count" id="total-count"><?php echo $total_posts; ?></div>
                          </div>
                          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-check-circle"></i> Published</span>
                            <div class="count green" id="published-count"><?php echo $published_posts; ?></div>
                          </div>
                          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-edit"></i> Draft</span>
                            <div class="count" id="draft-count"><?php echo $draft_posts; ?></div>
                          </div>
                          <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
                            <span class="count_top"><i class="fa fa-calendar"></i> Hari Ini</span>
                            <div class="count blue" id="today-count">
                              <?php
                              $today_posts = mysqli_num_rows(mysqli_query($db, "SELECT * FROM tb_postingan WHERE DATE(tanggal_posting) = CURDATE()"));
                              echo $today_posts;
                              ?>
                            </div>
                          </div>
                        </div>

                        <!-- Tabel Data -->
                        <table id="datatable" class="table table-striped table-bordered">
                          <thead>
                            <tr>
                              <th width="5%">No</th>
                              <th width="10%">Gambar</th>
                              <th width="12%">Kategori</th>
                              <th width="8%">Status</th>
                              <th width="25%">Caption</th>
                              <th width="15%">Link Konten</th>
                              <th width="12%">Tanggal Posting</th>
                              <th width="13%">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($db, "SELECT tb_postingan.*, tb_kategori.nama_kategori 
                           FROM tb_postingan
                           JOIN tb_kategori ON tb_postingan.id_kategori = tb_kategori.id_kategori
                           ORDER BY tb_postingan.id_postingan DESC");

                            if (mysqli_num_rows($query) > 0) {
                              while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr data-id="<?php echo $row['id_postingan']; ?>">
                                  <td class="text-center"><?php echo $no++; ?></td>
                                  <td class="text-center">
                                    <?php if (!empty($row['gambar']) && file_exists("uploads/" . $row['gambar'])) { ?>
                                      <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>"
                                        class="img-thumbnail-table" alt="Gambar Postingan"
                                        data-toggle="modal" data-target="#imageModal"
                                        onclick="showImage('uploads/<?php echo htmlspecialchars($row['gambar']); ?>')">
                                    <?php } else { ?>
                                      <div class="text-center" style="padding: 20px; background: #f8f9fa; border-radius: 6px;">
                                        <i class="fa fa-image text-muted"></i><br>
                                        <small class="text-muted">No Image</small>
                                      </div>
                                    <?php } ?>
                                  </td>
                                  <td>
                                    <span class="label label-info"><?php echo htmlspecialchars($row['nama_kategori']); ?></span>
                                  </td>
                                  <td class="text-center">
                                    <span class="status-badge <?php echo $row['status'] == 'publish' ? 'status-publish' : 'status-draft'; ?>">
                                      <?php echo ucfirst(htmlspecialchars($row['status'])); ?>
                                    </span>
                                  </td>
                                  <td>
                                    <div class="caption-preview" title="<?php echo htmlspecialchars($row['caption']); ?>">
                                      <?php echo htmlspecialchars($row['caption']); ?>
                                    </div>
                                  </td>
                                  <td>
                                    <?php if (!empty($row['link_konten'])) { ?>
                                      <div class="link-preview">
                                        <a href="<?php echo htmlspecialchars($row['link_konten']); ?>" target="_blank"
                                          class="btn btn-xs btn-link" title="<?php echo htmlspecialchars($row['link_konten']); ?>">
                                          <i class="fa fa-external-link"></i> Lihat Link
                                        </a>
                                      </div>
                                    <?php } else { ?>
                                      <span class="text-muted">-</span>
                                    <?php } ?>
                                  </td>
                                  <td class="text-center">
                                    <small>
                                      <?php echo date('d M Y', strtotime($row['tanggal_posting'])); ?><br>
                                      <span class="text-muted"><?php echo date('H:i', strtotime($row['tanggal_posting'])); ?></span>
                                    </small>
                                  </td>
                                  <td class="table-actions text-center">
                                    <div class="btn-group" role="group">
                                      <a href="detail-postingan.php?id=<?php echo $row['id_postingan']; ?>"
                                        class="btn btn-xs btn-info" title="Detail">
                                        <i class="fa fa-eye"></i>
                                      </a>
                                      <a href="edit_postingan.php?id=<?php echo $row['id_postingan']; ?>"
                                        class="btn btn-xs btn-warning" title="Edit">
                                        <i class="fa fa-edit"></i>
                                      </a>
                                      <button type="button" class="btn btn-xs btn-success" title="Toggle Status"
                                        onclick="toggleStatus(<?php echo $row['id_postingan']; ?>, '<?php echo $row['status']; ?>')">
                                        <i class="fa fa-refresh"></i>
                                      </button>
                                      <a href="#" class="btn btn-xs btn-danger" title="Hapus"
                                        onclick="confirmDelete(<?php echo $row['id_postingan']; ?>, '<?php echo htmlspecialchars(addslashes($row['caption'])); ?>')">
                                        <i class="fa fa-trash"></i>
                                      </a>
                                    </div>
                                  </td>
                                </tr>
                              <?php
                              }
                            } else {
                              ?>
                              <tr>
                                <td colspan="8" class="text-center">
                                  <div style="padding: 40px;">
                                    <i class="fa fa-inbox fa-3x text-muted"></i><br><br>
                                    <h4 class="text-muted">Belum Ada Data Postingan</h4>
                                    <p class="text-muted">Silakan tambah postingan baru untuk mulai mengelola konten</p>
                                    <a href="tambah-postingan.php" class="btn btn-primary">
                                      <i class="fa fa-plus"></i> Tambah Postingan Pertama
                                    </a>
                                  </div>
                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
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
          Sistem Manajemen Postingan
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /footer content -->
    </div>
  </div>

  <!-- Loading Overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
      <i class="fa fa-spinner fa-spin fa-3x"></i><br>
      <span>Memperbarui data...</span>
    </div>
  </div>

  <!-- Modal untuk Preview Gambar -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="imageModalLabel">Preview Gambar</h4>
        </div>
        <div class="modal-body text-center">
          <img id="modalImage" src="" class="img-responsive" style="max-width: 100%; height: auto;">
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Konfirmasi Hapus</h4>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus postingan ini?</p>
          <div class="alert alert-warning">
            <strong>Caption:</strong> <span id="deleteCaption"></span>
          </div>
          <p class="text-danger"><small><i class="fa fa-warning"></i> Tindakan ini tidak dapat dibatalkan!</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <a href="hapus_postingan.php" id="confirmDeleteBtn" class="btn btn-danger">
            <i class="fa fa-trash"></i> Ya, Hapus
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Toggle Status -->
  <div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Konfirmasi Ubah Status</h4>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin mengubah status postingan ini?</p>
          <div class="alert alert-info">
            <strong>Status saat ini:</strong> <span id="currentStatus"></span><br>
            <strong>Akan diubah menjadi:</strong> <span id="newStatus"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="button" id="confirmStatusBtn" class="btn btn-primary">
            <i class="fa fa-refresh"></i> Ya, Ubah Status
          </button>
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
  <!-- Datatables -->
  <script src="../assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="../assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="../assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <script src="../assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
  <script src="../assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
  <script src="../assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../assets/build/js/custom.min.js"></script>

  <!-- Custom Scripts -->
  <script>
    var table;

    $(document).ready(function() {
      if (!$.fn.DataTable.isDataTable('#datatable')) {
        table = $('#datatable').DataTable({
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
          },
          "pageLength": 10,
          "order": [
            [0, "desc"]
          ],
          "columnDefs": [{
            "orderable": false,
            "targets": [1, 7]
          }]
        });
      }
    });

    // Custom search
    $('#search-input').on('keyup', function() {
      table.search(this.value).draw();
    });

    // Auto-dismiss alert after 5 seconds
    setTimeout(function() {
      $('.alert-success').fadeOut('slow');
    }, 5000);

    // Check if page was refreshed due to update
    if (window.location.search.includes('updated=success')) {
      // Remove parameter from URL without page reload
      const url = new URL(window.location);
      url.searchParams.delete('updated');
      window.history.replaceState(null, null, url);
    }
    

    // Function untuk menampilkan gambar di modal
    function showImage(imageSrc) {
      $('#modalImage').attr('src', imageSrc);
    }

    // Function untuk konfirmasi hapus
    function confirmDelete(id, caption) {
      $('#deleteCaption').text(caption.length > 50 ? caption.substring(0, 50) + '...' : caption);
      $('#confirmDeleteBtn').attr('href', 'hapus_postingan.php?id=' + id);
      $('#deleteModal').modal('show');
    }

    // Function untuk toggle status
    function toggleStatus(id, currentStatus) {
      var newStatus = currentStatus === 'publish' ? 'draft' : 'publish';

      $('#currentStatus').text(currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1));
      $('#newStatus').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));

      $('#confirmStatusBtn').off('click').on('click', function() {
        updateStatus(id, newStatus);
      });

      $('#statusModal').modal('show');
    }

    // Function untuk update status via AJAX
    function updateStatus(id, newStatus) {
      $('#statusModal').modal('hide');
      showLoading(true);

      $.ajax({
        url: 'ajax/update_status.php', // Anda perlu membuat file ini
        type: 'POST',
        data: {
          id: id,
          status: newStatus
        },
        dataType: 'json',
        success: function(response) {
          showLoading(false);

          if (response.success) {
            // Update status badge di tabel
            var row = $('tr[data-id="' + id + '"]');
            var statusBadge = row.find('.status-badge');

            // Update class dan text
            statusBadge.removeClass('status-publish status-draft');
            statusBadge.addClass('status-' + newStatus);
            statusBadge.text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));

            // Highlight row
            row.addClass('updated');

            // Update statistics
            updateStatistics();

            // Show success message
            showNotification('Status berhasil diperbarui!', 'success');

            // Remove highlight after animation
            setTimeout(function() {
              row.removeClass('updated');
            }, 3000);

          } else {
            showNotification('Gagal memperbarui status: ' + (response.message || 'Unknown error'), 'danger');
          }
        },
        error: function() {
          showLoading(false);
          showNotification('Terjadi kesalahan saat memperbarui status', 'danger');
        }
      });
    }

    // Function untuk update statistik
    function updateStatistics() {
      $.ajax({
        url: 'ajax/get_statistics.php', // Anda perlu membuat file ini
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#total-count').text(data.total);
          $('#published-count').text(data.published);
          $('#draft-count').text(data.draft);
          $('#today-count').text(data.today);
        }
      });
    }

    // Function untuk show loading
    function showLoading(show) {
      if (show) {
        $('#loadingOverlay').show();
      } else {
        $('#loadingOverlay').hide();
      }
    }

    // Function untuk show notification
    function showNotification(message, type) {
      var alertClass = 'alert-' + type;
      var iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';

      var notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade in" role="alert">' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span></button>' +
        '<i class="fa ' + iconClass + '"></i> ' + message +
        '</div>');

      $('.page-title').after(notification);

      // Auto-dismiss after 5 seconds
      setTimeout(function() {
        notification.fadeOut('slow', function() {
          $(this).remove();
        });
      }, 5000);
    }

    // Function untuk filter status
    function filterStatus(status) {
      if (status === 'all') {
        table.column(3).search('').draw();
      } else {
        table.column(3).search(status).draw();
      }
    }

    // Function untuk export data
    function exportData(type) {
      alert('Fitur export ' + type + ' akan segera tersedia');
    }

    // Function untuk refresh data
    function refreshData() {
      var refreshIcon = $('#refresh-icon');
      refreshIcon.addClass('fa-spin');

      setTimeout(function() {
        location.reload();
      }, 1000);
    }

    // Auto refresh setiap 5 menit (opsional)
    setInterval(function() {
      updateStatistics();
    }, 300000); // 5 minutes
  </script>
</body>

</html>