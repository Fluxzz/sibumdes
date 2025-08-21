<?php
session_start();
require_once '../koneksi.php'; 
require_once '../auth/ceksession.php'; 

// --- Fetch Data for Stat Cards ---
$jumlah_surat_masuk = $db->query("SELECT COUNT(*) as total FROM tb_arsip_surat_masuk")->fetch_assoc()['total'] ?? 0;
$jumlah_surat_keluar = $db->query("SELECT COUNT(*) as total FROM tb_arsip_surat_keluar")->fetch_assoc()['total'] ?? 0;
$jumlah_posts = $db->query("SELECT COUNT(*) as total FROM tb_postingan")->fetch_assoc()['total'] ?? 0;
$jumlah_posts_published = $db->query("SELECT COUNT(*) as total FROM tb_postingan WHERE status='publish'")->fetch_assoc()['total'] ?? 0;

// Variables for the template
$pageTitle = "Dashboard";
$activeMenu = "dashboard";
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>
<body>
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-inbox"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Surat Masuk</p>
                            <h4 class="card-title"><?= $jumlah_surat_masuk ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Surat Keluar</p>
                            <h4 class="card-title"><?= $jumlah_surat_keluar ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Konten</p>
                            <h4 class="card-title"><?= $jumlah_posts ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Konten Published</p>
                            <h4 class="card-title"><?= $jumlah_posts_published ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Kalender Konten</h4>
                    <div class="card-tools">
                        <button class="btn btn-icon btn-link btn-primary btn-xs" onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i></button>
                        <span id="calendar-title" class="fw-bold mx-2">Loading...</span>
                        <button class="btn btn-icon btn-link btn-primary btn-xs" onclick="changeMonth(1)"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar-content" class="table-responsive">
                    <div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Memuat kalender...</p></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Konten pada <span id="modal-date"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-posts-content">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<?php
// Define the JavaScript for the calendar
$pageJS = "
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    function generateCalendar(month, year) {
        document.getElementById('calendar-title').textContent = monthNames[month] + ' ' + year;
        
        let firstDay = new Date(year, month).getDay();
        let daysInMonth = 32 - new Date(year, month, 32).getDate();
        
        let calendarHTML = '<table class=\"table table-bordered\"><thead><tr>';
        dayNames.forEach(day => { calendarHTML += `<th>\${day}</th>`; });
        calendarHTML += '</tr></thead><tbody>';
        
        let date = 1;
        for (let i = 0; i < 6; i++) {
            calendarHTML += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    calendarHTML += '<td></td>';
                } else if (date > daysInMonth) {
                    calendarHTML += '<td></td>';
                } else {
                    let dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(date).padStart(2, '0');
                    calendarHTML += `<td class='calendar-day' data-date='\${dateStr}' onclick='showPostsForDate(\"\${dateStr}\")'><div class='day-number'>\${date}</div><div class='post-indicators'></div></td>`;
                    date++;
                }
            }
            calendarHTML += '</tr>';
            if (date > daysInMonth) {
                break;
            }
        }
        calendarHTML += '</tbody></table>';
        document.getElementById('calendar-content').innerHTML = calendarHTML;
        loadPostsForMonth(month, year);
    }

    function loadPostsForMonth(month, year) {
        $.ajax({
            url: '/features/ajax/get_calendar_posts.php',
            type: 'POST',
            data: { 
                start_date: year + '-' + String(month + 1).padStart(2, '0') + '-01',
                end_date: year + '-' + String(month + 1).padStart(2, '0') + '-31'
            },
            dataType: 'json',
            success: function(response) {
                if (response) {
                    Object.keys(response).forEach(date => {
                        const dayElement = $(`.calendar-day[data-date='\${date}']`);
                        if (dayElement.length) {
                            dayElement.addClass('has-posts');
                            dayElement.find('.post-indicators').append(`<span class='badge badge-success'>\${response[date].length}</span>`);
                        }
                    });
                }
            }
        });
    }

    function changeMonth(direction) {
        currentMonth += direction;
        if (currentMonth > 11) { currentMonth = 0; currentYear++; }
        if (currentMonth < 0) { currentMonth = 11; currentYear--; }
        generateCalendar(currentMonth, currentYear);
    }

    function showPostsForDate(date) {
        $('#modal-date').text(new Date(date + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }));
        $('#modal-posts-content').html('<p class=\"text-center\">Memuat...</p>');
        $('#postModal').modal('show');

        $.ajax({
            url: '/features/ajax/get_posts_by_date.php',
            type: 'POST',
            data: { date: date },
            dataType: 'json',
            success: function(response) {
                let content = '';
                if (response && response.length > 0) {
                    response.forEach(post => {
                        content += `<div class='mb-3 border-bottom pb-3'>
                                        <h5>\${post.nama_kategori}</h5>
                                        <p>\${post.caption}</p>
                                        <div class='d-flex justify-content-between align-items-center'>
                                            <small class='text-muted'>Status: \${post.status}</small>
                                            <a href='/features/menu/postingan/detail-postingan.php?id=\${post.id_postingan}' class='btn btn-xs btn-primary'>Lihat Detail</a>
                                        </div>
                                    </div>`;
                    });
                } else {
                    content = '<p class=\"text-center\">Tidak ada konten pada tanggal ini.</p>';
                }
                $('#modal-posts-content').html(content);
            },
            error: function() { 
                $('#modal-posts-content').html('<p class=\"text-center text-danger\">Gagal memuat data.</p>'); 
            }
        });
    }

    // Initialize the calendar
    generateCalendar(currentMonth, currentYear);
";
?>

<?php include '../partials/footer.php';  ?>

    
</body>

