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

    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <strong><?php echo htmlspecialchars($article['title']); ?></strong><br>
                Penulis: <?php echo htmlspecialchars($article['author']); ?><br>
                <a href="edit_article.php?id=<?php echo $article['id']; ?>">Edit Artikel</a> | 
                <a href="delete_article.php?id=<?php echo $article['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">Hapus Artikel</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
