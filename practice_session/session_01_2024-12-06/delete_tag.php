<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $tag_id = $_GET['id'];

    // Hapus tag dari database
    $stmt = $conn->prepare("DELETE FROM tags WHERE id = ?");
    $stmt->execute([$tag_id]);

    echo "Tag berhasil dihapus!";
    header("Location: list_tags.php");  // Redirect ke daftar tags setelah berhasil
} else {
    echo "ID tag tidak ditemukan!";
}
?>
