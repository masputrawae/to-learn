<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari sesi
$userName = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

    <h2>Selamat datang, <?= htmlspecialchars($userName) ?>!</h2>
    <p>Ini adalah halaman dashboard kamu.</p>
    
    <a href="logout.php">Logout</a>

</body>
</html>
