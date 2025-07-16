   <?php
    session_start();
    $koneksi_file = '../koneksi/koneksi.php';
    if (!file_exists($koneksi_file)) {
        die("File koneksi.php tidak ditemukan.");
    }
    include $koneksi_file;

    // Ambil data username dan password dari form
    $username = mysqli_real_escape_string($db, $_POST['username_admin']);
    $password = $_POST['password']; // Ambil password tanpa hashing

    // Query untuk mendapatkan data user berdasarkan username
    $stmt = $db->prepare("SELECT * FROM tb_admin WHERE username_admin=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $data['password'])) {
            // Jika username dan password cocok
            echo "Login berhasil!";
            $_SESSION['r3su'] = 'dmn';
            $_SESSION['id'] = $data['id_admin'];
            $_SESSION['username'] = $data['username_admin'];
            $_SESSION['nama'] = $data['nama_admin'];
            header('Location: ../'); // Arahkan ke halaman dashboard atau halaman utama
            exit();
        } else {
            echo "<center>Username atau Password anda salah<br><br><h3>Silahkan Ulangi</h3></center>";
            echo "<meta http-equiv='refresh' content='2;url=../login/'>";
        }
    } else {
        echo "<center>Username atau Password anda salah<br><br><h3>Silahkan Ulangi</h3></center>";
        echo "<meta http-equiv='refresh' content='2;url=../login/'>";
    }
    ?>
