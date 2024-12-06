<?php
require_once 'config/database.php';

// Mengambil jumlah artikel
$articleStmt = $conn->query("SELECT COUNT(*) FROM articles");
$articleCount = $articleStmt->fetchColumn();

// Mengambil jumlah kategori
$categoryStmt = $conn->query("SELECT COUNT(*) FROM categories");
$categoryCount = $categoryStmt->fetchColumn();

// Mengambil jumlah tag
$tagStmt = $conn->query("SELECT COUNT(*) FROM tags");
$tagCount = $tagStmt->fetchColumn();
?>


<?php
session_start();

// Cek apakah pengguna sudah login dan apakah role-nya 'admin'
if ($_SESSION['role'] != 'admin') {
    // Jika bukan admin, alihkan ke halaman login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
    <!-- Konten dashboard admin lainnya -->
</body>
</html>

<div class="dashboard">
    <h2>Dashboard Admin</h2>
    <div class="stats">
        <p>Jumlah Artikel: <?php echo $articleCount; ?></p>
        <p>Jumlah Kategori: <?php echo $categoryCount; ?></p>
        <p>Jumlah Tag: <?php echo $tagCount; ?></p>
    </div>
</div>
<div class="actions">
    <h3>Manajemen Konten</h3>
    <ul>
        <li><a href="articles/create_article.php">Tambah Artikel</a></li>
        <li><a href="list_articles.php">Lihat Semua Artikel</a></li>
        <li><a href="create_category.php">Tambah Kategori</a></li>
        <li><a href="list_categories.php">Lihat Semua Kategori</a></li>
        <li><a href="create_tag.php">Tambah Tag</a></li>
        <li><a href="list_tags.php">Lihat Semua Tag</a></li>
    </ul>
</div>

<?php
// Mengambil artikel terbaru
$latestArticlesStmt = $conn->query("SELECT title FROM articles ORDER BY created_at DESC LIMIT 5");

$latestArticles = $latestArticlesStmt->fetchAll();

// Menampilkan artikel terbaru
echo "<h3>Artikel Terbaru</h3><ul>";
foreach ($latestArticles as $article) {
    echo "<li>" . $article['title'] . "</li>";
}
echo "</ul>";
?>
