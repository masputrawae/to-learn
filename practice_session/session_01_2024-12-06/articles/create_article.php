<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ambil data dari form
$title = $_POST['title'];
$author = $_POST['author'];
$content = $_POST['content'];
$tags = explode(',', $_POST['tags']);  // Memisahkan tag dengan koma
$categories = explode(',', $_POST['categories']);  // Memisahkan kategori dengan koma

// Mulai transaksi untuk memastikan semua langkah berhasil
$conn->beginTransaction();

try {
    // 1. Menambah Artikel
    $stmt = $conn->prepare("INSERT INTO articles (title, author, content) VALUES (?, ?, ?)");
    $stmt->execute([$title, $author, $content]);
    $article_id = $conn->lastInsertId(); // ID artikel yang baru ditambahkan

    // 2. Menambahkan Tag yang belum ada
    foreach ($tags as $tag) {
        $tag = trim($tag);  // Menghilangkan spasi sebelum dan sesudah tag

        // Cek apakah tag sudah ada
        $stmt = $conn->prepare("INSERT INTO tags (name) SELECT ? WHERE NOT EXISTS (SELECT 1 FROM tags WHERE name = ?)");
        $stmt->execute([$tag, $tag]);

        // Ambil ID tag yang baru atau yang sudah ada
        $stmt = $conn->prepare("SELECT id FROM tags WHERE name = ?");
        $stmt->execute([$tag]);
        $tag_id = $stmt->fetchColumn();

        // 3. Menghubungkan Artikel dengan Tag
        $stmt = $conn->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
        $stmt->execute([$article_id, $tag_id]);
    }

    // 3. Menambahkan Kategori yang belum ada
    foreach ($categories as $category) {
        $category = trim($category);  // Menghilangkan spasi sebelum dan sesudah kategori

        // Cek apakah kategori sudah ada
        $stmt = $conn->prepare("INSERT INTO categories (name) SELECT ? WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name = ?)");
        $stmt->execute([$category, $category]);

        // Ambil ID kategori yang baru atau yang sudah ada
        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$category]);
        $category_id = $stmt->fetchColumn();

        // 4. Menghubungkan Artikel dengan Kategori
        $stmt = $conn->prepare("INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)");
        $stmt->execute([$article_id, $category_id]);
    }

    // Commit transaksi jika semua langkah berhasil
    $conn->commit();
    echo "Artikel berhasil ditambahkan!";
} catch (Exception $e) {
    // Rollback transaksi jika ada error
    $conn->rollBack();
    echo "Gagal menambahkan artikel: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Artikel</title>
</head>
<body>
    <h1>Tambah Artikel Baru</h1>

    <form action="create_article.php" method="POST">
        <label for="title">Judul Artikel:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="author">Penulis:</label>
        <input type="text" id="author" name="author" required><br><br>

        <label for="content">Konten:</label>
        <textarea id="content" name="content" required></textarea><br><br>

        <label for="tags">Tag (pisahkan dengan koma):</label>
        <input type="text" id="tags" name="tags" required><br><br>

        <label for="categories">Kategori (pisahkan dengan koma):</label>
        <input type="text" id="categories" name="categories" required><br><br>

        <input type="submit" value="Tambah Artikel">
    </form>
</body>
</html>
