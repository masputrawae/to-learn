<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $categories_id = $_GET['id'];

    // Hapus categories dari database
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$categories_id]);

    echo "categories berhasil dihapus!";
    header("Location: list_categories.php");  // Redirect ke daftar categories setelah berhasil
} else {
    echo "ID categories tidak ditemukan!";
}
?>
