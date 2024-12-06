<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Cek apakah ID artikel ada di URL
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Mulai transaksi untuk menghapus artikel dan relasinya
    $conn->beginTransaction();

    try {
        // 1. Hapus artikel dari artikel
        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->execute([$article_id]);

        // 2. Hapus relasi antara artikel dengan tag
        $stmt = $conn->prepare("DELETE FROM article_tags WHERE article_id = ?");
        $stmt->execute([$article_id]);

        // 3. Hapus relasi antara artikel dengan kategori
        $stmt = $conn->prepare("DELETE FROM article_categories WHERE article_id = ?");
        $stmt->execute([$article_id]);

        // Commit transaksi jika semuanya berhasil
        $conn->commit();
        echo "Artikel berhasil dihapus!";
        header("Location: list_articles.php");  // Redirect kembali ke daftar artikel
    } catch (Exception $e) {
        // Rollback jika ada error
        $conn->rollBack();
        echo "Gagal menghapus artikel: " . $e->getMessage();
    }
} else {
    echo "ID artikel tidak ditemukan!";
}
?>
