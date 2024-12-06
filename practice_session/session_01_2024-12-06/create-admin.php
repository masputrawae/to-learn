<?php
require_once 'config/database.php';

$username = "admin";
$password = password_hash("adminpassword", PASSWORD_DEFAULT); // Meng-hash password
$role = "admin"; // Set role admin

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':role', $role);
$stmt->execute();

echo "Admin berhasil ditambahkan!";
?>
