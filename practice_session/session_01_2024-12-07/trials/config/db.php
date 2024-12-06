<?php 
$host = "localhost";
$username = "build_user";
$password = "build_password";
$dbname = "belajar";

try {
    $connect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $connect ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e)  {
    echo "Koneksi Gagal". $e->getMessage();
}
?>