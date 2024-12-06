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
