<?php 
require_once('../config/db.php'); // Pastikan koneksi database sudah benar

session_start(); // Mulai sesi
// Pengaturan session yang aman
ini_set('session.cookie_httponly', 1); // Membatasi cookie sesi agar tidak bisa diakses JavaScript
session_set_cookie_params([
    'lifetime' => 3600, // Sesi valid selama 1 jam
    'secure' => true, // Pastikan cookie hanya dikirim melalui HTTPS
    'samesite' => 'Strict' // Hanya kirim cookie ke situs yang sama
]);

// Cek jika form sudah disubmit
if (isset($_POST['login'])) {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (!empty($username) && !empty($password)) {
        
        // Cek apakah username ada di database
        try {
            $stmt = $connect->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Cek apakah user ditemukan
            if ($user) {
                // Verifikasi password dengan password yang sudah di-hash
                if (password_verify($password, $user['password'])) {
                    // Regenerasi ID sesi untuk keamanan
                    session_regenerate_id(true);
                    // Jika password cocok, simpan sesi dan arahkan ke halaman utama
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['name'] = $user['name'];
                    header('Location: dashboard.php'); // Redirect ke halaman dashboard
                    exit();
                } else {
                    $error = "Password salah!";
                }
            } else {
                $error = "Username tidak ditemukan!";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "Semua field harus diisi!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <h2>Login ke Akun</h2>

    <?php 
        // Tampilkan pesan error jika ada
        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
    ?>

    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Masukkan Username..." required value="<?= htmlspecialchars($username ?? '') ?>">
        <input type="password" name="password" placeholder="Masukkan Password..." required>
        <button type="submit" name="login">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>

</body>
</html>
