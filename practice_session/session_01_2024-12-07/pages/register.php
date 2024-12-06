<?php 

require_once('../config/db.php'); // Pastikan koneksi database sudah benar

session_start(); // Mulai sesi

// Cek jika form sudah disubmit
if (isset($_POST['register'])) {
    // Ambil data dari form
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi input
    if (!empty($name) && !empty($username) && !empty($email) && !empty($password)) {
        
        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format email tidak valid!";
        }
        
        // Cek apakah username sudah ada
        if (empty($error)) {
            try {
                // Query untuk mengecek apakah username sudah ada
                $stmt = $connect->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $userCount = $stmt->fetchColumn();

                if ($userCount > 0) {
                    $error = "Username sudah terdaftar!";
                }
            } catch (PDOException $e) {
                $error = "Terjadi kesalahan: " . $e->getMessage();
            }
        }

        // Validasi kekuatan password (minimal 8 karakter)
        if (strlen($password) < 8) {
            $error = "Password harus memiliki minimal 8 karakter!";
        }

        // Rate Limiting: Cek apakah ada pendaftaran dalam waktu 10 detik terakhir
        if (empty($error)) {
            $currentTime = time();
            if (isset($_SESSION['last_registration_time']) && ($currentTime - $_SESSION['last_registration_time']) < 10) {
                $error = "Tunggu beberapa saat sebelum mencoba lagi.";
            } else {
                $_SESSION['last_registration_time'] = $currentTime;
            }
        }

        // Jika tidak ada error, lanjutkan ke proses registrasi
        if (empty($error)) {
            // Hash password agar lebih aman
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Query untuk memasukkan data ke database
                $query = "INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)";
                $stmt = $connect->prepare($query);

                // Bind parameter untuk mencegah SQL injection
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);

                // Eksekusi query
                if ($stmt->execute()) {
                    $_SESSION['flash_message'] = "Pendaftaran berhasil! Silakan login.";
                    header('Location: login.php'); // Redirect ke halaman login
                    exit();
                } else {
                    $error = "Terjadi kesalahan saat pendaftaran.";
                }
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
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
    <title>Register</title>
</head>
<body>

    <h2>Daftar Akun Baru</h2>

    <?php 
        // Tampilkan pesan error atau sukses
        if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        }
        if (isset($_SESSION['flash_message'])) {
            echo "<p style='color: green;'>{$_SESSION['flash_message']}</p>";
            unset($_SESSION['flash_message']); // Hapus flash message setelah ditampilkan
        }
    ?>

    <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Masukkan Nama Kamu..." required value="<?= htmlspecialchars($name ?? '') ?>">
        <input type="text" name="username" placeholder="Buat Username..." required value="<?= htmlspecialchars($username ?? '') ?>">
        <input type="email" name="email" placeholder="Masukkan Email Kamu..." required value="<?= htmlspecialchars($email ?? '') ?>">
        <input type="password" name="password" placeholder="Masukkan Password Kamu..." required>
        <button type="submit" name="register">Daftar Sekarang</button>
    </form>

</body>
</html>
