<?php
session_start();

// Hapus semua sesi yang terkait dengan pengguna
session_unset();
session_destroy();

// Arahkan pengguna ke halaman login setelah logout
header('Location: login.php');
exit();
?>
