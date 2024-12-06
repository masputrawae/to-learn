<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'build';
$username = 'build_user';
$password = 'build_password';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Ambil semua artikel
$stmt = $conn->prepare("SELECT * FROM articles");
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Artikel</title>
</head>
<body>
    <h1>Daftar Artikel</h1>

    <?php if ($articles): ?>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                    <p><strong>Penulis:</strong> <?php echo htmlspecialchars($article['author']); ?></p>
                    <p><strong>Konten:</strong><br> <?php echo nl2br(htmlspecialchars($article['content'])); ?></p>

                    <?php
                    // Ambil tag yang terkait dengan artikel ini
                    $stmt = $conn->prepare("SELECT tags.name FROM tags
                                            JOIN article_tags ON tags.id = article_tags.tag_id
                                            WHERE article_tags.article_id = ?");
                    $stmt->execute([$article['id']]);
                    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Menampilkan tag
                    if ($tags):
                        echo "<p><strong>Tag:</strong> ";
                        foreach ($tags as $tag):
                            echo htmlspecialchars($tag['name']) . " ";
                        endforeach;
                        echo "</p>";
                    endif;

                    // Ambil kategori yang terkait dengan artikel ini
                    $stmt = $conn->prepare("SELECT categories.name FROM categories
                                            JOIN article_categories ON categories.id = article_categories.category_id
                                            WHERE article_categories.article_id = ?");
                    $stmt->execute([$article['id']]);
                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Menampilkan kategori
                    if ($categories):
                        echo "<p><strong>Kategori:</strong> ";
                        foreach ($categories as $category):
                            echo htmlspecialchars($category['name']) . " ";
                        endforeach;
                        echo "</p>";
                    endif;
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Belum ada artikel yang ditambahkan.</p>
    <?php endif; ?>

</body>
</html>
