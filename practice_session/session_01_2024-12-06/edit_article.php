<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ambil artikel berdasarkan ID yang diterima dari URL
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Ambil data artikel
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        die("Artikel tidak ditemukan!");
    }

    // Ambil tag yang terhubung dengan artikel
    $stmt = $conn->prepare("SELECT tags.name FROM tags
                            JOIN article_tags ON tags.id = article_tags.tag_id
                            WHERE article_tags.article_id = ?");
    $stmt->execute([$article_id]);
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil kategori yang terhubung dengan artikel
    $stmt = $conn->prepare("SELECT categories.name FROM categories
                            JOIN article_categories ON categories.id = article_categories.category_id
                            WHERE article_categories.article_id = ?");
    $stmt->execute([$article_id]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("ID artikel tidak diberikan.");
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
</head>
<body>
    <h1>Edit Artikel</h1>

    <form action="update_article.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">

        <label for="title">Judul Artikel:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required><br><br>

        <label for="author">Penulis:</label>
        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($article['author']); ?>" required><br><br>

        <label for="content">Konten:</label>
        <textarea id="content" name="content" required><?php echo htmlspecialchars($article['content']); ?></textarea><br><br>

        <label for="tags">Tag (pisahkan dengan koma):</label>
        <input type="text" id="tags" name="tags" value="<?php echo implode(', ', array_column($tags, 'name')); ?>" required><br><br>

        <label for="categories">Kategori (pisahkan dengan koma):</label>
        <input type="text" id="categories" name="categories" value="<?php echo implode(', ', array_column($categories, 'name')); ?>" required><br><br>

        <input type="submit" value="Perbarui Artikel">
    </form>
</body>
</html>
