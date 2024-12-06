<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ambil data dari form
$id = $_POST['id'];
$title = $_POST['title'];
$author = $_POST['author'];
$content = $_POST['content'];
$tags = explode(',', $_POST['tags']);  // Pisahkan tag
$categories = explode(',', $_POST['categories']);  // Pisahkan kategori

// Mulai transaksi untuk memastikan semua langkah berhasil
$conn->beginTransaction();

try {
    // 1. Update artikel
    $stmt = $conn->prepare("UPDATE articles SET title = ?, author = ?, content = ? WHERE id = ?");
    $stmt->execute([$title, $author, $content, $id]);

    // 2. Hapus tag yang lama dari artikel
    $stmt = $conn->prepare("DELETE FROM article_tags WHERE article_id = ?");
    $stmt->execute([$id]);

    // 3. Tambahkan tag baru
    foreach ($tags as $tag) {
        $tag = trim($tag);

        // Cek apakah tag sudah ada
        $stmt = $conn->prepare("INSERT INTO tags (name) SELECT ? WHERE NOT EXISTS (SELECT 1 FROM tags WHERE name = ?)");
        $stmt->execute([$tag, $tag]);

        // Ambil ID tag
        $stmt = $conn->prepare("SELECT id FROM tags WHERE name = ?");
        $stmt->execute([$tag]);
        $tag_id = $stmt->fetchColumn();

        // Hubungkan tag dengan artikel
        $stmt = $conn->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
        $stmt->execute([$id, $tag_id]);
    }

    // 4. Hapus kategori yang lama dari artikel
    $stmt = $conn->prepare("DELETE FROM article_categories WHERE article_id = ?");
    $stmt->execute([$id]);

    // 5. Tambahkan kategori baru
    foreach ($categories as $category) {
        $category = trim($category);

        // Cek apakah kategori sudah ada
        $stmt = $conn->prepare("INSERT INTO categories (name) SELECT ? WHERE NOT EXISTS (SELECT 1 FROM categories WHERE name = ?)");
        $stmt->execute([$category, $category]);

        // Ambil ID kategori
        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->execute([$category]);
        $category_id = $stmt->fetchColumn();

        // Hubungkan kategori dengan artikel
        $stmt = $conn->prepare("INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)");
        $stmt->execute([$id, $category_id]);
    }

    // Commit transaksi jika semua langkah berhasil
    $conn->commit();
    echo "Artikel berhasil diperbarui!";
} catch (Exception $e) {
    // Rollback transaksi jika ada error
    $conn->rollBack();
    echo "Gagal memperbarui artikel: " . $e->getMessage();
}
?>
